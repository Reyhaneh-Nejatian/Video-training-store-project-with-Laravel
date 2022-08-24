@extends('User::Front.master')
@section('content')
    <form action="{{ route('password.update') }}" class="form" method="post">
        @csrf

        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="form-content form-account">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <input id="password" type="password" class="txt txt-l @error('password') is-invalid @enderror"
                   placeholder="رمز عبور جدید" name="password" required autocomplete="new-password" >
            @error('password')
            <span class="is-invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            <input id="password-confirm" type="password" class="txt txt-l" placeholder="تایید رمز عبور جدید"
                   name="password_confirmation" required autocomplete="new-password">

            <span class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای غیر الفبا مانند !@#$%^&*() باشد.</span>


            <br>
            <button type="submit" class="btn btn-recoverpass">بروزرسانی رمز عبور</button>
        </div>
        <div class="form-footer">
            <a href="{{ route('login') }}">صفحه ورود</a>
        </div>
    </form>

@endsection
