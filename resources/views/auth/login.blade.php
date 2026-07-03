<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Halo MANAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background */
        .bg-glow {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 20%, rgba(59,130,246,0.15) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(99,102,241,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 50%, rgba(14,165,233,0.05) 0%, transparent 70%);
            animation: glowShift 8s ease-in-out infinite alternate;
        }
        @keyframes glowShift {
            0%   { opacity: 0.7; transform: scale(1); }
            100% { opacity: 1;   transform: scale(1.05); }
        }

        /* Grid pattern */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(59,130,246,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,130,246,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Floating dots */
        .dot {
            position: absolute;
            border-radius: 50%;
            background: rgba(59,130,246,0.25);
            animation: floatDot linear infinite;
        }
        .dot:nth-child(1)  { width:8px;  height:8px;  top:15%; left:10%;  animation-duration:14s; animation-delay:0s;   }
        .dot:nth-child(2)  { width:5px;  height:5px;  top:70%; left:80%;  animation-duration:18s; animation-delay:2s;   }
        .dot:nth-child(3)  { width:10px; height:10px; top:40%; left:90%;  animation-duration:12s; animation-delay:4s;   background: rgba(99,102,241,0.2); }
        .dot:nth-child(4)  { width:6px;  height:6px;  top:85%; left:25%;  animation-duration:16s; animation-delay:1s;   }
        .dot:nth-child(5)  { width:4px;  height:4px;  top:10%; left:65%;  animation-duration:20s; animation-delay:3s;   }
        @keyframes floatDot {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
            50%       { transform: translateY(-30px) translateX(15px); opacity: 0.8; }
        }

        /* Login card */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow:
                0 25px 60px rgba(0,0,0,0.4),
                0 0 0 1px rgba(255,255,255,0.04) inset,
                0 1px 0 rgba(255,255,255,0.1) inset;
        }

        /* Logo */
        .logo-wrap {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-img {
            height: 52px;
            width: auto;
            margin: 0 auto 0.75rem;
            display: block;
            filter: drop-shadow(0 4px 12px rgba(59,130,246,0.4));
        }
        .brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .brand-name span {
            color: #60a5fa;
        }
        .brand-sub {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 2px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            margin-bottom: 2rem;
        }

        /* Title */
        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 0.25rem;
        }
        .card-subtitle {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 1.75rem;
        }

        /* Error message */
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            color: #fca5a5;
            font-size: 0.83rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        /* Form group */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        .form-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            color: #e2e8f0;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }
        .form-input::placeholder { color: #334155; }
        .form-input:focus {
            border-color: rgba(59,130,246,0.5);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .form-input:focus + .input-icon-right,
        .input-wrap:focus-within .input-icon { color: #60a5fa; }

        /* Password toggle */
        .toggle-pw {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            cursor: pointer;
            font-size: 0.85rem;
            transition: color 0.2s;
            background: none;
            border: none;
            padding: 0;
        }
        .toggle-pw:hover { color: #94a3b8; }

        /* Remember me */
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.75rem;
        }
        .form-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #3b82f6;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-check label {
            font-size: 0.83rem;
            color: #64748b;
            cursor: pointer;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(59,130,246,0.35);
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(59,130,246,0.45); }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        /* Footer */
        .card-footer {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .card-footer p {
            font-size: 0.78rem;
            color: #475569;
        }
        .card-footer span {
            color: #60a5fa;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem; border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="bg-grid"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-wrap">
                <img src="{{ asset('assets/images/halomanaplogo.png') }}" alt="Logo HaloMANAP" class="logo-img">
                <div class="brand-name">Halo<span>MANAP</span></div>
                <div class="brand-sub">Sistem Manajemen Pengaduan</div>
            </div>

            <div class="divider"></div>

            <p class="card-title">Selamat Datang</p>
            <p class="card-subtitle">Masuk untuk mengakses dashboard admin</p>

            {{-- Error --}}
            @if($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nip">NIP</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-id-badge input-icon"></i>
                        <input
                            type="text"
                            id="nip"
                            name="nip"
                            class="form-input"
                            placeholder="Masukkan NIP"
                            value="{{ old('nip') }}"
                            autocomplete="username"
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Masukkan Password"
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-pw" id="togglePw">
                            <i class="fa-regular fa-eye" id="pwEyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="card-footer">
                <p>Halo MANAP &mdash; <span>Manajemen Pengaduan Pasien</span></p>
            </div>
        </div>
    </div>

    <script>
        const togglePw = document.getElementById('togglePw');
        const pwInput  = document.getElementById('password');
        const eyeIcon  = document.getElementById('pwEyeIcon');
        togglePw.addEventListener('click', () => {
            const isHidden = pwInput.type === 'password';
            pwInput.type = isHidden ? 'text' : 'password';
            eyeIcon.className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    </script>
</body>
</html>
