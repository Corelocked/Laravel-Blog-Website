<?php
function hexToRgba($hex, $alpha = 1){
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "rgba($r, $g, $b, $alpha)";
}
?>
<x-admin-layout>
    <x-dashboard-navbar route="{{ route('dashboard') }}"/>

    <div class="categories">
        <p class="head">Kategorie</p>
        <div class="category_list">
            <table>
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nazwa</th>
                    <th scope="col">Tło</th>
                    <th scope="col">Kolor</th>
                    <th scope="col">Podgląd</th>
                    <th scope="col">Ilość postów</th>
                    <th scope="col">Akcje</th>
                </tr>
                </thead>
                <tbody class="body_user_list">
                @foreach ($categories as $category)
                    <tr>
                        <td data-label="ID">{{ $category->id }}</td>
                        <td data-label="Nazwa">{{ $category->name }}</td>
                        <td data-label="Tło">{{ $category->backgroundColor }} <span class="box" style="background: {{ $category->backgroundColor }};"> </span> </td>
                        <td data-label="Kolor">{{ $category->textColor }} <span class="box" style="background: {{ $category->textColor }};"> </span> </td>
                        <td data-label="Podgląd"><span class="preview" style="background: <?php echo hexToRgba($category->backgroundColor, 0.80); ?>; color: {{ $category->textColor }};">{{ $category->name }} </span> </td>
                        <td data-label="Ilość postów">{{ $category->posts_count }} </td>
                        <td data-label="Akcje">
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
        </div>
    </div>
</x-admin-layout>
