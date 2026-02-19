@extends('layouts.app')

@section('content')
    <header class="page-header flex justify-between items-center px-8 py-4 bg-white border-b border-slate-200 mb-8">
        <div>
            <h2 class="font-bold text-slate-700 text-lg">Payroll Processing</h2>
            <p class="text-xs text-slate-500">Computing period: 15-Day Payroll ({{ date('F Y') }})</p>
        </div>
        <form action="{{ route('payroll.store') }}" method="POST" onsubmit="return confirm('Freeze these numbers and save the batch?')">
            @csrf
            <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                <span>💾</span> Save Batch
            </button>
        </form>
    </header>

    <div class="px-8 pb-8">
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">❌ {{ session('error') }}</div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
                @foreach(['all' => 'All Employees', 'permanent' => 'Fixed', 'contractual' => 'Contractual', 'daily' => 'Daily Paid', 'hourly' => 'Hourly Paid'] as $key => $label)
                    <a href="{{ route('payroll.index', ['filter' => $key, 'search' => request('search')]) }}" 
                       class="px-5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm border whitespace-nowrap {{ $filter === $key ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-500 hover:bg-slate-50 border-slate-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('payroll.index') }}" method="GET" class="relative shrink-0">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or ID..." class="w-full md:w-64 pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-teal-500 shadow-sm transition-all">
                <div class="absolute left-3 top-2.5 opacity-50">🔍</div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Base Pay & Additions</th>
                        <th class="px-6 py-4">Gross Pay</th>
                        <th class="px-6 py-4">Deductions</th>
                        <th class="px-6 py-4 bg-teal-50/50 text-teal-800">Net Pay</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payrollRecords as $record)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-700 text-sm">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</p>
                            <p class="text-[10px] font-mono text-slate-400">{{ $record->employee->employee_id ?? 'N/A' }} • {{ $record->employee->salary_type }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500 flex justify-between"><span>Base:</span> <span class="font-mono">₱{{ number_format($record->gross_basic, 2) }}</span></div>
                            @if($record->absence_deduction > 0)<div class="text-xs text-red-500 flex justify-between"><span>Abs ({{ $record->days_absent }}):</span> <span class="font-mono">-₱{{ number_format($record->absence_deduction, 2) }}</span></div>@endif
                            <div class="text-xs text-emerald-600 flex justify-between border-t mt-1"><span>Additions:</span> <span class="font-mono">+₱{{ number_format($record->additions, 2) }}</span></div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700 font-mono">₱{{ number_format($record->gross_pay, 2) }}</td>
                        <td class="px-6 py-4 text-[10px] text-slate-500">
                            <p>SSS/PHIC: <span class="text-red-500">-₱{{ number_format($record->sss + $record->philhealth, 2) }}</span></p>
                            <p>HDMF: <span class="text-red-500">-₱{{ number_format($record->pagibig, 2) }}</span></p>
                        </td>
                        <td class="px-6 py-4 bg-teal-50/30 text-lg font-black text-teal-700 font-mono">₱{{ number_format($record->net_pay, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection