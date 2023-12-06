<x-admin-layout :edit="true">
    @section('scripts')
        <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        @vite('resources/js/history.js')
    @endsection

    <header class="header_post_edit">
        <a href="/dashboard/posts/{{ $id }}/edit"><i class="fa-solid fa-left-long"></i> Powrót</a>
        <span class="info">Historia posta</span>
        <div class="profile">
            <img src="{{ asset(Auth::user()->image_path) }}" alt="" class="profile_img">
            <i class="fa-solid fa-angles-down"></i>
        </div>
    </header>

    <section class="post__history">
        <div class="history_card h_0 active">
            <a class="show_actual">Pokaż aktualnie edytowany post</a>
        </div>
        
        @if(count($posts) > 0)
            @foreach ($posts as $post)
            <div class="history_card h_{{ $post->id }}">
                <img src="{{ asset($post->image_path) }}" alt="">
                <div class="body">
                    <span class="title">{{ $post->title }}</span>          
                    <span class="created"><i class="fa-regular fa-calendar-plus"></i> {{ $post->created_at->format('d.m.Y H:i') }} {!! $post->additional_info == 1 ? '<i class="fa-solid fa-clock-rotate-left"></i> Przywrócono' : '' !!}{!! $post->additional_info == 2 ? '<i class="fa-solid fa-floppy-disk"></i> Autozapis' : '' !!}</span>
                    <span class="excerpt">{{ $post->excerpt }}</span>
                </div>
                <span class="actions">
                    <a onClick="show({{ $post->id }})">Zobacz <i class="fa-solid fa-eye"></i></a>
                    <a onClick="revert({{ $id }},{{ $post->id }})">Przywróć <i class="fa-solid fa-clock-rotate-left"></i></a>
                </span>
            </div>
            @endforeach
        @else
            <div class="history_card">
                <span class="info">Pusto :(</span>
            </div>
        @endif
        
    </section>
    <aside class="post__preview">
        <div class="post_container">
            <div class="top">
                <img src="{{ asset($actualPost->image_path) }}" id="output">
                <div class="info">
                    <p class="preview_title">{{ $actualPost->title }}</p>
                    <p class="date">{{ $actualPost->updated_at->format('d.m.Y') }} by {{ $actualPost->user->firstname . ' ' . $actualPost->user->lastname }}</p>
                </div>
            </div>
        </div>
        <div class="post_body">
            {!! $actualPost->body !!}

            <div class="actions">
                <a><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a>
                <a>Następny post <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="exit_preview" onClick="exitPreview();">Do góry <i class="fa-solid fa-arrow-up"></i></div>
        </div>
    </aside>
</x-admin-layout>