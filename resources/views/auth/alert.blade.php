@if (session('alert-success'))
<div class="clearfix">
    <div class="alert alert-success">
        <h5>{{ Helper::trans('auth.success', 'Success') }}</h5>
        <p>{{ session('alert-success') }}</p>
    </div>
</div>
@endif
@if (isset($errors) && count($errors) > 0)
<div class="clearfix">
    <div class="alert alert-danger">
        <h5>{{ Helper::trans('auth.please_check', 'Please check') }}</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif