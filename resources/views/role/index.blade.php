<x-admin-layout>
    <x-dashboard-navbar route="/dashboard"/>

    <section class="roles">
        <p class="head">Role</p>
        <div class="roles_list">
            <table>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nazwa</th>
                        <th scope="col" width="30%">Akcje</th>
                    </tr>
                </thead>
                <tbody class="body_user_list">
                    @foreach ($roles as $role)
                        <tr>
                            <td data-label="ID">{{ $role->id }}</td>
                            <td data-label="Nazwa">{{ $role->name }}</td>
                            <td data-label="Akcje">
                                <a href="{{ route('roles.show', $role->id) }}" class="show"><i class="fa-solid fa-eye"></i></a>
                                @if(Auth::User()->roles[0]->id == $role->id)
                                    <a onClick="cannot('Nie można edytować swojej roli!')" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                @else
                                    <a href="{{ route('roles.edit', $role->id) }}" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                @endif
                                @can('role-delete')
                                    @if(Auth::User()->roles[0]->name != $role->name)
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" id="role_{{ $role->id }}">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        <button onClick="confirmDelete({{ $role->id }}, 'role')" class="delete"><i class="fa-solid fa-trash"></i></a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-admin-layout>