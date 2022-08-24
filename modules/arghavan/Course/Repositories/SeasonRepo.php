<?php


namespace arghavan\Course\Repositories;

use arghavan\Course\Models\Season;

class SeasonRepo
{
    public function store($id,$values){

        return Season::create([

            'course_id' => $id,
            'user_id' => auth()->id(),
            'title' => $values->title,
            'number' => $this->generateNumber($values->number, $id),
            'confirmation_status' => Season::CONFIRMATION_STATUS_PENDING,
            'status' => Season::STATUS_OPENED,
        ]);
    }

    public function findById($id)
    {
        return Season::query()->findOrFail($id);
    }

    public function update($id,$values)
    {
        return Season::query()->whereId($id)->update([
            'title' => $values->title,
            'number' => $this->generateNumber($values->number, $id)
        ]);
    }


    public function generateNumber($number, $courseId): int
    {
        $courseRepo = new CourseRepo();

        if (is_null($number)) {

            $number = $courseRepo->findById($courseId)->seasons()->orderby('number', 'desc')->firstOrNew([])->number ?: 0;
            $number++;
        }
        return $number;
    }

    public function updateConfirmationStatus($id, string $status)
    {
        return Season::query()->whereId($id)->update(['confirmation_status' => $status]);
    }

    public function updateStatus($id, string $status){

        return Season::query()->whereId($id)->update(['status' => $status]);
    }

    public function getCourseSeassons($courseId)
    {
        return Season::query()->where('course_id',$courseId)
            ->where('confirmation_status',Season::CONFIRMATION_STATUS_ACCEPTED)
            ->orderBy('number')->get();
    }

    public function findByIdandCourseId($seasonId,$courseId){

        return Season::query()->where('id',$seasonId)->where('course_id',$courseId)->first();
    }


}
