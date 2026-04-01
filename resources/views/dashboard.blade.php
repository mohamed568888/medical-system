@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ... نفس الستايل الخاص بك بدون تغيير ... */
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            overflow-x: hidden;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1050;
            height: 60px;
        }

        .sidebar {
            width: 260px;
            background-color: #198754;
            color: white;
            height: calc(100vh - 60px);
            position: fixed;
            left: 0;
            top: 60px;
            transition: all 0.4s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.closed {
            left: -260px;
        }

        .doctor-profile {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            padding-top: 20px;
        }

        .avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            object-fit: cover;
        }

        .doctor-name {
            display: block;
            margin-top: 10px;
            font-weight: 700;
            font-size: 15px;
        }

        .sidebar h2 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.6);
            margin: 20px 10px 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li a,
        .sidebar form button {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.3s;
            font-size: 14px;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
        }

        .sidebar ul li a i {
            width: 25px;
            font-size: 18px;
        }

        .sidebar ul li a:hover,
        .sidebar form button:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar ul li a.active {
            background: white;
            color: #198754;
            font-weight: 700;
        }

        .main-content {
            margin-left: 260px;
            margin-top: 60px;
            padding: 30px;
            transition: all 0.4s ease;
        }

        .main-content.full-width {
            margin-left: 0;
        }

        #toggle-btn {
            position: fixed;
            left: 270px;
            top: 75px;
            z-index: 1001;
            background: #198754;
            color: white;
            border: none;
            border-radius: 8px;
            width: 35px;
            height: 35px;
            cursor: pointer;
            transition: 0.4s;
        }

        #toggle-btn.moved {
            left: 15px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            border: none;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card i {
            font-size: 40px;
            color: #198754;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .card h3 {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .card h3 a {
            text-decoration: none;
            color: inherit;
        }

        .card .count {
            font-size: 32px;
            font-weight: 800;
            color: #212529;
        }
    </style>
@endsection

@section('content')
    <button id="toggle-btn"><i class="fa-solid fa-bars"></i></button>

    <div class="sidebar" id="sidebar">
        <div class="doctor-profile">
            <img src="{{ asset('image/mm.webp') }}" alt="Doctor" class="avatar">
            <span class="doctor-name">
                {{ Auth::user()->role == 'doctor' ? 'Dr.' : 'Nurse.' }} {{ Auth::user()->name }}
            </span>
        </div>

        <h2>الرئيسية</h2>
        <ul>
            <li><a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}"><i
                        class="fa-solid fa-house"></i> Dashboard</a></li>
        </ul>

        {{-- يظهر للدكتور فقط --}}
        @if (Auth::user()->role == 'doctor')
            <h2>إدارة النظام</h2>
            <ul>
                <li><a href="{{ route('user.create') }}"><i class="fa-solid fa-user-plus"></i> Add User</a></li>
                <li><a href="{{ route('clinic.index') }}"><i class="fa-solid fa-hospital-user"></i> Clinics List</a></li>
                <li><a href="{{ route('clinic.create') }}"><i class="fa-solid fa-plus-circle"></i> Add Clinic</a></li>
            </ul>
        @endif

        <h2>إدارة المرضى</h2>
        <ul>
            <li><a href="{{ route('patient.patients') }}"><i class="fa-solid fa-user-group"></i> All Patients</a></li>
            <li><a href="{{ route('patient.create') }}"><i class="fa-solid fa-user-plus"></i> Add Patient</a></li>
        </ul>

        {{-- الإدارة المالية للدكتور فقط --}}
        @if (Auth::user()->role == 'doctor')
            <h2>الإدارة المالية</h2>
            <ul>
                <li>
                    <a href="{{ route('finance.index') }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i> الحسابات والتقارير
                    </a>
                </li>
            </ul>
        @endif

        <h2>النظام</h2>
        <ul>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="fa-solid fa-power-off"></i> Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <div class="main-content" id="main-content">
        <header class="mb-5">
            <h1 class="fw-bold">Welcome Back, {{ Auth::user()->name }}!</h1>
            <p class="text-muted">Here's what's happening in your clinics today.</p>
        </header>

        <section class="cards">
            {{-- كارت المستخدمين يظهر للدكتور فقط --}}
            @if (Auth::user()->role == 'doctor')
                <div class="card">
                    <i class="fa-solid fa-user-shield"></i>
                    <h3><a href="{{ route('user.users') }}">Total Users</a></h3>
                    <div class="count">{{ \App\Models\User::count() }}</div>
                </div>
            @endif

            <div class="card">
                <i class="fa-solid fa-hospital-user"></i>
                <h3><a href="{{ route('patient.patients') }}">Total Patients</a></h3>
                <div class="count">{{ \App\Models\Patient::count() }}</div>
            </div>

            <div class="card">
                <i class="fa-solid fa-clinic-medical"></i>
                <h3><a href="{{ route('clinic.index') }}">Total Clinics</a></h3>
                <div class="count">{{ \App\Models\Clinic::count() }}</div>
            </div>

            <div class="card">
                <i class="fa-solid fa-calendar-check"></i>
                <h3><a href="{{ route('booking.booked') }}">New Bookings</a></h3>
                <div class="count" style="font-size: 18px; color: #f39c12;">Check List 🔔</div>
            </div>

            {{-- كارت الإيراد يظهر للدكتور فقط --}}
            @if (Auth::user()->role == 'doctor')
                <div class="card" style="border-right: 5px solid #198754;">
                    <i class="fa-solid fa-sack-dollar" style="color: #198754;"></i>
                    <h3><a href="{{ route('finance.index') }}">إيراد اليوم</a></h3>
                    <div class="count" style="color: #198754;">
                        {{ number_format(\App\Models\Invoice::whereDate('created_at', today())->sum('amount'), 0) }}
                        <span style="font-size: 14px;">ج.م</span>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <script>
        const btn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('main-content');

        btn.onclick = function() {
            sidebar.classList.toggle('closed');
            content.classList.toggle('full-width');
            btn.classList.toggle('moved');
        };
    </script>
@endsection
