@extends('website')

@section('title', Helper::trans('auth.verify', 'Verify'))

@section('content')
<div class="clearfix p-3 p-lg-5">
    <div class="container section-padding text-center">
        <h5 class="text-center mb-4">{{ Helper::trans('auth.verify_your_email', 'Verify your email') }}</h5>

        <div class="w-lg-40 m-auto" style="max-width:400px;">
        @include('auth.alert')

            <div class="clearfix">
                <div class="text-secondary">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                        {{ Helper::trans('auth.verification_message', 'A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ Helper::trans('auth.verification_warning', 'Before proceeding, please check your email for a verification link.') }}
                    {{ Helper::trans('auth.not_receive_email', 'If you did not receive the email') }}, <a class="text-dark" href="{{ route('verification.resend') }}">{{ Helper::trans('auth.request_another', 'click here to request another') }}</a>.
                </div>
            </div>

            <form class="form-group d-flex align-items-center justify-content-between mt-2 mb-0" action="<?= url('logout');?>" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-link text-dark p-0">{{ Helper::trans('auth.swith_account', 'Swith account') }}</button>
            </form>
        </div>
    </div>
</div>



@endsection
