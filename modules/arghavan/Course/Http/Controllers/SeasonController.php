<?php

namespace arghavan\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Course\Http\Requests\SeasonRequest;
use arghavan\Course\Models\Season;
use arghavan\Course\Repositories\CourseRepo;
use arghavan\Course\Repositories\SeasonRepo;

class SeasonController extends Controller
{
    public $seasonRepo;
    public $courseRepo;
    public function __construct(SeasonRepo $seasonRepo ,CourseRepo $courseRepo){

        $this->seasonRepo = $seasonRepo;
        $this->courseRepo = $courseRepo;

    }

    public function store($courseId,SeasonRequest $request){

        $this->authorize('createSeason',$this->courseRepo->findById($courseId));
        $this->seasonRepo->store($courseId,$request);

        newFeedback();

        return back();
    }

    public function edit($id){

        $season = $this->seasonRepo->findById($id);
        $this->authorize('edit',$season);
        return view('Courses::seasons.edit',compact('season'));
    }

    public function update($id,SeasonRequest $request){

        $this->authorize('edit',$this->seasonRepo->findById($id));
        $this->seasonRepo->update($id,$request);

        newFeedback();

        return back();
    }

    public function destroy($id){

        $season = $this->seasonRepo->findById($id);
        $this->authorize('delete',$season);
        $season->delete();
        return AjaxResponses::SuccessResponse();
    }

    public function accept($id){

        $this->authorize('change_confirmation_status',Season::class);

        if($this->seasonRepo->updateConfirmationStatus($id,Season::CONFIRMATION_STATUS_ACCEPTED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function reject($id){

        $this->authorize('change_confirmation_status',Season::class);

        if($this->seasonRepo->updateConfirmationStatus($id,Season::CONFIRMATION_STATUS_REJECTED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function lock($id){

        $this->authorize('change_confirmation_status',Season::class);

        if($this->seasonRepo->updateStatus($id,Season::STATUS_LOCKED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function unlock($id){

        $this->authorize('change_confirmation_status',Season::class);

        if($this->seasonRepo->updateStatus($id,Season::STATUS_OPENED)){

            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }
}
