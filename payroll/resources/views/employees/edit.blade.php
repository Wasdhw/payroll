@extends('layouts.app')

@section('content')
{{-- Include Alpine.js and Mask Plugin --}}
<script defer src="https://unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-6xl mx-auto py-12 px-6">
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Edit Employee Record</h2>
    </div>

    <form action="{{ route('employees.update', $employee->id) }}" method="POST"
        x-data="{
            employmentType: '{{ old('employment_type', $employee->employment_type) }}',
            workSchedule: '{{ old('work_schedule', $employee->work_schedule) }}',
            salaryType: '{{ old('salary_type', $employee->salary_type) }}',
            birthDate: '{{ old('birth_date', \Carbon\Carbon::parse($employee->birth_date)->format('Y-m-d')) }}',
            age: '',
            rawSalary: '{{ old('salary', $employee->salary) }}',
            displaySalary: '',

            calculateAge() {
                if (!this.birthDate) {
                    this.age = '';
                    return;
                }
                const birth = new Date(this.birthDate);
                const today = new Date();
                let calculatedAge = today.getFullYear() - birth.getFullYear();
                const monthDiff = today.getMonth() - birth.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                    calculatedAge--;
                }
                this.age = calculatedAge >= 0 ? calculatedAge + ' Years Old' : 'Invalid Date';
            },
            
            formatWithCommas(value) {
                if (!value) return '';
                let clean = value.toString().replace(/[^0-9.]/g, '');
                let parts = clean.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return parts.length > 1 ? parts[0] + '.' + parts[1].substring(0, 2) : parts[0];
            },

            updateDefaults() {
                if (this.employmentType === 'Regular') {
                    this.workSchedule = 'Daily';
                    this.salaryType = 'Monthly';
                }
            },
            
            updateSalaryType() {
                if (this.workSchedule === 'Hourly') {
                    this.salaryType = 'Hourly';
                }
            },
            get isRegular() { return this.employmentType === 'Regular'; },
            get isHourlySchedule() { return this.workSchedule === 'Hourly'; }
        }"
        x-init="
            calculateAge();
            displaySalary = formatWithCommas(rawSalary);
        ">
        @csrf
        @method('PUT')
        
        <div class="grid gap-10">

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">👤</span> Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Employee ID</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" 
                               class="w-full rounded-xl py-3 px-4 bg-slate-100 border-slate-300 text-slate-500 shadow-sm cursor-not-allowed" readonly tabindex="-1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" placeholder="Optional" class="w-full rounded-xl border-slate-300 py-3 px-4 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" x-model="birthDate" @change="calculateAge()" 
                               class="w-full rounded-xl py-3 px-4 transition-all @error('birth_date') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                        @error('birth_date') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Calculated Age</label>
                        <input type="text" x-model="age" readonly 
                               class="w-full rounded-xl py-3 px-4 border font-bold shadow-sm cursor-not-allowed bg-teal-50 text-teal-700 border-teal-200" 
                               tabindex="-1">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Gender</label>
                        <select name="gender" class="w-full rounded-xl py-3 px-4 bg-white border-slate-300 shadow-sm">
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                                        
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Civil Status <span class="text-red-500">*</span>
                        </label>
                        <select name="civil_status" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('civil_status') border-red-500 bg-red-50 ring-1 ring-red-500 @else border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Status</option>
                            <option value="Single" {{ old('civil_status', $employee->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status', $employee->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ old('civil_status', $employee->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('civil_status') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Home Address</label>
                        <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Contact Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 shadow-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Employment Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">💼</span> Employment Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Department</label>
                        <select name="department" class="w-full rounded-xl py-3 px-4 bg-white border-slate-300 shadow-sm">
                            <option value="HR" {{ old('department', $employee->department) == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="Faculty" {{ old('department', $employee->department) == 'Faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="Admin" {{ old('department', $employee->department) == 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Position</label>
                        <input type="text" name="job_title" value="{{ old('job_title', $employee->job_title) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Employment Type</label>
                        <select name="employment_type" x-model="employmentType" @change="updateDefaults" class="w-full rounded-xl py-3 px-4 bg-white border-slate-300 shadow-sm">
                            <option value="Regular">Regular</option>
                            <option value="Probationary">Probationary</option>
                            <option value="Contractual">Contractual</option>
                            <option value="Part-Time">Part-Time</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Supervisor</label>
                        <input type="text" name="supervisor" value="{{ old('supervisor', $employee->supervisor) }}" class="w-full rounded-xl py-3 px-4 border-slate-300 shadow-sm">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Date Hired <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="join_date" 
                               value="{{ old('join_date', \Carbon\Carbon::parse($employee->join_date)->format('Y-m-d')) }}"
                               class="w-full rounded-xl py-3 px-4 shadow-sm border-slate-300 bg-slate-100 text-slate-500 cursor-not-allowed pointer-events-none" 
                               readonly tabindex="-1">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                        <select name="status" class="w-full rounded-xl py-3 px-4 bg-white border-slate-300 shadow-sm">
                            <option value="Active" {{ old('status', $employee->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="On Leave" {{ old('status', $employee->status) == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="Resigned" {{ old('status', $employee->status) == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Work Schedule</label>
                        <select name="work_schedule" x-model="workSchedule" @change="updateSalaryType" :disabled="isRegular" class="w-full rounded-xl py-3 px-4 bg-white border-slate-300 shadow-sm disabled:bg-slate-100 disabled:cursor-not-allowed">
                            <option value="Daily">Daily</option>
                            <option value="Hourly">Hourly</option>
                        </select>
                        {{-- Hidden input sends data when the dropdown above is disabled --}}
                        <template x-if="isRegular">
                            <input type="hidden" name="work_schedule" :value="workSchedule">
                        </template>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Salary Type</label>
                        <select name="salary_type" x-model="salaryType" :disabled="isHourlySchedule || isRegular" class="w-full rounded-xl py-3 px-4 bg-white border-slate-300 shadow-sm disabled:bg-slate-100 disabled:cursor-not-allowed">
                            <option value="Monthly">Monthly Fixed</option>
                            <option value="Daily">Daily Rate</option>
                            <option value="Hourly">Hourly Rate</option>
                        </select>
                        {{-- FIX: Hidden input ensures 'Hourly' is sent to the DB even if disabled --}}
                        <template x-if="isHourlySchedule || isRegular">
                            <input type="hidden" name="salary_type" :value="salaryType">
                        </template>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Amount / Rate (PHP) <span class="text-red-500">*</span></label>
                        <input type="text" 
                               x-model="displaySalary"
                               @input="
                                   let val = $event.target.value.replace(/[^0-9.]/g, '');
                                   rawSalary = val;
                                   displaySalary = formatWithCommas(val);
                               "
                               placeholder="0.00"
                               class="w-full rounded-xl py-3 px-4 border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        
                        <input type="hidden" name="salary" :value="rawSalary">
                        @error('salary') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center gap-6 mt-6 pb-12">
                <a href="{{ route('employees.index') }}" class="text-slate-500 hover:text-slate-800 font-bold text-lg px-6 py-4">Cancel</a>
                <button type="submit" 
                        class="bg-teal-700 hover:bg-teal-800 text-white font-bold text-xl py-4 px-12 rounded-xl transition-all shadow-xl hover:-translate-y-1 active:translate-y-0">
                    Save Employee Record
                </button>
            </div>
        </div>
    </form>
</div>
@endsection