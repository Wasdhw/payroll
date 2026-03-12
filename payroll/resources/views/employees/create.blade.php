@extends('layouts.app')

@section('content')
{{-- Include Alpine.js and Mask Plugin --}}
<script defer src="https://unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-6xl mx-auto py-12 px-6">
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Add New Employee</h2>
    </div>

    <form action="{{ route('employees.store') }}" method="POST"
        x-data="{
            employmentType: '{{ old('employment_type', 'Regular') }}',
            workSchedule: '{{ old('work_schedule', 'Daily') }}',
            salaryType: '{{ old('salary_type', 'Monthly') }}',
            birthDate: '{{ old('birth_date', '') }}',
            phone: '{{ old('phone', '') }}',
            age: '',
            rawSalary: '{{ old('salary', '') }}',
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
            get isHourlySchedule() { return this.workSchedule === 'Hourly'; },
            
            // Check if phone has value but is less than 11 digits
            get isPhoneInvalid() { 
                let cleanPhone = this.phone ? this.phone.replace(/\D/g, '') : '';
                return cleanPhone.length > 0 && cleanPhone.length < 11; 
            }
        }"
        x-init="
            calculateAge();
            if(rawSalary) displaySalary = formatWithCommas(rawSalary);
        ">
        @csrf
        
        <div class="grid gap-10">

            {{-- 1. Personal Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">👤</span> Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Employee ID <span class="text-red-500">*</span></label>
                        <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="e.g. C-21-001" 
                               class="w-full rounded-xl py-3 px-4 border border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm @error('employee_id') border-red-500 @enderror">
                        @error('employee_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full rounded-xl py-3 px-4 border border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        @error('first_name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" class="w-full rounded-xl border border-slate-300 py-3 px-4 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full rounded-xl py-3 px-4 border border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        @error('last_name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" x-model="birthDate" @change="calculateAge()" 
                               max="{{ now()->format('Y-m-d') }}"
                               class="w-full rounded-xl py-3 px-4 border border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        @error('birth_date') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Calculated Age</label>
                        <input type="text" x-model="age" readonly tabindex="-1" class="w-full rounded-xl py-3 px-4 border border-slate-300 font-bold shadow-sm cursor-not-allowed bg-teal-50 text-teal-700 border-teal-200">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Gender</label>
                        <select name="gender" class="w-full rounded-xl py-3 px-4 bg-white border border-slate-300 shadow-sm">
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Civil Status <span class="text-red-500">*</span>
                        </label>
                        <select name="civil_status" class="w-full rounded-xl py-3 px-4 bg-white transition-all @error('civil_status') border-red-500 bg-red-50 ring-1 ring-red-500 @else border border-slate-300 focus:ring-teal-500 focus:border-teal-500 @enderror shadow-sm">
                            <option value="">Select Status</option>
                            <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('civil_status') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Home Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-xl py-3 px-4 border border-slate-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Contact Number</label>
                        <input type="text" name="phone" 
                               x-model="phone" 
                               x-mask="99999999999" 
                               maxlength="11"
                               placeholder="09123456789"
                               :class="isPhoneInvalid ? 'border-red-500 bg-red-50 ring-1 ring-red-500' : 'border-slate-300 focus:ring-teal-500 focus:border-teal-500'"
                               class="w-full rounded-xl py-3 px-4 border shadow-sm transition-all">
                        <p x-show="isPhoneInvalid" style="display: none;" class="text-red-500 text-xs mt-1 font-bold">Contact number must be exactly 11 digits.</p>
                        @error('phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl py-3 px-4 border border-slate-300 shadow-sm">
                    </div>
                </div>
            </div>

            {{-- 2. Employment Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-2xl font-bold text-slate-700 mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="bg-teal-50 text-teal-600 p-2 rounded-lg">💼</span> Employment Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Department</label>
                        <select name="department" class="w-full rounded-xl py-3 px-4 bg-white border border-slate-300 shadow-sm">
                            <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="Faculty" {{ old('department') == 'Faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="Admin" {{ old('department') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
    
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Position</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full rounded-xl py-3 px-4 border border-slate-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Employment Type</label>
                        <select name="employment_type" x-model="employmentType" @change="updateDefaults" class="w-full rounded-xl py-3 px-4 bg-white border border-slate-300 shadow-sm">
                            <option value="Regular">Regular</option>
                            <option value="Contractual">Contractual</option>
                            <option value="Part-Time">Part-Time</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Supervisor</label>
                        <input type="text" name="supervisor" value="{{ old('supervisor') }}" class="w-full rounded-xl py-3 px-4 border border-slate-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Join Date <span class="text-red-500">*</span></label>
                        <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" 
                               class="w-full rounded-xl py-3 px-4 border border-slate-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                        <div class="pointer-events-none">
                            <select name="status" tabindex="-1" class="w-full rounded-xl py-3 px-4 bg-slate-100 border border-slate-300 text-slate-500 shadow-sm cursor-not-allowed">
                                <option value="Active" selected>Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Resigned">Resigned</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Work Schedule</label>
                        <select name="work_schedule" x-model="workSchedule" @change="updateSalaryType" class="w-full rounded-xl py-3 px-4 bg-white border border-slate-300 shadow-sm disabled:bg-slate-100">
                            <option value="Daily">Daily</option>
                            <option value="Hourly">Hourly</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Salary Type</label>
                        <select name="salary_type" x-model="salaryType" :disabled="isHourlySchedule" class="w-full rounded-xl py-3 px-4 bg-white border border-slate-300 shadow-sm disabled:bg-slate-100">
                            <option value="Monthly">Monthly Fixed</option>
                            <option value="Daily">Daily Rate</option>
                            <option value="Hourly">Hourly Rate</option>
                        </select>
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
                               class="w-full rounded-xl py-3 px-4 border border-slate-300 focus:ring-teal-500 focus:border-teal-500 shadow-sm font-semibold text-teal-700">
                        
                        <input type="hidden" name="salary" :value="rawSalary">
                        @error('salary') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center gap-6 mt-6 pb-12">
                <a href="{{ route('employees.index') }}" class="text-slate-500 hover:text-slate-800 font-bold text-lg px-6 py-4">Cancel</a>
                <button type="submit" 
                        :disabled="isPhoneInvalid"
                        class="bg-teal-700 hover:bg-teal-800 text-white font-bold text-xl py-4 px-12 rounded-xl transition-all shadow-xl hover:-translate-y-1 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                    Save Employee Record
                </button>
            </div>
        </div>
    </form>
</div>
@endsection