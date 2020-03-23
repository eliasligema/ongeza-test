@extends('website')

@section('title', Helper::trans('auth.email', 'Email'))

@section('content')
<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.enter_your_email', 'Enter your email') }} </h5>
        <form class="w-lg-40 m-auto" style="max-width:400px;" action="<?= url('password/email');?>" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="input-group border rounded">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-envelope"></i></div>
                    </span>
                    <input name="email" type="email" class="form-control border-0" value="<?= old('email');?>" placeholder="{{ Helper::trans('auth.email_address', 'E-mail Address') }}">
                </div>
                @if(!empty($errors->first()))
                <div class="text-danger">
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">{{ Helper::trans('auth.send_email', 'Send email') }}</button>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-0">
                <a class="text-dark" href="<?= url('login')?>">{{ Helper::trans('auth.back_to_login', 'Back to login') }}</a>
            </div>
        </form>
    </div>
</div>


@endsection           