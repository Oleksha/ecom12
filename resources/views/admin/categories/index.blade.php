@extends('admin.layout.layout')
@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6"><h3 class="mb-0">Управление каталогом</h3></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Категории</li>
                        </ol>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Категории</h3>
                                @if($categoriesModule['edit_access'] == 1 || $categoriesModule['full_access'] == 1)
                                    <a style="max-width: 250px; float:right; display: inline-block;"
                                       href="{{ route('categories.create') }}"
                                       class="btn btn-block btn-primary">
                                        Добавить категорию
                                    </a>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @if(Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                        <strong>Success: </strong> {{ Session::get('success_message') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                <table id="categories" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Parent Category</th>
                                        <th>URL</th>
                                        <th>Created On</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->parent_category->name ?? '' }}</td>
                                            <td>{{ $category->url }}</td>
                                            <td>{{ $category->created_at->format('F j, Y, g:i a') }}</td>
                                            <td>
                                                @if($categoriesModule['edit_access'] == 1 || $categoriesModule['full_access'] == 1)
                                                    @if($category->status == 1)
                                                        <a class="updateCategoryStatus me-2"
                                                           data-category-id="{{ $category->id }}"
                                                           style="color: #3f6ed3" href="javascript:void(0)"><i class="fas fa-toggle-on" data-status="Active"></i></a>
                                                    @else
                                                        <a class="updateCategoryStatus me-2"
                                                           data-category-id="{{ $category->id }}"
                                                           style="color: grey" href="javascript:void(0)"><i class="fas fa-toggle-off" data-status="Inactive"></i></a>
                                                    @endif
                                                    <a class="me-2" href="{{ route('categories.edit', $category->id) }}"><i class="fas fa-edit"></i></a>
                                                    @if($categoriesModule['full_access'] == 1)
                                                        <form action="{{ route('categories.destroy', $category->id) }}"
                                                              method="POST" style="display: inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="confirmDelete"
                                                                    name="Category"
                                                                    title="DeleteCategory"
                                                                    type="button"
                                                                    data-module="category"
                                                                    data-id="{{ $category->id }}"
                                                                    style="border: none; background: none; color: #3f6ed3">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
@endsection
