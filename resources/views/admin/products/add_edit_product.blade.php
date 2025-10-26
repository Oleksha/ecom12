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
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
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
                <div class="row g-4">
                    <!--begin::Col-->
                    <div class="col-md-6">
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header"><div class="card-title">{{ $title }}</div></div>
                            <!--end::Header-->
                            @if(Session::has('error_message'))
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Error: </strong> {{ Session::get('error_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                    <strong>Success: </strong> {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Error! </strong> {!! $error !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                            <!--begin::Form-->
                            <form name="productForm" id="productForm" method="post"
                                  action="{{ isset($product->id) ? route('products.update', $product->id) : route('products.store') }}">@csrf
                                @if(isset($product->id)) @method('PATCH') @endif
                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="category_id">Category Level (Select Category)*</label>
                                        <select name="category_id" class="form-control" id="category_id">
                                            <option value="">Select</option>
                                            @foreach($getCategories as $cat)
                                                <option value="{{ $cat['id'] }}" @if(old('category_id', $product->category_id ?? '') == $cat['id']) selected @endif>{{ $cat['name'] }}</option>
                                                @if(!empty($cat['subcategories']))
                                                    @foreach($cat['subcategories'] as $subcat)
                                                        <option value="{{ $subcat['id'] }}" @if(old('category_id', $product->category_id ?? '') == $subcat['id']) selected @endif>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subcat['name'] }}
                                                        </option>
                                                        @if(!empty($subcat['subcategories']))
                                                            @foreach($subcat['subcategories'] as $subsubcat)
                                                                <option value="{{ $subsubcat['id'] }}" @if(old('category_id', $product->category_id ?? '') == $subsubcat['id']) selected @endif>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subsubcat['name'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Имя продукта</label>
                                        <input type="text" class="form-control" id="product_name"
                                               name="product_name" placeholder="Введите имя категории"
                                               value="{{ old('product_name', $product->name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_code" class="form-label">Код продукта</label>
                                        <input type="text" class="form-control" id="product_code"
                                               name="product_code" placeholder="Введите код продукта"
                                               value="{{ old('product_code', $product->code ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_color" class="form-label">Цвет продукта</label>
                                        <input type="text" class="form-control" id="product_color"
                                               name="product_color" placeholder="Введите цвет продукта"
                                               value="{{ old('product_color', $product->color ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="family_color" class="form-label">Family Color</label>
                                        <input type="text" class="form-control" id="family_color"
                                               name="family_color" placeholder="Enter Family Color"
                                               value="{{ old('family_color', $product->family_color ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="group_code" class="form-label">Group Code</label>
                                        <input type="text" class="form-control" id="group_code"
                                               name="group_code" placeholder="Enter Group Code"
                                               value="{{ old('group_code', $product->group_code ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_price" class="form-label">Product Price*</label>
                                        <input type="text" class="form-control" id="product_price"
                                               name="product_price" placeholder="Enter Product Price"
                                               value="{{ old('product_price', $product->product_price ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_discount" class="form-label">Product Discount (%)</label>
                                        <input type="number" step="0.01" class="form-control"
                                               id="product_discount" name="product_discount"
                                               value="{{ old('product_discount', $product->product_discount ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_gst" class="form-label">Product GST (%)</label>
                                        <input type="number" step="0.01" class="form-control"
                                               id="product_gst" name="product_gst"
                                               value="{{ old('product_gst', $product->product_gst ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_weight" class="form-label">Вес продукта (кг)</label>
                                        <input type="number" step="0.01" class="form-control"
                                               id="product_weight" name="product_weight"
                                               value="{{ old('product_weight', $product->product_weight ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="wash_care" class="form-label">Wash Care</label>
                                        <textarea name="wash_care" class="form-control" placeholder="Enter Wash Care">{{ old('wash_care', $product->wash_care ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Описание продукта</label>
                                        <textarea name="description" class="form-control" placeholder="Введите описание продукта">{{ old('description', $product->description ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="search_keywords" class="form-label">Search Keywords</label>
                                        <textarea name="search_keywords" class="form-control" placeholder="Enter Search Keywords">{{ old('search_keywords', $product->search_keywords ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title"
                                               name="meta_title" placeholder="Enter Meta Title"
                                               value="{{ old('meta_title', $product->meta_title ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <input type="text" class="form-control" id="meta_description"
                                               name="meta_description" placeholder="Enter Meta Description"
                                               value="{{ old('meta_description', $product->meta_description ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords"
                                               name="meta_keywords" placeholder="Enter Meta Keywords"
                                               value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="is_featured" class="form-label">Is Featured?</label>
                                        <select name="is_featured" id="is_featured" class="form-select">
                                            <option value="No" {{ (old('is_featured' , $product->is_featured ?? '') == 'No') ? 'selected' : '' }}></option>
                                            <option value="Yes" {{ (old('is_featured' , $product->is_featured ?? '') == 'Yes') ? 'selected' : '' }}></option>
                                        </select>
                                    </div>
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                <!--end::Footer-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Quick Example-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
@endsection
