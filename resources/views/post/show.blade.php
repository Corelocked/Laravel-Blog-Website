<x-main-layout>
    <div class="article">
        <div class="post_container">
            <div class="top">
                <img src="{{ asset($post->image_path) }}" alt="">
                <div class="info">
                    <p class="title">{{ $post->title }}</p>
                    @if ($post->category)
                        <div class="category" style="background: {{ $post->category->backgroundColor }}CC; color: {{ $post->category->textColor }}">{{ $post->category->name }}</div>
                    @endif
                    <p class="date">{{ $post->created_at->format('d.m.Y') }} by {{ $post->user->firstname . ' ' . $post->user->lastname }}</p>
                    @if($post->created_at != $post->updated_at)
                        <p class="date">Zaktualizowano: {{ $post->updated_at->format('d.m.Y') }}</p>
                    @endif
                    @if($post->is_published == false)
                        <p class="date">(Nie widoczne)</p>
                    @endif
                    @role('Admin')
                        <a href="{{ route('posts.edit', $post->id) }}" class="edit">Edytuj</a>
                    @else
                        @if(Auth::User())
                            @if(Auth::User()->id == $post->user_id AND Auth::User()->can('post-edit'))
                                <a href="{{ route('posts.edit', $post->id) }}" class="edit">Edytuj</a>
                            @endif
                        @endif
                    @endrole
                </div>
            </div>
        </div>
        <div class="post_body">

            {!! $post->body !!}

            <div class="actions">
                @isset($nextPost)
                    <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a>
                    <a href="{{ route('post.show', $nextPost->slug) }}">Następny post <i class="fa-solid fa-arrow-right"></i></a>
                @else
                    <a href="{{ route('home') }}" style="width: 100%"><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a>
                @endisset
            </div>
        </div>
        <div class="comments">
            <p class="info">Komentarze ({{ count($post->comments) }})</p>
            <div class="add__comment">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <label>Imię i/lub Nazwisko</label>
                    <input type="text" name="name" autocomplete="off" value="{{ Auth::User() ? Auth::User()->firstname . ' ' . Auth::User()->lastname : '' }}">
                    <label>Tekst</label>
                    <textarea name="body"></textarea>
                    <input type="submit" value="Dodaj">
                </form>
            </div>
            <div class="line-1"></div>
            @isset($post->comments)
                @foreach ($post->comments as $comment)
                    <x-comment-card :comment="$comment" :post="$post" />
                @endforeach
            @endisset
        </div>
    </div>

    <script>
        $('img:not(.profile_img)').addClass('img-enlargable').click(function(){
            var src = $(this).attr('src');
            $('<div>').css({
                background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
                backgroundSize: 'contain',
                width:'100%', height:'100%',
                position:'fixed',
                zIndex:'10000',
                top:'0', left:'0',
                cursor: 'zoom-out'
            }).click(function(){
                $(this).remove();
            }).appendTo('body');
        });
    </script>
</x-main-layout>
