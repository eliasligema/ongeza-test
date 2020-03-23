@extends('admin')

@section('title', 'Dashboard')

@section('content')


<div class="main-container-middle">
    <div class="container-header container-customer" style="overflow:visible;">
        <div class="d-flex align-items-center justify-content-between p-3">
            <span class="h5 m-0">{{ Helper::trans('dashboard.title', 'Dashboard') }}</span>
        </div>
    </div>
    <div class="container-detail bg-white">
        <div class="container-customer ">
            <div class="">
                {!! \App\Handlers\Admin\Dashboard\IndexContentHandler::handler(request(), false) !!}
            </div>
        </div>
    </div>
</div>


@endsection
