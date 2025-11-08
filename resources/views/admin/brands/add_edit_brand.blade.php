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
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                    <strong>Ошибка: </strong> {{ Session::get('error_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                    <strong>Успешно: </strong> {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                    <strong>Ошибка! </strong> {!! $error !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                            <!--begin::Form-->
                            <form name="brandForm" id="brandForm" method="post"
                                  action="{{ isset($brand->id) ? route('brands.update', $brand->id) : route('brands.store') }}"
                                  enctype="multipart/form-data">@csrf
                                @if(isset($brand->id)) @method('PATCH') @endif
                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Имя бренда*</label>
                                        <input type="text" class="form-control" id="name"
                                               name="name" placeholder="Введите имя бренда"
                                               value="{{ old('name', $brand->name ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Изображение бренда</label>
                                        <input type="file" class="form-control" id="image"
                                               name="image" accept="image/*">
                                        @if(!empty($brand->image))
                                            <div class="mt-2" id="brandImageBlock">
                                                <a target="_blank" href="{{ asset('front/images/brands/' . $brand->image) }}"><img src="{{ asset('front/images/brands/' . $brand->image) }}" width="50" alt="Изображение бренда"></a>
                                                <a href="javascript:void(0)" id="deleteBrandImage" data-brend-id="{{ $brand->id }}" class="text-danger">Delete</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Логотип бренда</label>
                                        <input type="file" class="form-control" id="logo"
                                               name="logo" accept="image/*">
                                        @if(!empty($brand->logo))
                                            <div class="mt-2" id="brandLogoBlock">
                                                <a target="_blank" href="{{ asset('front/images/logos/' . $brand->logo) }}"><img src="{{ asset('front/images/logos/' . $brand->logo) }}" width="50" alt="Логотип бренда"></a>
                                                <a href="javascript:void(0)" id="deleteLogoImage" data-brend-id="{{ $brand->id }}" class="text-danger">Delete</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="brand_discount" class="form-label">Скидка бренда</label>
                                        <input type="text" class="form-control" placeholder="Введите скидку бренда"
                                               id="brand_discount" name="brand_discount"
                                               value="{{ old('brand_discount', $brand->discount ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL бренда*</label>
                                        <input type="text" class="form-control" placeholder="Введите URL бренда"
                                               id="url" name="url"
                                               value="{{ old('url', $brand->url ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Описание бренда</label>
                                        <textarea class="form-control" rows="3" id="description"
                                                  name="description" placeholder="Введите описание">{{ old('description', $brand->description ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta-Заголовок</label>
                                        <input type="text" class="form-control"
                                               placeholder="Введите Meta-Заголовок"
                                               id="meta_title" name="meta_title"
                                               value="{{ old('meta_title', $brand->meta_title ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta-Описание</label>
                                        <input type="text" class="form-control"
                                               placeholder="Введите Meta-Описание"
                                               id="meta_description" name="meta_description"
                                               value="{{ old('meta_description', $brand->meta_description ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta-Ключевые слова</label>
                                        <input type="text" class="form-control"
                                               placeholder="Введите Meta-Ключевые слова"
                                               id="meta_keywords" name="meta_keywords"
                                               value="{{ old('meta_keywords', $brand->meta_keywords ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="menu_status" class="form-check-label">Показывать в главном меню</label><br>
                                        <input type="checkbox" name="menu_status"
                                               id="menu_status" class="form-check-input"
                                               value="1" {{ !empty($brand->menu_status) ? 'checked' : '' }}>
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
