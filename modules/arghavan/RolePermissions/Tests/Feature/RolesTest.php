<?php


namespace arghavan\RolePermissions\Tests\Feature;


use arghavan\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use arghavan\RolePermissions\Models\Permission;
use arghavan\RolePermissions\Models\Role;
use arghavan\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // permitted user can see Role index
    public function test_permitted_user_can_see_index(){

        $this->actAsAdmin();
        $this->get(route('role-permissions.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_index(){

        $this->actAsUser();
        $this->get(route('role-permissions.index'))->assertStatus(403);
    }


    // permitted user can create Role
    public function test_permitted_user_can_store_role(){

        $this->actAsAdmin();
        $this->post(route('role-permissions.store'), [
            "name" => "testtest",
            "permissions" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_TEACH
                ]
        ])->assertRedirect(route('role-permissions.index'));

        $this->assertEquals(count(Role::$roles)+1,Role::count());
    }

    public function test_Normal_user_can_not_store_role(){

        $this->actAsUser();
        $this->post(route('role-permissions.store'), [
            "name" => "testtest",
            "permissions" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_TEACH
            ]
        ])->assertStatus(403);

        $this->assertEquals(count(Role::$roles),Role::count());
    }



    // permitted user can see Role edit
    public function test_permitted_user_can_see_edit(){

        $this->actAsAdmin();
        $role = $this->createRole();
        $this->get(route('role-permissions.index',$role->id))->assertOk();
    }

    public function test_normal_user_can_not_see_edit(){

        $this->actAsUser();
        $role = $this->createRole();
        $this->get(route('role-permissions.index',$role->id))->assertStatus(403);
    }


    // permitted user can update Role
    public function test_permitted_user_can_update_role(){

        $this->actAsAdmin();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            "name" => "testtest2323",
            "id" => $role->id,
            "permissions" => [
                Permission::PERMISSION_TEACH
            ]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals("testtest2323", $role->fresh()->name);
    }

    public function test_Normal_user_can_not_update_role(){

        $this->actAsUser();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update',$role->id), [
            "name" => "testtest0121",
            "id" => $role->id,
            "permissions" => [
                Permission::PERMISSION_TEACH
            ]
        ])->assertStatus(403);

        $this->assertEquals($role->name, $role->fresh()->name);
    }


    // permitted user can delete role
    public function test_permitted_user_can_delete_role(){

        $this->actAsAdmin();
        $role = $this->createRole();

        $this->delete(route('role-permissions.destroy',$role->id))->assertOk();
        $this->assertEquals(count(Role::$roles),Role::count());
    }

    public function test_normal_user_can_not_delete_role(){

        $this->actAsUser();
        $role = $this->createRole();

        $this->delete(route('role-permissions.destroy',$role->id))->assertStatus(403);
        $this->assertEquals(count(Role::$roles)+1,Role::count());
    }




    private function createUser()
    {
        $this->actingAs(User::factory()->create());
        $this->seed(RolePermissionTableSeeder::class);
    }

    private function actAsAdmin(){

        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSIONS);

    }

    private function actAsSuperAdmin(){

        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);

    }

    private function actAsUser(){

        $this->createUser();
    }

    public function createRole()
    {
        return Role::create(["name" => "testtest",])->syncPermissions([ Permission::PERMISSION_MANAGE_COURSES,
            Permission::PERMISSION_TEACH]);
    }

}
