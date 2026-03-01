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

        {{-- Dropdown Card --}}
        <div x-show="open" 
                x-cloak
                @click.away="open = false" 
                class="dropdown-card"
                x-transition>
    
            @if(Auth::user()->role === 'super_admin')
                <div class="dropdown-header">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manage Account</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-link"> My Profile</a>
                <a href="{{ route('settings.index') }}" class="dropdown-link border-b border-slate-100"> Register</a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-link-danger w-full text-left"> Log Out</button>
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

        <div class="card flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stat-label">15-Day Payroll Est.</p>
                    <h3 class="stat-number text-teal-700">₱{{ number_format($totalPayroll, 2) }}</h3>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                    Current Period Additions Included
                </p>
            </div>
        </div>

        @php
            $payDate = $isFirstHalf ? date('M 15, Y') : date('M t, Y');
            $periodStart = $isFirstHalf ? date('M 01') : date('M 16');
            $periodEnd = $isFirstHalf ? date('M 15') : date('M t');
        @endphp

        <div class="bg-[#003366] text-white p-6 rounded-xl shadow-lg border bg-gradient-to-br from-teal-800 to-teal-600 flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-teal-100 uppercase tracking-wider">Next Pay Date</p>
                <h3 class="text-3xl font-black mt-2">{{ $payDate }}</h3>
            </div>
            <p class="text-xs text-teal-200 mt-4 font-medium bg-teal-900/30 w-fit px-3 py-1.5 rounded-lg">
                Period: {{ $periodStart }} - {{ $periodEnd }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 table-container">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-700">Recent Payroll Batches</h3>
                <a href="{{ route('payroll.history') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition-colors">View All →</a>
            </div>
            <table class="w-full text-left">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">Batch ID</th>
                        <th class="px-6 py-4">Period</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $recentBatches = \App\Models\PayrollBatch::latest()->take(5)->get();
                    @endphp

                    @forelse($recentBatches as $batch)
                    <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location='{{ route('payroll.show', $batch->id) }}'">
                        <td class="table-cell font-mono font-bold text-[#003366]">{{ $batch->batch_id }}</td>
                        <td class="table-cell">{{ date('M d', strtotime($batch->period_start)) }} - {{ date('M d', strtotime($batch->period_end)) }}</td>
                        <td class="table-cell font-bold text-slate-600">₱{{ number_format($batch->total_net, 2) }}</td>
                        <td class="table-cell"><span class="stat-badge-green">Completed</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">No payroll batches processed yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Sidebar Quick Actions --}}
        <div class="card h-fit">
            <h3 class="font-bold text-slate-700 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                
                @if(Auth::user()->role === 'super_admin')
                    <a href="{{ route('employees.create') }}" class="btn-action w-full flex items-center justify-center gap-2">
                        Add Employee
                    </a>
                @endif
                
                <a href="{{ route('payroll.history') }}" class="btn-secondary w-full flex items-center justify-center gap-2 bg-white border border-slate-300 text-slate-700 font-bold py-2 rounded-xl hover:bg-slate-50 transition shadow-sm">
                    Generate Slip
                </a>
                
                {{-- Only Super Admin can see Settings --}}
                @if(Auth::user()->role === 'super_admin')
                    <a href="{{ route('settings.index') }}" class="btn-secondary w-full flex items-center justify-center gap-2 bg-white border border-slate-300 text-slate-700 font-bold py-2 rounded-xl hover:bg-slate-50 transition shadow-sm">
                        Settings
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection