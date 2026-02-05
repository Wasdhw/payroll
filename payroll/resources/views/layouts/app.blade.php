<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDSC Payroll</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        <aside class="sidebar">
            <div class="logo-container">
                <h1 class="logo-text">SDSC</h1>
                <p class="logo-subtext">Payroll System</p>
            </div>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }}">Dashboard</a>
                <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.index') ? 'nav-link-active' : 'nav-link' }}">Employees</a>
                <a href="{{ route('attendance') }}" class="{{ request()->routeIs('attendance') ? 'nav-link-active' : 'nav-link' }}">Attendance</a>
            </nav>

        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50">
            @yield('content')
        </main>
    </div>

</body>
</html>