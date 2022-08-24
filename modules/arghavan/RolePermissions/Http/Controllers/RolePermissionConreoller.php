<?php

namespace arghavan\RolePermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\RolePermissions\Http\Requests\RoleRequest;
use arghavan\RolePermissions\Http\Requests\RoleUpdateRequest;
use arghavan\RolePermissions\Models\Role;
use arghavan\RolePermissions\Repositories\PermissionRepo;
use arghavan\RolePermissions\Repositories\RoleRepo;

class RolePermissionConreoller extends Controller
{
    private $roleRipo;
    private $permissionRipo;
    public function __construct(RoleRepo $roleRepo,PermissionRepo $permissionRepo){

        $this->roleRipo = $roleRepo;
        $this->permissionRipo = $permissionRepo;
    }

    public function index()
    {
        $this->authorize('index',Role::class);
        $roles = $this->roleRipo->all();
        $permissions = $this->permissionRipo->all();
        return view('RolePermissions::index',compact('roles','permissions'));
    }

    public function create()
    {
        //
    }


    public function store(RoleRequest $request)
    {
        $this->authorize('create',Role::class);
        $this->roleRipo->create($request);
        return redirect(route('role-permissions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($roleId)
    {
        $this->authorize('edit',Role::class);
        $role = $this->roleRipo->findById($roleId);
        $permissions = $this->permissionRipo->all();
        return view('RolePermissions::edit',compact('role','permissions'));
    }


    public function update(RoleUpdateRequest $request, $id)
    {
        $this->authorize('edit',Role::class);
        $this->roleRipo->update($id,$request);
        return redirect(route('role-permissions.index'));
    }


    public function destroy($id)
    {
        $this->authorize('delete',Role::class);
        $this->roleRipo->delete($id);
        return AjaxResponses::SuccessResponse();
    }
}
