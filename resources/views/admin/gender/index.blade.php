@extends('admin')

@section('title', 'Gender')

@section('content')


<div class="main-container-middle">
    <div class="container-header container-customer" style="overflow:visible;">
        <div class="d-flex align-items-center justify-content-between p-3">
            <span class="h5 m-0">{{ Helper::trans('gender.list_tile', 'Gender list') }}</span>
            <div>
                <a href="{{ url('admin/gender/create') }}" class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i> Add new</a>
            </div>
        </div>
    </div>
    <div class="container-detail">
        <div class="container-customer">
            {!! \App\Handlers\Admin\Gender\IndexTableHandler::handler(request(), false) !!}
        </div>
    </div>
</div>


@endsection
