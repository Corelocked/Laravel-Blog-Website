<header>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('contact') }}">Contact</a>
    @if(Auth::user())
        <a class="profile">
            <img src="{{ Auth::User() ? asset(Auth::user()->image_path) : '' }}" alt="profile" class="profile_img">
            <i class="fa-solid fa-angles-down"></i>
            <span class="notifications_count">{{ $unreadNotifications }}</span>
        </a>
    @else
        <a href="{{ route('login') }}">Logout</a>
    @endif
</header>
