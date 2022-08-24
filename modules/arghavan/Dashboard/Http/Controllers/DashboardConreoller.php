<?php

namespace arghavan\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Payment\Repositories\PaymentRepo;

class DashboardConreoller extends Controller
{
    public function home(PaymentRepo $paymentRepo){

        $totalSales = $paymentRepo->getUserTotalSuccessAmount(auth()->id());

        $totalBenefit = $paymentRepo->getUserTotalBenefit(auth()->id());

        $totalSiteShare = $paymentRepo->getUserTotalSiteShare(auth()->id());

        $todayBenefit = $paymentRepo->getUserTotalBenefitByDay(auth()->id(),now());

        $last30DaysBenefit = $paymentRepo->getUserTotalBenefitByPeriod(auth()->id(),now(),now()->addDays(-30));

        $todaySuccessPaymentsTotal = $paymentRepo->getUserTotalSellByDay(auth()->id(),now());

        $todaySuccessPaymentsCount = $paymentRepo->getUserSellCountByDay(auth()->id(),now());

        $payments = $paymentRepo->paymentsBySellerId(auth()->id())->paginate();

        $last30DaysTotal = $paymentRepo->getLastNDaysTotal(-30);

        $last30DaysSellerShare = $paymentRepo->getLastNDaysSellerShare(-30);

        $totalSell = $paymentRepo->getLastNDaysTotal();


        $dates = collect();
        foreach (range(-30, 0) as $i) {
            $dates->put(now()->addDays($i)->format("Y-m-d"),0);
        }

        $summery =  $paymentRepo->getDailySummery($dates,auth()->id());

        return view('Dashboard::index',compact('totalSales','totalBenefit','totalSiteShare',
            'todayBenefit','last30DaysBenefit','todaySuccessPaymentsTotal','todaySuccessPaymentsCount',
            'payments','last30DaysTotal','last30DaysSellerShare','totalSell','dates','summery'));
    }
}
