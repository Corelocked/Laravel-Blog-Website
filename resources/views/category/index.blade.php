<x-admin-layout>
    @section('scripts')
        @vite(['resources/js/filtr.js'])
    @endsection

    <x-dashboard-navbar route="{{ route('dashboard') }}"/>

    <div class="categories">
        <div class="filter">
            <div class="filtr_collapse">
                <p class="head">Categories</p>
                <i class="fa-solid fa-caret-up button_collapse"></i>
            </div>
            <div class="filtr_body">
                <div class="sort">
                    <p class="name">Sorting</p>
                    <div class="buttons sort_buttons">
                        <div class="filter-button" onclick="filterCheck(1);" data-order="desc">
                            <div class="dot"><i class="fa-solid fa-circle-dot"></i></div>
                            <p>ID descending</p>
                        </div>
                        <div class="filter-button active" onclick="filterCheck(2);"  data-order="asc">
                            <div class="dot"><i class="fa-solid fa-circle-check"></i></div>
                            <p>ID ascending</p>
                        </div>
                        <div class="filter-button" onclick="filterCheck(3);" data-order="ascAlphabetical">
                            <div class="dot"><i class="fa-solid fa-circle-dot"></i></div>
                            <p>Alphabetically ascending</p>
                        </div>
                        <div class="filter-button" onclick="filterCheck(4);" data-order="descAlphabetical">
                            <div class="dot"><i class="fa-solid fa-circle-dot"></i></div>
                            <p>Alphabetically descending</p>
                        </div>
                    </div>
                </div>
                <div class="term">
                    <p class="name">Search</p>
                    <div class="inputs">
                        <input type="text" name="term" value="{{ $terms ?? '' }}">
                    </div>
                </div>
                <div class="records">
                    <p class="name">Records</p>
                    <div class="buttons">
                        <div class="filter-button rec_1" onclick="radioCheck(1);">
                            <span class="dot"><i class="fa-solid fa-square-xmark"></i></span>
                            <p>20 records</p>
                        </div>
                        <div class="filter-button rec_2" onclick="radioCheck(2);">
                            <span class="dot"><i class="fa-regular fa-square"></i></span>
                            <p>50 records</p>
                        </div>
                        <div class="filter-button rec_3" onclick="radioCheck(3);">
                            <span class="dot"><i class="fa-regular fa-square"></i></span>
                            <p>100 records</p>
                        </div>
                        <div class="filter-button rec_4" onclick="radioCheck(4);">
                            <span class="dot"><i class="fa-regular fa-square"></i></span>
                            <p>Max records</p>
                        </div>
                    </div>
                </div>
                <div class="filter-button show_results">
                    <p>Apply filters</p>
                </div>
                <form style="display: none" id="filter_form">
                    <input type="text" id="term" name="q" value="{{ $terms ?? '' }}">
                    <input type="text" id="order" name="order" value="{{ $order ?? 'desc' }}">
                    <input type="text" id="limit" name="limit" value="{{ $limit ?? 20 }}">
                </form>
            </div>
        </div>
        <div class="category_list">
            <table>
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Background</th>
                    <th scope="col">Color</th>
                    <th scope="col">Preview</th>
                    <th scope="col">Posts count</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody class="body_user_list">
                @foreach ($categories as $category)
                    <tr>
                        <td data-label="ID">{{ $category->id }}</td>
                        <td data-label="Name">{{ $category->name }}</td>
                        <td data-label="Background">{{ $category->backgroundColor }} <span class="box" style="background: {{ $category->backgroundColor }};"> </span> </td>
                        <td data-label="Color">{{ $category->textColor }} <span class="box" style="background: {{ $category->textColor }};"> </span> </td>
                        <td data-label="Preview"><span class="preview" style="background: {{ $category->backgroundColor }}CC; color: {{ $category->textColor }};">{{ $category->name }} </span> </td>
                        <td data-label="Posts count">{{ $category->posts_count }} </td>
                        <td data-label="Actions">
                            @can('category-edit')
                                <a href="{{ route('categories.edit', $category->id) }}" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            @endcan
                            @can('category-delete')
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" id="category_{{ $category->id }}">
                                @method('DELETE')
                                @csrf
                                </form>
                                <button onClick="confirmDelete({{ $category->id }}, 'category')" class="delete"><i class="fa-solid fa-trash"></i></button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $categories->appends([
                 'q' => $terms ?? '',
                 'order' => $order ?? 'desc',
                 'limit' => $limit ?? 20,
            ])->links('pagination.default') }}
        </div>
    </div>
</x-admin-layout>
