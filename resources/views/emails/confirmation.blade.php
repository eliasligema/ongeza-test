
@extends('email')

@section('title', 'Confirmation')

@section('content')

<section>
    <div>
        <h1>{{ config('app.name') }}</h1>
        <h5>{{ $user->firstname.' '.$user->lastname }}</h5>
        <p>You have been invited as {{ $role }} to {{ $campaign->name }}</p>
        <a href="<?= url('confirmation/assign/'.$id.'/'.$token);?>" >To confirm please click</a>
    </div>
</section>

@endsection