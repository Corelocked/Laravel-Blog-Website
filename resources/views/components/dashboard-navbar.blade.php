<header>
    <a href="{{ $route }}"><i class="fa-solid fa-left-long"></i> Powrót</a>
    <div class="profile">
        <img src="{{ asset(Auth::user()->image_path) }}" alt="" class="profile_img">
        <i class="fa-solid fa-angles-down"></i>
    </div>
</header>