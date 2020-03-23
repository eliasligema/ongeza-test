@extends('admin')

@section('title', 'Reset')

@section('content')
<div class="clearfix p-5">
    <div class="container section-padding bg-white">
        <div class="text-center mb-4"><div class="fa fa-lock fa-2x"></div></div>
        <h5 class="text-center mb-4">Change your password</h5>
        <form class="w-25 m-auto" style="min-width:400px;" action="<?= url('user-profile/change-password');?>" method="POST">
            {{ csrf_field() }}

            <!----- Include view from components/alert----->
            @component('components.alert')@endcomponent
            <!----- End include view from components/alert----->

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <div class="input-group border">
                    <span class="input-group-prepend text-primary">
                        <div class="btn"><i class="fa fa-lock-open"></i></div>
                    </span>
                    <input type="password" class="form-control border-0" name="password_old" placeholder="Old password">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group border">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-key"></i></div>
                    </span>
                    <input type="password" class="form-control border-0" name="password" placeholder="New password">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group border">
                    <span class="input-group-prepend text-light">
                        <div class="btn"><i class="fa fa-key"></i></div> 
                    </span>
                    <input type="password" class="form-control border-0" name="password_confirmation" placeholder="New password Confirmation">
                </div>
                @if(!empty($errors->first()))
                <div class="text-danger">
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Change</button>
            </div>
        </form>
    </div>
</div>


@endsection           