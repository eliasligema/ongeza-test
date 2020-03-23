@extends('website')

@section('title', Helper::trans('auth.registration', 'Registration'))

@section('content')
<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.register_account', 'Register your account now') }}</h5>

        <form class="w-lg-40 m-auto" style="max-width:400px;" action="<?= url('register');?>" method="POST">
            {{ csrf_field() }}
            @include('auth.alert')

            <!-- <div class="form-group">
                <a href="#" class="btn btn-facebook btn-block"><i class="fab fa-facebook-f mr-2"></i> Use facebook</a>
            </div> -->
            
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn border-right"><i class="fa fa-user"></i></div>
                    </span>
                    <input name="firstname" type="text" class="form-control border-0" value="<?= old('firstname');?>" placeholder="{{ Helper::trans('auth.first_name', 'First name') }}">
                    <span class="input-group-prepend">
                        <div class="btn border-left px-0"></div>
                    </span>
                    <input name="lastname" type="text" class="form-control border-0" value="<?= old('lastname');?>" placeholder="{{ Helper::trans('auth.last_name', 'Last name') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-phone"></i></div>
                    </span>
                    <input name="phone" type="phone" class="form-control border-0" value="<?= old('phone');?>" placeholder="{{ Helper::trans('auth.phone_number', 'Phone number') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-key"></i></div> 
                    </span>
                    <input name="password" type="password" class="form-control border-0" placeholder="Password"/>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-key"></i></div> 
                    </span>
                    <input name="password_confirmation" type="password" class="form-control border-0" placeholder="Confirm Password"/>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">{{ Helper::trans('auth.register', 'Register') }}</button>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-0 ">
                <a href="<?= url('login')?>" class="text-dark">{{ Helper::trans('auth.back_to_login', 'Back to login') }}</a>
            </div>
        </form>
    </div>
</div>


@endsection           