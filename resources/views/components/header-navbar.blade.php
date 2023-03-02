<header>
    <a href="/">Strona Główna</a>
    <a href="/contact">Kontakt</a>
    @if(Auth::user())
        <a class="profile">
            <img src="{{ Auth::User() ? asset(Auth::user()->image_path) : '' }}" alt="" class="profile_img">
            <i class="fa-solid fa-angles-down"></i>
        </a>
    @else
        <a href="{{ route('login') }}">Logowanie</a>
    @endif
</header>