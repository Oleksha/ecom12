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
                            <li class="breadcrumb-item active" aria-current="page">Баннеры</li>
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
                                <h3 class="card-title">Баннеры</h3>
                                @if($bannersModule['edit_access'] == 1 || $bannersModule['full_access'] == 1)
                                    <a style="max-width: 250px; float:right; display: inline-block;"
                                       href="{{ route('banners.create') }}"
                                       class="btn btn-block btn-primary">
                                        Добавить баннер
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
                                <table id="banners" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Type</th>
                                        <th>Link</th>
                                        <th>Title</th>
                                        <th>Alt</th>
                                        <th>Sort</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banners as $banner)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('front/images/banners/' . $banner->image) }}" width="100" alt="">
                                            </td>
                                            <td>{{ $banner->type }}</td>
                                            <td>{{ $banner->link }}</td>
                                            <td>{{ $banner->title }}</td>
                                            <td>{{ $banner->alt }}</td>
                                            <td>{{ $banner->sort }}</td>
                                            <td>
                                                @if($bannersModule['edit_access'] == 1 || $bannersModule['full_access'] == 1)
                                                    @if($banner->status == 1)
                                                        <a class="updateBannerStatus me-2"
                                                           data-id="{{ $banner->id }}"
                                                           style="color: #3f6ed3" href="javascript:void(0)"><i class="fas fa-toggle-on" data-status="Active"></i></a>
                                                    @else
                                                        <a class="updateBannerStatus me-2"
                                                           data-id="{{ $banner->id }}"
                                                           style="color: grey" href="javascript:void(0)"><i class="fas fa-toggle-off" data-status="Inactive"></i></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($bannersModule['edit_access'] == 1 || $bannersModule['full_access'] == 1)
                                                    <a class="me-2" href="{{ route('banners.edit', $banner->id) }}"><i class="fas fa-edit"></i></a>
                                                    @if($bannersModule['full_access'] == 1)
                                                        <form action="{{ route('banners.destroy', $banner->id) }}"
                                                              method="POST" style="display: inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="confirmDelete"
                                                                    name="Banner"
                                                                    title="DeleteBanner"
                                                                    type="button"
                                                                    data-module="banners"
                                                                    data-id="{{ $banner->id }}"
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
