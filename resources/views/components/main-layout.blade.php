<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
    <script src="https://kit.fontawesome.com/15901ecbea.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header-navbar />
    
    {{ $slot }}

    @if(Auth::User())
        <x-user-panel />
    @endif

    <footer>
        <p>Mateusz Zaborski © 2023</p>
    </footer>

</body>
</html>