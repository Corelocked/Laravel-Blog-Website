<x-admin-layout>
    <x-dashboard-navbar route="{{ route('dashboard') }}"/>

    <div class="users">
        <p class="head">Użytkownicy</p>
        <div class="users_list">
            <table>
                <thead>
                    <tr>
                        <th scope="col">IMG</th>
                        <th scope="col">Imię</th>
                        <th scope="col">Nazwisko</th>
                        <th scope="col">Email</th>
                        <th scope="col">Rola</th>
                        <th scope="col" style="width:125px;">Akcje</th>
                    </tr>
                </thead>
                <tbody class="body_user_list">
                    @foreach ($data as $key => $user)
                        <tr>
                            <td data-label="IMG"><img src="{{ asset($user->image_path) }}" alt="{{ $user->firstname }}"></td>
                            <td data-label="Imię">{{ $user->firstname }}</td>
                            <td data-label="Nazwisko">{{ $user->lastname }}</td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Rola">
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span>{{ $v }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td data-label="Akcje">
                                @can('user-edit')
                                    <a href="{{ route('users.edit', $user->id) }}" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                @endcan
                                @can('user-delete')
                                    @if(Auth::id() == $user->id)
                                        <button class="delete" onClick="cannot('Nie można usunąć swojego konta!')"><i class="fa-solid fa-trash"></i></a>
                                        @else
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="user_{{ $user->id }}">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        <button class="delete" onClick="confirmDelete({{ $user->id }}, 'user')"><i class="fa-solid fa-trash"></i></a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>

    </script>
</x-admin-layout>
