<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semen Indonesia - Enterprise Learning Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #047857; /* Warna Hijau BUMN */
        }
        .nav-links a {
            text-decoration: none;
            color: #374151;
            margin-left: 20px;
            font-size: 16px;
        }
        .btn-login {
            background-color: #047857;
            color: white !important;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #065f46;
        }
        .hero {
            text-align: center;
            padding: 100px 20px;
        }
        .hero h1 {
            font-size: 40px;
            color: #1f2937;
        }
        .hero p {
            font-size: 18px;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto 30px auto;
        }
    </style>
</head>
<body>

    <!-- Navigasi / Top Bar -->
    <nav class="navbar">
        <div class="logo">SIG LMS</div>
        <div class="nav-links">
            <a href="#">Katalog Pelatihan</a>
            <a href="#">Bantuan Helpdesk</a>
            
            <!-- Logika Auth/Guest Session -->
            @auth
                <a href="{{ route('dashboard') }}" class="btn-login">Dasbor {{ Auth::user()->name }}</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Learning Center</a>
            @endauth
        </div>
    </nav>

    <!-- Konten Promosi -->
    <div class="hero">
        <h1>Tingkatkan Kompetensi Anda Bersama SIG</h1>
        <p>Akses ratusan modul pelatihan sertifikasi industri, keselamatan kerja (K3), dan manajemen bagi karyawan maupun mitra bisnis umum kami.</p>
    </div>

</body>
</html>
