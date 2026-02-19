@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>

<div x-data="{ logModalOpen: false, empId: '', empName: '' }">

    <header class="page-header flex justify-between items-center px-8 py-4 bg-white border-b border-slate-200 mb-8">
        <div>
            <h2 class="font-bold text-slate-700 text-lg">Attendance & Incentives</h2>
            <p class="text-xs text-slate-500">Viewing logs for: <span class="font-bold text-teal-600">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold py-2 px-4 rounded-xl text-xs transition-colors shadow-sm"> Export Log</button>
        </div>
    </header>

    <div class="px-8 pb-8">
        
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex justify-between items-center shadow-sm">
                <span>✅ {{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-600 font-bold">&times;</button>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-slate-100 pb-4">
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('attendance.index', ['date' => \Carbon\Carbon::parse($selectedDate)->subMonth()->startOfMonth()->format('Y-m-d')]) }}" 
                       class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-colors" title="Previous Month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </a>

                    <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2 min-w-[180px] justify-center">
                        <span>📅</span> {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
                    </h3>

                    <a href="{{ route('attendance.index', ['date' => \Carbon\Carbon::parse($selectedDate)->addMonth()->startOfMonth()->format('Y-m-d')]) }}" 
                       class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-colors" title="Next Month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                <div class="flex items-center gap-3">
                    <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center m-0">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-2 hidden md:block">Jump to:</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                               class="text-xs border-slate-200 rounded-lg text-slate-600 focus:ring-teal-500 focus:border-teal-500 py-2 px-3 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                    </form>

                    @if($selectedDate !== date('Y-m-d'))
                        <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-100 hover:bg-teal-100 hover:text-teal-800 px-4 py-2 rounded-lg transition-colors shadow-sm">
                            Return to Today
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-7 gap-3 text-center">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $day }}</div>
                @endforeach
                
                @php
                    $viewDate = \Carbon\Carbon::parse($selectedDate);
                    $startDay = $viewDate->copy()->startOfMonth()->dayOfWeek; 
                    $daysInMonth = $viewDate->daysInMonth;
                @endphp
                
                @for($i = 0; $i < $startDay; $i++)
                    <div class="p-2"></div>
                @endfor
                
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = $viewDate->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $hasLog = isset($loggedDates) && in_array($currentDate, $loggedDates);
                        $isToday = $currentDate === date('Y-m-d');
                        $isSelected = $currentDate === $selectedDate;
                    @endphp
                    
                    <a href="{{ route('attendance.index', ['date' => $currentDate]) }}" 
                       class="relative group p-3 rounded-xl border transition-all duration-200 flex flex-col items-center justify-center h-16 
                              {{ $isSelected ? 'border-teal-500 bg-teal-50 shadow-sm ring-1 ring-teal-500' : 'border-slate-100 hover:border-teal-300 hover:bg-slate-50' }} 
                              {{ $hasLog && !$isSelected ? 'bg-emerald-50/30 border-emerald-100' : '' }}">
                        
                        <span class="text-sm font-bold {{ $isSelected || $isToday ? 'text-teal-700' : 'text-slate-600' }}">
                            {{ $day }}
                        </span>
                        
                        @if($hasLog)
                            <div class="mt-1.5 w-2 h-2 bg-emerald-500 rounded-full shadow-sm"></div>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-slate-800 text-white text-[10px] font-bold py-1 px-3 rounded-lg whitespace-nowrap z-10 shadow-lg">
                                Logs Saved ✅
                            </div>
                        @endif
                    </a>
                @endfor
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">OT (Hrs)</th>
                        <th class="px-6 py-4">Allowances</th>
                        <th class="px-6 py-4">Incentives</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    
                    @forelse($employees as $employee)
                    
                    @php
                        // Fetch log for the SELECTED date, not just today
                        $todayLog = $employee->attendances->where('attendance_date', $selectedDate)->first();
                    @endphp

                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-700 text-sm">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </p>
                            <p class="text-[10px] font-mono text-teal-600">
                                @if($employee->employee_id) {{ $employee->employee_id }} @else EMP-00{{ $employee->id }} @endif
                            </p>
                        </td>
                        
                        <td class="px-6 py-4">
                            @if($todayLog && $todayLog->status === 'Present')
                                <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase tracking-wider">Present</span>
                            @elseif($todayLog && $todayLog->status === 'Absent')
                                <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase tracking-wider">Absent</span>
                            @elseif($todayLog && $todayLog->status === 'On Leave')
                                <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase tracking-wider">On Leave</span>
                            @else
                                <span class="bg-slate-100 text-slate-500 py-1 px-3 rounded-full text-[10px] font-bold uppercase tracking-wider">Pending Log</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                            {{ $todayLog ? $todayLog->overtime_hours : '0' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            ₱{{ number_format($todayLog ? $todayLog->allowance : 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-teal-600 font-bold">
                            ₱{{ number_format($todayLog ? $todayLog->incentive : 0, 2) }}
                        </td>
                        
                        <td class="px-6 py-4 text-right">
                            <button type="button" 
                                    @click="logModalOpen = true; empId = '{{ $employee->id }}'; empName = '{{ addslashes($employee->first_name . ' ' . $employee->last_name) }}'" 
                                    class="text-xs font-bold uppercase transition-colors px-4 py-2 rounded-lg border shadow-sm {{ $todayLog ? 'text-slate-500 hover:bg-slate-100 bg-white border-slate-300' : 'text-teal-700 hover:text-white hover:bg-teal-700 bg-teal-50 border-teal-200' }}">
                                {{ $todayLog ? 'Edit Log' : 'Log Action' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                            No employees found in the database.
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
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all" 
             @click.away="logModalOpen = false"
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
            
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-700 text-lg flex items-center gap-2">
                    <span>🕒</span> Log Attendance
                </h3>
                <button @click="logModalOpen = false" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-lg font-black text-xl leading-none transition-colors">&times;</button>
            </div>
            
            <form action="{{ route('attendance.store') }}" method="POST" class="p-6">
                @csrf
                
                <input type="hidden" name="employee_id" :value="empId">

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Employee Name</label>
                    <input type="text" :value="empName" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-600 font-bold cursor-not-allowed shadow-inner focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="attendance_date" value="{{ $selectedDate }}" required class="w-full border-slate-300 rounded-xl px-4 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition-all bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full border-slate-300 rounded-xl px-4 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition-all">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-5 mb-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">OT (Hrs)</label>
                        <input type="number" step="0.5" name="overtime_hours" value="0" class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">Allowance (₱)</label>
                        <input type="number" step="0.01" name="allowance" value="0" class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-2">Incentive (₱)</label>
                        <input type="number" step="0.01" name="incentive" value="0" class="w-full border-slate-300 rounded-xl px-3 py-3 focus:ring-teal-500 focus:border-teal-500 shadow-sm text-center font-mono text-slate-700 transition-all">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="logModalOpen = false" class="px-6 py-2.5 text-slate-500 font-bold hover:bg-slate-100 rounded-xl transition-colors">Cancel</button>
                    <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">Save Log</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection