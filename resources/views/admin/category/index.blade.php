@extends('admin.layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Category</h1>    
    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('admin.category.create')}}" class="btn btn-primary btn-sm btn-icon-split float-right">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Category</span>
            </a>
            <h6 class="m-0 font-weight-bold text-primary">Category</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="category">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Edit
                                </th>
                                <th>
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        {{ $category->name }}
                              
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('admin.category.edit',$category)}}">Edit</a>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{route('admin.category.destroy',$category)}}">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this category ?')" class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                        
                                    </td>
                                </tr>
                                
                                @if ($category->children->count() > 0)
                                    @include('admin.category.partials.subcategories', ['subcategories' => $category->children])
                                @endif
                            
                            @endforeach
                        
                        </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection

@section('styles')
<style>

    #name-error{
        color: red
    }
</style>
<!-- Custom styles for this page -->
<link href="{{ asset('admin-theme/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('admin-theme/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-theme/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript">

 

</script>
@endsection