<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ResyFlowHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100%;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #0d6efd;
            padding: 20px 0;
            color: white;
            transition: all 0.3s;
            z-index: 1060;
            overflow-y: auto;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 10px 20px;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .navbar-toggler {
            display: none;
        }

        /* Mobile menu toggle button */
        .menu-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1070;
            background-color: #0d6efd;
            border: none;
            color: white;
            padding: 8px 10px;
            border-radius: 4px;
            display: none;
        }

        /* Zorg dat de uitlog knop niet overlapt met inhoud */
        .sidebar-content {
            padding-bottom: 60px; /* Ruimte voor de uitlogknop */
        }
        
        /* Zorg dat de uitlog knop onderaan blijft maar niet overlapt */
        .sidebar-footer {
            position: sticky;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
            background-color: #0d6efd;
            margin-top: 20px;
        }

        /* Voeg overlay toe voor beter visueel effect op mobiel */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1055;
        }
        
        .sidebar-overlay.active {
            display: block;
        }

        /* Responsive styles */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 280px; /* Vaste breedte op mobiel */
                left: -280px;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .menu-toggle {
                display: block;
            }
        }

        /* Tablet styles */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .sidebar {
                width: 240px;
                left: -240px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content.sidebar-active {
                margin-left: 240px;
            }
        }
    </style>
    <!-- Font Awesome voor de iconen -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Hamburger menu toggle button -->
    <button class="menu-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay voor mobiel -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="px-3 mb-4 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold">Reservero</h3>
            <button class="btn text-white d-block d-lg-none" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigatie Items -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-th"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calendar') }}" class="nav-link">
                    <i class="fas fa-calendar"></i> Kalender
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" class="nav-link">
                    <i class="fas fa-users"></i> Klanten
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('reservation.form') }}" class="nav-link">
                    <i class="fas fa-th-large"></i> widget  
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="fas fa-users"></i> Gebruikers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link">
                    <i class="fas fa-cog"></i> Instellingen
                </a>
            </li>
        </ul>

        <!-- Uitloggen Knop -->
        <div class="mt-auto position-absolute bottom-0 w-100 mb-3">
            <a href="#" class="nav-link px-3">
                <i class="fas fa-sign-out-alt"></i> Uitloggen
            </a>
        </div>
    </div>

    <div class="main-content" id="content">
        @yield('content')
    </div>

    <!-- Bootstrap JS en Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>

    <!-- JavaScript voor responsive sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const overlay = document.getElementById('sidebarOverlay');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('sidebar-active');
                overlay.classList.toggle('active');
            });

            closeSidebar.addEventListener('click', function() {
                sidebar.classList.remove('active');
                content.classList.remove('sidebar-active');
                overlay.classList.remove('active');
            });

            // Close sidebar when clicking on overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                content.classList.remove('sidebar-active');
                overlay.classList.remove('active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggleButton = sidebarToggle.contains(event.target);
                const isClickOnOverlay = overlay.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggleButton && !isClickOnOverlay && window.innerWidth < 992 && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    content.classList.remove('sidebar-active');
                    overlay.classList.remove('active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('active');
                    content.classList.remove('sidebar-active');
                    overlay.classList.remove('active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
