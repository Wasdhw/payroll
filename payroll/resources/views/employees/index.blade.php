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
        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl">
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
                    <th class="px-6 py-4">Salary</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($employees as $employee)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-700">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        <p class="text-xs text-slate-400">{{ $employee->email }}</p>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $employee->job_title }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 py-1 px-3 rounded-full text-xs font-bold">
                            {{ $employee->department }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-xs font-bold">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 font-mono text-slate-700">₱{{ number_format($employee->salary, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-slate-400 hover:text-teal-600 font-bold transition">Edit</button>
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