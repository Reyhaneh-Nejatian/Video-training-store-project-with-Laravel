<?php

namespace arghavan\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Category\Http\Requests\CategoryRequest;
use arghavan\Category\Models\Category;
use arghavan\Category\Repositories\CategoryRepo;
use arghavan\Common\Responses\AjaxResponses;

class CategoryConreoller extends Controller
{

    public $repo;
    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->repo = $categoryRepo;
    }

    public function index()
    {
        $this->authorize('manage',Category::class);
        $categories = $this->repo->all();
        return view('Categories::index',compact('categories'));
    }


    public function create()
    {
        //
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('manage',Category::class);
        $this->repo->store($request);
        return back();
    }


    public function show($id)
    {
        //
    }


    public function edit($categoryId)
    {
        $this->authorize('manage',Category::class);
        $category = $this->repo->findById($categoryId);
        $categories = $this->repo->allExceptById($categoryId);
        return view('Categories::edit',compact('category','categories'));
    }


    public function update($categoryId, CategoryRequest $request)
    {
        $this->authorize('manage',Category::class);
        $this->repo->update($categoryId,$request);


        return redirect(route('categories.index'));
    }


    public function destroy($categoryId)
    {
        $this->authorize('manage',Category::class);
        $this->repo->delete($categoryId);

        return AjaxResponses::SuccessResponse();

    }
}
