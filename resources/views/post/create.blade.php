<x-admin-layout>
    @section('scripts')
        <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        @vite(['resources/js/post.js', 'resources/js/createPost.js'])
    @endsection

    <x-dashboard-navbar route="/dashboard"/>

    <section class="post__create">
        <form action="{{ route('posts.store') }}" method="POST" id="form" enctype="multipart/form-data">
            @csrf
            <div id="content" data-image-url="{{route('image.store')}}">
            <div class="post_container">
                @if(count($errors) > 0)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="top">
                    <div class="image">
                        <img src="{{ isset($post) ? ($post->image_path ? asset($post->image_path) : asset('images/picture3.jpg')) : asset('images/picture3.jpg') }}" id="output">
                        <input id="image" type="file" name="image" accept="image/*" onchange="loadFile(event)" style="display: none;">
                        <div class="change_image"><i class="fa-solid fa-image"></i> Zmień</div>
                    </div>
                    <div class="info">
                        <p class="info_title_length">Maksymalnie 255 znaków. <span class='current_title_length'>{{ isset($post) ? Str::length($post->title) : 5 }}/255</span></p>
                        <input type="text" name="title" class="title" autocomplete="off" value="{{ isset($post) ? ($post->title ? $post->title : 'Tytuł') : 'Tytuł' }}">
                        <p class="date">{{ date('d.m.Y') }} by {{ Auth::User()->firstname . ' ' . Auth::User()->lastname }}</p>
                    </div>
                </div>
            </div>
            <div class="post_body">
                <div id="editor">
                    
                </div>
                
                <textarea name="body" style="display: none" id="hiddenArea">{!! isset($post) ? $post->body : '' !!}</textarea>

                <div class="actions">
                    <a><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a>
                    <a>Następny post <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="post_options">
                <div class="header">Dodatkowe opcje:</div>
                <label>Krótki opis</label>
                <p class="info excerpt_length">Maksymalnie 510 znaków. <span class='current_excerpt_length'>{{ isset($post) ? Str::length($post->excerpt) : 0 }}/510</span></p>
                <textarea name="excerpt">{{ isset($post) ? $post->excerpt  : '' }}</textarea>
                <label>Widoczność</label>
                <div class="published">
                    <p>Ustaw widoczność na publiczne</p>
                    <label class="switch">
                        <input type="checkbox" name="is_published" {{ isset($post) ? ($post->is_published ? 'checked' : '') : 'checked' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <input type="hidden" name="id_saved_post" value="{{ isset($post) ? ($post->id ? $post->id : 0) : 0 }}">
                <div class="create_post_actions">
                    <input type="submit" value="Opublikuj">
                    <div class="save" onClick="save();">Zapisz</div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>