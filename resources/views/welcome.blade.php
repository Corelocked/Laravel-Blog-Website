<x-main-layout>
    <article>
        <div class="image__container">
            <div class="text">
                <p>Witaj na Blogu!</p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi rhoncus varius placerat. Praesent erat tellus, mattis ac finibus at, mollis in arcu. Nam malesuada libero vitae nulla pharetra sodales. Sed gravida nibh vel eros auctor, sit amet bibendum dui pharetra. Mauris iaculis sapien nisl, sit amet consequat odio consequat in. Curabitur ultrices ligula in ligula varius, ac viverra est finibus. Cras convallis et felis vitae convallis. Ut blandit ornare elementum. Praesent dapibus maximus vestibulum.</div>
            <img src="{{ asset('images/picture3.jpg') }}">
        </div>

        <div class="container">
            <div class="posts">      
                @foreach ($posts as $post)
                    <x-main-post-card :post="$post" />
                @endforeach
            </div>
        </div>   
    </article>
</x-main-layout>