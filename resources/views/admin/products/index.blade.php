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
                            <li class="breadcrumb-item active" aria-current="page">Продукты</li>
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
                                <h3 class="card-title">Продукты</h3>
                                @if($productsModule['edit_access'] == 1 || $productsModule['full_access'] == 1)
                                    <a style="max-width: 250px; float:right; display: inline-block;"
                                       href="{{ route('products.create') }}"
                                       class="btn btn-block btn-primary">
                                        Добавить продукт
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
                                <table id="products" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Product Color</th>
                                        <th>Category</th>
                                        <th>Parent</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>{{ $product->product_color }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>
                                                @if(isset($product->category->parent_category->name))
                                                    {{ $product->category->parent_category->name }}
                                                @else
                                                    ROOT
                                                @endif
                                            </td>
                                            <td>
                                                @if($productsModule['edit_access'] == 1 || $productsModule['full_access'] == 1)
                                                    @if($product->status == 1)
                                                        <a class="updateProductStatus" data-product-id="{{ $product->id }}"
                                                           style="color: #3f6ed3" href="javascript:void(0)"><i class="fas fa-toggle-on" data-status="Active"></i></a>
                                                    @else
                                                        <a class="updateProductStatus" data-product-id="{{ $product->id }}"
                                                           style="color: gray" href="javascript:void(0)"><i class="fas fa-toggle-off" data-status="Inactive"></i></a>
                                                    @endif
                                                    <a class="ms-1" href="{{ route('products.edit', $product->id) }}"><i class="fas fa-edit"></i></a>
                                                @endif
                                                @if($productsModule['full_access'] == 1)
                                                    <form action="{{ route('products.destroy', $product->id) }}"
                                                          method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="confirmDelete"
                                                                name="Product"
                                                                title="Delete Product"
                                                                type="button"
                                                                data-module="product"
                                                                data-id="{{ $product->id }}"
                                                                style="border: none; background: none; color: #3f6ed3">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
