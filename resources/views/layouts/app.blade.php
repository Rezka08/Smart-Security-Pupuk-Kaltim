<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Security System') - Pupuk Kaltim</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #0d6efd;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #0d6efd;
        }

        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #wrapper {
            display: flex;
            width: 100%;
            transition: all 0.3s ease;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: var(--sidebar-width);
            margin-left: 0;
            background-color: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
            left: 0;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            background: rgba(0, 0, 0, 0.2);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 12px 25px;
            background-color: transparent;
            color: #cbd5e1;
            font-size: 0.95rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            padding-left: 30px;
        }

        #sidebar-wrapper .list-group-item.active {
            background-color: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }

        #sidebar-wrapper .list-group-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-submenu {
            background-color: rgba(0, 0, 0, 0.2);
            font-size: 0.9rem;
        }
        
        .sidebar-submenu .list-group-item {
            padding-left: 55px !important;
        }

        #page-content-wrapper {
            width: 100%;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 15px 20px;
        }

        .sidebar-divider {
            padding: 15px 25px 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: calc(var(--sidebar-width) * -1);
        }

        #wrapper.toggled #page-content-wrapper {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            #page-content-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled #page-content-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled::before {
                content: "";
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <div class="d-flex" id="wrapper">
        
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-shield-check text-primary"></i> 
                <span>Smart Security</span>
            </div>
            
            <div class="list-group list-group-flush my-3">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <!-- Admin Menu -->
                @if(auth()->user()->isAdmin())
                    <div class="sidebar-divider">Administrator</div>
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Kelola User
                    </a>
                    <a href="{{ route('reports.validation') }}" class="list-group-item list-group-item-action {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-check-fill"></i> Validasi Laporan
                    </a>
                @endif

                <!-- Security Menu -->
                @if(auth()->user()->isSecurity())
                    <div class="sidebar-divider">Operasional</div>
                    
                    <a href="#submenuCCTV" data-bs-toggle="collapse" class="list-group-item list-group-item-action dropdown-toggle" aria-expanded="false">
                        <i class="bi bi-camera-video-fill"></i> CCTV & Inventaris
                    </a>
                    <div class="collapse {{ request()->routeIs('cctv-logs.*') || request()->routeIs('inventory.*') ? 'show' : '' }}" id="submenuCCTV">
                        <div class="list-group sidebar-submenu">
                            <a href="{{ route('cctv-logs.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('cctv-logs.create') ? 'active' : '' }}">
                                Input Log CCTV
                            </a>
                            <a href="{{ route('cctv-logs.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('cctv-logs.index') ? 'active' : '' }}">
                                Riwayat Log CCTV
                            </a>
                            <a href="{{ route('inventory.pos.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('inventory.pos.*') ? 'active' : '' }}">
                                Cek Inventaris Pos
                            </a>
                            <a href="{{ route('inventory.general.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('inventory.general.*') ? 'active' : '' }}">
                                Cek Inventaris General
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('my-tickets') }}" class="list-group-item list-group-item-action {{ request()->routeIs('my-tickets') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated-fill"></i> Tiket Saya
                    </a>
                @endif

                <!-- Maintenance Menu -->
                @if(auth()->user()->isMaintenance())
                    <div class="sidebar-divider">Maintenance</div>
                    <a href="{{ route('tickets.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="bi bi-tools"></i> Tiket Perbaikan
                    </a>
                @endif

                <!-- User Settings -->
                <div class="sidebar-divider">Akun</div>
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Pengaturan Profil
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="d-grid gap-2 px-3 mt-4">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm text-start ps-3">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-custom">
                <div class="container-fluid">
                    <button class="btn btn-light shadow-sm me-3" id="sidebarToggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <span class="navbar-text d-none d-md-block fw-bold text-secondary">
                        Sistem Keamanan Terpadu - Pupuk Kaltim
                    </span>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0 align-items-center">
                            
                            <!-- Role Badge -->
                            <li class="nav-item me-3">
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    {{ auth()->user()->role ?? 'User' }}
                                </span>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span class="fw-medium text-dark">{{ auth()->user()->full_name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i> Profil Saya
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content Container -->
            <div class="container-fluid px-4 py-4">
                
                <!-- Breadcrumbs/Title Section (Optional) -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 mb-0 text-gray-800">@yield('page-title')</h2>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white text-center py-3 mt-auto border-top">
                <div class="container text-muted small">
                    &copy; {{ date('Y') }} Smart Security System - PT Pupuk Kaltim. All rights reserved.
                </div>
            </footer>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var trigger = document.getElementById('sidebarToggle');
            var wrapper = document.getElementById('wrapper');
            
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                wrapper.classList.toggle('toggled');
            });
            
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && 
                    wrapper.classList.contains('toggled') && 
                    !document.getElementById('sidebar-wrapper').contains(e.target) && 
                    !trigger.contains(e.target)) {
                    wrapper.classList.remove('toggled');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>