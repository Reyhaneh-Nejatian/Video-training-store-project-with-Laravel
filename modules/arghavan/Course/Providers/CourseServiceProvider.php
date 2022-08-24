<?php
namespace arghavan\Course\Providers;



use arghavan\Course\Models\Course;
use arghavan\Course\Models\Lesson;
use arghavan\Course\Models\Season;
use arghavan\Course\Policies\CoursePolicy;
use arghavan\Course\Policies\LessonPolicy;
use arghavan\Course\Policies\SeasonPolicy;
use arghavan\RolePermissions\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__.'/../Routes/Courses_routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/Seasons_routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/Lessons_routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Courses');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/Lang');


        Gate::policy(Course::class,CoursePolicy::class);
        Gate::policy(Season::class,SeasonPolicy::class);
        Gate::policy(Lesson::class,LessonPolicy::class);

    }

    public function boot(){

        config()->set('sidebar.items.courses',[
            'icon' => 'i-courses',
            'title' => 'دوره ها',
            'url' => route('courses.index'),
            'permission' => [
                Permission::PERMISSION_MANAGE_OWN_COURSES,
                Permission::PERMISSION_MANAGE_OWN_COURSES
                ]
        ]);

    }
}
