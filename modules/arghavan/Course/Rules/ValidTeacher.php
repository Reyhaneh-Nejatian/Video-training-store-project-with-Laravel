<?php


namespace arghavan\Course\Rules;


use arghavan\User\Repositories\UserRepo;
use Illuminate\Contracts\Validation\Rule;

class ValidTeacher implements Rule
{

    public function passes($attribute, $value)
    {
        $user = resolve(UserRepo::class)->findById($value);
        return $user->hasPermissionTo('teach');
    }

    public function message()
    {
        return "کاربر انتخاب شده یک مدرس معتبر نمی باشد.";
    }
}
