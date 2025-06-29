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
                    @if ($post->read_time)
                        <div class="reading-info">
                            <p class="reading-text">Reading time: </p>
                            <i class="fa-solid fa-clock"></i>
                            <p class="reading-time">{{ $post->read_time }} min</p>
                        </div>
                    @endif
                    <p class="date">{{ $post->created_at->format('d.m.Y') }} by {{ $post->user->firstname . ' ' . $post->user->lastname }}</p>
                    @if($post->created_at != $post->updated_at)
                        <p class="date">Updated: {{ $post->updated_at->format('d.m.Y') }}</p>
                    @endif
                    @if($post->is_published == false)
                        <p class="date">(Not visible)</p>
                    @endif
                    @can(['post-super-list', 'post-edit'])
                        <a href="{{ route('posts.edit', $post->id) }}" class="edit">Edit</a>
                    @else
                        @if(Auth::User())
                            @if(Auth::User()->id == $post->user_id AND Auth::User()->can('post-edit'))
                                <a href="{{ route('posts.edit', $post->id) }}" class="edit">Edit</a>
                            @endif
                        @endif
                    @endcan
                </div>
            </div>
        </div>
        <div class="post_body">

            {!! $post->body !!}

            <div class="actions">
                @isset($nextPost)
                    <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Back to home page</a>
                    <a href="{{ route('post.show', $nextPost->slug) }}">Next post <i class="fa-solid fa-arrow-right"></i></a>
                @else
                    <a href="{{ route('home') }}" style="width: 100%"><i class="fa-solid fa-arrow-left"></i> Back to home page</a>
                @endisset
            </div>
        </div>
        <div class="comments">
            <p class="info">Comments ({{ count($post->comments) }})</p>
            <div class="add__comment">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <label>First and/or Last Name</label>
                    <input type="text" name="name" autocomplete="off" value="{{ Auth::User() ? Auth::User()->firstname . ' ' . Auth::User()->lastname : '' }}">
                    <label>Text</label>
                    <textarea name="body"></textarea>
                    <input type="submit" value="Add">
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
        document.querySelectorAll('img:not(.profile_img)').forEach(function (img) {
            img.classList.add('img-enlargable');
            img.addEventListener('click', function () {
                let src = img.getAttribute('src');

                let overlay = document.createElement('div');
                overlay.style.cssText = 'background: RGBA(0,0,0,.5) url(' + src + ') no-repeat center; background-size: contain; width: 100%; height: 100%; position: fixed; z-index: 10000; top: 0; left: 0; cursor: zoom-out;';

                overlay.addEventListener('click', function () {
                    overlay.remove();
                });

                document.body.appendChild(overlay);
            });
        });
    </script>
</x-main-layout>
