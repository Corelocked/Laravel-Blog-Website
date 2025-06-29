<div class="modal">
    <div class="modal-profile">
        <span>Welcome!</span>
        <i class="fa-solid fa-circle-xmark close close-modal"></i>
        <div class="notifications">{{ $unreadNotifications }} <i class="fa-solid fa-angles-right"></i></div>
        <p class="name">{{ Auth::User() ? Auth::User()->firstname . ' ' . Auth::User()->lastname : '' }}</p>
        <p class="role_profile">{{ Auth::User() ? Auth::User()->roles[0]->name : '' }}</p>
        <p class="info">Available actions</p>
        <a href="{{ route('dashboard') }}" class="button"><i class="fa-solid fa-wrench"></i><p>Panel</p></a>
        <a href="{{ route('profile') }}" class="button"><i class="fa-solid fa-id-card"></i><p>Profile</p></a>
        <div class="button toggle-mode" onClick="toggleMode();"><i class="fa-solid fa-moon"></i><p>Dark Mode</p></div>
        <a href="{{ route('logout') }}" class="button"><i class="fa-solid fa-right-from-bracket"></i><p>Logout</p></a>
        <p class="info">Quick actions</p>
        @can('post-create')
            <a href="{{ route('posts.create') }}" class="button"><i class="fa-solid fa-plus"></i><p>Add post</p></a>
        @endcan
        @can('category-create')
            <a href="{{ route('categories.create') }}" class="button"><i class="fa-solid fa-square-plus"></i><p>Add category</p></a>
        @endcan
        @can('user-create')
            <a href="{{ route('users.create') }}" class="button"><i class="fa-solid fa-user-plus"></i><p>Add user</p></a>
        @endcan
        @can('role-create')
            <a href="{{ route('roles.create') }}" class="button"><i class="fa-solid fa-wrench"></i><p>Add role</p></a>
        @endcan
        <div class="line-1"></div>
        <div class="clock">
            <p class="info">Current time</p>
            <div class="time">
                <span id="hours">23</span>
                <span id="minutes">59</span>
            </div>
        </div>
        <div class="line-1"></div>
    </div>
    <div class="modal-notifications">
        <div class="back"><i class="fa-solid fa-angles-left"></i> <p>Back</p></div>
        @if (count($notifications) === 0)
            <div class="notification action">
                <p class="empty">No notifications</p>
            </div>
        @else
            <div class="notification action">
                <div class="clear_notifications" onclick="clearNotifications();">Clear notifications</div>
            </div>
        @endif
        @php
            $groupedNotifications = $notifications->groupBy(function ($notification) {
                return $notification->created_at->format('Y-m-d');
            });
        @endphp

        @foreach ($groupedNotifications as $date => $group)
            @php
                $notificationDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
                $formattedDate = '';

                if ($notificationDate->isToday()) {
                    $formattedDate = 'Today';
                } elseif ($notificationDate->isYesterday()) {
                    $formattedDate = 'Yesterday';
                } else {
                    $formattedDate = $notificationDate->format('d.m.Y');
                }
            @endphp

            <div class="date">{{ $formattedDate }}</div>

            @foreach ($group as $notification)
                @php
                    $class = null;
                    switch($notification->data['type']) {
                        case "SUKCES":
                            $class = " success";
                            break;
                        case "INFO":
                            $class = " info";
                            break;
                        case "OSTRZEŻENIE":
                            $class = " warning";
                            break;
                        case "BŁĄD":
                            $class = " error";
                            break;
                        default:
                            $class = " info";
                    }
                    switch($notification->type) {
                        case \App\Notifications\PostNotification::class:
                            $icon = '<i class="fa-solid fa-newspaper icon"></i>';
                            break;
                        case \App\Notifications\RoleNotification::class:
                            $icon = '<i class="fa-solid fa-toolbox icon"></i>';
                            break;
                        case \App\Notifications\CommentNotification::class:
                            $icon = '<i class="fa-solid fa-comment icon"></i>';
                            break;
                        default:
                            $icon = '<i class="fa-regular fa-bell"></i>';
                    }
                @endphp
                <div class="notification{{$class}}">
                    <div class="header">
                        {!! $icon !!}
                        <i class="fa-solid fa-circle"></i>
                        <div class="type">
                            @switch($notification->data['type'])
                                @case('SUKCES') Success @break
                                @case('INFO') Info @break
                                @case('OSTRZEŻENIE') Warning @break
                                @case('BŁĄD') Error @break
                                @default {{ $notification->data['type'] }}
                            @endswitch
                        </div>
                        <i class="fa-solid fa-circle"></i>
                        <div class="time">{{ str_pad($notification->created_at->format('H:i'), 5, "0", STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="message"> {{ $notification->data['message'] }} </div>
                    @if($notification->data['link'])
                        <a href="{{ $notification->data['link'] }}" class="link"> >> Go</a>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>
    @vite(['resources/js/profile.js'])
</div>
