@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2 class="font-bold text-slate-700 text-lg">Dashboard Overview</h2>
        
        <div class="flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-slate-700">Admin User</p>
                <p class="text-xs text-slate-500">Human Resources</p>
            </div>
            <div class="h-10 w-10 rounded-full bg-[#003366] text-white flex items-center justify-center font-bold">A</div>
        </div>
    </header>

    <div class="p-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="stat-label">Total Employees</p>
                        <h3 class="stat-number">385</h3>
                    </div>
                    <span class="stat-badge-green">+12 New</span>
                </div>
            </div>

            <div class="card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="stat-label">Monthly Payroll</p>
                        <h3 class="stat-number">₱1,240,500</h3>
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
                    <button class="btn-action">
                        <span>➕</span> Add Employee
                    </button>
                    <button class="btn-secondary">
                        <span>📄</span> Generate Slip
                    </button>
                    <button class="btn-secondary">
                        <span>⚙️</span> Settings
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection