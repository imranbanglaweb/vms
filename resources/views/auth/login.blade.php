<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transport & Vehicle Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap + FontAwesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <style>

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: "Open Sans", sans-serif;
            overflow: hidden;
            background: #f4f6f9;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
        }

        /* LEFT PANEL */
        .login-left {
            width: 38%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-shadow: 5px 0 20px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-box img {
            width: 80px;
            display: block;
            margin: 0 auto 20px;
        }

        .login-box h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #2b3e51;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
            font-size: 0.95rem;
            padding-left: 40px;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #999;
        }

        .btn-login {
            background: #3498db;
            color: #fff;
            font-weight: 600;
            width: 100%;
            padding: 12px 0;
            border-radius: 5px;
            border: none;
            transition: .3s;
        }

        .btn-login:hover {
            background: #2980b9;
        }

        /* RIGHT PANEL */
        .login-right {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .bg-road {
            height: 100vh;
            width: 100%;
            background: url('public/admin_resource/assets/images/city-road-bg.jpg') no-repeat center center/cover;
            filter: brightness(0.55);
        }

        /* COMPANY TITLE + TAGLINE */
        .system-text {
            position: absolute;
            top: 15%;
            left: 50px;
            z-index: 20;
            color: #fff;
            text-shadow: 0 0 10px rgba(0,0,0,0.6);
        }

        .system-text h1 {
            font-size: 42px;
            font-weight: 700;
        }

        .system-text p {
            font-size: 18px;
            opacity: .9;
        }

        /* MOVING CAR */
        .car-animation {
            position: absolute;
            bottom: 60px;
            left: -300px;
            width: 280px;
            animation: carMove 8s linear infinite;
            z-index: 15;
        }
        

        @keyframes carMove {
            0% { left: -300px; }
            100% { left: 110%; }
        }

        .car-glow {
            position: absolute;
            bottom: 40px;
            width: 180px;
            height: 20px;
            left: -300px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            filter: blur(10px);
            animation: carMove 8s linear infinite;
        }

        /* --- 1. FOG ANIMATION --- */
        .fog {
            position: absolute;
            top: 0;
            left: -200px;
            width: 500px;
            height: 100vh;
            background: url('public/admin_resource/assets/images/fog.png') no-repeat center/cover;
            opacity: 0.22;
            animation: fogMove 20s linear infinite;
            z-index: 5;
        }

        @keyframes fogMove {
            0% { left: -300px; }
            100% { left: 110%; }
        }

        /* --- 2. MOVING LIGHT TRAILS --- */
        .light-trail {
            position: absolute;
            bottom: 120px;
            height: 4px;
            width: 200px;
            background: rgba(0, 180, 255, 0.8);
            box-shadow: 0 0 15px rgba(0,180,255,0.8);
            left: -250px;
            border-radius: 5px;
            animation: trailMove 4s linear infinite;
            z-index: 12;
        }

        .light-trail:nth-child(2) {
            bottom: 150px;
            animation-delay: 1s;
        }

        .light-trail:nth-child(3) {
            bottom: 180px;
            animation-delay: 2s;
        }

        @keyframes trailMove {
            0% { left: -300px; opacity: 0; }
            10% { opacity: 1; }
            100% { left: 120%; opacity: 0; }
        }

        @media(max-width: 850px) {
            .login-left {
                width: 100%;
                box-shadow: none;
            }
            .login-right {
                display: none;
            }
        }

    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT SIDE LOGIN -->
    <div class="login-left">
        <div class="login-box">

            <img src="{{ asset('public/admin_resource/assets/images/vault-logo.webp') }}" alt="Logo">
            <h3>Login</h3>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group position-relative">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Enter Employee ID"
                           required autofocus>
                    @error('email') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>

                <div class="form-group position-relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter Password"
                           required>
                    @error('password') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT SIDE WITH ANIMATIONS -->
    <div class="login-right">
        <div class="bg-road"></div>

        <!-- TITLE + TAGLINE -->
        <div class="system-text">
            <h1>TRANSPORT & <SPAN Style="color:yellowgreen">VEHICLE</SPAN> AUTOMATION</h1>
            <p>Smart Logistics. Better Operations.</p>
        </div>

        <!-- Fog Layer -->
        <div class="fog"></div>

        <!-- Light Trails -->
        <div class="light-trail"></div>
        <div class="light-trail"></div>
        <div class="light-trail"></div>

        <!-- Car Animation -->
        <img src="public/admin_resource/assets/images/car.png" class="car-animation">
        <div class="car-glow"></div>
    </div>

</div>

</body>
</html>
