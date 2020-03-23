
@extends('error')

@section('title', '401')

@section('content')

<style>
.error-code {
    font-size: 20rem;
    font-weight: bold;
    margin-bottom: 0rem;
}

.error-text {
    font-size: 2rem;
}
</style>

<section class="text-center py-5">
    <div class="container">
        <h1 class="error-code ">403</h1>
        <h5 class="error-text">Unauthorized</h5>
        <p>You cannot access this page! This is for {{ isset($role)? $role: 'ADMIN' }} only</p>
        
        <div class="clearfix">
            <a href="{{ URL::previous() }}" class="btn btn-dark btn-lg mt-4">Go back</a>
            <a href="{{ url('/') }}" class="btn btn-light btn-lg mt-4">Home</a>
        </div>
    </div>
</section>
@endsection

        