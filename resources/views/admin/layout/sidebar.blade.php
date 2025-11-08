<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="#" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="{{ asset('admin/images/AdminLTELogo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false"
            >
                <li class="nav-item {{ in_array(Session::get('page'), ['dashboard', 'update-password', 'update-details', 'subadmins']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array(Session::get('page'), ['dashboard', 'update-password', 'update-details', 'subadmins']) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Управление
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/dashboard') }}" class="nav-link {{ (Session::get('page') == 'dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.update-password') }}" class="nav-link {{ (Session::get('page') == 'update-password') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Update Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.update-details') }}" class="nav-link {{ (Session::get('page') == 'update-details') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Update Details</p>
                            </a>
                        </li>
                        @if(Auth::guard('admin')->user()->role == 'admin')
                            <!-- Admins / Sub Admins -->
                            <li class="nav-item">
                                <a href="{{ url('admin/subadmins') }}" class="nav-link {{ (Session::get('page') == 'subadmins') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Subadmins</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item {{ in_array(Session::get('page'), ['categories', 'brands', 'products']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array(Session::get('page'), ['categories', 'brands', 'products']) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Каталог
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ (Session::get('page') == 'categories') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Категории</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brands.index') }}" class="nav-link {{ (Session::get('page') == 'brands') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Бренды</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ (Session::get('page') == 'products') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Продукты</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
