@extends('User::Front.master')

@section('content')
    <form action="{{ route('register') }}" class="form" method="post">
        @csrf

        <a class="account-logo" href="index.html">
            <img src="img/weblogo.png" alt="">
        </a>
        <div class="form-content form-account">
            <input type="text" class="txt @error('name') is-invalid @enderror" placeholder="نام و نام خانوادگی *"
                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus >
            @error('name')
            <span class="is-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <input id="email" type="email" class="txt txt-l @error('email') is-invalid @enderror" placeholder="ایمیل *"
                   name="email" value="{{ old('email') }}" required autocomplete="email" >
            @error('email')
            <span class="is-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <input id="mobile" type="text" class="txt txt-l @error('mobile') is-invalid @enderror" placeholder="موبایل"
                   name="mobile" value="{{ old('mobile') }}" autocomplete="mobile" >
            @error('mobile')
            <span class="is-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <input id="password" type="password" class="txt txt-l @error('password') is-invalid @enderror"
                   placeholder="رمز عبور *" name="password" required autocomplete="new-password" >
            @error('password')
            <span class="is-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <input id="password-confirm" type="password" class="txt txt-l" placeholder="تایید رمز عبور *"
                   name="password_confirmation" required autocomplete="new-password">

            <span class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای غیر الفبا مانند !@#$%^&*() باشد.</span>

            <br>
            <button class="btn continue-btn">ثبت نام و ادامه</button>

        </div>
        <div class="form-footer">
            <a href="{{ route('login') }}">صفحه ورود</a>
        </div>
    </form>
@endsection
