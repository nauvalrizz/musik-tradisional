<!DOCTYPE html>
<html>

<head>
    <title>Admin</title>
    <style>
        body {
            font-family: Arial;
            background: #0f172a;
            color: white;
        }

        .container {
            width: 1000px;
            margin: 30px auto;
        }

        .card-form {
            background: rgba(30, 41, 59, 0.7);
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            margin-bottom: 5px;
            color: #94a3b8;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #0f172a;
            color: white;
        }

        .btn-save {
            margin-top: 10px;
            padding: 10px 18px;
            border-radius: 10px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: white;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
            background: rgba(30, 41, 59, 0.7);
            border-radius: 12px;
            overflow: hidden;
        }

        .table-modern th {
            background: rgba(51, 65, 85, 0.8);
            padding: 12px;
            text-align: left;
        }

        .table-modern td {
            padding: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table-modern tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* ACTION BUTTON */
        .action-cell {
            display: flex;
            gap: 8px;
        }

        .btn-cari {
            padding: 6px 12px;
            background: #3b82f6;
            border-radius: 6px;
            color: white;
            border: none;
        }

        .btn-edit {
            padding: 6px 12px;
            background: #3b82f6;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-delete {
            padding: 6px 12px;
            background: #ef4444;
            border-radius: 6px;
            color: white;
            border: none;
            font-size: 13px;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.85;
        }

        .custom-file {
            display: inline-block;
            padding: 10px 15px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 10px;
            cursor: pointer;
            color: #cbd5f5;
            font-size: 14px;
        }

        .custom-file input {
            display: none;
        }

        .img-preview {
            margin-top: 12px;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #334155;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .admin-container {
            padding: 30px;
        }

        .admin-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .admin-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #1e293b;
            padding: 20px;
            border-radius: 12px;
            color: white;
            transition: 0.3s;
        }

        .admin-card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 26px;
            padding: 12px;
            border-radius: 10px;
        }

        .card-icon.blue {
            background: #2563eb;
        }

        .card-icon.green {
            background: #16a34a;
        }

        .card-icon.yellow {
            background: #eab308;
        }

        .card-label {
            font-size: 14px;
            opacity: 0.7;
        }

        .admin-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .menu-card {
            display: block;
            padding: 20px;
            border-radius: 12px;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu-card.blue {
            background: #2563eb;
        }

        .menu-card.green {
            background: #16a34a;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            opacity: 0.9;
        }

        /* PAGINATION FIX */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            list-style: none;
            padding: 0;
            margin-top: 25px;
        }

        .pagination li {
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 8px 14px;
            border-radius: 8px;
            background: #1e293b;
            color: #cbd5f5;
            text-decoration: none;
            border: 1px solid #334155;
            font-size: 14px;
            transition: 0.2s;
        }

        /* hover */
        .pagination li a:hover {
            background: #2563eb;
            color: white;
        }

        /* active */
        .pagination .active span {
            background: #16a34a;
            color: white;
            border: none;
        }

        /* disabled */
        .pagination .disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        ul.pagination {
            list-style: none !important;
        }
    </style>
    <script>
        const savedScroll = sessionStorage.getItem("scrollY");
        if (savedScroll !== null) {
            window.scrollTo(0, parseInt(savedScroll));
        }
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')

    <script>
        // simpan posisi scroll sebelum pindah halaman
        document.addEventListener("click", function(e) {
            if (e.target.closest(".pagination a") || e.target.closest("form")) {
                sessionStorage.setItem("scrollY", window.scrollY);
            }
        });

        // balikin scroll setelah halaman load

        function saveScroll() {
            sessionStorage.setItem("scrollY", window.scrollY);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const scrollY = sessionStorage.getItem("scrollY");

            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
                sessionStorage.removeItem("scrollY");
            }
        });
    </script>

</body>

</html>
