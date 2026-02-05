@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Add New Employee</h2>
    </div>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        
        <div class="grid gap-10">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">👤</span> Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Employee ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="e.g. C-21-001" 
                               class="w-full rounded-xl py-3 px-4 transition-all @error('employee_id') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('employee_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('first_name') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('first_name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional"
                               class="w-full rounded-xl border-slate-300 py-3 px-4 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('last_name') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('last_name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('birth_date') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('birth_date') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Gender <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('gender') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Civil Status <span class="text-red-500">*</span>
                        </label>
                        <select name="civil_status" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('civil_status') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Status</option>
                            <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('civil_status') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Home Address <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('address') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('address') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Contact Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('phone') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('email') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('emergency_contact_name') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('emergency_contact_name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                               
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Emergency Number</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('emergency_contact_phone') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('emergency_contact_phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror

                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">💼</span> Employment Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('department') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Dept</option>
                            <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="Faculty" {{ old('department') == 'Faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="Admin" {{ old('department') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('department') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Position <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('job_title') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('job_title') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Employment Type <span class="text-red-500">*</span>
                        </label>
                        <select name="employment_type" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('employment_type') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Type</option>
                            <option value="Permanent" {{ old('employment_type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="Contractual" {{ old('employment_type') == 'Contractual' ? 'selected' : '' }}>Contractual</option>
                            <option value="Part-Time" {{ old('employment_type') == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                        </select>
                        @error('employment_type') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Date Hired <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="join_date" value="{{ old('join_date') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('join_date') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('join_date') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Work Schedule <span class="text-red-500">*</span>
                        </label>
                        <select name="work_schedule" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('work_schedule') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Schedule</option>
                            <option value="Daily" {{ old('work_schedule') == 'Daily' ? 'selected' : '' }}>Daily</option>
                            <option value="Hourly" {{ old('work_schedule') == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                        </select>
                        @error('work_schedule') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('status') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="On Leave" {{ old('status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="Resigned" {{ old('status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Basic Salary (PHP) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="salary" value="{{ old('salary') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('salary') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('salary') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Supervisor / Dept Head</label>
                        <input type="text" name="supervisor" value="{{ old('supervisor') }}"
                               class="w-full rounded-xl py-3 px-4 transition-all @error('supervisor') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('supervisor') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <div class="flex justify-end items-center gap-6 mt-6 pb-12">
                <a href="{{ route('employees.index') }}" 
                   class="text-slate-500 hover:text-slate-800 font-bold text-lg px-6 py-4 rounded-xl hover:bg-slate-100 transition-colors">
                    Cancel
                </a>

                <button type="submit" 
                        class="bg-teal-700 hover:bg-teal-800 text-white font-bold text-xl py-4 px-12 rounded-xl transition-all shadow-xl hover:-translate-y-1 active:translate-y-0">
                    Save Employee Record
                </button>
            </div>

        </div>
    </form>
</div>
@endsection