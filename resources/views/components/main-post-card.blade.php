<a href="{{ route('posts.show', $post->id) }}" class="read_post">
<div class="post">
    <img src="{{ asset($post->image_path) }}">
    <div class="read"><i class="fa-solid fa-angles-right"></i>Przeczytaj</div>
    <div class="body">
        <p class="title">{{ $post->title }}</p>
        <div class="user">
            <img src="{{ $post->user->image_path }}" alt="">
            <p><span class="name">{{ $post->user->firstname . ' ' . $post->user->lastname }}</span><br><span class="date"> {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('d F, Y') }}</span></p>
        </div>
        <p class="short_body">{{ $post->excerpt }}</p>
    </div>
</div>
</a>