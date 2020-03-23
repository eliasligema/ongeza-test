@extends('website')

@section('title', Helper::trans('auth.log_in', 'Log in'))

@section('content')
<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.log_id_to_account', 'Log in to your account') }}</h5>
        <form class="w-lg-40 m-auto" style="max-width:400px;" action="<?= url('login');?>" method="POST">
            {{ csrf_field() }}
            @include('auth.alert')

            <!-- <div class="form-group">
                <a href="#" class="btn btn-facebook btn-block"><i class="fab fa-facebook-f mr-2"></i> {{ Helper::trans('auth.use_facebook', 'Use facebook') }}</a>
            </div> -->

            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn px-3"><i class="fa fa-envelope"></i></div>
                    </span>
                    <input name="email" type="text" class="form-control border-0" value="<?= old('email');?>" placeholder="{{ Helper::trans('auth.email', 'E-mail') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn px-3"><i class="fa fa-key"></i></div> 
                    </span>
                    <input name="password" type="password" class="form-control border-0" placeholder="{{ Helper::trans('auth.password', 'Password') }}"/>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox text-left">
                    <input type="checkbox" name="remember_token" class="custom-control-input" id="remember_token">
                    <label class="custom-control-label pt-1" for="remember_token">{{ Helper::trans('auth.remember_me', 'Remember me') }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">{{ Helper::trans('auth.log_in', 'Log in') }}</button>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex align-items-center justify-content-between mb-0 ">
                <a href="<?= url('password/phone');?>" class="text-dark">{{ Helper::trans('auth.forget_password', 'Forget your password?') }}</a>
                <a href="<?= url('register');?>" class="text-dark">{{ Helper::trans('auth.register_now', 'Register now') }}</a>
            </div>
        </form>
    </div>
</div>


@endsection           