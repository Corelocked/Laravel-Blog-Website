<x-admin-layout>

    @section('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endsection

    <x-dashboard-navbar route="/dashboard"/>

    <section class="dashboard">
        <div class="saved_posts">
            <div class="saved_card new_post">
                <form action="{{ route('posts.create') }}" method="GET">
                    <input type="hidden" value="1" name="new">
                    <button type="submit"><i class="fa-regular fa-square-plus"></i></button>
                </form>
                <p>Nowy</p>
            </div>
            @foreach ($posts as $post)
                <x-saved-post-card :post="$post" />
            @endforeach
        </div>
    </section>
</x-admin-layout>