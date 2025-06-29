<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">

    <div class="login-form">
        <img src="{{ asset('images/picture3.jpg') }}">
        <form method="POST" action="{{ route('postlogin') }}">
            @csrf
            <div class="login-text">Login</div>
            @if(\Session::has('message'))
            <span class="error">
                {{ \Session::get('message') }}
            </span>
            @endif
            <label>Email</label>
            <input type="email" name="email">
            <span class="error">{{ $errors->first('email') }}</span>
            <label>Password</label>
            <input type="password" name="password">
            <span class="error">{{ $errors->first('password') }}</span>
            <input type="submit" value="Login">
        </form>
    </div>

</body>
</html>
