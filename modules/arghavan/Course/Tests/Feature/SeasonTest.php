<?php

namespace arghavan\Course\Tests\Feature;

use arghavan\Category\Models\Category;
use arghavan\Course\Models\Course;
use arghavan\Course\Models\Season;
use arghavan\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SeasonTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    //store
    public function test_permitted_user_can_see_course_details_page(){

        $this->withoutExceptionHandling();
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->get(route('courses.details',$course->id))->assertOk();

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->get(route('courses.details', $course->id))->assertOk();

        $this->actAsSuperAdmin();
        $this->get(route('courses.details',$course->id));
    }

    public function test_not_permitted_user_can_not_see_course_details_page(){
        $this->actAsAdmin();
        $course = $this->createCourse();

        $this->actAsUser();
        $this->get(route('courses.details',$course->id))->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('courses.details',$course->id))->assertStatus(403);

    }

    public function test_permitted_user_can_create_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title' => 'test1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'test2',
        ]);

        $this->assertEquals(2,Season::count());

        $this->assertEquals(2,Season::find(2)->number);
    }

    public function test_not_permitted_user_can_not_create_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();

        $this->actAsUser();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'test3',
        ])->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->post(route('seasons.store',$course->id),[
            'title' => 'test4',
        ])->assertStatus(403);

    }

    //edit
    public function test_permitted_user_can_see_edit_season_page(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->get(route('seasons.edit',1))->assertOk();

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->get(route('seasons.edit',1))->assertOk();
    }

    public function test_not_permitted_user_can_not_see_edit_season_page(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->actAsUser();
        $this->get(route('seasons.edit',1))->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('seasons.edit',1))->assertStatus(403);
    }

    public function test_permitted_user_can_update_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());

        $this->patch(route('seasons.update',1),[
            'title' => 'title2',
        ]);
        $this->assertEquals('title2',Season::find(1)->title);

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->patch(route('seasons.update',1),[
            'title' => 'title3',
            'number' => '5',
        ]);

        $this->assertEquals('title3',Season::find(1)->title);
        $this->assertEquals(5,Season::find(1)->number);
    }


    public function test_not_permitted_user_can_not_update_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());
        $this->actAsUser();
        $this->patch(route('seasons.update',1),[
            'title' => 'title3',
            'number' => '5',
        ])->assertStatus(403);
        $this->assertEquals('title1',Season::find(1)->title);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('seasons.update',1),[
            'title' => 'title3',
            'number' => '5',
        ])->assertStatus(403);
        $this->assertEquals('title1',Season::find(1)->title);
    }

    //delete
    public function test_permitted_user_can_delete_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());
        $this->delete(route('seasons.destroy',1));
        $this->assertEquals(0,Season::count());

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);
        $this->assertEquals(1,Season::count());
        $this->delete(route('seasons.destroy',2));
        $this->assertEquals(0,Season::count());
    }

    public function test_not_permitted_user_can_not_delete_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());
        $this->actAsUser();
        $this->delete(route('seasons.destroy',1))->assertStatus(403);
        $this->assertEquals(1,Season::count());

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->delete(route('seasons.destroy',1))->assertStatus(403);
        $this->assertEquals(1,Season::count());
    }

    //accept
    public function test_permitted_user_can_accept_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(1)->confirmation_status);
        $this->patch(route('seasons.accept',1))->assertOk();
        $this->assertEquals(Season::CONFIRMATION_STATUS_ACCEPTED,Season::find(1)->confirmation_status);

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title2',
            'number' => '2',
        ]);

        $this->assertEquals(2,Season::count());
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(2)->confirmation_status);
        $this->patch(route('seasons.accept',2))->assertStatus(403);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(2)->confirmation_status);
    }

    //reject
    public function test_permitted_user_can_reject_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(1)->confirmation_status);
        $this->patch(route('seasons.reject',1))->assertOk();
        $this->assertEquals(Season::CONFIRMATION_STATUS_REJECTED,Season::find(1)->confirmation_status);

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title2',
            'number' => '2',
        ]);
        $this->assertEquals(2,Season::count());
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(2)->confirmation_status);
        $this->patch(route('seasons.reject',2))->assertStatus(403);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(2)->confirmation_status);
    }

    //lock
    public function test_permitted_user_can_lock_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->assertEquals(1,Season::count());
        $this->assertEquals(Season::STATUS_OPENED,Season::find(1)->status);
        $this->patch(route('seasons.lock',1))->assertOk();
        $this->assertEquals(Season::STATUS_LOCKED,Season::find(1)->status);

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title2',
            'number' => '2',
        ]);
        $this->assertEquals(2,Season::count());
        $this->assertEquals(Season::STATUS_OPENED,Season::find(2)->status);
        $this->patch(route('seasons.lock',2))->assertStatus(403);
        $this->assertEquals(Season::STATUS_OPENED,Season::find(2)->status);
    }

    public function test_permitted_user_can_unlock_season(){

        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('seasons.store',$course->id),[
            'title' => 'title1',
            'number' => '1',
        ]);

        $this->patch(route('seasons.lock',1))->assertOk();

        $this->assertEquals(1,Season::count());

        $this->assertEquals(Season::STATUS_LOCKED,Season::find(1)->status);
        $this->patch(route('seasons.unlock',1))->assertOk();
        $this->assertEquals(Season::STATUS_OPENED,Season::find(1)->status);

        $this->patch(route('seasons.lock',1))->assertOk();

        $this->actAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course->teacher_id = auth()->id();
        $course->save();

        $this->assertEquals(Season::STATUS_LOCKED,Season::find(1)->status);
        $this->patch(route('seasons.unlock',1))->assertStatus(403);
        $this->assertEquals(Season::STATUS_LOCKED,Season::find(1)->status);
    }



    private function createUser()
    {
        $this->actingAs(User::factory()->create());
        $this->seed(RolePermissionTableSeeder::class);
    }

    private function actAsAdmin(){

        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COURSES);

    }

    private function actAsSuperAdmin(){

        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);

    }

    private function actAsUser(){

        $this->createUser();
    }

    private function createCourse()
    {
        $data = $this->courseData() + ['confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,];
        unset($data['image']);
        return Course::create($data);
    }

    private function createCategory()
    {
        return Category::create(['title' => $this->faker->word, "slug" => $this->faker->word]);
    }

    private function courseData()
    {
        $category = $this->createCategory();
        return[
            'title' => $this->faker->sentence(2),
            "slug" => $this->faker->sentence(2),
            'teacher_id' => auth()->id(),
            'category_id' => $category->id,
            "priority" => 12,
            "price" => 1200,
            "percent" => 70,
            "type" => Course::TYPE_FREE,
            "image" => UploadedFile::fake()->image('banner.jpg'),
            "status" => Course::STATUS_COMPLETED,
        ];
    }
}
