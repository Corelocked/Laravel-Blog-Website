<div class="post">
    <img src="{{ asset($post->image_path) }}">
    <div class="body">
        <p class="title">{{ $post->title }}</p>
        <p class="date">{{ $post->updated_at->format('d.m.Y') }} by {{ $post->user->firstname . ' ' . $post->user->lastname }}</p>
    </div>
    <div class="actions">
        <a href="{{ route('posts.show', $post->slug) }}" class="read_more">Przejdź <i class="fa-solid fa-angles-right"></i></a>
        @can('post-edit')
            <a href="{{ route('posts.edit', $post->id) }}" class="edit">Edytuj <i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        @can('post-delete')
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" id="post_{{ $post->id }}">
                @method('DELETE')
                @csrf
            </form>
            <button onClick="confirmDelete({{ $post->id }}, 'post')" class="delete">Usuń <i class="fa-solid fa-trash"></i></button>
        @endcan
    </div>
</div>