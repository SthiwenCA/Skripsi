<!DOCTYPE html>
<html>
<head>
    <title>Map Kerusakan Jalan</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <style>
        body { margin: 0; font-family: Arial; }

        .container { display: flex; }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #e6e1e1;
            padding: 20px;
        }

        .btn-type {
            padding: 10px;
            border-radius: 20px;
            margin: 10px 0;
            cursor: pointer;
            background: white;
            border: 2px solid #ccc;
        }

        .btn-type.active.crack { background: blue; color: white; }
        .btn-type.active.pothole { background: red; color: white; }
        .btn-type.active.deformation { background: green; color: white; }

        #map {
            flex: 1;
            height: 100vh;
        }

        .navbar {
            position: absolute;
            top: 10px;
            right: 20px;
            z-index: 1000;
        }

        .navbar a, .navbar button {
            margin-left: 10px;
            padding: 6px 10px;
            border: none;
            background: white;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- 🔥 NAVBAR (SUDAH DIPERBAIKI) -->
<div class="navbar">
    @if(auth()->check())
        <span>{{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endif
</div>

<!-- CONTENT -->
@yield('content')

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- SCRIPT HALAMAN -->
@yield('scripts')

</body>
</html>