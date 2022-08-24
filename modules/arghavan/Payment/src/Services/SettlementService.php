<?php

namespace arghavan\Payment\Services;

use arghavan\Payment\Models\Settlement;
use arghavan\Payment\Repositories\SettlementRepo;

class SettlementService
{
    public static function store($data)
    {
        $repo = new SettlementRepo();
        $repo->store($data);

        auth()->user()->balance -= $data->amount;
        auth()->user()->save();
    }
    public static function update($settlementId,$data)
    {
        $repo = new SettlementRepo();
        $settlement = $repo->find($settlementId);
        if(!in_array($settlement->status,[Settlement::STATUS_CANCELLED,Settlement::STATUS_REJECTED]) &&
            in_array($data->status,[Settlement::STATUS_CANCELLED,Settlement::STATUS_REJECTED]))
        {
            $settlement->user->balance += $settlement->amount;
            $settlement->user->save();
        }


        if(in_array($settlement->status,[Settlement::STATUS_CANCELLED,Settlement::STATUS_REJECTED]) &&
            in_array($data->status,[Settlement::STATUS_SETTLED,Settlement::STATUS_PENDING]))
        {
            if($settlement->user->balance < $settlement->amount)
            {
                newFeedback("ناموفق","موجودی حساب کاربر کافی نمیباشد!",'error');
                return;
            }

            $settlement->user->balance -= $settlement->amount;
            $settlement->user->save();
        }
        $repo->update($settlementId,$data);
    }
}



