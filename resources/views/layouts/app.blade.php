<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Prácticas Vexis')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Estilos personalizados -->
    <style>
        /* Asegurar que el dropdown del usuario no se corte */
        .navbar-nav .dropdown-menu {
            right: 0;
            left: auto;
        }
        
        @media (max-width: 991.98px) {
            .navbar-nav .dropdown-menu {
                right: auto;
                left: 0;
            }
        }
        
        /* Estilos para paginación Bootstrap */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            justify-content: center;
        }
        
        .pagination .page-item {
            margin: 0 2px;
        }
        
        .pagination .page-link {
            position: relative;
            display: block;
            padding: 0.375rem 0.75rem;
            color: #0d6efd;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        
        .pagination .page-link:hover {
            z-index: 2;
            color: #0a58ca;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        /* Ocultar elementos duplicados de paginación */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        
        /* Asegurar que solo se muestre una paginación */
        .pagination-wrapper > ul:not(:first-child) {
            display: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">Vexis</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        
                        @can('ver usuarios')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                            </li>
                        @endcan
                        
                        @can('ver departamentos')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('departamentos.index') }}">Departamentos</a>
                            </li>
                        @endcan
                        
                        @can('ver centros')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('centros.index') }}">Centros</a>
                            </li>
                        @endcan
                        
                        @can('ver roles')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('roles.index') }}">Roles y Permisos</a>
                            </li>
                        @endcan

                        @can('ver restricciones')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('restricciones.index') }}">Restricciones</a>
                            </li>
                        @endcan

                        @can('ver clientes')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a>
                            </li>
                        @endcan

                        @can('ver vehículos')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vehiculos.index') }}">Vehículos</a>
                            </li>
                        @endcan

                        @can('ver ofertas')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ofertas.index') }}">Ofertas</a>
                            </li>
                        @endcan
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3">
            © 2025 Vexis - Sistema de Gestión
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    @stack('scripts')
</body>
</html>