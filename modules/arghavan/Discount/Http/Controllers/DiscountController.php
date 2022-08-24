<?php


namespace arghavan\Discount\Http\Controllers;


use App\Http\Controllers\Controller;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Course\Models\Course;
use arghavan\Course\Repositories\CourseRepo;
use arghavan\Discount\Http\Requests\DiscountRequest;
use arghavan\Discount\Models\Discount;
use arghavan\Discount\Repositories\DiscountRepo;
use arghavan\Discount\Services\DiscountService;

class DiscountController extends Controller
{

    public $discountRepo;
    public $courseRepo;

    public function __construct(DiscountRepo $discountRepo,CourseRepo $courseRepo)
    {
        $this->discountRepo = $discountRepo;
        $this->courseRepo = $courseRepo;
    }
    public function index()
    {
        $this->authorize('manage',Discount::class);
        $discounts = $this->discountRepo->paginateAll();
        $courses = $this->courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);
        return view('Discounts::index',compact('courses','discounts'));
    }

    public function store(DiscountRequest $request)
    {
        $this->authorize('manage',Discount::class);
        $this->discountRepo->store($request->all());
        return redirect(route('discounts.index'));
    }

    public function edit($id)
    {
        $this->authorize('manage',Discount::class);
        $discount = $this->discountRepo->findById($id);

        $courses = $this->courseRepo->getAll(Course::CONFIRMATION_STATUS_ACCEPTED);

        return view('Discounts::edit',compact('discount','courses'));
    }

    public function update($discountId,DiscountRequest $request)
    {
        $this->authorize('manage',Discount::class);
        $this->discountRepo->update($discountId,$request->all());
        return redirect(route('discounts.index'));
    }

    public function destroy($id)
    {
        $this->authorize('manage',Discount::class);
        $this->discountRepo->delete($id);

        return AjaxResponses::SuccessResponse();
    }

    public function check($code,Course $course)
    {
        $discount = $this->discountRepo->getValidDiscountByCode($code,$course->id);

        if($discount)
        {
            $discountAmount = DiscountService::calculateDiscountAmount($course->getFinalPrice(),$discount->percent);

            $discountPercent = $discount->percent;
            $response = [
                "status" => 'valid',
                "payableAmount" => $course->getFinalPrice() - $discountAmount,  // هزینه قابل پرداخت
                "discountAmount" => $discountAmount,  // مبلغ تخفیف
                "discountPercent" => $discountPercent,  // درصد تخفیف
            ];
            return response()->json($response);
        }

        return response()->json([
            "status" => 'invalid',
        ])->setStatusCode(422);
    }
}
