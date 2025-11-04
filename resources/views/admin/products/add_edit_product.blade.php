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
                                        <label for="product_name" class="form-label">Имя продукта*</label>
                                        <input type="text" class="form-control" id="product_name"
                                               name="product_name" placeholder="Введите имя категории"
                                               value="{{ old('product_name', $product->product_name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_code" class="form-label">Код продукта*</label>
                                        <input type="text" class="form-control" id="product_code"
                                               name="product_code" placeholder="Введите код продукта"
                                               value="{{ old('product_code', $product->product_code ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_color" class="form-label">Цвет продукта*</label>
                                        <input type="text" class="form-control" id="product_color"
                                               name="product_color" placeholder="Введите цвет продукта"
                                               value="{{ old('product_color', $product->product_color ?? '') }}">
                                    </div>
                                    @php $familyColors = \App\Models\Color::colors() @endphp
                                    <div class="mb-3">
                                        <label for="family_color" class="form-label">Семейство цветов*</label>
                                        <select name="family_color" class="form-select" id="family_color">
                                            <option value="">Выберите цвет...</option>
                                            @foreach($familyColors as $color)
                                                <option value="{{ $color->name }}" @if(isset($product['family_color']) && $product['family_color'] == $color->name) selected @endif>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                        <label for="product_weight" class="form-label">Вес продукта (г)</label>
                                        <input type="number" step="0.01" class="form-control"
                                               id="product_weight" name="product_weight"
                                               value="{{ old('product_weight', $product->product_weight ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-1">Product Attributes</label>
                                        {{-- Header row --}}
                                        <div class="d-none d-md-flex fw-semibold bg-light border rounded px-2 py-1 mb-2">
                                            <div class="flex-fill col-2">Size</div>
                                            <div class="flex-fill col-2 ms-5">SKU</div>
                                            <div class="flex-fill col-2 ms-5">Price</div>
                                            <div class="flex-fill col-2 ms-5">Stock</div>
                                            <div class="flex-fill col-2 ms-5">Sort</div>
                                            <div style="width: 60px"></div>
                                        </div>
                                        {{-- Dynamic rows --}}
                                        <div class="field_wrapper">
                                            {{-- first row --}}
                                            <div class="d-flex align-items-center gap-2 mb-2 attribute-row">
                                                <input name="size[]" class="form-control flex-fill col-2" placeholder="Size">
                                                <input name="sku[]" class="form-control flex-fill col-2" placeholder="SKU">
                                                <input name="price[]" class="form-control flex-fill col-2" placeholder="Price">
                                                <input name="stock[]" class="form-control flex-fill col-2" placeholder="Stock">
                                                <input name="sort[]" class="form-control flex-fill col-2" placeholder="Sort">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-success add_button" title="Add row">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($product['attributes']) && count($product['attributes']) > 0)
                                        <div class="mb-3">
                                            <label class="form-label mb-1">Existing Product Attributes</label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle mb-0">
                                                    <thead class="table-light text-center">
                                                    <tr>
                                                        <th style="width: 15%;">Size</th>
                                                        <th style="width: 20%;">SKU</th>
                                                        <th style="width: 15%;">Price</th>
                                                        <th style="width: 15%;">Stock</th>
                                                        <th style="width: 15%;">Sort</th>
                                                        <th style="width: 15%;">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($product['attributes'] as $attribute)
                                                        <input type="hidden" name="attrId[]" value="{{ $attribute['id'] }}">
                                                        <tr class="text-center">
                                                            <td>{{ $attribute['size'] }}</td>
                                                            <td>{{ $attribute['sku'] }}</td>
                                                            <td>
                                                                <input type="number" name="update_price[]"
                                                                       value="{{ $attribute['price'] }}"
                                                                       class="form-control text-center" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="update_stock[]"
                                                                       value="{{ $attribute['stock'] }}"
                                                                       class="form-control text-center" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="update_sort[]"
                                                                       value="{{ $attribute['sort'] }}"
                                                                       class="form-control text-center" required>
                                                            </td>
                                                            <td>
                                                                @if($attribute['status'] == 1)
                                                                    <a class="updateAttributeStatus text-primary me-2"
                                                                       id="attribute-{{ $attribute['id'] }}"
                                                                       data-attribute-id="{{ $attribute['id'] }}"
                                                                       href="javascript:void(0)"><i class="fa fa-toggle-on" data-status="Active"></i></a>
                                                                @else
                                                                    <a class="updateAttributeStatus text-secondary me-2"
                                                                       id="attribute-{{ $attribute['id'] }}"
                                                                       data-attribute-id="{{ $attribute['id'] }}"
                                                                       href="javascript:void(0)"><i class="fa fa-toggle-off" data-status="Inactive"></i></a>
                                                                @endif
                                                                <a title="Delete Attribute" href="javascript:void(0)"
                                                                   class="confirmDelete text-danger" data-module="product-attribute"
                                                                   data-id="{{ $attribute['id'] }}">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="product_video_dropzone" class="form-label">Product Main Image (Max 500 KB)</label>
                                        <div id="mainImageDropzoneError" style="color: red; display: none;"></div>
                                        <div class="dropzone" id="mainImageDropzone"></div>
                                        {{-- Product Main Image --}}
                                        @if(!empty($product['main_image']))
                                            <a target="_blank" href="{{ url('front/images/products/' . $product['main_image']) }}"><img style="margin: 10px" src="{{ asset('product-image/thumbnail/' . $product['main_image']) }}" alt="{{ $product['product_name'] }}"></a>
                                            <a style="color: #3f6ed3" class="confirmDelete"
                                               title="Delete Product Image" href="javascript:void(0)"
                                               data-module="product-main-image" data-id="{{ $product['id'] }}"><i class="fas fa-trash"></i></a>
                                        @endif

                                        <!-- Hidden input to send uploaded image -->
                                        <input type="hidden" name="main_image" id="main_image_hidden">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="product_images_dropzone">
                                            Alternate Product Images (Multiple Uploads Allowed, Max 500 KB each)
                                        </label>
                                        <div class="dropzone" id="productImagesDropzone"></div>
                                        @if(isset($product->product_images) && $product->product_images->count() > 0)
                                            @if($product->product_images->count() > 1)
                                                {{-- Instruction Line --}}
                                                <p class="drag-instruction">
                                                    <i class="fas fa-arrows-alt"></i> Перетащите изображения ниже, чтобы изменить их порядок.
                                                </p>
                                            @endif
                                            {{-- Контейнер для сортируемых изображений --}}
                                            <div id="sortable-images" class="sortable-wrapper d-flex gap-2 overflow-auto">
                                                {{-- Product Alternate Images --}}
                                                @foreach($product->product_images as $img)
                                                    <div class="sortable-item" data-id="{{ $img->id }}" style="position: relative;">
                                                        <a target="_blank"
                                                           href="{{ url('front/images/products/' . $img->image) }}">
                                                            <img src="{{ asset('product-image/thumbnail/' . $img->image) }}" alt="">
                                                        </a>
                                                        <a href="javascript:void(0)" class="confirmDelete"
                                                           data-module="product-image" data-id="{{ $img->id }}"
                                                           data-image="{{ $img->image }}">
                                                            <i class="fas fa-trash"
                                                               style="position: absolute; top: 0; right: 0; color: red;"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Hidden input to collect alternate images -->
                                        <input type="hidden" name="product_images" id="product_images_hidden">
                                    </div>
                                    <div class="mb-3">
                                        <label for="main_image_dropzone" class="form-label">Product Video (Max 2 MB)</label>
                                        <div class="dropzone" id="productVideoDropzone"></div>

                                        @if(!empty($product['product_video']))
                                            <a target="_blank" href="{{ url('front/videos/products/' . $product['product_video']) }}">
                                                View Video
                                            </a> | <a class="confirmDelete" href="javascript:void(0)"
                                                      data-module="product-video" data-id="{{ $product['id'] }}">Delete Video</a>
                                        @endif

                                        <!-- Hidden input to send uploaded image -->
                                        <input type="hidden" name="product_video" id="product_video_hidden">
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
