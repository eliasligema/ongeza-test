@extends('website')

@section('title', Helper::trans('auth.verify', 'Verify'))

@section('content')
<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.verify_phone_number', 'Verify your phone number') }}</h5>

        <div class="w-lg-40 m-auto " style="max-width:400px;">
            @include('auth.alert')

            <form class="w-lg-40 m-auto" style="max-width:400px;" action="<?= url('phone/verify');?>" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="input-group border rounded" style="overflow:hidden;">
                        <input name="verification_code" type="text" class="form-control border-0" value="" placeholder="{{ Helper::trans('auth.code', 'Code') }}">
                        <span class="input-group-prepend">
                            <button type="submit" class="btn btn-primary px-3">{{ Helper::trans('auth.verify', 'Verify') }}</button>
                        </span>
                    </div>
                </div>
            </form>
            <div class="clearfix">
                <div class="text-secondary">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                        {{ Helper::trans('auth.verification_message', 'A fresh verification link has been sent to your phone.') }}
                        </div>
                    @endif

                    {{ Helper::trans('auth.verification_warning', 'Before proceeding, please check your phone for a verification code.') }}
                    {{ Helper::trans('auth.not_receive_message', 'If you did not receive the message') }}, <a class="text-dark" href="{{ url('phone/resend') }}">{{ Helper::trans('auth.request_another', 'click here to request another') }}</a>.
                </div>
            </div>

            <!-- <form class="form-group d-flex align-items-center justify-content-between mt-2 mb-0" action="<?= url('logout');?>" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-link text-light p-0">{{ Helper::trans('auth.switch_account', 'Switch account') }}</button>
            </form> -->
        </div>
    </div>
</div>



@endsection
