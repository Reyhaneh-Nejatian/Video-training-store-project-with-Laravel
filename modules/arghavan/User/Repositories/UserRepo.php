<?php


namespace arghavan\User\Repositories;


use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Models\User;

class UserRepo
{
    public function findByEmail($email){

        return User::query()->where('email',$email)->first();
    }

    public function getTeachers(){

        return User::permission(Permission::PERMISSION_TEACH)->get();
    }

    public function findById($id){

        return User::findOrFail($id);
    }

    public function paginate()
    {
        return User::query()->paginate();
    }

    public function update($userId, $values)
    {
        $update =[
            'name' => $values->name,
            'email' => $values->email,
            'mobile' => $values->mobile,
            'username' => $values->username,
            'headline' => $values->headline,
            'status' => $values->status,
            'bio' => $values->bio,
            'image_id' => $values->image_id
        ];

        if(! is_null($values->password)){

            $update['password'] = bcrypt($values->password);
        }

        $user = $this->findById($userId);
        $user->syncRoles([]);
        if($values['role'])
            $user->assignRole($values['role']);

        return User::query()->whereId($userId)->update($update);
    }

    public function updateProfile($request)
    {
        auth()->user()->name = $request->name;
        auth()->user()->telegram = $request->telegram;

        if(auth()->user()->email != $request->email){

            auth()->user()->email = $request->email;
            auth()->user()->email_verified_at = null;
        }

        if(auth()->user()->hasPermissionTo(Permission::PERMISSION_TEACH)){

            auth()->user()->card_number = $request->card_number;
            auth()->user()->shaba = $request->shaba;
            auth()->user()->headline = $request->headline;
            auth()->user()->bio = $request->bio;
            auth()->user()->username = $request->username;
        }

        if ($request->password) {
            auth()->user()->password = bcrypt($request->password);
        }

        auth()->user()->save();
    }

    public function findByIdFullInfo($userId)
    {
        return User::query()->whereId($userId)
            ->with('settlement','payments','courses','purchases')
            ->firstOrFail();
    }


}
