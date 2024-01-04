<x-admin-layout :edit="true">
    <header class="header_post_edit">
        <a href="{{ route('posts.edit', $id) }}"><i class="fa-solid fa-left-long"></i> Powrót</a>
        <span class="info">Historia posta</span>
        <div class="profile">
            <img src="{{ asset(Auth::user()->image_path) }}" alt="" class="profile_img">
            <i class="fa-solid fa-angles-down"></i>
        </div>
    </header>
    <div class="history-list">
        @if (count($posts) > 0)
            @php($lastDate = $posts[0]->updated_at->format('Y-m-d'))
        @else
            @php($lastDate = $currentPost->updated_at->format('Y-m-d'))
        @endif
        @if ($lastDate === $currentPost->updated_at->format('Y-m-d') or $lastDate === null)
            <div class="date">
                Zmiany z {{ \Carbon\Carbon::parse($lastDate)->translatedFormat('d F, Y') }}
                <div class="line-v"></div>
            </div>
        @endif
        <a href="{{ route('history.show', [$id, 'current']) }}">
            <div class="history_card">
                <img src="{{ asset($currentPost->image_path) }}" alt="">
                <div class="body">
                    <div class="top-info">
                        @if ($currentPost->category)
                            <div class="category" style="background: {{ $currentPost->category->backgroundColor }}CC; color: {{ $currentPost->category->textColor }}">{{ $currentPost->category->name }}</div>
                        @endif
                        @if ($currentPost->read_time)
                            <i class="fa-solid fa-clock"></i>
                            <p class="reading-time">{{ $currentPost->read_time }} min</p>
                        @endif
                    </div>
                    <span class="title">{{ $currentPost->title }}</span>
                    <span class="excerpt">{{ $currentPost->excerpt }}</span>
                    <div class="bottom-info">
                        <span class="created"><i class="fa-regular fa-clock"></i> {{ $currentPost->updated_at->diffForHumans() }}, <span class="time">{{ $currentPost->updated_at->format('H:i') }}</span></span>
                        <span class="additional_info"><i class="fa-solid fa-bolt"></i> Aktualny</span>
                        <span class="additional_info">{!! $currentPost->additional_info == 1 ? '<i class="fa-solid fa-clock-rotate-left"></i> Przywrócono' : '' !!}</span>
                    </div>
                    @if ($currentPost->changelog)
                        <div class="changelog-info">
                            <span class="user"><i class="fa-solid fa-user"></i> {{ $currentPost->changeUser->firstname . ' ' . $currentPost->changeUser->lastname }}</span>
                            <span class="changelog"><i class="fa-solid fa-square-pen"></i> <span class="text">{{ $currentPost->changelog }}</span></span>
                        </div>
                    @endif
                </div>
            </div>
        </a>
        @if(count($posts) > 0)
            @foreach ($posts as $post)
                @php($postDate = $post->updated_at->format('Y-m-d'))
                @if($lastDate != $postDate)
                    @php($lastDate = $postDate)
                    <div class="date">
                        <div class="line-v"></div>
                        Zmiany z {{ \Carbon\Carbon::parse($postDate)->translatedFormat('d F, Y') }}
                        <div class="line-v"></div>
                    </div>
                @else
                    <div class="margin-10"> </div>
                @endif
                <a href="{{ route('history.show', [$id, $post->id]) }}">
                    <div class="history_card">
                        <img src="{{ asset($post->image_path) }}" alt="">
                        <div class="body">
                            <div class="top-info">
                                @if ($post->category)
                                    <div class="category" style="background: {{ $post->category->backgroundColor }}CC; color: {{ $post->category->textColor }}">{{ $post->category->name }}</div>
                                @endif
                                @if ($post->read_time)
                                    <i class="fa-solid fa-clock"></i>
                                    <p class="reading-time">{{ $post->read_time }} min</p>
                                @endif
                            </div>
                            <span class="title">{{ $post->title }}</span>
                            <span class="excerpt">{{ $post->excerpt }}</span>
                            <div class="bottom-info">
                                <span class="created"><i class="fa-regular fa-clock"></i> {{ $post->created_at->diffForHumans() }}, <span class="time">{{ $post->created_at->format('H:i') }}</span></span>
                                @if($post->additional_info)
                                    <span class="additional_info">{!! $post->additional_info == 1 ? '<i class="fa-solid fa-clock-rotate-left"></i> Przywrócono' : '' !!}{!! $post->additional_info == 2 ? '<i class="fa-solid fa-floppy-disk"></i> Autozapis' : '' !!}</span>
                                @endif
                            </div>
                            @if ($post->changelog)
                                <div class="changelog-info">
                                    <span class="user"><i class="fa-solid fa-user"></i> {{ $post->changeUser->firstname . ' ' . $post->changeUser->lastname }}</span>
                                    <span class="changelog"><i class="fa-solid fa-square-pen"></i> <span class="text">{{ $post->changelog }}</span></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        @endif

    </div>
</x-admin-layout>
