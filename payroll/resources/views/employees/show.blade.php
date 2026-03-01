@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    
    <div class="mb-10 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Employee Record Details</h2>
        <a href="{{ route('employees.index') }}" class="text-slate-500 hover:text-slate-800 font-bold">← Back</a>
    </div>

    <div class="grid gap-10">
        
        {{-- Personal Information Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">👤</span> Personal Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Employee ID</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-slate-50 border border-slate-200 text-[#003366] font-bold shadow-sm">
                        {{ $employee->employee_id }}
                    </p>
                </div>
                
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">First Name</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->first_name }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Middle Name</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->middle_name ?? 'N/A' }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Last Name</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->last_name }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Date of Birth</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->birth_date }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Gender</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->gender }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Civil Status</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->civil_status }}
                    </p>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Home Address</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->address }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Contact Number</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->phone }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Email Address</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->email }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Emergency Contact Name</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->emergency_contact_name }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Emergency Number</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->emergency_contact_phone }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Employment Details Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">💼</span> Employment Details
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Department</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->department }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Position</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->job_title }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Employment Type</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->employment_type }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Date Hired</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->join_date }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Work Schedule</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->work_schedule }}
                    </p>
                </div>
                
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 font-bold shadow-sm {{ $employee->status === 'Active' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $employee->status }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Salary Type</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->salary_type }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Amount / Rate (PHP)</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-teal-700 font-bold shadow-sm">
                        ₱{{ number_format($employee->salary, 2) }}
                    </p>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Supervisor / Dept Head</label>
                    <p class="w-full rounded-xl py-3 px-4 bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                        {{ $employee->supervisor ?? 'None' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-end items-center gap-6 mt-6 pb-12">
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('employees.edit', $employee->id) }}" 
                   class="bg-teal-700 hover:bg-teal-800 text-white font-bold text-xl py-4 px-12 rounded-xl transition-all shadow-xl hover:-translate-y-1 active:translate-y-0 text-center">
                    Edit Record
                </a>
            @endif
        </div>

    </div>
</div>
@endsection