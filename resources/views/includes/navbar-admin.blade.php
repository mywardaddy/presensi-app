@php
    $app = App\Models\Application::first();
@endphp
<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle">
        <i data-feather="menu"></i>
    </button>
    <!-- Navbar Brand-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="">
        {{ $app->app_name }}
    </a>
    <!-- Navbar Search Input-->
    <!-- * * Note: * * Visible only on and above the lg breakpoint-->
    <form class="form-inline me-auto d-none d-lg-block me-3">
        <div class="input-group input-group-joined input-group-solid">
            
        </div>
    </form>
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <!-- Navbar Search Dropdown-->
        <!-- * * Note: * * Visible only below the lg breakpoint-->

        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <div class="dropdown-toggle" id="navbarDropdownUserImage" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <a class="btn btn-icon btn-transparent-dark" href="javascript:void(0);" style="margin-right: 5px;">
                    @if (Auth::user()->profile != NULL)
                    <img class="img-fluid" src="{{ asset('gambarprofile/' . Auth::user()->profile) }}" />
                @else
                    <img class="img-fluid" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" />
                @endif
                </a>
            </div>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    @if (Auth::user()->profile != NULL)
                    <img class="dropdown-user-img" src="{{ asset('gambarprofile/' . Auth::user()->profile) }}" />
                @else
                    <img class="dropdown-user-img" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" />
                @endif
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">{{ Auth::user()->name }}</div>
                        <div class="dropdown-user-details-email">{{ Auth::user()->email }}</div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>