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
            </div>
            <div class="body_form">
                <div class="welcome-2">Dodaj nowy post</div>
                @if(count($errors) > 0)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <label>Obrazek</label>
                <div class="image_upload">
                    <input type="file" name="image" accept="image/*" onchange="loadFile(event)">
                </div>
                <label>Widoczność</label>
                <div class="published">
                    <p>Ustaw widoczność na publiczne</p>
                    <label class="switch">
                        <input type="checkbox" name="is_published" {{ isset($post) ? ($post->is_published ? 'checked' : '') : 'checked' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <label>Nagłówek</label>
                <p class="info title_length">Maksymalnie 255 znaków.</p>
                <input type="text" name="title" autocomplete="off" value="{{ isset($post) ? $post->title  : '' }}">
                <label>Krótki opis</label>
                <p class="info excerpt_length">Maksymalnie 510 znaków.</p>
                <textarea name="excerpt">{{ isset($post) ? $post->excerpt  : '' }}</textarea>
                <label>Tekst</label>
                <div id="editor">
                    
                </div>
                {{-- <textarea name="body" style="display: none" id="hiddenArea">{!! $post->body !!}</textarea> --}}
                <textarea name="body" style="display: none" id="hiddenArea">{!! isset($post) ? $post->body : '' !!}</textarea>
                <input type="hidden" name="id_saved_post" value="{{ isset($post) ? ($post->id ? $post->id : 0) : 0 }}">
                <div class="create_post_actions">
                    <div class="preview_mode" onClick="showPreview();">Podgląd</div>
                    <div class="save" onClick="save();">Zapisz</div>
                </div>
                <input type="submit" value="Opublikuj">
            </div>
        </form>
    </section>
    <aside class="post__preview">
        <div class="post_container">
            <div class="top">
                <img src="{{ isset($post) ? ($post->image_path ? asset($post->image_path) : asset('images/picture3.jpg')) : asset('images/picture3.jpg') }}" id="output">
                <div class="info">
                    <p class="preview_title">{{ isset($post) ? ($post->title ? $post->title : 'Tytuł') : 'Tytuł' }}</p>
                    <p class="date">{{ date('d.m.Y') }} by {{ Auth::User()->firstname . ' ' . Auth::User()->lastname }}</p>
                </div>
            </div>
        </div>
        <div class="post_body">
            @if(isset($post))
                @if($post->body)
                    {!! $post->body !!}
                @else
                    Ciało posta...
                @endif
            @else
                Ciało posta...
            @endif

            <div class="actions">
                <a href=""><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a>
                <a href="">Następny post <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="exit_preview" onClick="exitPreview();">Do góry <i class="fa-solid fa-arrow-up"></i></div>
        </div>
    </aside>
</x-admin-layout>