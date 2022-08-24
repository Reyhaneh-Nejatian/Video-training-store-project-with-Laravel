<?php


namespace arghavan\Slider\Http\Controllers;


use App\Http\Controllers\Controller;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Media\Services\MediaFileService;
use arghavan\Slider\Database\Repositories\SlideRepo;
use arghavan\Slider\Http\Requests\SlideRequest;
use arghavan\Slider\Models\Slide;

class SlideControllers extends Controller
{
    public $slideRepo;
    public function __construct(SlideRepo $slideRepo)
    {
        $this->slideRepo = $slideRepo;
    }
    public function index()
    {
        $this->authorize('manage', Slide::class);
        $slides = $this->slideRepo->all();
        return view("Sliders::index",compact('slides'));
    }

    public function store(SlideRequest $request)
    {
        $this->authorize('manage', Slide::class);
        $request->request->add(['media_id' => MediaFileService::publicUpload($request->file('image'))->id]);
        $this->slideRepo->store($request);
        return redirect()->route('slides.index');
    }

    public function edit(Slide $slide)
    {
        $this->authorize('manage', Slide::class);
        return view('Sliders::edit',compact('slide'));
    }

    public function update(Slide $slide,SlideRequest $request)
    {
        $this->authorize('manage', Slide::class);
        if($request->hasFile('image'))
        {
            $request->request->add(['media_id' => MediaFileService::publicUpload($request->file('image'))->id]);

            if($slide->media)
            {
                $slide->media->delete();
            }
        }
        else
        {
            $request->request->add(['media_id' => $slide->media_id]);
        }

        $this->slideRepo->update($slide->id,$request);
        return redirect()->route('slides.index');
    }

    public function destroy(Slide $slide)
    {
        $this->authorize('manage', Slide::class);

        if($slide->media)
        {
            $slide->media->delete();
        }
        $slide->delete();

        return AjaxResponses::SuccessResponse();
    }


}
