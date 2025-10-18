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
                            <form name="categoryForm" id="categoryForm" method="post"
                                  action="{{ isset($category->id) ? route('categories.update', $category->id) : route('categories.store') }}"
                                  enctype="multipart/form-data">@csrf
                                @if(isset($category->id)) @method('PATCH') @endif
                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label">Имя категории</label>
                                        <input type="text" class="form-control" id="category_name"
                                               name="category_name" placeholder="Введите имя категории"
                                               value="{{ old('category_name', $category->name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="parent_id" class="form-label">
                                            Category Level (Parent Category)*
                                        </label>
                                        <select name="parent_id" class="form-control" id="parent_id">
                                            <option value="">Select</option>
                                            <option value="" @if(is_null($category->parent_id)) selected @endif>Main Category</option>
                                            @foreach($getCategories as $cat)
                                                <option value="{{ $cat['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $cat['id']) selected @endif>{{ $cat['name'] }}</option>
                                                @if(!empty($cat['subcategories']))
                                                    @foreach($cat['subcategories'] as $subcat)
                                                        <option value="{{ $subcat['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $subcat['id']) selected @endif>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subcat['name'] }}
                                                        </option>
                                                        @if(!empty($subcat['subcategories']))
                                                            @foreach($subcat['subcategories'] as $subsubcat)
                                                                <option value="{{ $subsubcat['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $subsubcat['id']) selected @endif>
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
                                        <label for="category_image" class="form-label">Изображение</label>
                                        <input type="file" class="form-control" id="category_image"
                                               name="category_image" accept="image/*">
                                        @if(!empty($category->image))
                                            <div class="mt-2">
                                                <img src="{{ asset('front/images/categories/' . $category->image) }}" width="50" alt="Изображение">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="size_chart" class="form-label">Size Chart</label>
                                        <input type="file" class="form-control" id="size_chart"
                                               name="size_chart" accept="image/*">
                                        @if(!empty($category->size_chart))
                                            <div class="mt-2">
                                                <img src="{{ asset('front/images/size-charts/' . $category->size_chart) }}" width="50" alt="Size Chart">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_discount" class="form-label">Скидка</label>
                                        <input type="text" class="form-control" placeholder="Введите скидку"
                                               id="category_discount" name="category_discount"
                                               value="{{ old('category_discount', $category->discount ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" class="form-control" placeholder="Введите скидку"
                                               id="url" name="url"
                                               value="{{ old('url', $category->url ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Описание</label>
                                        <textarea class="form-control" rows="3" id="description"
                                                  name="description" placeholder="Введите описание">
                                            {{ old('description', $category->description ?? '') }}
                                        </textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta-Заголовок</label>
                                        <input type="text" class="form-control"
                                               placeholder="Введите Meta-Заголовок"
                                               id="meta_title" name="meta_title"
                                               value="{{ old('meta_title', $category->meta_title ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta-Описание</label>
                                        <input type="text" class="form-control"
                                               placeholder="Введите Meta-Описание"
                                               id="meta_description" name="meta_description"
                                               value="{{ old('meta_description', $category->meta_description ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta-Ключевые слова</label>
                                        <input type="text" class="form-control"
                                               placeholder="Введите Meta-Ключевые слова"
                                               id="meta_keywords" name="meta_keywords"
                                               value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="menu_status" class="form-check-label">Показывать в главном меню</label><br>
                                        <input type="checkbox" name="menu_status"
                                               id="menu_status" class="form-check-input"
                                               value="1" {{ !empty($category->menu_status) ? 'checked' : '' }}>
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
