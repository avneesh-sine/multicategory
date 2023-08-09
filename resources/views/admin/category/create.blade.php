@extends('admin.layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Category</h1>
    <!-- Content Row -->
    <div class="card shadow mb-4">
        <form action="{{ route('admin.category.store') }}" method="POST" id="category">
            @csrf
            <label for="name">Category Name:</label>
            <input type="text" name="name" id="name" required>
            
            <label for="parent_id">Parent Category:</label>
            <select name="parent_id" id="parent_id">
                <option value="">None</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            
            <button type="submit">Create Category</button>
        </form>
    </div>
</div>
<!-- /.container-fluid -->
@endsection
<style>

    #name-error{
        color: red
    }
</style>
@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    const errorMessage = document.getElementById('name-error');

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.remove(); // Remove the error message after 2 seconds
        }, 2000);
    }
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