@extends('admin.layout.layout')
@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6"><h3 class="mb-0">Управление баннерами</h3></div>
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
                                    <strong>Ошибка: </strong> {{ Session::get('error_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                    <strong>Успешно: </strong> {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <strong>Ошибка! </strong> {!! $error !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                            <!--begin::Form-->
                            <form name="bannerForm" id="bannerForm" method="post"
                                  action="{{ isset($banner->id) ? route('banners.update', $banner->id) : route('banners.store') }}"
                                  enctype="multipart/form-data">@csrf
                                @if(isset($banner->id)) @method('PATCH') @endif
                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Тип баннера*</label>
                                        <select name="type" id="type" class="form-select">
                                            <option value="">Выберите Тип</option>
                                            <option value="Slider" {{ old('type', $banner->type ?? '') == 'Slider' ? 'selected' : '' }}>Слайдер</option>
                                            <option value="Fix" {{ old('type', $banner->type ?? '') == 'Fix' ? 'selected' : '' }}>Фиксированный</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Заголовок баннера*</label>
                                        <input type="text" class="form-control" id="title"
                                               name="title" placeholder="Введите заголовок баннера"
                                               value="{{ old('title', $banner->title ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="alt" class="form-label">Альтернативный текст</label>
                                        <input type="text" class="form-control" id="alt"
                                               name="alt" placeholder="Введите альтернативный текст"
                                               value="{{ old('alt', $banner->alt ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="link" class="form-label">Ссылка баннера</label>
                                        <input type="text" class="form-control" id="link"
                                               name="link" placeholder="Введите ссылку баннера"
                                               value="{{ old('link', $banner->link ?? '') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="sort" class="form-label">Номер для сортировки</label>
                                        <input type="number" class="form-control" id="sort"
                                               name="sort" value="{{ old('sort', $banner->sort ?? 0) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Изображение баннера@if(!isset($banner->id))*@endif</label>
                                        <input type="file" class="form-control" id="image"
                                               name="image" accept="image/*">
                                        @if(!empty($banner->image))
                                            <div class="mt-2">
                                                <img src="{{ asset('front/images/banners/' . $banner->image) }}" width="100" alt="Изображение баннера">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-check-label">Статус активности</label><br>
                                        <input type="checkbox" name="status"
                                               id="status" class="form-check-input"
                                               value="1" {{ (old('status', $banner->status ?? 1) == 1) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Завершить</button>
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
