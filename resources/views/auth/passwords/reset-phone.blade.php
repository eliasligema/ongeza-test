@extends('website')

@section('title', Helper::trans('auth.reset', 'Reset'))

@section('content')

<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.reset_password', 'Reset your password') }}</h5>
        <form class="w-lg-40 m-auto" style="max-width:400px;" action="<?= url('password/change');?>" method="POST">
            {{ csrf_field() }}
            @include('auth.alert')
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-phone"></i></div>
                    </span>
                    <input name="phone" type="phone" class="form-control border-0" value="{{ isset($phone)? $phone: null }}" placeholder="{{ Helper::trans('auth.phone_number', 'Phone number') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-hashtag"></i></div>
                    </span>
                    <input name="verification_code" type="text" class="form-control border-0" placeholder="{{ Helper::trans('auth.verification_code', 'Verification code') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-lock"></i></div>
                    </span>
                    <input type="password" class="form-control border-0" name="password" placeholder="{{ Helper::trans('auth.password', 'Password') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-lock"></i></div> 
                    </span>
                    <input type="password" class="form-control border-0" name="password_confirmation" placeholder="{{ Helper::trans('auth.password_confirmation', 'Password Confirmation') }}">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">{{ Helper::trans('auth.reset', 'Reset') }}</button>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-0">
                <a class="text-dark" href="<?= url('login')?>">{{ Helper::trans('auth.back_to_login', 'Back to login') }}</a>
            </div>
        </form>
    </div>
</div>


@endsection           