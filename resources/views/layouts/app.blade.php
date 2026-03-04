<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UMAEP v3.2') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --umaep-primary: #4e73df;
            --umaep-secondary: #858796;
            --umaep-success: #1cc88a;
            --umaep-info: #36b9cc;
            --umaep-warning: #f6c23e;
            --umaep-danger: #e74a3b;
            --umaep-dark: #222e3c;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fc;
            color: #333;
        }

        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--umaep-dark);
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            z-index: 1000;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            display: block;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: 0.3s;
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a i {
            margin-right: 10px;
        }

        #content {
            width: 100%;
            padding: 0;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 25px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            border-radius: 0.5rem;
        }

        .profile-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 50rem;
            text-transform: uppercase;
            font-weight: 700;
        }
    </style>
    @yield('extra_css')

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center justify-content-center">
                <h4 class="mb-0 fw-bold text-white">UMAEP <span class="text-primary">v3.2</span></h4>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                    <a href="{{ url('/dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                </li>

                @if(session('current_profile_type') == 'advertiser')
                    <li class="{{ Request::is('campaigns*') ? 'active' : '' }}">
                        <a href="{{ url('/campaigns') }}"><i class="bi bi-megaphone"></i> Campaigns</a>
                    </li>
                    <li class="{{ Request::is('executions*') ? 'active' : '' }}">
                        <a href="{{ url('/executions') }}"><i class="bi bi-play-circle"></i> Executions</a>
                    </li>
                @endif

                @if(session('current_profile_type') == 'vendor')
                    <li class="{{ Request::is('inventory*') ? 'active' : '' }}">
                        <a href="{{ url('/inventory') }}"><i class="bi bi-grid-3x3-gap"></i> My Inventory</a>
                    </li>
                @endif

                <li class="{{ Request::is('messages*') ? 'active' : '' }}">
                    <a href="{{ route('messages.index') }}"><i class="bi bi-chat-dots"></i> Messages</a>
                </li>

                <li class="">
                    <a href="{{ url('/invoices') }}"><i class="bi bi-file-earmark-text"></i> Invoices</a>
                </li>

                <li class="mt-auto border-top border-secondary-subtle">
                    <a href="{{ route('profiles.index') }}" class="text-info"><i
                            class="bi bi-arrow-left-right text-info"></i> Switch Profile</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-link text-dark p-0 me-3">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        @auth
                            <!-- Profile Switcher Component -->
                            <x-profile-switcher />

                            <div class="dropdown ms-3">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    <div class="text-end me-2 d-none d-sm-block">
                                        <div class="fw-bold small">{{ Auth::user()->email }}</div>
                                        <div class="text-muted smaller" style="font-size: 0.7rem;">Verified Account</div>
                                    </div>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> My Account</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-shield-lock me-2"></i>
                                            Security</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-4 px-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra_js')
</body>

</html>