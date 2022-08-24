<?php


namespace arghavan\Course\Repositories;


use arghavan\Course\Models\Course;
use arghavan\Course\Models\Lesson;
use Illuminate\Support\Str;

class CourseRepo
{
    public function store($values){

        return Course::create([
            'teacher_id' => $values->teacher_id,
            'category_id' => $values->category_id,
            'banner_id' => $values->banner_id,
            'title' => $values->title,
            'slug' => Str::slug($values->slug),
            'priority' => $values->priority,
            'price' => $values->price,
            'percent' => $values->percent,
            'type' => $values->type,
            'status' => $values->status,
            'body' => $values->body,
            'confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,
        ]);
    }

    public function paginate()
    {
        return Course::paginate();
    }

    public function findById($id)
    {
        return Course::query()->findOrFail($id);
    }

    public function update($id, $values)
    {
        return Course::query()->whereId($id)->update([
            'teacher_id' => $values->teacher_id,
            'category_id' => $values->category_id,
            'banner_id' => $values->banner_id,
            'title' => $values->title,
            'slug' => Str::slug($values->slug),
            'priority' => $values->priority,
            'price' => $values->price,
            'percent' => $values->percent,
            'type' => $values->type,
            'status' => $values->status,
            'body' => $values->body,
        ]);
    }

    public function updateConfirmationStatus($id, string $status)
    {
        return Course::query()->whereId($id)->update(['confirmation_status' => $status]);
    }

    public function updateStatus($id, string $status){

        return Course::query()->whereId($id)->update(['status' => $status]);
    }

    public function getCoursesByTeacherId(int $id)
    {
        return Course::query()->where('teacher_id',$id)->get();
    }

    public function latestCourses()
    {
        return Course::query()->where('confirmation_status',Course::CONFIRMATION_STATUS_ACCEPTED)
            ->latest()->take(8)->get();
    }

    public function getDuration($id)
    {
        return $this->getLessonsQuery($id)->sum('time');
    }

    public function getlessonsCount($id)
    {
        return $this->getLessonsQuery($id)->count();
    }

    public function addStudentToCourse(Course $course,$studentId)
    {
        if(!$this->getCourseStudentById($course,$studentId)){

            $course->students()->attach($studentId);
        }
    }

    public function getCourseStudentById(Course $course,$studentId)
    {
        return $course->students()->whereId($studentId)->first();
    }

    public function hasStudent(Course $course,$student_id)
    {
        return $course->students->contains($student_id);
    }

    public function getLessons($id)
    {
        return $this->getLessonsQuery($id)->get();
    }

    public function getLessonsQuery($id)
    {
        return Lesson::query()->where('course_id',$id)
            ->where('confirmation_status',Lesson::CONFIRMATION_STATUS_ACCEPTED);
    }

    public function getAll(string $status = null)
    {
        $query = Course::query();
        if($status) $query->where('confirmation_status',$status)->get();

        return $query->latest()->get();
    }
}
