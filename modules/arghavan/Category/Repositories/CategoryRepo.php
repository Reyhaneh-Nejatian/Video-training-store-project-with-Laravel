<?php


namespace arghavan\Category\Repositories;


use arghavan\Category\Models\Category;

class CategoryRepo
{
   public function all(){

       return Category::all();
   }

//   public function allExceptById($id){
//
//       return $this->all()->filter(function ($item) use ($id){
//           return $item->id != $id;
//       });
//   }

//این متد بالا برابر است با متد پایین

    public function allExceptById($id){

        return $this->all()->filter(fn ($item) => $item->id != $id);
    }

   public function findById($id){

       return Category::findOrFail($id);
   }

   public function store($value){

       return Category::create([
           'title' => $value->title,
           'slug' => $value->slug,
           'parent_id' => $value->parent_id,
       ]);
   }

   public function update($id,$value){

       Category::whereId($id)->update([
           'title' => $value->title,
           'slug' => $value->slug,
           'parent_id' => $value->parent_id,
       ]);
   }

   public function delete($id){

       Category::whereId($id)->delete();
   }

    public function tree()
    {
        return Category::query()->where('parent_id',null)->with('subCategories')->get();
    }
}

