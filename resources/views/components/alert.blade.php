@if(session('alert-success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5>Success</h5>
        <div><i class="fa fa-check-circle"></i> {!! session('alert-success') !!}</div>
    </div>
@endif

@if(session('alert-info'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5>Information</h5>
        <div><i class="fa fa-tiexclamationmes-circle"></i> {!! session('alert-info') !!}</div>
    </div>
@endif

@if(session('alert-warning'))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5>Warning</h5>
        <div><i class="fa fa-exclamation-triangle"></i> {!! session('alert-warning') !!}</div>
    </div>
@endif

@if(session('alert-fail'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5>Error</h5>
        <div><i class="fa fa-times-circle"></i> {!! session('alert-fail') !!}</div>
    </div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5>Error</h5>
    <div><i class="fa fa-times-circle"></i> Please fill the require fields</div>
    <!-- <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul> -->
</div>
@endif