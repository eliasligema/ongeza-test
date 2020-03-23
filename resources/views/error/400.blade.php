
@extends($layout)

@section('title', '400')

@section('content')

<style>
.error-code {
    font-size: 8rem;
    font-weight: bold;
}

.error-text {
    font-size: 2rem;
}
</style>

<section class="text-center py-5">
    <div class="container">
        <h1 class="error-code display-3">{{ $code }}</h1>
        <h5 class="error-text display-4">{{ $message }}</h5>
        <p>oops! {{ $message }}</p>

        <div class="clearfix">
            <a href="{{ URL::previous() }}" class="btn btn-dark btn-lg mt-4">Go back</a>
            <a href="{{ url('/') }}" class="btn btn-light btn-lg mt-4">Home</a>
        </div>
    </div>
</section>
@endsection

        