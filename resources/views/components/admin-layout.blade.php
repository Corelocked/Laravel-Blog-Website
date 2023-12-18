<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
    <script src="https://kit.fontawesome.com/15901ecbea.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>
@if( Request::Url() == route('posts.create') OR $edit)
    <body style="display: block;">
@else
    <body>
@endif

    {{ $slot }}

    <x-user-panel />

</body>
</html>
