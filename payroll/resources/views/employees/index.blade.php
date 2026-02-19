@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">Employee Management</h2>
        </div>
        <a href="{{ route('employees.create') }}" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            ➕ Add Employee
        </a>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl transition-all">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-4">Employee Name</th>
                    <th class="px-6 py-4">Job Title</th>
                    <th class="px-6 py-4">Department</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Compensation</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($employees as $employee)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-700">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        <p class="text-[10px] font-mono text-teal-600">
                            @if($employee->employee_id) {{ $employee->employee_id }} @else EMP-00{{ $employee->id }} @endif
                        </p>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $employee->job_title }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 py-1 px-3 rounded-full text-[10px] font-bold uppercase">
                            {{ $employee->department }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($employee->status === 'Active')
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase">Active</span>
                        @elseif($employee->status === 'On Leave')
                            <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase">On Leave</span>
                        @else
                            <span class="bg-slate-100 text-slate-500 py-1 px-3 rounded-full text-[10px] font-bold uppercase">{{ $employee->status }}</span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 font-mono">
                        <div class="text-slate-700 font-bold text-sm">₱{{ number_format($employee->salary, 2) }}</div>
                        <div class="text-[9px] text-teal-600 font-bold uppercase tracking-wider mt-0.5 bg-teal-50 border border-teal-100 w-fit px-2 py-0.5 rounded">
                            {{ $employee->salary_type ?? 'Monthly' }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('employees.edit', $employee->id) }}" 
                           class="text-xs font-bold uppercase transition-colors px-4 py-2 rounded-lg border shadow-sm text-slate-500 hover:bg-slate-100 bg-white border-slate-300">
                            Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                        No employees found. Click "Add Employee" to start.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection