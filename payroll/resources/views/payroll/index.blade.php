@extends('layouts.app')

@section('content')
    <header class="page-header flex justify-between items-center px-8 py-4 bg-white border-b border-slate-200 mb-8">
        <div>
            <h2 class="font-bold text-slate-700 text-lg">Payroll Processing</h2>
            <p class="text-xs text-slate-500">
                Computing period: <span class="font-bold text-teal-600">{{ date('M d', strtotime($startDate)) }} - {{ date('M d, Y', strtotime($endDate)) }}</span>
            </p>
        </div>
        
        @if(!$isProcessed)
            <form action="{{ route('payroll.store') }}" method="POST" onsubmit="return confirm('Freeze these numbers and save the batch? You cannot undo this action.')">
                @csrf
                @if(Auth::user()->role === 'super_admin')
                <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                    <span></span> Save Batch
                </button>
                @endif
            </form>
        @else
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-xl text-xs font-bold shadow-sm flex items-center gap-2">
                <span>✅</span> Batch Processed (Resetting next period)
            </div>
        @endif
    </header>

    <div class="px-8 pb-8">
        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex justify-between items-center">
                <span>✅ {{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- Locked Batch Notice --}}
        @if($isProcessed)
            <div class="mb-6 bg-slate-800 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">Payroll Complete for this Period</h3>
                    <p class="text-xs text-slate-300 mt-1">Numbers are locked. The process screen will automatically reset when the next 15-day cycle begins.</p>
                </div>
                <a href="{{ route('payroll.history') }}" class="bg-white text-slate-800 px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-100 transition-colors shadow-sm">
                    View Saved Slips
                </a>
            </div>
        @endif

        {{-- Filters and Search Bar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            
            {{-- Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
                @foreach(['all' => 'All Employees', 'permanent' => 'Fixed', 'contractual' => 'Contractual', 'daily' => 'Daily Paid', 'hourly' => 'Hourly Paid'] as $key => $label)
                    <a href="{{ route('payroll.index', ['filter' => $key, 'search' => request('search')]) }}" 
                       class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border whitespace-nowrap 
                       {{ $filter === $key ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-500 hover:bg-slate-50 border-slate-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('payroll.index') }}" method="GET" class="relative shrink-0 w-full md:w-auto">
                <input type="hidden" name="filter" value="{{ $filter }}">
                
                <div class="relative group">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search name or ID..." 
                           class="w-full md:w-72 pl-10 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition-all text-slate-700 font-medium">
                    
                    {{-- Search Icon --}}
                    <div class="absolute left-3.5 top-3 text-slate-400 group-focus-within:text-teal-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    {{-- Clear Button (Only shows if searching) --}}
                    @if(request('search'))
                        <a href="{{ route('payroll.index', ['filter' => $filter]) }}" 
                           class="absolute right-3 top-2.5 text-slate-300 hover:text-red-500 transition-colors"
                           title="Clear Search">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Payroll Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Base Pay & Adjustments</th>
                        <th class="px-6 py-4">Gross Pay</th>
                        <th class="px-6 py-4">Deductions & Tax</th>
                        <th class="px-6 py-4 bg-teal-50/50 text-teal-800">Net Pay</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payrollRecords as $record)
                    
                    {{-- SMART FILTER: Hide ONLY if Resigned AND has 0 Pay --}}
                    @if($record->employee->status === 'Resigned' && $record->gross_pay <= 0)
                        @continue
                    @endif

                    <tr class="hover:bg-slate-50 transition {{ $isProcessed ? 'opacity-60 grayscale-[0.5]' : '' }}">
                        {{-- Employee Info --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="font-bold text-slate-700 text-sm">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">{{ $record->employee->employee_id ?? 'N/A' }}</span>
                                        <span class="text-[10px] font-bold text-teal-600 uppercase">{{ $record->employee->salary_type }}</span>
                                        @if($record->employee->status === 'Resigned')
                                            <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100">FINAL PAY</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Earnings Breakdown --}}
                        <td class="px-6 py-4">
                            {{-- Gross Basic --}}
                            <div class="text-xs text-slate-500 flex justify-between items-center mb-1">
                                <span>Basic <span class="text-[9px] text-slate-400">({{ $record->total_hours }} hrs)</span>:</span> 
                                <span class="font-mono font-bold text-slate-600">₱{{ number_format($record->gross_basic, 2) }}</span>
                            </div>
                            
                            {{-- Absence Deduction --}}
                            @if($record->absence_deduction > 0)
                                <div class="text-xs text-red-500 flex justify-between items-center mb-1 bg-red-50/50 px-1 rounded">
                                    <span>Less: Absences ({{ $record->days_absent }}):</span> 
                                    <span class="font-mono font-bold">-₱{{ number_format($record->absence_deduction, 2) }}</span>
                                </div>
                            @endif

                            {{-- Additions --}}
                            <div class="text-xs text-emerald-600 flex justify-between items-center border-t border-slate-100 mt-1 pt-1">
                                <span>Additions:</span> 
                                <span class="font-mono font-bold">+₱{{ number_format($record->additions, 2) }}</span>
                            </div>
                        </td>

                        {{-- Gross Pay --}}
                        <td class="px-6 py-4">
                            <span class="font-black text-slate-700 font-mono text-base">₱{{ number_format($record->gross_pay, 2) }}</span>
                        </td>

                        {{-- Deductions --}}
                        <td class="px-6 py-4 text-[10px] text-slate-500">
                            <div class="flex justify-between mb-1">
                                <span>Gov't Mandatories:</span> 
                                <span class="text-red-500 font-mono">-₱{{ number_format($record->sss + $record->philhealth + $record->pagibig, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-slate-700 mt-1 pt-1 border-t border-slate-100">
                                <span>Withholding Tax:</span> 
                                <span class="text-red-600 font-mono">-₱{{ number_format($record->tax, 2) }}</span>
                            </div>
                        </td>

                        {{-- Net Pay --}}
                        <td class="px-6 py-4 bg-teal-50/30">
                            <div class="text-lg font-black text-teal-700 font-mono">₱{{ number_format($record->net_pay, 2) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="inline-block p-4 rounded-full bg-slate-50 text-4xl mb-3">🔍</div>
                            <p class="text-slate-500 font-bold text-sm">No employees found.</p>
                            <p class="text-slate-400 text-xs">Try adjusting your filters or search terms.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection