<?php


namespace arghavan\Discount\Repositories;


use arghavan\Discount\Models\Discount;
use Morilog\Jalali\Jalalian;

class DiscountRepo
{
    public function store($data)
    {
        $discount = Discount::query()->create([
            "user_id" => auth()->id(),
            "code" => $data['code'],
            "percent" => $data['percent'],
            "usage_limitation" => $data['usage_limitation'],
            "expire_at" => $data['expire_at'] ? Jalalian::fromFormat('Y/m/d H:i',$data['expire_at'])->toCarbon() : null,
            "link" => $data['link'],
            "type" => $data['type'],
            "description" => $data['description'],
            "uses" => 0,
        ]);

        if($discount->type == Discount::TYPE_SPECIAL)
        {
            $discount->courses()->sync($data['courses']);
        }
    }

    public function paginateAll()
    {
        return Discount::query()->latest()->paginate();
    }

    public function findById($id)
    {
        return Discount::query()->findOrFail($id);
    }

    public function delete($id)
    {
        Discount::query()->whereId($id)->delete();
    }

    public function update($discountId, array $data)
    {
        Discount::query()->whereId($discountId)->update([
            "code" => $data['code'],
            "percent" => $data['percent'],
            "usage_limitation" => $data['usage_limitation'],
            "expire_at" => $data['expire_at'] ? Jalalian::fromFormat('Y/m/d H:i',$data['expire_at'])->toCarbon() : null,
            "link" => $data['link'],
            "type" => $data['type'],
            "description" => $data['description'],
        ]);

        $discount = $this->find($discountId);
        if($discount->type == Discount::TYPE_SPECIAL)
        {
            $discount->courses()->sync($data['courses']);
        }else{
            $discount->courses()->sync([]);
        }
    }

    public function find($discountId)
    {
        return Discount::query()->find($discountId);
    }

    //نخفیف
    public function getValidDiscountsQuery($type = 'all',$courseId = null)
    {
        $query = Discount::query()->where('expire_at','>',now())
            ->where('type',$type)->whereNull('code');

        if($courseId){
            $query->whereHas('courses',function ($query) use ($courseId){
                $query->where('id',$courseId);
            });
        }

        $query->where(function ($query)
        {
            $query->where('usage_limitation','>','0')
                ->orWhereNull('usage_limitation');

        })->orderByDesc('percent');

        return $query;
    }

    public function getGlobalBiggerDiscount()  //بزرگ ترین کد تخفیف عمومی
    {
        return $this->getValidDiscountsQuery()->where('type','all')->first();
    }

    public function getCourseBiggerDiscount($courseId)
    {
        return $this->getValidDiscountsQuery(Discount::TYPE_SPECIAL,$courseId)->first();
    }

    //کد تخفیف
    public function  getValidDiscountByCode($code,$courseId)
    {
        return Discount::query()->where('code',$code)
            ->where(function ($query) use ($courseId)
            {
                return $query->whereHas('courses',function ($query) use ($courseId)
                {
                    return $query->where('id',$courseId);

                })->orWhereDoesntHave('courses');

            })->first();
    }

}
