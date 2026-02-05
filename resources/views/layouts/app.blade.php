<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIDAPEG')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --pink-light: #e8f1fb;
            --pink-medium: #90caf9;
            --pink-dark: #1e88e5;
            --pink-darker: #0d47a1;
            --white: #ffffff;
            --gray-light: #f5f7fa;
            --shadow: rgba(30, 136, 229, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--pink-light), var(--white));
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 250px;
            background: var(--white);
            box-shadow: 5px 0 15px var(--shadow);
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.3s ease;
        }

        .logo {
            text-align: center;
            border-bottom: 2px solid var(--pink-light);
            /* margin-bottom: 30px; */
        }

        .logo h2 {
            color: var(--pink-darker);
            font-size: 24px;
        }

        .logo p {
            color: var(--pink-dark);
            font-size: 14px;
        }

        .nav-links {
            list-style: none;
            padding: 0 20px;
        }

        .nav-links li {
            margin-bottom: 10px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            padding: 15px;
            color: #666;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .nav-links a i {
            margin-right: 15px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: linear-gradient(135deg, var(--pink-dark), var(--pink-darker));
            color: white;
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Mobile Header & Toggle */
        .mobile-header {
            display: none;
            background: var(--white);
            padding: 15px 20px;
            box-shadow: 0 2px 10px var(--shadow);
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 900;
        }

        #sidebar-toggle {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--pink-dark);
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 950;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
                /* Hide sidebar off-screen */
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding-top: 80px;
                /* Space for mobile header */
            }

            .mobile-header {
                display: flex;
            }

            .overlay.active {
                display: block;
            }
        }

        /* Common Components - Keeping existing styles */
        .card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px var(--shadow);
            border: none;
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--pink-light);
        }

        @media (max-width: 576px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        .page-header h1,
        .page-header h2 {
            color: var(--pink-darker);
            font-size: 32px;
            margin: 0;
        }

        @media (max-width: 576px) {

            .page-header h1,
            .page-header h2 {
                font-size: 24px;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-dark), var(--pink-darker));
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff1493, #ff69b4);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--shadow);
        }

        .table-container {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px var(--shadow);
            margin-top: 20px;
            overflow-x: auto;
            /* Allow horizontal scroll on table */
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-top: 10px;
            white-space: nowrap;
            /* Prevent wrap on small screens */
        }

        .table thead th {
            background-color: var(--pink-light);
            color: var(--pink-darker);
            border: none;
            padding: 15px;
            font-weight: 600;
            text-align: left;
        }

        .table tbody td {
            padding: 15px;
            background: var(--white);
            border: none;
            vertical-align: middle;
        }

        .table tbody tr {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
        }

        .table tbody tr:hover {
            transform: scale(1.01);
        }

        .action-btns {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 14px;
        }

        .btn-edit {
            background: var(--pink-light);
            color: var(--pink-darker);
        }

        .btn-edit:hover {
            background: var(--pink-medium);
            color: var(--white);
        }

        .btn-danger {
            background: #ff5e5e;
            color: white;
        }

        .btn-danger:hover {
            background: #ff3b3b;
            box-shadow: 0 5px 15px rgba(255, 94, 94, 0.3);
        }

        /* Stats Cards Global with Responsive Grid */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            margin-bottom: 15px;
        }

        .card .number {
            font-size: 28px;
            font-weight: 700;
            color: var(--pink-darker);
            margin-bottom: 5px;
        }

        .card .trend {
            color: #666;
            font-size: 13px;
        }

        .card h3 {
            color: #333;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .bg-pink-gradient {
            background: linear-gradient(135deg, #ff6b8b, #ff9999) !important;
        }

        .bg-orange-gradient {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4) !important;
        }

        .bg-purple-gradient {
            background: linear-gradient(135deg, #a18cd1, #fbc2eb) !important;
        }

        .bg-peach-gradient {
            background: linear-gradient(135deg, #ffecd2, #fcb69f) !important;
        }

        /* Sidebar User Info */
        .user-info {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
            padding: 12px;
            background: linear-gradient(135deg, var(--pink-light), #fff);
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 5px 15px var(--shadow);
            border: 1px solid var(--pink-medium);
            transition: all 0.3s;
            text-decoration: none;
        }

        .user-info:hover {
            transform: translateY(-3px);
            background: var(--white);
            box-shadow: 0 8px 20px var(--shadow);
        }

        .user-info .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--pink-dark), var(--pink-darker));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .user-details-mini {
            flex-grow: 1;
            overflow: hidden;
            text-align: left;
        }

        .user-details-mini h4 {
            color: var(--pink-darker);
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .user-details-mini p {
            color: #888;
            font-size: 11px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-logout-mini {
            color: #ff5e5e;
            font-size: 14px;
            padding: 5px;
            transition: transform 0.2s;
        }

        .user-logout-mini:hover {
            transform: scale(1.2);
        }
    </style>
    @stack('styles')
</head>

<body>
    @if (request()->is('login'))
        @yield('content')
    @else
        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="d-flex align-items-center">
                <button id="sidebar-toggle" class="me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="m-0 fw-bold text-primary">SIDAPEG</h4>
            </div>
            <div class="user-profile">
                <!-- Simple Avatar for Mobile Header -->
                <i class="fas fa-user-circle fa-lg text-secondary"></i>
            </div>
        </div>

        <!-- Layout Structure -->
        @include('partials.sidebar')

        <div class="main-content">
            @yield('content')
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Sidebar Toggle Logic
            $('#sidebar-toggle').click(function() {
                $('.sidebar').toggleClass('active');
                $('.overlay').toggleClass('active');
            });

            // Close sidebar when clicking overlay
            $('#overlay').click(function() {
                $('.sidebar').removeClass('active');
                $(this).removeClass('active');
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#fff',
                    iconColor: 'var(--pink-dark)',
                    customClass: {
                        popup: 'rounded-20'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    background: '#fff',
                    iconColor: '#ff5e5e',
                    customClass: {
                        popup: 'rounded-20'
                    }
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
