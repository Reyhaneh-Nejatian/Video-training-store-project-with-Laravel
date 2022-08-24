<?php

namespace arghavan\Front\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Course\Repositories\CourseRepo;
use arghavan\Course\Repositories\LessonRepo;
use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Models\User;
use Illuminate\Support\Str;

class FrontCotroller extends Controller
{
    public function index()
    {
        return view('Front::index');
    }

    public function singleCourse($slug,CourseRepo $courseRepo,LessonRepo $lessonRepo)
    {
        $courseId = $this->extractId($slug,'c');

        $course = $courseRepo->findById($courseId);

        $lessons = $lessonRepo->getAcceptedLessons($courseId);

        if(request()->lesson)
        {
            $lesson = $lessonRepo->getLesson($courseId,$this->extractId(request()->lesson,'l'));
        }else{
            $lesson = $lessonRepo->getFirstLesson($courseId);
        }


        return view('Front::singleCourse',compact('course','lessons','lesson'));
    }

    public function extractId($slug, $key)
    {
        return Str::before(Str::after($slug, $key .'-'), '-');
    }

    public function singleTutor($username)
    {
        $tutor = User::permission(Permission::PERMISSION_TEACH)->where('username',$username)->first();
        return view('Front::tutor',compact('tutor'));
    }
}
