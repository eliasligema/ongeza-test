
@extends('email')

@section('title', 'Alert')

@section('content')

<section>
    <div>
        @php
            $btn_text = "To confirm please click";
        @endphp
        <h1>Hi {{ $user->first_name.' '.$user->last_name }}!</h1>
        <p>You have been invited as {{ $role }} to {{ $campaign->name }}</p>
        <div style="padding:20px 0;">
            <center>
                <a href="<?= url('confirmation/assign/'.$id.'/'.$token);?>" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#f47721;border-top:10px solid #f47721;border-right:18px solid #f47721;border-bottom:10px solid #f47721;border-left:18px solid #f47721">{{ $btn_text }}</a>
            </center>
        </div>
        <hr>
        <p>
            If you’re having trouble clicking the "{{ $btn_text }}" button, copy and paste the URL below into your web browser: {{ url('') }}
        </p>
    </div>
</section>

@endsection