<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} | {{ config('app.name', 'ShalCell') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet">
    <style>
        :root {
            --ink: #344767;
            --muted: #67748e;
            --line: #e9ecef;
            --accent: #7928ca;
            --accent-dark: #ff0080;
            --soft: #f8f9fa;
        }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: var(--soft); color: var(--ink); font-family: 'DM Sans', sans-serif; }
        .login-shell { display: grid; grid-template-columns: minmax(320px, .9fr) minmax(460px, 1.1fr); min-height: 100vh; }
        .login-intro { position: relative; display: flex; align-items: flex-end; min-height: 100%; padding: 48px clamp(32px, 7vw, 104px); overflow: hidden; background: linear-gradient(145deg, rgba(52, 71, 103, .92), rgba(121, 40, 202, .78)), url('{{ asset('img/curved-images/curved14.jpg') }}') center / cover; }
        .login-intro::after { position: absolute; width: 360px; height: 360px; right: -150px; top: 16%; border: 1px solid rgba(255, 255, 255, .2); border-radius: 50%; content: ''; }
        .intro-content { position: relative; z-index: 1; max-width: 470px; color: #fff; }
        .brand { position: absolute; top: 42px; left: clamp(32px, 7vw, 104px); color: #fff; font-family: 'Manrope', sans-serif; font-size: 1.25rem; font-weight: 800; letter-spacing: .12em; text-decoration: none; }
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; color: rgba(255, 255, 255, .78); font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .eyebrow::before { width: 28px; height: 2px; background: #ff0080; content: ''; }
        .intro-content h1 { max-width: 420px; margin: 0 0 18px; color: #fff; font-family: 'Manrope', sans-serif; font-size: clamp(2rem, 4vw, 3.5rem); line-height: 1.08; }
        .intro-content p { max-width: 390px; margin: 0; color: rgba(255, 255, 255, .78); font-size: 1rem; line-height: 1.7; }
        .login-panel { display: flex; align-items: center; padding: 48px clamp(28px, 8vw, 120px); background: #fff; }
        .form-wrap { width: min(100%, 440px); margin: 0 auto; }
        .form-wrap h2 { margin: 0 0 8px; color: var(--ink); font-family: 'Manrope', sans-serif; font-size: 2rem; }
        .form-subtitle { margin: 0 0 32px; color: var(--muted); line-height: 1.6; }
        .status-message { margin-bottom: 22px; padding: 12px 14px; border: 1px solid #bbf7d0; border-radius: 10px; color: #166534; background: #f0fdf4; font-size: .86rem; }
        .field { margin-bottom: 20px; }
        .field label { display: block; margin-bottom: 8px; color: var(--ink); font-size: .88rem; font-weight: 700; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; top: 14px; left: 15px; color: #91a0b9; font-size: 1rem; }
        .input-wrap input { width: 100%; min-height: 49px; padding: 12px 15px 12px 43px; border: 1px solid var(--line); border-radius: 10px; outline: 0; color: var(--ink); background: #fbfcfe; font: inherit; transition: border-color .2s, box-shadow .2s, background .2s; }
        .input-wrap input:focus { border-color: #7928ca; background: #fff; box-shadow: 0 0 0 4px rgba(121, 40, 202, .12); }
        .input-wrap input.is-invalid { border-color: #ef4444; }
        .invalid-feedback { display: block; margin-top: 7px; color: #dc2626; font-size: .78rem; }
        .form-options { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin: 4px 0 26px; color: var(--muted); font-size: .83rem; }
        .remember { display: inline-flex; align-items: center; gap: 8px; }
        .remember input { width: 16px; height: 16px; accent-color: var(--accent); }
        .form-options a, .register-link a { color: var(--accent); font-weight: 700; text-decoration: none; }
        .submit-button { width: 100%; min-height: 50px; border: 0; border-radius: 10px; color: #fff; background: linear-gradient(310deg, var(--accent) 0%, var(--accent-dark) 100%); font: inherit; font-weight: 700; cursor: pointer; box-shadow: 0 10px 20px rgba(121, 40, 202, .2); transition: filter .2s, transform .2s; }
        .submit-button:hover { filter: brightness(.96); transform: translateY(-1px); }
        .register-link { margin: 22px 0 0; color: var(--muted); font-size: .88rem; text-align: center; }
        @media (max-width: 800px) {
            .login-shell { display: block; }
            .login-intro { min-height: 330px; padding: 38px 28px; }
            .brand { top: 28px; left: 28px; }
            .intro-content { margin-top: 70px; }
            .intro-content h1 { font-size: 2.2rem; }
            .login-panel { padding: 42px 24px 56px; }
        }
        @media (max-width: 420px) {
            .form-options { align-items: flex-start; flex-direction: column; gap: 12px; }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="login-intro">
            <a class="brand" href="{{ url('/') }}">ShalCell</a>
            <div class="intro-content">
                <span class="eyebrow">ShalCell management system</span>
                <h1>Welcome back.</h1>
                <p>Masuk ke workspace ShalCell untuk mengelola data customer dan operasional Anda.</p>
            </div>
        </section>
        <section class="login-panel">
            <div class="form-wrap">
                <h2>Selamat datang kembali</h2>
                <p class="form-subtitle">Masukkan detail akun Anda untuk melanjutkan.</p>

                @if (session('status'))
                    <div class="status-message" role="status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="field">
                        <label for="email">Alamat email</label>
                        <div class="input-wrap">
                            <i class="ni ni-email-83" aria-hidden="true"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" placeholder="nama@perusahaan.com" required autocomplete="email" autofocus>
                        </div>
                        @error('email') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="ni ni-lock-circle-open" aria-hidden="true"></i>
                            <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror" placeholder="Masukkan password Anda" required autocomplete="current-password">
                        </div>
                        @error('password') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-options">
                        <label class="remember">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Lupa password?</a>
                        @endif
                    </div>
                    <button type="submit" class="submit-button">Masuk ke dashboard</button>
                    <p class="register-link">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
