<?php

namespace arghavan\Payment\Listeners;

class AddSellersShareToHisAccount
{

    public function __construct()
    {
        //
    }


    public function handle($event)
    {
        if($event->payment->seller)
        {
            dd('pp');
            $event->payment->seller->balance += $event->payment->seller_share;
            $event->payment->seller->save();
        }
    }
}
