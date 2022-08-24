<?php


namespace arghavan\Media\Services;


use arghavan\Media\Contracts\FileServiceContract;
use arghavan\Media\Models\Media;
use Illuminate\Support\Facades\Storage;

class VideoFileService extends DefaultFileService implements FileServiceContract
{
    public static function upload($file,$filename,$dir) :array{

        Storage::putFileAs($dir,$file,$filename. '.' .$file->getClientOriginalExtension());
        return ["video" => $filename. '.' .$file->getClientOriginalExtension()];
    }

    public static function thumb(Media $media)
    {
        return url('img/video-thumb.png');
    }

    public static function getFilename()
    {
        return (static::$media->is_private ? 'private/' : 'public/'). static::$media->files['video'];
    }
}
