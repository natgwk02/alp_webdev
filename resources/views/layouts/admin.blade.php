<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chile Mart - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    @stack('styles')

    <style>
        :root {
            --sidebar-width: 250px;
            --navbar-height: 56px;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: var(--sidebar-width);
            z-index: 1031;
            transition: transform 0.3s ease-in-out;
            background-color: #fff;
        }

        .main-wrapper {
            transition: margin-left 0.3s ease-in-out;
            margin-left: var(--sidebar-width);
        }

        .main-content {
            padding: 20px;
            min-height: 100vh;
        }

        .main-navbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        #sidebarToggleBtn {
            display: none;
            font-size: 1.25rem;
            color: #333;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1030;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 992px) {

            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .main-navbar {
                display: flex;
            }

            .main-content {
                padding-top: calc(var(--navbar-height) + 20px);
            }

            #sidebarToggleBtn {
                display: block;
            }
        }
    </style>


</head>

<body class="sticky-top bg-light">
    <div id="sidebar">
        @include('includes.sidebar')
    </div>

    <div class="main-wrapper">
        <nav class="navbar navbar-expand navbar-light bg-white sticky-top shadow-sm main-navbar">
            <div class="container-fluid">
                {{-- Tombol Toggle hanya muncul di mobile --}}
                <button class="btn btn-link d-lg-none" type="button" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>

        <main class="main-content" id="mainContent">
            @yield('content')
        </main>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
        });
    </script>
</body>

</html>
