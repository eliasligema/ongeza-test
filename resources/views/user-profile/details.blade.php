@extends('admin')

@section('title', 'User Profile')

@section('content')
<div class="main-container-middle">
    <div class="container-detail">
        <div class="container-fluid card-body px-5 mt-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="h3 m-0">My profile ( {{ $model->first_name }} {{ $model->last_name }} )</span>
            </div>

            <div class="card  card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="h3 m-0">Form</span>
                </div>  
                <form action="{{ url('user-profile/update') }}" method="POST">
                    {{ csrf_field() }}

                    <!----- Include view from components/alert----->
                    @component('components.alert')@endcomponent
                    <!----- End include view from components/alert----->

                    <div class="clearfix">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="firstname">First name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $model->first_name }}" placeholder="First name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="lastname">Second name</label>
                                    <input type="text" class="form-control" name="second_name" value="{{ $model->second_name }}" placeholder="Second name">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="lastname">Last name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $model->last_name }}" placeholder="Last name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username">Usernme</label>
                            <input type="text" class="form-control" name="username" value="{{ $model->username }}" placeholder="username">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $model->email }}" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ $model->phone }}" placeholder="Phone">
                        </div>
                        
                        <button class="btn btn-success" type="submit">Update</button>
                    </div>
                    
                </form>
            </div>

            
        </div>
    </div>
</div>


@endsection