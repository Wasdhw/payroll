@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 py-2 px-2">Employee Management</h2>
        </div>
        <a href="{{ route('employees.create') }}" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            <span></span> Add Employee
        </a>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl transition-all shadow-sm flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('employees.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            
            {{-- Search Bar --}}
            <div class="relative flex-grow w-full md:w-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="pl-10 w-full border-slate-200 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500 py-2.5 shadow-sm text-slate-700 font-medium placeholder:font-normal" 
                       placeholder="Search by name or ID...">
            </div>

            {{-- Status Filter --}}
            <div class="w-full md:w-48">
                <select name="status" onchange="this.form.submit()" class="w-full border-slate-200 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500 py-2.5 shadow-sm text-slate-600 font-bold cursor-pointer">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>🟢 Active</option>
                    <option value="On Leave" {{ request('status') === 'On Leave' ? 'selected' : '' }}>🟠 On Leave</option>
                    <option value="Resigned" {{ request('status') === 'Resigned' ? 'selected' : '' }}>🔴 Resigned</option>
                </select>
            </div>

            {{-- Reset Button --}}
            @if(request()->filled('search') || (request('status') && request('status') !== 'all'))
                <a href="{{ route('employees.index') }}" class="w-full md:w-auto px-6 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition-colors font-bold text-sm flex items-center justify-center gap-2 whitespace-nowrap" title="Clear All Filters">
                    <span>✕</span> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 border-b border-slate-200">
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
                {{-- 
                    SPECIFIC SORT ORDER:
                    1. Active
                    2. On Leave
                    3. Resigned
                    4. Others (default)
                --}}
                @forelse($employees->sortBy(function($e) {
                    return match($e->status) {
                        'Active' => 1,
                        'On Leave' => 2,
                        'Resigned' => 3,
                        default => 4,
                    };
                }) as $employee)
                <tr class="hover:bg-slate-50 transition {{ $employee->status === 'Resigned' ? 'bg-slate-50/50' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full {{ $employee->status === 'Resigned' ? 'bg-slate-200 text-slate-500' : 'bg-teal-50 text-teal-600' }} flex items-center justify-center font-bold text-xs border {{ $employee->status === 'Resigned' ? 'border-slate-300' : 'border-teal-100' }}">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold {{ $employee->status === 'Resigned' ? 'text-slate-500' : 'text-slate-700' }}">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="text-[10px] font-mono {{ $employee->status === 'Resigned' ? 'text-slate-400 bg-slate-100' : 'text-teal-600 bg-teal-50' }} px-1.5 py-0.5 rounded w-fit mt-0.5 border border-transparent">
                                    @if($employee->employee_id) {{ $employee->employee_id }} @else EMP-00{{ $employee->id }} @endif
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium {{ $employee->status === 'Resigned' ? 'text-slate-400' : '' }}">{{ $employee->job_title }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 py-1 px-3 rounded-full text-[10px] font-bold uppercase border border-slate-200">
                            {{ $employee->department }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($employee->status === 'Active')
                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase border border-emerald-200">Active</span>
                        @elseif($employee->status === 'On Leave')
                            <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase border border-amber-200">On Leave</span>
                        @elseif($employee->status === 'Resigned')
                            <span class="bg-red-50 text-red-600 py-1 px-3 rounded-full text-[10px] font-bold uppercase border border-red-100">Resigned</span>
                        @else
                            <span class="bg-slate-100 text-slate-500 py-1 px-3 rounded-full text-[10px] font-bold uppercase border border-slate-200">{{ $employee->status }}</span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 font-mono {{ $employee->status === 'Resigned' ? 'opacity-50' : '' }}">
                        <div class="text-slate-700 font-bold text-sm">₱{{ number_format($employee->salary, 2) }}</div>
                        <div class="text-[9px] text-teal-600 font-bold uppercase tracking-wider mt-0.5">
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
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <div class="text-4xl mb-3">🔍</div>
                        <p class="font-bold text-sm text-slate-500">No employees found.</p>
                        <p class="text-xs mt-1">Try adjusting your search filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection