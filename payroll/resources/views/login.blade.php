<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SDSC Payroll</title>
    @vite('resources/css/app.css')
</head>
<body class="login-body">

    <div class="login-card">
        <div class="flex justify-center mb-6">
            <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-3xl border border-slate-100 shadow-inner">
                🎓
            </div>
        </div>

        <h2 class="login-title">SDSC Payroll</h2>
        <p class="login-subtitle">St. Dominic Savio College</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="input-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="@sdsc.edu.ph" required>
                @error('email')
                    <span class="error-msg">⚠ {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">
                Sign In Securely
            </button>
        </form>
        

    </div>

</body>
</html>