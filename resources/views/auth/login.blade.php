<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Laundry-Wit Management System">
    <title>Login - Laundry-Wit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0a1f1a;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /* ── Left panel ── */
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            position: relative;
            z-index: 1;
        }
        .left-panel .tagline-icon {
            font-size: 72px;
            margin-bottom: 24px;
            filter: drop-shadow(0 0 24px rgba(245,158,11,.5));
        }
        .left-panel h2 {
            font-size: 36px;
            font-weight: 800;
            color: #fef3c7;
            line-height: 1.2;
            text-align: center;
            margin-bottom: 16px;
        }
        .left-panel p {
            font-size: 15px;
            color: #6ee7b7;
            text-align: center;
            max-width: 340px;
            line-height: 1.7;
        }
        .badge-row {
            display: flex;
            gap: 12px;
            margin-top: 36px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .badge {
            background: rgba(245,158,11,.15);
            border: 1px solid rgba(245,158,11,.3);
            color: #fbbf24;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Orbs ── */
        .bg-orbs { position: fixed; inset: 0; pointer-events: none; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: .2;
            animation: float 8s ease-in-out infinite;
        }
        .orb1 { width: 500px; height: 500px; background: #059669; top: -150px; left: -150px; animation-delay: 0s; }
        .orb2 { width: 350px; height: 350px; background: #d97706; bottom: -100px; left: 30%; animation-delay: 3s; }
        .orb3 { width: 250px; height: 250px; background: #0d9488; top: 40%; left: 10%; animation-delay: 5s; }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(20px, -30px) scale(1.05); }
        }

        /* ── Right panel / card ── */
        .right-panel {
            width: 460px;
            min-height: 100vh;
            background: rgba(15, 40, 30, 0.9);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(110,231,183,.12);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            z-index: 10;
            box-shadow: -24px 0 64px rgba(0,0,0,.5);
        }
        .login-inner { width: 100%; }

        .login-logo {
            display: flex; align-items: center; gap: 14px; margin-bottom: 32px;
        }
        .login-logo .icon {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, #d97706, #f59e0b);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            box-shadow: 0 8px 24px rgba(217,119,6,.45);
        }
        .login-logo h1 { font-size: 22px; font-weight: 800; color: #fef3c7; }
        .login-logo p  { font-size: 12px; color: #6ee7b7; margin-top: 2px; }

        .separator { height: 1px; background: rgba(110,231,183,.1); margin-bottom: 28px; }

        h2 { font-size: 20px; font-weight: 700; color: #fef3c7; margin-bottom: 6px; }
        .subtitle { font-size: 13px; color: #6ee7b7; margin-bottom: 24px; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #a7f3d0; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #6ee7b7; font-size: 15px;
        }
        .form-control {
            width: 100%; padding: 12px 14px 12px 42px;
            background: rgba(110,231,183,.07);
            border: 1px solid rgba(110,231,183,.18);
            border-radius: 12px; color: #ecfdf5;
            font-size: 14px; font-family: inherit;
            transition: all .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245,158,11,.25);
            background: rgba(245,158,11,.06);
        }
        .form-control::placeholder { color: #4b7c6f; }

        .show-pass {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #6ee7b7; cursor: pointer; border: none;
            background: none; font-size: 14px;
            transition: color .2s;
        }
        .show-pass:hover { color: #a7f3d0; }

        .error-msg { color: #fb923c; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }

        .remember-row {
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }
        .remember-row input[type="checkbox"] { accent-color: #f59e0b; width: 15px; height: 15px; cursor: pointer; }
        .remember-row label { font-size: 13px; color: #6ee7b7; cursor: pointer; }

        .btn-login {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #d97706, #f59e0b);
            color: #1c0a00; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s;
            box-shadow: 0 6px 20px rgba(245,158,11,.4);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(245,158,11,.5); }
        .btn-login:active { transform: translateY(0); }

        .hint {
            margin-top: 20px; text-align: center;
            font-size: 12px; color: #4b7c6f;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-panel { display: none; }
            .right-panel {
                width: 100%; min-height: 100vh;
                border-left: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
        <div class="orb orb3"></div>
    </div>

    <!-- Left branding panel -->
    <div class="left-panel">
        <div class="tagline-icon">🤣</div>
        <h2>Kelola Laundry<br>Lebih Cerdas</h2>
        <p>yapppingyappping yapppingyappping yapppingyapppingyapppingyappping yappping yapppingyappping yapppingyapppingyappping yappping</p>
        <div class="badge-row">
            <div class="badge"><i class="fas fa-bolt"></i>yappping</div>
            <div class="badge"><i class="fas fa-shield-alt"></i> yappping</div>
            <div class="badge"><i class="fas fa-chart-line"></i> yapppingyappping-yappping</div>
        </div>
    </div>

    <!-- Right login card -->
    <div class="right-panel">
        <div class="login-inner">
            <div class="login-logo">
                <div class="icon">🤣</div>
                <div>
                    <h1>LaundryLaundryan</h1>
                    <p>Web Laundry</p>
                </div>
            </div>
            <div class="separator"></div>

            <h2>Selamat Datang</h2>
            <p class="subtitle">Silakan masuk untuk mengakses sistem</p>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" class="form-control"
                            placeholder="admin@laundry.com"
                            value="{{ old('email') }}" autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password" class="form-control"
                            placeholder="Masukkan password" autocomplete="current-password">
                        <button type="button" class="show-pass" onclick="togglePassword()" id="eyeBtn">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="hint">
                akuntes:    admin@gmail.com / admin123
                            operator@gmail.com / operator123
                            pimpinan@gmail.com / pimpinan123
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>