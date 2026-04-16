<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - LaundryLaundryan">
    <title>Login - LaundryLaundryan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f8fafc;
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
            color: var(--primary);
        }
        .left-panel h2 {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary-dark);
            line-height: 1.2;
            text-align: center;
            margin-bottom: 16px;
        }
        .left-panel p {
            font-size: 15px;
            color: var(--text-muted);
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
            background: var(--primary);
            border: 1px solid var(--primary-dark);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Orbs removed */

        /* ── Right panel / card ── */
        .right-panel {
            width: 460px;
            min-height: 100vh;
            background: #ffffff;
            border-left: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            z-index: 10;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
        }
        .login-inner { width: 100%; }

        .login-logo {
            display: flex; align-items: center; gap: 14px; margin-bottom: 32px;
        }
        .login-logo .icon {
            width: 54px; height: 54px;
            background: var(--secondary);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            color: var(--primary);
            box-shadow: 0 8px 16px rgba(22,163,74,.15);
        }
        .login-logo h1 { font-size: 22px; font-weight: 800; color: var(--primary-dark); }
        .login-logo p  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

        .separator { height: 1px; background: #e5e7eb; margin-bottom: 28px; }

        h2 { font-size: 20px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
        .subtitle { font-size: 13px; color: var(--text-muted); margin-bottom: 24px; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: var(--primary); font-size: 15px;
        }
        .form-control {
            width: 100%; padding: 12px 14px 12px 42px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px; color: var(--text);
            font-size: 14px; font-family: inherit;
            transition: all .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22,163,74,.2);
        }
        .form-control::placeholder { color: #94a3b8; }

        .show-pass {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--primary); cursor: pointer; border: none;
            background: none; font-size: 14px;
            transition: color .2s;
        }
        .show-pass:hover { color: var(--primary-dark); }

        .error-msg { color: #fb923c; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }

        .remember-row {
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }
        .remember-row input[type="checkbox"] { accent-color: var(--primary); width: 15px; height: 15px; cursor: pointer; }
        .remember-row label { font-size: 13px; color: var(--text-muted); cursor: pointer; }

        .btn-login {
            width: 100%; padding: 13px;
            background: var(--primary);
            color: #000; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s;
            box-shadow: 0 6px 16px rgba(22,163,74,0.3);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(22,163,74,0.4); }
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
    {{-- Orbs removed --}}

    <!-- Left branding panel -->
    <div class="left-panel">
        <div class="tagline-icon"><i class="bi bi-droplet-half"></i></div>
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
                <div class="icon"><i class="bi bi-droplet-half"></i></div>
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