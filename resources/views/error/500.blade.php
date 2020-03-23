
@extends($layout)

@section('title', '404')

@section('content')

<style>
.error-code {
    font-size: 20rem;
    font-weight: bold;
}

.error-text {
    font-size: 2rem;
}
</style>

<section class="text-center py-5">
    <div class="container">
        <h1 class="error-code display-1">500</h1>
        <h5 class="error-text display-4">Internal error</h5>
        <p>oops! internal error</p>
        
        <div class="clearfix">
            <a href="{{ URL::previous() }}" class="btn btn-dark btn-lg mt-4">Go back</a>
            <a href="{{ url('/') }}" class="btn btn-light btn-lg mt-4">Home</a>
        </div>
    </div>
</section>
@endsection

        