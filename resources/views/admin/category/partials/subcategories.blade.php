{{-- <ul>
    @foreach ($subcategories as $subcategory)
        <li>
            {{ $subcategory->name }}
            @if ($subcategory->children->count() > 0)
                @include('admin.category.partials.subcategories', ['subcategories' => $subcategory->children])
            @endif
        </li>
    @endforeach
</ul> --}}


@foreach ($subcategories as $subcategory)
    <tr>
        <td> --  {{ $subcategory->name }}</td>
        <td>
            <a class="btn btn-primary" href="{{route('admin.category.edit',$subcategory)}}">Edit</a>
        </td>
        <td>
            <form method="POST" action="{{route('admin.category.destroy',$subcategory)}}">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Are you sure you want to delete this subcategory ?')" class="btn btn-danger" type="submit">Delete</button>
            </form>
            
        </td>
    </tr>
    @if ($subcategory->children->count() > 0)
        @include('admin.category.partials.subcategories', ['subcategories' => $subcategory->children])
    @endif
@endforeach
