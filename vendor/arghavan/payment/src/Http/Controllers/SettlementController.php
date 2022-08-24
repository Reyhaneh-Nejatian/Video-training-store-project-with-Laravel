<?php

namespace arghavan\Payment\Http\Controllers;



use App\Http\Controllers\Controller;
use arghavan\Payment\Http\Requests\SettlementRequest;
use arghavan\Payment\Models\Settlement;
use arghavan\Payment\Repositories\SettlementRepo;
use arghavan\Payment\Services\SettlementService;
use arghavan\RolePermissions\Models\Permission;


class SettlementController extends Controller
{
    public $settlementRepo;
    public function __construct(SettlementRepo $settlementRepo)
    {
        $this->settlementRepo = $settlementRepo;
    }

    public function index()
    {
        $this->authorize('index',Settlement::class);

        if(auth()->user()->can(Permission::PERMISSION_MANAGE_SETTLEMENTS))
        {
            $settlements = $this->settlementRepo->latest()->paginate();
        }else{
            $settlements = $this->settlementRepo->paginateUserSettlements(auth()->user());
        }

        return view('Payment::settlements.index',compact('settlements'));

    }

    public function create()
    {
        $this->authorize('store',Settlement::class);

        if($this->settlementRepo->getLatestPendingSettlement(auth()->id())){
            newFeedback("ناموفق", "شما یک درخواست تسویه در حال انتظار دارید و نمیتوانید درخواست جدیدی فعلا ثبت بکنید.", "error");
            return redirect()->route('settlements.index');
        }
        return view('Payment::settlements.create');
    }

    public function store(SettlementRequest $request)
    {
        $this->authorize('store',Settlement::class);

        if($this->settlementRepo->getLatestPendingSettlement(auth()->id())){
            newFeedback("ناموفق", "شما یک درخواست تسویه در حال انتظار دارید و نمیتوانید درخواست جدیدی فعلا ثبت بکنید.", "error");
            return redirect()->route('settlements.index');
        }

        SettlementService::store($request);

        return redirect(route('settlements.index'));
    }

    public function edit($settlementId)
    {
//        $requestedSettlement = $this->settlementRepo->find($settlementId);
        $settlement = $this->settlementRepo->find($settlementId);

        $settlementLatest = $this->settlementRepo->getLatestSettlement($settlement->user_id);

        $this->authorize('manage',Settlement::class);

        if($settlementLatest->id != $settlementId)
        {
            newFeedback("ناموفق", "این درخواست تسویه قابل ویرایش نیست و بایگانی شده است. فقط آخرین درخواست تسویه ی هر کاربر قابل ویرایش است.", "error");
            return  redirect()->route("settlements.index");
        }

        return view('Payment::settlements.edit',compact('settlement'));
    }

    public function update($settlementId,SettlementRequest $request)
    {
        $settlement = $this->settlementRepo->find($settlementId);

        $settlementLatest = $this->settlementRepo->getLatestSettlement($settlement->user_id);

        $this->authorize('manage',Settlement::class);

        if($settlementLatest->id != $settlementId)
        {
            newFeedback("ناموفق", "این درخواست تسویه قابل ویرایش نیست و بایگانی شده است. فقط آخرین درخواست تسویه ی هر کاربر قابل ویرایش است.", "error");
            return  redirect()->route("settlements.index");
        }

        SettlementService::update($settlementId,$request);

        return redirect(route('settlements.index'));
    }
}
