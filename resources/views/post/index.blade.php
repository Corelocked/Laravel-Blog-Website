<x-admin-layout>
    @section('scripts')
        @vite(['resources/js/filtr.js'])
    @endsection

    <x-dashboard-navbar route="{{ route('dashboard') }}"/>

    <div class="divided_minimal">
        <div class="posts">
            <div class="filter">
                <div class="filtr_collapse">
                    <p class="head">Posty</p>
                    <i class="fa-solid fa-caret-up button_collapse"></i>
                </div>
                <div class="filtr_body">
                    <div class="view">
                        <p class="name">Widok</p>
                        <div class="buttons view">
                            <div class="view_button list active" onclick="changeView('list', 'post');"><i class="fa-solid fa-bars"></i></div>
                            <div class="view_button tiles" onclick="changeView('tile', 'post');"><i class='bx bxs-grid-alt'></i></div>
                        </div>
                    </div>
                    <div class="sort">
                        <p class="name">Sortowanie</p>
                        <div class="buttons">
                            <div class="filter-button f_1 active" onclick="filterCheck(1);">
                                <div class="dot"><i class="fa-solid fa-circle-check"></i></div>
                                <p>Nowe posty</p>
                            </div>
                            <div class="filter-button f_2" onclick="filterCheck(2);">
                                <div class="dot"><i class="fa-solid fa-circle-dot"></i></div>
                                <p>Stare posty</p>
                            </div>
                        </div>
                    </div>
                    <div class="records">
                        <p class="name">Rekordy</p>
                        <div class="buttons">
                            <div class="filter-button rec_1" onclick="radioCheck(1);">
                                <span class="dot"><i class="fa-solid fa-square-xmark"></i></span>
                                <p>20 rekordów</p>
                            </div>
                            <div class="filter-button rec_2" onclick="radioCheck(2);">
                                <span class="dot"><i class="fa-regular fa-square"></i></span>
                                <p>50 rekordów</p>
                            </div>
                            <div class="filter-button rec_3" onclick="radioCheck(3);">
                                <span class="dot"><i class="fa-regular fa-square"></i></span>
                                <p>100 rekordów</p>
                            </div>
                            <div class="filter-button rec_4" onclick="radioCheck(4);">
                                <span class="dot"><i class="fa-regular fa-square"></i></span>
                                <p>Max rekordów</p>
                            </div>
                        </div>
                    </div>
                    @role('Admin')
                        <div class="highlight">
                            <p class="name">Wyróżnione</p>
                            <div class="buttons">
                                <div class="checkbox" onclick="selectHighlight('yes');" data-highlight="yes">
                                    <div class="check"><i class="{{ $highlight ? ($highlight[0] === '1' ? 'fa-solid fa-square-check' : 'fa-regular fa-square') : 'fa-regular fa-square' }}"></i></div>
                                    <p>Tak ({{ $countHighlighted }})</p>
                                </div>
                                <div class="checkbox" onclick="selectHighlight('no');" data-highlight="no">
                                    <div class="check"><i class="{{ $highlight ? ($highlight[1] === '1' ? 'fa-solid fa-square-check' : 'fa-regular fa-square') : 'fa-regular fa-square' }}"></i></div>
                                    <p>Nie ({{ $countPosts - $countHighlighted }})</p>
                                </div>
                            </div>
                        </div>
                    @endrole
                    <div class="category">
                        <p class="name">Kategorie</p>
                        <div class="buttons">
                            @php($elements = count($categories))
                            @foreach($categories as $category)
                                @if(isset($selected_categories) && in_array($category->toArray(), $selected_categories))
                                    <div class="checkbox" onclick="selectCategory(event, {{ $category->id }})" data-category-id="{{ $category->id }}">
                                        <div class="check" style="color: {{ $category->backgroundColor }}"><i class="fa-solid fa-square-check"></i></div>
                                        <p>{{ $category->name }} ({{ $category->posts_count }})</p>
                                    </div>
                                @else
                                    <div class="checkbox {{ $elements >= 3 && $loop->index + 1> 3 && !isset($selected_categories) ? 'hidden' : '' }}" onclick="selectCategory(event, {{ $category->id }})" data-category-id="{{ $category->id }}">
                                        <div class="check" style="color: {{ $category->backgroundColor }}"><i class="fa-regular fa-square"></i></div>
                                        <p>{{ $category->name }} ({{ $category->posts_count }})</p>
                                    </div>
                                @endif
                                @if($elements >= 3 && $loop->index + 1 == $elements && !isset($selected_categories))
                                    <p class="categories_extend" onclick="categoriesToggle();"><i class="fa-solid fa-chevron-down"></i> Pokaż więcej</p>
                                @endif

    {{--                            <div class="category" style="background: {{ $category->backgroundColor }}CC; color: {{ $category->textColor }}" onclick="selectCategory(event, {{ $category->id }})">{{ $category->name }}</div>--}}
                            @endforeach
                        </div>
                    </div>
                    @role('Admin')
                        <div class="user">
                            <p class="name">Użytkownik</p>
                            <div class="buttons">
                                @foreach ($users as $user)
                                    @if (isset($selected_users) && in_array($user->toArray(), $selected_users))
                                        <div class="checkbox" onclick="selectUser(event, {{ $user->id }})" data-user-id="{{ $user->id }}">
                                            <div class="check"><i class="fa-solid fa-square-check"></i></div>
                                            <p>{{ $user->firstname . ' ' . $user->lastname }} ({{ $user->posts_count }})</p>
                                        </div>
                                    @else
                                        <div class="checkbox" onclick="selectUser(event, {{ $user->id }})" data-user-id="{{ $user->id }}">
                                            <div class="check"><i class="fa-regular fa-square"></i></div>
                                            <p>{{ $user->firstname . ' ' . $user->lastname }} ({{ $user->posts_count }})</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endrole
                    <div class="filter-button show_results">
                        <p>Zastosuj filtry</p>
                    </div>
                    <form style="display: none" id="filter_form">
                        <input type="text" id="order" name="order" value="{{ $order ? $order : 'desc' }}">
                        <input type="text" id="limit" name="limit" value="{{ $limit ? $limit : ($limit == 0 ? 0 : 20) }}">
                        <input type="text" id="categories" name="categories[]" value="{{ is_array($selected_categories_array) ? implode(',', $selected_categories_array) : '' }}">
                        @role('Admin')
                            <input type="text" id="highlight" name="highlight[]" value="{{ $highlight ? implode(',', $highlight) : '' }}">
                            <input type="text" id="users" name="users[]" value="{{ is_array($selected_users_array) ? implode(',', $selected_users_array) : '' }}">
                        @endrole
                    </form>
                </div>
            </div>
            <div class="posts-list">
                @foreach ($posts as $post)
                    <x-admin-post-card :post="$post" :countHighlighted="$countHighlighted"/>
                @endforeach
            </div>

        </div>
        @if ((int)$limit !== 0)
            @role('Admin')
                {{ $posts->appends(['order' => $order ? $order : 'desc', 'limit' => $limit ? $limit : ($limit == 0 ? 0 : 20), 'categories' => is_array($selected_categories_array) ? implode(',', $selected_categories_array) : [], 'users' => is_array($selected_users_array) ? $selected_users_array : '', 'highlight' => $highlight ? implode(',', $highlight) : '' ])->links('pagination.default') }}
            @else
                {{ $posts->appends(['order' => $order ? $order : 'desc', 'limit' => $limit ? $limit : ($limit == 0 ? 0 : 20), 'categories' => is_array($selected_categories_array) ? implode(',', $selected_categories_array) : []])->links('pagination.default') }}
            @endrole
        @endif
    </div>
    <script type="module">
        const currentView = localStorage.getItem('postView');
        const defaultView = currentView || 'list';
        if (defaultView === 'list') {
            changeView('list', 'post');
        } else {
            changeView('tile', 'post');
        }
    </script>
</x-admin-layout>
