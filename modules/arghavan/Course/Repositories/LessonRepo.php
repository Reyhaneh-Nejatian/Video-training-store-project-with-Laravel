<?php


namespace arghavan\Course\Repositories;

use arghavan\Course\Models\Lesson;
use Illuminate\Support\Str;

class LessonRepo
{
    public function store($courseId,$values){

        return Lesson::create([
            "title" => $values->title,
            "slug" => $values->slug ? Str::slug($values->slug) : Str::slug($values->title),
            "time" => $values->time,
            'season_id' => $values->season_id,
            'media_id' => $values->media_id,
            'course_id' => $courseId,
            'user_id' => auth()->id(),
            'body' => $values->body,
            'number' => $this->generateNumber($values->number, $courseId),
            'confirmation_status' => Lesson::CONFIRMATION_STATUS_PENDING,
            'status' => Lesson::STATUS_OPENED,
            "is_free" => $values->is_free
        ]);
    }

    public function findById($id)
    {
        return Lesson::query()->findOrFail($id);
    }


    public function generateNumber($number, $courseId): int
    {
        $courseRepo = new CourseRepo();

        if (is_null($number)) {

            $number = $courseRepo->findById($courseId)->lessons()->orderby('number', 'desc')->firstOrNew([])->number ?: 0;
            $number++;
        }
        return $number;
    }

    public function update($id,$courseId,$values){

        return Lesson::query()->whereId($id)->update([

            "title" => $values->title,
            "slug" => $values->slug ? Str::slug($values->slug) : Str::slug($values->title),
            "time" => $values->time,
            'season_id' => $values->season_id,
            'media_id' => $values->media_id,
            'body' => $values->body,
            'number' => $this->generateNumber($values->number, $courseId),
            "is_free" => $values->is_free
        ]);

    }

    public function updateConfirmationStatus($id, string $status)
    {
        if(is_array($id)){

            return Lesson::query()->whereIn('id',$id)->update(['confirmation_status'=> $status]);
        }
        return Lesson::query()->whereId($id)->update(['confirmation_status' => $status]);
    }

    public function updateStatus($id, string $status){

        return Lesson::query()->whereId($id)->update(['status' => $status]);
    }

    public function getCourseSeassons($courseId)
    {
        return Lesson::query()->where('course_id',$courseId)
            ->where('confirmation_status',Lesson::CONFIRMATION_STATUS_ACCEPTED)
            ->orderBy('number')->get();
    }

    public function paginate($courseId)
    {
        return Lesson::query()->where('course_id',$courseId)->orderBy('number')->paginate();
    }

    public function acceptAll($courseId)
    {
        return Lesson::query()->where('course_id',$courseId)->update(['confirmation_status'=> Lesson::CONFIRMATION_STATUS_ACCEPTED]);
    }

    public function getAcceptedLessons(int $courseId)
    {
        return Lesson::query()->where('course_id',$courseId)
            ->where('confirmation_status' , Lesson::CONFIRMATION_STATUS_ACCEPTED)->get();
    }

    public function getFirstLesson(int $courseId)
    {
        return Lesson::query()->where('course_id',$courseId)
            ->where('confirmation_status' , Lesson::CONFIRMATION_STATUS_ACCEPTED)
            ->orderBy('number','asc')->first();
    }

    public function getLesson(int $courseId, int $lessonId)
    {
        return Lesson::query()->where('course_id',$courseId)->where('id',$lessonId)->first();
    }


}
