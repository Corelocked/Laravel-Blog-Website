<x-admin-layout>
    @section('scripts')
        @vite(['resources/js/filtr.js'])
    @endsection

    <x-dashboard-navbar route="{{ route('dashboard') }}"/>

    <div class="roles">
        <div class="filter">
            <div class="filtr_collapse">
                <p class="head">Roles</p>
                <i class="fa-solid fa-caret-up button_collapse"></i>
            </div>
            <div class="filtr_body">
                <div class="sort">
                    <p class="name">Sorting</p>
                    <div class="buttons sort_buttons">
                        <div class="filter-button active" onclick="filterCheck(1);" data-order="desc">
                            <div class="dot"><i class="fa-solid fa-circle-check"></i></div>
                            <p>Newest</p>
                        </div>
                        <div class="filter-button" onclick="filterCheck(2);" data-order="asc">
                            <div class="dot"><i class="fa-solid fa-circle-dot"></i></div>
                            <p>Oldest</p>
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
        <div class="roles_list">
            <table>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Users</th>
                        <th scope="col" width="30%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td data-label="ID">{{ $role->id }}</td>
                            <td data-label="Name">{{ $role->name }}</td>
                            <td data-label="Users">{{ $role->users_count }}</td>
                            <td data-label="Actions">
                                <a href="{{ route('roles.show', $role->id) }}" class="show"><i class="fa-solid fa-eye"></i></a>
                                @if(Auth::User()->roles[0]->id == $role->id)
                                    <a onClick="cannot('You cannot edit your own role!')" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                @else
                                    <a href="{{ route('roles.edit', $role->id) }}" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                @endif
                                @can('role-delete')
                                    @if(Auth::User()->roles[0]->name != $role->name)
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" id="role_{{ $role->id }}">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        <button onClick="confirmDelete({{ $role->id }}, 'role')" class="delete"><i class="fa-solid fa-trash"></i></button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $roles->appends([
                 'q' => $terms ?? '',
                 'order' => $order ?? 'desc',
                 'limit' => $limit ?? 20,
            ])->links('pagination.default') }}
        </div>
    </div>
</x-admin-layout>
