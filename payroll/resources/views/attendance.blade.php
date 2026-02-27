@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>

<div x-data="{ 
    logModalOpen: false, 
    importModalOpen: false,
    empId: '', 
    empName: '',
    logStatus: 'Present',
    logHours: 8, 
    logOt: 0,
    logAllowance: 0,
    logIncentive: 0 
}">

    <header class="page-header flex justify-between items-center px-8 py-4 bg-white border-b border-slate-200 mb-8">
        <div>
            <h2 class="font-bold text-slate-700 text-lg">Attendance & Incentives</h2>
            <p class="text-xs text-slate-500">Managing records for: <span class="font-bold text-teal-600">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="importModalOpen = true" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded-xl text-xs transition-colors shadow-sm flex items-center gap-2">
                <span></span> Import Biometrics
            </button>
        </div>
    </header>

    <div class="px-8 pb-8">
        
        {{-- Success Alert --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex justify-between items-center shadow-sm">
                <span>✅ {{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-600 font-bold">&times;</button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm relative">
                <button @click="show = false" class="absolute top-3 right-4 text-red-600 font-bold">&times;</button>
                <p class="text-xs font-bold uppercase tracking-wider mb-1">Upload Failed:</p>
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6 flex flex-col lg:flex-row justify-between items-center gap-4">
            
            <div class="flex items-center gap-3 w-full lg:w-auto justify-between lg:justify-start">
                <a href="{{ route('attendance.index', ['date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d'), 'search' => request('search')]) }}" 
                   class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-colors border border-transparent hover:border-teal-100" title="Previous Day">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div class="text-center">
                    <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                        <span>📅</span> {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($selectedDate)->format('l') }}</p>
                </div>

                <a href="{{ route('attendance.index', ['date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d'), 'search' => request('search')]) }}" 
                   class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-colors border border-transparent hover:border-teal-100" title="Next Day">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                
                <form action="{{ route('attendance.index') }}" method="GET" class="relative w-full md:w-64">
                    <input type="hidden" name="date" value="{{ $selectedDate }}"> <div class="relative group">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search employee..." 
                               class="w-full pl-10 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition-all text-slate-700 font-bold placeholder:font-normal">
                        
                        <div class="absolute left-3 top-2.5 text-slate-400 group-focus-within:text-teal-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                        @if(request('search'))
                            <a href="{{ route('attendance.index', ['date' => $selectedDate]) }}" 
                               class="absolute right-3 top-2.5 text-slate-300 hover:text-red-500 transition-colors" title="Clear Search">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center m-0 w-full md:w-auto">
                    <input type="hidden" name="search" value="{{ request('search') }}"> <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                           class="w-full md:w-auto text-xs border-slate-200 rounded-lg text-slate-600 focus:ring-teal-500 focus:border-teal-500 py-2 px-3 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors font-bold">
                </form>

                @if($selectedDate !== date('Y-m-d'))
                    <a href="{{ route('attendance.index', ['search' => request('search')]) }}" class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-100 hover:bg-teal-100 hover:text-teal-800 px-4 py-2 rounded-lg transition-colors shadow-sm whitespace-nowrap">
                        Today
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Daily Status</th>
                        <th class="px-6 py-4 text-center">Reg. Hrs</th>
                        <th class="px-6 py-4 text-center">OT (Hrs)</th>
                        <th class="px-6 py-4 text-center">Additions (₱)</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" x-data="{ expandedEmp: null }">
                    
                    @php
                        $viewDate = \Carbon\Carbon::parse($selectedDate);
                        $startDay = $viewDate->copy()->startOfMonth()->dayOfWeek; 
                        $daysInMonth = $viewDate->daysInMonth;
                    @endphp

                    @forelse($employees as $employee)
                    
                    @php
                        // --- SMART RESIGNED FILTER ---
                        $hasLogsForThisPeriod = $employee->attendances->isNotEmpty();

                        if($employee->status === 'Resigned' && !$hasLogsForThisPeriod) {
                            continue; 
                        }

                        $todayLog = $employee->attendances->where('attendance_date', $selectedDate)->first();
                    @endphp

                    <tr class="hover:bg-slate-50 transition cursor-pointer" @click="expandedEmp = expandedEmp === {{ $employee->id }} ? null : {{ $employee->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-slate-700 text-sm flex items-center gap-2">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                        <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="expandedEmp === {{ $employee->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-mono text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded">
                                            @if($employee->employee_id) {{ $employee->employee_id }} @else EMP-00{{ $employee->id }} @endif
                                        </span>
                                        @if($employee->status === 'Resigned')
                                            <span class="text-[9px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100 uppercase">RESIGNED</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            @if($todayLog && $todayLog->status === 'Present')
                                <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-md text-[10px] font-bold uppercase tracking-wider border border-emerald-200">Present</span>
                            @elseif($todayLog && $todayLog->status === 'Absent')
                                <span class="bg-red-100 text-red-700 py-1 px-3 rounded-md text-[10px] font-bold uppercase tracking-wider border border-red-200">Absent</span>
                            @elseif($todayLog && $todayLog->status === 'On Leave')
                                <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-md text-[10px] font-bold uppercase tracking-wider border border-amber-200">On Leave</span>
                            @else
                                <span class="bg-slate-100 text-slate-500 py-1 px-3 rounded-md text-[10px] font-bold uppercase tracking-wider border border-slate-200">Pending</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-slate-600 text-center font-mono">
                            {{ $todayLog ? $todayLog->hours_worked : '0.0' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600 text-center font-mono">
                            {{ $todayLog ? $todayLog->overtime_hours : '0.0' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 text-center font-mono">
                            {{ number_format(($todayLog ? $todayLog->allowance : 0) + ($todayLog ? $todayLog->incentive : 0), 2) }}
                        </td>
                        
                        <td class="px-6 py-4 text-right">
                            <button type="button" 
                                    @click.stop="
                                        logModalOpen = true; 
                                        empId = '{{ $employee->id }}'; 
                                        empName = '{{ addslashes($employee->first_name . ' ' . $employee->last_name) }}';
                                        logStatus = '{{ $todayLog ? $todayLog->status : 'Present' }}';
                                        logHours = {{ $todayLog ? $todayLog->hours_worked : 8 }};
                                        logOt = {{ $todayLog ? $todayLog->overtime_hours : 0 }};
                                        logAllowance = {{ $todayLog ? $todayLog->allowance : 0 }};
                                        logIncentive = {{ $todayLog ? $todayLog->incentive : 0 }};
                                    " 
                                    class="text-xs font-bold uppercase transition-colors px-4 py-2 rounded-lg shadow-sm border {{ $todayLog ? 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' : 'bg-teal-700 text-white border-teal-800 hover:bg-teal-800' }}">
                                {{ $todayLog ? 'Edit' : 'Log Entry' }}
                            </button>
                        </td>
                    </tr>

                    <tr x-show="expandedEmp === {{ $employee->id }}" x-cloak class="bg-slate-50/80 border-b border-slate-200">
                        <td colspan="6" class="px-8 py-6">
                            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-bold text-slate-700 text-sm">🗓️ Monthly Overview: {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}</h4>
                                    <div class="flex gap-3 text-[10px] font-bold uppercase text-slate-500">
                                        <span class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></div> Present</span>
                                        <span class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-red-100 border border-red-300"></div> Absent</span>
                                        <span class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-amber-100 border border-amber-300"></div> Leave</span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-7 gap-2 text-center">
                                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                        <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $day }}</div>
                                    @endforeach
                                    
                                    @for($i = 0; $i < $startDay; $i++)
                                        <div class="p-2 bg-slate-50 rounded-lg opacity-50"></div>
                                    @endfor
                                    
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = $viewDate->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                                            $empLog = $employee->attendances->where('attendance_date', $dateStr)->first();
                                            
                                            $bgClass = 'bg-white border-slate-200 hover:border-teal-300';
                                            $textClass = 'text-slate-600';
                                            
                                            if($empLog) {
                                                if($empLog->status === 'Present') { $bgClass = 'bg-emerald-50 border-emerald-200'; $textClass = 'text-emerald-700'; }
                                                elseif($empLog->status === 'Absent') { $bgClass = 'bg-red-50 border-red-200'; $textClass = 'text-red-700'; }
                                                elseif($empLog->status === 'On Leave') { $bgClass = 'bg-amber-50 border-amber-200'; $textClass = 'text-amber-700'; }
                                            }
                                            
                                            if($dateStr === date('Y-m-d')) {
                                                $bgClass .= ' ring-2 ring-teal-500';
                                            }
                                        @endphp
                                        
                                        <div class="border rounded-lg p-2 {{ $bgClass }} flex flex-col items-center justify-center h-12 transition-colors relative group">
                                            <span class="text-xs font-bold {{ $textClass }}">{{ $day }}</span>
                                            
                                            @if($empLog)
                                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-slate-800 text-white text-[10px] p-2 rounded-lg whitespace-nowrap z-10 shadow-lg text-left">
                                                    <p>Status: {{ $empLog->status }}</p>
                                                    <p>Reg Hrs: {{ $empLog->hours_worked }}</p>
                                                    @if($empLog->overtime_hours > 0)<p>OT: {{ $empLog->overtime_hours }} hrs</p>@endif
                                                    @if($empLog->allowance > 0)<p>Allow: ₱{{ $empLog->allowance }}</p>@endif
                                                    @if($empLog->incentive > 0)<p>Incent: ₱{{ $empLog->incentive }}</p>@endif
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                            <div class="inline-block p-4 rounded-full bg-slate-50 text-4xl mb-3">🔍</div>
                            <p class="text-slate-500 font-bold text-sm">No employees found.</p>
                            @if(request('search'))
                                <p class="text-slate-400 text-xs mt-1">Try adjusting your search terms.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="logModalOpen" 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all" 
             @click.away="logModalOpen = false"
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
            
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                    <span>📝</span> Record Entry for {{ \Carbon\Carbon::parse($selectedDate)->format('M d') }}
                </h3>
                <button @click="logModalOpen = false" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-lg font-black text-xl leading-none transition-colors">&times;</button>
            </div>
            
            <form action="{{ route('attendance.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="employee_id" :value="empId">
                <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Employee</label>
                    <input type="text" :value="empName" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-bold cursor-not-allowed shadow-inner focus:outline-none">
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="Present" x-model="logStatus" @click="logHours = 8" class="peer hidden">
                            <div class="text-center px-4 py-3 rounded-xl border border-slate-200 font-bold text-sm text-slate-500 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all shadow-sm">Present</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="Absent" x-model="logStatus" @click="logHours = 0" class="peer hidden">
                            <div class="text-center px-4 py-3 rounded-xl border border-slate-200 font-bold text-sm text-slate-500 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all shadow-sm">Absent</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="On Leave" x-model="logStatus" @click="logHours = 0" class="peer hidden">
                            <div class="text-center px-4 py-3 rounded-xl border border-slate-200 font-bold text-sm text-slate-500 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 transition-all shadow-sm">Leave</div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mb-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">Reg. Hrs</label>
                        {{-- UPDATED INPUT WITH LIMITS AND ALPINE CLAMPING --}}
                        <input type="number" 
                               step="0.5" 
                               min="0" 
                               max="8" 
                               name="hours_worked" 
                               x-model="logHours" 
                               @input="if(logHours > 8) logHours = 8; if(logHours < 0) logHours = 0;"
                               class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono font-bold text-slate-700 transition-all bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">Overtime (Hrs)</label>
                        <input type="number" step="0.5" name="overtime_hours" x-model="logOt" class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono font-bold text-slate-700 transition-all bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">Allowance (₱)</label>
                        <input type="number" step="0.01" name="allowance" x-model="logAllowance" class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono font-bold text-slate-700 transition-all bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">Incentive (₱)</label>
                        <input type="number" step="0.01" name="incentive" x-model="logIncentive" class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono font-bold text-slate-700 transition-all bg-slate-50">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="logModalOpen = false" class="px-6 py-2.5 text-slate-500 font-bold hover:bg-slate-100 rounded-xl transition-colors">Cancel</button>
                    <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="importModalOpen" 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all" 
             @click.away="importModalOpen = false"
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
            
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                    <span>📠</span> Import CSV Data
                </h3>
                <button @click="importModalOpen = false" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-lg font-black text-xl leading-none transition-colors">&times;</button>
            </div>
            
            <form action="{{ route('attendance.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="mb-6" x-data="{ fileName: null }">
                    <div class="border-2 border-dashed rounded-xl p-8 text-center transition-colors cursor-pointer relative"
                         :class="fileName ? 'bg-teal-50 border-teal-400' : 'border-slate-300 hover:bg-slate-50'">
                        
                        <input type="file" name="biometric_file" accept=".csv,.txt" required 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                               @change="fileName = $event.target.files[0].name">
                        
                        <div class="text-4xl mb-3" x-show="!fileName">📄</div>
                        <div class="text-4xl mb-3" x-show="fileName" x-cloak>✅</div>
                        
                        <p class="text-sm font-bold text-slate-700 mb-1" x-text="fileName ? fileName : 'Upload Biometric CSV'"></p>
                        <p class="text-xs text-slate-500 mb-4" x-show="!fileName">Click to browse or drag and drop.</p>
                        
                        <span class="text-xs font-bold px-4 py-2 rounded-full pointer-events-none transition-colors"
                              :class="fileName ? 'bg-teal-600 text-white shadow-md' : 'bg-teal-50 text-teal-700'"
                              x-text="fileName ? 'Change File' : 'Select File'"></span>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                    <p class="text-[10px] font-bold text-blue-800 uppercase tracking-widest mb-2">Expected CSV Format:</p>
                    <table class="w-full text-left text-xs text-blue-900 font-mono">
                        <tr class="border-b border-blue-200/50"><td class="py-1">Col 1:</td><td>Employee ID (e.g., C-21-200)</td></tr>
                        <tr class="border-b border-blue-200/50"><td class="py-1">Col 2:</td><td>Date (YYYY-MM-DD)</td></tr>
                        <tr class="border-b border-blue-200/50"><td class="py-1">Col 3:</td><td>Status (Present/Absent)</td></tr>
                        <tr class="border-b border-blue-200/50"><td class="py-1">Col 4:</td><td class="font-bold text-blue-700">Reg. Hours Worked</td></tr>
                        <tr><td class="py-1">Col 5:</td><td>Overtime Hours</td></tr>
                    </table>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="importModalOpen = false" class="px-6 py-2.5 text-slate-500 font-bold hover:bg-slate-100 rounded-xl transition-colors">Cancel</button>
                    <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-md">Start Import</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection