<?php

namespace arghavan\User\Models;

use arghavan\Comment\Models\Comment;
use arghavan\Course\Models\Course;
use arghavan\Course\Models\Lesson;
use arghavan\Course\Models\Season;
use arghavan\Media\Models\Media;
use arghavan\Payment\Models\Payment;
use arghavan\Payment\Models\Settlement;
use arghavan\RolePermissions\Models\Role;
use arghavan\Ticket\Models\Reply;
use arghavan\Ticket\Models\Ticket;
use arghavan\User\Notifications\ResetPasswordRequestNotification;
use arghavan\User\Notifications\VerifyMailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

   const STATUS_ACTIVE = 'active';
   const STATUS_INACTIVE = 'inactive';
   const STATUS_BAN = 'ban';

   public static $statuses = [
       self::STATUS_ACTIVE,
       self::STATUS_INACTIVE,
       self::STATUS_BAN
   ];

   public static $defultUsers = [
       [
           'email' => 'admin@admin.com',
           'password' => 'admin',
           'name' => 'Admin',
           'role' => Role::ROLE_SUPER_AMIN
       ]
   ];


    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMailNotification());
    }

    public function sendResetPasswordRequestNotification(){

        $this->notify(new ResetPasswordRequestNotification());
    }

    public function image(){

        return $this->belongsTo(Media::class,'image_id');
    }

    public function courses(){

        return $this->hasMany(Course::class,'teacher_id');
    }

    public function profilePath(){

        return null;
        return $this->username ? route('viewProfile',$this->username) : route('viewProfile','username');
    }

    public function seasons(){

        return $this->hasMany(Season::class);
    }

    public function lessons(){

        return $this->hasMany(Lesson::class);
    }

    public function getThumbAttribute(){

        if($this->image)

        return '/storage/'.$this->image->files[300];

        return '/panel/img/profile.jpg';

    }

    public function hasAccessToCourse(Course $course)
    {
        if($this->can('manage',Course::class)||
        $this->id == $course->teacher_id||
        $course->students->contains($this->id)) return true;

        return false;
    }

    public function purchases()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'buyer_id');
    }

    public function settlement()
    {
        return $this->hasMany(Settlement::class);
    }

    public function studentsCount()
    {
        return DB::table("courses")
            ->select("course_id")->where("teacher_id",$this->id)
            ->join("course_user","courses.id", "=" , "course_user.course_id")->count();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketReplies()
    {
        return $this->hasMany(Reply::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
