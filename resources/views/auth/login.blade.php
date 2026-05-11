<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — TriFaCore</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">🐔 TriFaCore</h3>
                    <p class="text-muted mb-0">Poultry Management System</p>
                </div>

                <form>
                    <x-form-input name="email" label="Email" type="email" required placeholder="admin@trifacore.com" />
                    <x-form-input name="password" label="Password" type="password" required placeholder="••••••••" />

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 py-2 fw-semibold">
                        🔐 Masuk
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
