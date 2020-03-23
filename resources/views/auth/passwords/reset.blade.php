zzz@extends('website')

@section('title', Helper::trans('auth.reset', 'Reset'))

@section('content')

<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.reset_password', 'Reset your password') }}</h5>
        <form class="w-lg-40 m-auto" style="max-width:400px;" action="<?= url('password/reset');?>" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-envelope"></i></div>
                    </span>
                    <input name="email" type="email" class="form-control border-0" value="{{ $email }}" placeholder="{{ Helper::trans('auth.email_address', 'E-mail Address') }}">
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
                @if(!empty($errors->first()))
                <div class="text-danger">
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif
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