<div class="modal">
    <div class="modal-profile">
        <span>Witaj!</span>
        <i class="fa-solid fa-circle-xmark close"></i>
        <p class="name">{{ Auth::User() ? Auth::User()->firstname . ' ' . Auth::User()->lastname : '' }}</p>
        <p class="role_profile">{{ Auth::User() ? Auth::User()->roles[0]->name : '' }}</p>
        <p class="info">Dostępne akcje</p>
        <a href="{{ route('dashboard') }}" class="button"><i class="fa-solid fa-wrench"></i><p>Panel</p></a>
        <a href="{{ route('profile') }}" class="button"><i class="fa-solid fa-id-card"></i><p>Profil</p></a>
        <div class="button toggle-mode" onClick="toggleMode();"><i class="fa-solid fa-moon"></i><p>Tryb Ciemny</p></div>
        <a href="{{ route('logout') }}" class="button"><i class="fa-solid fa-right-from-bracket"></i><p>Wyloguj</p></a>
        <p class="info">Szybkie akcje</p>
        @can('post-create')
            <a href="{{ route('posts.create') }}" class="button"><i class="fa-solid fa-plus"></i><p>Dodaj post</p></a>
        @endcan
        @can('category-create')
            <a href="{{ route('categories.create') }}" class="button"><i class="fa-solid fa-square-plus"></i><p>Dodaj kategorię</p></a>
        @endcan
        @can('user-create')
            <a href="{{ route('users.create') }}" class="button"><i class="fa-solid fa-user-plus"></i><p>Dodaj użytownika</p></a>
        @endcan
        @can('role-create')
            <a href="{{ route('roles.create') }}" class="button"><i class="fa-solid fa-wrench"></i><p>Dodaj role</p></a>
        @endcan
        <div class="line-1"></div>
        <div class="clock">
            <p class="info">Aktualny czas</p>
            <div class="time">
                <span id="hours">23</span>
                <span id="minutes">59</span>
            </div>
        </div>
        <div class="line-1"></div>
    </div>
    @vite(['resources/js/profile.js'])
</div>
