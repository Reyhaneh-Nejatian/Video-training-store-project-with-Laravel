<?php
function newFeedback($title = 'عملیات موفقیت آمیز',$body = 'عملیات با موفقیت انجام شد',$type = 'success'){

    $session = session()->has('feedbacks') ? session()->get('feedbacks')  : [];

    $session[] = ['title' => $title,'body' => $body,'type' => $type];

    session()->flash('feedbacks',$session);

}

function dateFromJalali($date,$format = "Y/m/d"){
    return $date ? \Morilog\Jalali\Jalalian::fromFormat($format,$date)->toCarbon() :null;
}

function getJalaliFormFormat($date,$format = 'Y-m-d')
{
    return \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::createFromFormat($format,$date))->format($format);
}

function createFromCarbon(\Carbon\Carbon $carbon)
{
    return \Morilog\Jalali\Jalalian::fromCarbon($carbon);
}
