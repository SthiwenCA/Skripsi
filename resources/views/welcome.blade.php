<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Monitoring Kerusakan Jalan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1e3a8a, #3b82f6);
            color: white;
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: space-between;
            padding: 50px;
        }

        /* 🔥 INI BAGIAN .left */
        .left {
            max-width: 50%;
            padding: 20px;
        }

        .left h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .left p {
            font-size: 18px;
            line-height: 1.5;
        }

        /* 🔥 OPTIONAL: BACKGROUND GAMBAR */
        .left {
            /* background: url('https://images.unsplash.com/photo-1502920917128-1aa500764ce7') no-repeat center; */
            background-size: cover;
            border-radius: 10px;
        }

        .right {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }

        .btn {
            display: block;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .login {
            background: #2563eb;
        }

        .register {
            background: #16a34a;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            left: 20px;
            font-size: 12px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- 🔥 INI YANG NAMANYA .left -->
    <div class="left">
        <h1>Sistem Monitoring Kerusakan Jalan</h1>
        <p>
            Aplikasi ini digunakan untuk mendeteksi dan memetakan kerusakan jalan seperti retak, lubang, 
            dan deformasi secara interaktif menggunakan peta digital.
        </p>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <h2>Selamat Datang</h2>

        <a href="{{ route('login') }}" class="btn login">Login</a>
        <a href="{{ route('register') }}" class="btn register">Register</a>
    </div>

</div>

<div class="footer">
    © {{ date('Y') }} Sistem Monitoring Jalan
</div>

</body>
</html>