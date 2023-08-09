@extends('admin.layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Category</h1>
    <!-- Content Row -->
    <div class="card shadow mb-4">
        <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="name">Category Name:</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" required>
            
            <label for="parent_id">Parent Category:</label>
            <select name="parent_id" id="parent_id">
                <option value="">None</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit">Update Category</button>
        </form>
    </div>
</div>
<style>

    #name-error{
        color: red
    }
</style>
<!-- /.container-fluid -->
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#category').validate({
        rules: {
            name: {
                required: true
            },
        }
    });
});
</script>
@endsection