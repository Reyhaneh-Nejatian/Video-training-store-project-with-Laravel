<?php


namespace arghavan\Course\Http\Controllers;


use App\Http\Controllers\Controller;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Course\Http\Requests\LessonRequest;
use arghavan\Course\Models\Course;
use arghavan\Course\Models\Lesson;
use arghavan\Course\Repositories\CourseRepo;
use arghavan\Course\Repositories\LessonRepo;
use arghavan\Course\Repositories\SeasonRepo;
use arghavan\Media\Services\MediaFileService;
use Illuminate\Http\Request;

class LessonController extends Controller
{

    public $seasonRepo;
    public $courseRepo;
    public $lessonRepo;
    public function __construct(SeasonRepo $seasonRepo ,CourseRepo $courseRepo,LessonRepo $lessonRepo)
    {
        $this->seasonRepo = $seasonRepo;
        $this->courseRepo = $courseRepo;
        $this->lessonRepo = $lessonRepo;
    }

    public function create($courseId)
    {
        $course = $this->courseRepo->findById($courseId);
        $this->authorize('createLesson',$course);
        $seasons = $this->seasonRepo->getCourseSeassons($courseId);

        return view('Courses::lessons.create',compact('seasons','course'));
    }

    public function store($courseId,LessonRequest $request)
    {
        $course = $this->courseRepo->findById($courseId);
        $this->authorize('createLesson',$course);
        $request->request->add(["media_id" => MediaFileService::privateUpload($request->file('lesson_file'))->id]);
        $this->lessonRepo->store($courseId,$request);
        newFeedback();
        return redirect(route('courses.details', $course->id));

    }

    public function edit($courseId,$lessonId)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('edit',$lesson);
        $seasons = $this->seasonRepo->getCourseSeassons($courseId);
        $course = $this->courseRepo->findById($courseId);
        return view('Courses::lessons.edit',compact('lesson','seasons','course'));

    }

    public function update($courseId,$lessonId,LessonRequest $lessonRequest)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('edit',$lesson);

        if($lessonRequest->hasFile('lesson_file')){

            if($lesson->media)
                $lesson->media->delete();

            $lessonRequest->request->add(['media_id' => MediaFileService::privateUpload($lessonRequest->file('lesson_file'))->id]);
        }else{
            $lessonRequest->request->add(['media_id' => $lesson->media_id]);
        }

        $this->lessonRepo->update($lessonId,$courseId,$lessonRequest);

        newFeedback();
        return redirect(route('courses.details',$courseId));
    }

    public function accept($id)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->updateConfirmationStatus($id,Lesson::CONFIRMATION_STATUS_ACCEPTED);
        return AjaxResponses::SuccessResponse();
    }

    public function acceptAll($courseId)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->acceptAll($courseId);
        newFeedback();
        return back();
    }

    public function acceptMultiple(Request $request)
    {
        $this->authorize('manage',Course::class);
        $ids = explode(',',$request->ids);
        $this->lessonRepo->updateConfirmationStatus($ids,Lesson::CONFIRMATION_STATUS_ACCEPTED);
        newFeedback();
        return back();
    }



    public function reject($id)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->updateConfirmationStatus($id,Lesson::CONFIRMATION_STATUS_REJECTED);
        return AjaxResponses::SuccessResponse();
    }

    public function rejectMultiple(Request $request)
    {
        $this->authorize('manage',Course::class);
        $ids = explode(',',$request->ids);
        $this->lessonRepo->updateConfirmationStatus($ids,Lesson::CONFIRMATION_STATUS_REJECTED);
        newFeedback();
        return back();
    }

    public function lock($id)
    {
        $this->authorize('manage',Course::class);
        if($this->lessonRepo->updateStatus($id,Lesson::STATUS_LOCKED))
        {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FaileResponse();
    }

    public function unlock($id)
    {
        $this->authorize('manage',Course::class);
        if($this->lessonRepo->updateStatus($id,Lesson::STATUS_OPENED))
        {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FaileResponse();
    }

    public function destroy($courseId,$lessonId)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('delete',$lesson);
        if($lesson->media)
            $lesson->media->delete();
        $lesson->delete();
        return AjaxResponses::SuccessResponse();
    }

    public function destroyMultiple(Request $request)
    {
        $ids = explode(',',$request->ids);
        foreach ($ids as $id){

            $lesson = $this->lessonRepo->findById($id);
            $this->authorize('delete',$lesson);
            if($lesson->media)
                $lesson->media->delete();
            $lesson->delete();
        }
        newFeedback();
        return back();
    }
}
