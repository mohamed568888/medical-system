<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        // التأكد من الثيم المحفوظ قبل رندر الصفحة
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
    </script>
    @yield('styles')
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            height: 60px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1100 !important;
            background-color: white !important;
        }

        .clinic-selector {
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .clinic-selector:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        .main-content,
        .container {
            margin-top: 70px !important;
        }

        #app {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        footer {
            background-color: #198754;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 14px;
            margin-top: auto;
        }

        @media (max-width: 576px) {

            .main-content,
            .container {
                margin-top: 80px !important;
            }
        }
    </style>
</head>

<body>

    <div id="app">
        @php
            $noLayoutRoutes = ['login', 'register'];
            $allClinics = \App\Models\Clinic::all(); // جلب كل العيادات للمركز
        @endphp

        @if (!in_array(Route::currentRouteName(), $noLayoutRoutes))
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
                <div class="container-fluid px-4">
                    {{-- شعار اللوحة --}}
                    <a class="navbar-brand fw-bold text-success d-flex align-items-center"
                        href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-stethoscope me-2 fs-4"></i>
                        <span class="d-none d-sm-inline">Medical Panel</span>
                    </a>

                    <div class="ms-auto d-flex align-items-center">

                        @auth
                            {{-- اختيار العيادة لجميع الدكاترة --}}
                            @if ($allClinics->count() > 0)
                                <div class="d-flex align-items-center me-3">
                                    <label class="me-2 text-muted d-none d-md-block small fw-bold text-nowrap">Active
                                        Clinic:</label>
                                    <select class="form-select form-select-sm clinic-selector text-success fw-bold"
                                        onchange="window.location.href='{{ url('/select-clinic') }}/' + this.value"
                                        style="min-width: 160px; border-radius: 20px; border-color: #198754;">

                                        {{-- في حال لم يتم اختيار عيادة بعد --}}
                                        @if (!session()->has('active_clinic_id'))
                                            <option selected disabled>اختر العيادة...</option>
                                        @endif

                                        @foreach ($allClinics as $clinic)
                                            <option value="{{ $clinic->id }}"
                                                {{ session('active_clinic_id') == $clinic->id ? 'selected' : '' }}>
                                                🏥 {{ $clinic->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            
                          

                            {{-- الجرس --}}
                            <div class="dropdown me-3">
                                <a class="nav-link dropdown-toggle position-relative p-0" href="#" id="notif"
                                    role="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-bell text-success fs-5"></i>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="font-size: 0.6rem;">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 300px;">
                                    <li class="p-2 border-bottom d-flex justify-content-between align-items-center">
                                        <span class="fw-bold small">New Bookings</span>
                                        <a href="{{ route('notifications.markAllRead') }}"
                                            class="btn btn-sm text-primary p-0" style="font-size: 11px;">Mark all read</a>
                                    </li>
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <li>
                                            <a class="dropdown-item py-2 border-bottom"
                                                href="{{ route('booking.booked') }}">
                                                <small
                                                    class="d-block fw-bold text-dark">{{ $notification->data['patient_name'] ?? 'New Patient' }}</small>
                                                <small class="text-muted">Clinic:
                                                    {{ $notification->data['clinic_name'] ?? 'N/A' }}</small>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="p-3 text-center small text-muted">No new notifications</li>
                                    @endforelse
                                </ul>
                            </div>

                            {{-- تسجيل الخروج --}}
                            <form method="POST" action="{{ route('logout') }}" class="ms-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </nav>
        @endif

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @if (!in_array(Route::currentRouteName(), $noLayoutRoutes))
        <footer>
            <div class="container">
                <p>&copy; All rights reserved | Medical Panel {{ date('Y') }} </p>
                <small>Developed by Mohamed Sakr 💚</small>
            </div>
        </footer>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
