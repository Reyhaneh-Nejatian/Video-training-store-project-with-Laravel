<?php


namespace arghavan\Category\Tests\Feature;

use arghavan\Category\Models\Category;
use arghavan\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    public function test_permitted_user_can_see_categories_panel(){

        $this->actAsAdmin();

        $this->get(route('categories.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_categories_panel(){

        $this->actAsUser();

        $this->get(route('categories.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_create_category(){

        $this->withExceptionHandling();

        $this->actAsAdmin();

        $this->createCategory();

        $this->assertEquals(1, Category::all()->count());
    }

    public function test_permitted_user_can_update_category(){

        $newTitle = 'html';

        $this->withExceptionHandling();

        $this->actAsAdmin();

        $this->createCategory();

        $this->assertEquals(1,Category::all()->count());

        $this->patch(route('categories.update',1),[
            'title' => $newTitle,
            'slug' => $this->faker->word,
        ]);

        $this->assertEquals(1,Category::whereTitle($newTitle)->count());
    }

    public function test_user_can_see_delete_category(){

        $this->actAsAdmin();

        $this->createCategory();

        $this->assertEquals(1,Category::all()->count());

        $this->delete(route('categories.destroy',1))->assertOk();

    }

    private function actAsAdmin(){

        $this->actingAs(User::factory()->create());
        $this->seed(RolePermissionTableSeeder::class);
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);

    }

    private function actAsUser(){

        $this->actingAs(User::factory()->make());

        $this->seed(RolePermissionTableSeeder::class);

//        $this->actingAs(factory(User::class)->create());  //این دستور علاوه بر ایجاد یک یوزر ان را اکتیو می کند و لاگین میکند.
    }


    private function createCategory(){

        return $this->post(route('categories.store'),[
            'title' => $this->faker->word,
            'slug' => $this->faker->word,
        ]);
    }


}
