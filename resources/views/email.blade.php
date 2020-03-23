<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'WEZESHA sasa') }}</title>   
</head>
<body style="margin:0;padding:0;background-color:#FFFFFF;font-family: sans-serif;">
    <center>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="margin:0;padding:0;color:#777777;background-color:#FFFFFF;font-family:sans-serif;">
            <tr>
                <td align="center" valign="top" bgcolor="#363752" style="padding:30px 20px">
                    <a href="{{url("/")}}">
                        <img width="240" src="{{asset("images/icons/wezeshasasa.png")}}" alt=""/>
                    </a>
                </td>
            </tr>

            <tr>
                <td align="center" valign="top">
                    <div style="width:100%;max-width:700px;padding:40px;text-align:left;">
                        @yield('content')
                    </div>
                </td>
            </tr>

            <tr>
                <td align="center" valign="top" bgcolor="#f47721" style="padding:30px 20px;color:#fff;">
                    <div style="width:100%;max-width:700px;padding:40px;">
                        &copy; {{ date('Y') }} {{ config('app.name', 'WEZESHA sasa') }}. All rights reserved.
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>