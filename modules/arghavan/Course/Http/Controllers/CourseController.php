<?php

namespace arghavan\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Category\Repositories\CategoryRepo;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Course\Http\Requests\CourseRequest;
use arghavan\Course\Models\Course;
use arghavan\Course\Repositories\CourseRepo;
use arghavan\Course\Repositories\LessonRepo;
use arghavan\Media\Services\MediaFileService;
use arghavan\Payment\Gateways\Gateway;
use arghavan\Payment\Services\PaymentService;
use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Repositories\UserRepo;


class CourseController extends Controller
{

    public $courserepo;
    public $userrepo;
    public $categoryrepo;
    public function __construct(CourseRepo $courseRepo,UserRepo $userRepo,CategoryRepo $categoryRepo){

        $this->courserepo = $courseRepo;
        $this->userrepo = $userRepo;
        $this->categoryrepo = $categoryRepo;
    }

    public function index()
    {
        $this->authorize('index',Course::class);
        if(auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSES,Permission::PERMISSION_SUPER_ADMIN])){
            $courses = $this->courserepo->paginate();
        }else{
            $courses = $this->courserepo->getCoursesByTeacherId(auth()->id());
        }

        return view('Courses::index',compact('courses'));
    }


    public function create()
    {
        $this->authorize('create',Course::class);
        $teachers = $this->userrepo->getTeachers();
        $categories = $this->categoryrepo->all();
        return view('Courses::create',compact('teachers','categories'));
    }


    public function store(CourseRequest $request)
    {
        $this->authorize('create',Course::class);
        $request->request->add(['banner_id' => MediaFileService::publicUpload($request->file('image'))->id]);
        $this->courserepo->store($request);
        return redirect()->route('courses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $course = $this->courserepo->findById($id);
        $this->authorize('edit',$course);

        $teachers = $this->userrepo->getTeachers();
        $categories = $this->categoryrepo->all();
        return view('Courses::edit',compact('course','teachers','categories'));
    }


    public function update(CourseRequest $request, $id)
    {
        $course = $this->courserepo->findById($id);
        $this->authorize('edit',$course);

        if($request->hasFile('image')){

            $request->request->add(['banner_id' => MediaFileService::publicUpload($request->file('image'))->id]);
            if($course->banner){

                $course->banner->delete();
            }

        }else{

            $request->request->add(['banner_id' => $course->banner_id]);
        }
        $this->courserepo->update($id,$request);

        return redirect()->route('courses.index');
    }

    public function details($id,LessonRepo $lessonRepo){

        $course = $this->courserepo->findById($id);
        $lessons = $lessonRepo->paginate($id);
        $this->authorize('details',$course);
        return view('Courses::details',compact('course','lessons'));
    }

    public function downloadLinks($id)
    {
        $course = $this->courserepo->findById($id);
        $this->authorize('download',$course);

        return implode('<br>',$course->downloadLinks());
    }


    public function destroy($id)
    {
        $course = $this->courserepo->findById($id);
        $this->authorize('delete',$course);

        if($course->banner){

            $course->banner->delete();
        }

        $course->delete();

        return AjaxResponses::SuccessResponse();
    }

    public function accept($id){

        $this->authorize('change_confirmation_status',Course::class);

        if($this->courserepo->updateConfirmationStatus($id,Course::CONFIRMATION_STATUS_ACCEPTED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function reject($id){

        $this->authorize('change_confirmation_status',Course::class);

        if($this->courserepo->updateConfirmationStatus($id,Course::CONFIRMATION_STATUS_REJECTED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function lock($id){

        $this->authorize('change_confirmation_status',Course::class);

        if($this->courserepo->updateStatus($id,Course::STATUS_LOCKED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function buy($courseId)
    {
        $course = $this->courserepo->findById($courseId);

        if(! $this->courseCanBePurchased($course))
        {
            return back();
        }

        if(! $this->authUserCanPurchaseCourse($course))
        {
            return back();
        }

        [$amount,$discounts] = $course->getFinalPrice(request()->code,true);
        if($amount <= 0){
            $this->courserepo->addStudentToCourse($course,auth()->id());
            newFeedback("عملیات موفقیت آمیز", "شما با موفقیت در دوره ثبت نام کردید.");
            return redirect($course->path());
        }
        $payment = PaymentService::generate($amount, $course, auth()->user(),$course->teacher_id,$discounts);

        resolve(Gateway::class)->redirect($payment->invoice_id);
    }

    //دوره هایی که قابل خریداری نیستند
    private function courseCanBePurchased(Course $course)
    {
        if($course->type == Course::TYPE_FREE)
        {
            dd('free');
            newFeedback("عملیات ناموفق", "دوره های رایگان قابل خریداری نیستند!", "error");
            return false;
        }

        if($course->status == Course::STATUS_LOCKED)
        {
            dd('قفل');
            newFeedback("عملیات ناموفق", "این دوره قفل شده است و قعلا قابل خریداری نیست!", "error");
            return false;
        }

        if($course->confirmation_status != Course::CONFIRMATION_STATUS_ACCEPTED)
        {
            dd('تایید');
            newFeedback("عملیات ناموفق", "دوره ی انتخابی شما هنوز تایید نشده است!", "error");
            return false;
        }

        return  true;
    }

    //کاربرانی که دوره را رایگان میتوانند ببینند
    private function authUserCanPurchaseCourse(Course $course)
    {
        if(auth()->id() == $course->teacher_id)
        {
            dd('مدرس');
            newFeedback("عملیات ناموفق", "شما مدرس این دوره هستید.", "error");
            return false;
        }

        if(auth()->user()->can("download",$course))
        {
            dd('دسترسی');
            newFeedback("عملیات ناموفق", "شما به دوره دسترسی دارید.", "error");
            return false;
        }

        return true;
    }
}
