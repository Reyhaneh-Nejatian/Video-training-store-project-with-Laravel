<?php


namespace arghavan\Media\Models;


use arghavan\Media\Services\MediaFileService;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $casts = [
        'files' => 'json'
    ];

    public static function booted()
    {
        static::deleting(function ($media){
            MediaFileService::delete($media);
        });
    }

    public function getThumbAttribute()
    {
        return MediaFileService::thumb($this);
    }

    public function getUrl($quality = "original"): string
    {
        return "/storage/" . $this->files[$quality];
    }
}
