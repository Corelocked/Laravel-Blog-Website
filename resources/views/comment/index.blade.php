<x-admin-layout>
    @section('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/filtr.js'])
    @endsection

    <x-dashboard-navbar route="/dashboard"/>
    
    
    <section class="divided_minimal">
        <p class="head">Komentarze</p>
        <div class="line-1"></div>
        <div class="comments">
            <div class="filter">
                <span class="filtr_collapse">
                    <p id="filtr">Filtruj</p>
                    <i class="fa-solid fa-caret-down button_collapse"></i>
                </span>
                <div class="filtr_body" style="height: {{ Auth::User()->hasRole('Admin') ? '455px' : '356px' }}">
                    <div class="line-1"></div>
                    <div class="filter-button f_1 active">
                        <p>Nowe komentarze</p>
                        <div class="dot"><i class="fa-solid fa-circle-check"></i></div>
                    </div>
                    <div class="filter-button f_2">
                        <p>Stare komentarze</p>
                        <div class="dot"><i class="fa-solid fa-circle-dot"></i></div>
                    </div>
                    <div class="line-1"></div>
                    <p id="filtr-2">Ilość rekordów</p>
                    <div class="filter-button rec_1">
                        <p>20 rekordów</p>
                        <span class="dot"><i class="fa-solid fa-square-xmark"></i></span>
                    </div>
                    <div class="filter-button rec_2">
                        <p>50 rekordów</p>
                        <span class="dot"><i class="fa-regular fa-square"></i></span>
                    </div>
                    <div class="filter-button rec_3">
                        <p>100 rekordów</p>
                        <span class="dot"><i class="fa-regular fa-square"></i></span>
                    </div>
                    <div class="filter-button rec_4">
                        <p>Wszystkie rekordy</p>
                        <span class="dot"><i class="fa-regular fa-square"></i></span>
                    </div>
                    @role('Admin')
                    <div class="line-1"></div>
                        <p id="filtr-2">Wyszukaj z postu użytkownika</p>
                        <select class="js-select2" id="user_modal" name="user">
                            <option value="0">Wszyscy</option>
                            @foreach ($users as $user)
                                @if ($selectedUser == $user->id)
                                    <option value="{{ $user->id }}" selected>{{ $user->firstname . ' ' . $user->lastname }}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->firstname . ' ' . $user->lastname }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endrole
                    <div class="line-1"></div>
                    <div class="filter-button show_results">
                        <p>Pokaż</p>
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <form action="" style="display: none" id="filter_form">
                        <input type="text" id="order" name="order" value="{{ $order ? $order : 'desc' }}">
                        <input type="text" id="limit" name="limit" value="{{ $limit ? $limit : ($limit == 0 ? 0 : 20) }}">
                        @role('Admin')
                            <input type="text" id="user" name="user" value="{{ $selectedUser ? $selectedUser : 0 }}">
                        @endrole
                    </form>
                </div>
            </div>
            <div class="comments-list">
                @foreach ($comments as $comment)
                    <x-admin-comment-card :comment="$comment"/>
                @endforeach
            </div>
        </div>
    </section>
</x-admin-layout>