<?php
return [
    "MediaTypeServices" => [
        "image" => [
            "extensions" => [
                'png','jpg','jpeg'
            ],
            "handler" => \arghavan\Media\Services\ImageFileService::class
        ],
        "video" => [
            "extensions" =>[
                'avi','mp4','mkv'
            ],
            "handler" => \arghavan\Media\Services\VideoFileService::class
        ],
        "zip" => [
            "extensions" =>[
                'zip','rar','tar'
            ],
            "handler" => \arghavan\Media\Services\ZipFileService::class
        ],
    ]
];
