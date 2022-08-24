<?php


namespace arghavan\Slider\Database\Repositories;


use arghavan\Slider\Models\Slide;

class SlideRepo
{

    public function all()
    {
        return Slide::query()->orderBy('priority')->get();
    }

    public function store($value)
    {
        return Slide::create([
            "user_id" => auth()->id(),
            'priority' => $value->priority,
            'media_id' => $value->media_id,
            'link' => $value->link,
            'status' => $value->status,
        ]);
    }

    public function update($id,$value)
    {
        Slide::where('id', $id)->update([
            'priority' => $value->priority,
            'media_id' => $value->media_id,
            'link' => $value->link,
            'status' => $value->status,
        ]);
    }

    public function delete($id)
    {
        Slide::query()->where('id',$id)->delete();
    }
}
