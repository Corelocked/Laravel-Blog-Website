<div class="post">
    <img src="{{ asset($post->image_path) }}">
    <div class="body">
        <p class="title">{{ $post->title }}</p>
        <p class="date">{{ $post->created_at->format('d.m.Y') }} by {{ $post->user->firstname . ' ' . $post->user->lastname }}</p>
        <p class="short_body">{{ $post->excerpt }}
        <a href="{{ route('posts.show', $post->id) }}" class="read_more">Czytaj dalej <i class="fa-solid fa-angles-right"></i></a>
    </div>
</div>