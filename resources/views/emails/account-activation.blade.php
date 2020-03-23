
@extends('email')

@section('title', '404')

@section('content')

<section>
    <div>
        @php
            $btn_text = "Activate account";
            $btn_link = url('confirmation/accaount-activation/'.$user->id.'/'.$token);
        @endphp
        <h1>Hi {{ $user->first_name.' '.$user->last_name }}!</h1>
        <p>You have signup to {{ config('app.name', 'WEZESHA sasa') }}, please click the button below to activate your account</p>
        <div style="padding:20px 0;">
            <center>
                <a href="{{ $btn_link }}" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#f47721;border-top:10px solid #f47721;border-right:18px solid #f47721;border-bottom:10px solid #f47721;border-left:18px solid #f47721">{{ $btn_text }}</a>
            </center>
        </div>
        <hr style="height:1px;border:0;background:#eaeaea;">
        <p>
            If you’re having trouble clicking the "{{ $btn_text }}" button, 
            copy and paste the URL below into your web browser: {{ $btn_link }}
        </p>
    </div>
</section>

@endsection