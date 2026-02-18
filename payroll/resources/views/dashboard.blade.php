@extends('layouts.app')

@section('content')
<header class="page-header flex justify-between items-center">
    
    <h2 class="font-bold text-slate-700 text-2xl">Dashboard Overview</h2>

    <div class="relative" x-data="{ open: false }">
        
        <button @click="open = !open" class="profile-trigger flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</p>
                @if(Auth::user()->role === 'super_admin')
                    <p class="text-[10px] font-bold text-purple-600 uppercase tracking-widest">⭐ Super Admin</p>
                @else
                    <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest">Human Resources</p>
                @endif
            </div>
            <div class="h-10 w-10 rounded-full bg-teal-800 text-white flex items-center justify-center font-bold shadow-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </button>

        <div x-show="open" 
            x-cloak
             @click.away="open = false" 
             class="dropdown-card"
             x-transition>
            
            <div class="dropdown-header">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manage Account</p>
            </div>

            <a href="{{ route('profile.edit') }}" class="dropdown-link"> My Profile</a>
            
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('settings.index') }}" class="dropdown-link border-b border-slate-100"> Settings</a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-link-danger"> Log Out</button>
            </form>
        </div>

    </div>
</header>

<div class="p-8">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <a href="{{ route('employees.index') }}" class="card block hover:border-teal-500 transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stat-label group-hover:text-teal-700 transition-colors">Total Employees</p>
                    <h3 class="stat-number">{{ $totalEmployees }}</h3>
                </div>
                
                @if($newEmployees > 0)
                    <span class="stat-badge-green">+{{ $newEmployees }} New</span>
                @endif
            </div>
            
            <div class="flex items-center gap-2 mt-4">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider group-hover:text-teal-600">
                    View All Employees →
                </p>
                @if($newEmployees > 0)
                 <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">This Week</span>
                @endif
            </div>
        </a>

        <div class="card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stat-label">Monthly Payroll</p>
                    <h3 class="stat-number">₱{{ number_format($totalPayroll, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-[#003366] text-white p-6 rounded-xl shadow-lg border bg-gradient-to-br from-teal-800 to-teal-600">
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider">Next Pay Date</p>
            <h3 class="text-3xl font-black mt-2">Jan 31</h3>
            <p class="text-xs text-blue-300 mt-4">Period: Jan 16 - Jan 30</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 table-container">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-700">Recent Payroll Batches</h3>
            </div>
            <table class="w-full text-left">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">Batch ID</th>
                        <th class="px-6 py-4">Period</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="table-cell font-mono font-bold text-[#003366]">#PY-2026-02</td>
                        <td class="table-cell">Jan 01 - Jan 15</td>
                        <td class="table-cell"><span class="stat-badge-green">Completed</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="table-cell font-mono font-bold text-[#003366]">#PY-2026-01</td>
                        <td class="table-cell">Dec 16 - Dec 31</td>
                        <td class="table-cell"><span class="stat-badge-green">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card h-fit">
            <h3 class="font-bold text-slate-700 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('employees.create') }}" class="btn-action w-full flex items-center justify-center gap-2">
                    <span></span> Add Employee
                </a>
                
                <button class="btn-secondary w-full">
                    <span></span> Generate Slip
                </button>
                
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('settings.index') }}" class="btn-secondary w-full flex items-center justify-center gap-2">
                    <span></span> Settings
                </a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection