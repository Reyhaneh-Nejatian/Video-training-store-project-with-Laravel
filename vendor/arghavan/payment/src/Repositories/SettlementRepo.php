<?php


namespace arghavan\Payment\Repositories;


use arghavan\Payment\Models\Settlement;

class SettlementRepo
{
    private $query;
    public function __construct()
    {
        $this->query = Settlement::query();
    }


    public function store($request)
    {
        return Settlement::query()->create([
            "user_id" => auth()->id(),
            "to" => [
                "cart" => $request->cart,
                "name" => $request->name
            ],
            "amount" => $request->amount
        ]);
    }

    public function paginate()
    {
        return $this->query->paginate();
    }

    public function latest()
    {
        return $this->query->latest();
    }

    public function Settled()
    {
        $this->query->where('status',Settlement::STATUS_SETTLED);

        return $this;
    }

    public function find($id)
    {
        return $this->query->findOrFail($id);
    }

    public function update($id,$request)
    {
        return $this->query->where('id',$id)->update([
            "from" => [
                "name" => $request->from['name'],
                "cart" => $request->from['cart'],
            ],
            "to" => [
                "name" => $request->to['name'],
                "cart" => $request->to['cart'],
            ],
            "status" => $request->status,
        ]);
    }

    public function getLatestPendingSettlement($id)
    {
        return $this->query->whereId($id)
            ->where('status',Settlement::STATUS_PENDING)->latest()->first();
    }

    public function getLatestSettlement($id)
    {
        return $this->query->where('user_id',$id)->latest()->first();
    }

    public function paginateUserSettlements($userId)
    {
        return $this->query->where('user_id',$userId)->latest()->paginate();
    }


}
