<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HRD Management') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
            color: #fff;
            text-decoration: none;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        .sidebar .nav-link {
            position: relative;
            display: block;
        }
        .sidebar .badge {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .navbar-brand {
            font-weight: bold;
            color: #667eea !important;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        .page-title {
            color: #667eea;
            font-weight: bold;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table {
            min-width: 800px;
            white-space: nowrap;
        }
        .table td, .table th {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        .table .btn {
            margin: 0.25rem;
            white-space: nowrap;
        }
        .menu-section {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
            padding: 20px 20px 10px 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <h4 class="text-white text-center mb-4">
                        <i class="fas fa-users-cog"></i> HRD System
                    </h4>
                    
                    <ul class="nav flex-column">
                        <!-- Menu Utama -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- Section: Kehadiran & Lembur -->
                        <div class="menu-section">Kehadiran</div>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('attendances*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                                <i class="fas fa-calendar-check me-2"></i> Absensi
                            </a>
                        </li>
                        
                        <!-- Menu Lembur dengan Badge untuk Admin -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('overtimes*') ? 'active' : '' }}" href="{{ route('overtimes.index') }}">
                                <i class="fas fa-clock me-2"></i> 
                                @auth
                                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                        Kelola Lembur
                                        @php
                                            $pendingCount = \App\Models\OvertimeRequest::where('status', 'pending')->count();
                                        @endphp
                                        @if($pendingCount > 0)
                                            <span class="badge bg-danger">{{ $pendingCount }}</span>
                                        @endif
                                    @else
                                        Lembur Saya
                                    @endif
                                @else
                                    Lembur
                                @endauth
                            </a>
                        </li>
                        
                        <!-- Section: Penggajian -->
                        <div class="menu-section">Penggajian</div>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('salaries*') ? 'active' : '' }}" href="{{ route('salaries.index') }}">
                                <i class="fas fa-money-bill-wave me-2"></i> 
                                @auth
                                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                        Kelola Payroll
                                    @else
                                        Slip Gaji
                                    @endif
                                @else
                                    Payroll
                                @endauth
                            </a>
                        </li>
                        
                        <!-- Section: Manajemen (Admin Only) -->
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <div class="menu-section">Manajemen</div>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i> Kelola User
                                </a>
                            </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="navbar-nav ms-auto">
                            @auth
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <div class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </div>
                            @endauth
                        </div>
                    </div>
                </nav>

                <!-- Page content -->
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>