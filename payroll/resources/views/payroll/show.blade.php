@extends('layouts.app')

@section('content')
<div class="px-8 py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="{{ route('payroll.history') }}" class="text-xs font-bold text-slate-400 hover:text-teal-600 transition-colors mb-2 inline-block">← Back to History</a>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Batch: {{ $batch->batch_id }}</h2>
            <p class="text-xs text-slate-500">Period: {{ date('M d', strtotime($batch->period_start)) }} - {{ date('M d, Y', strtotime($batch->period_end)) }}</p>
        </div>
    <div class="flex items-center gap-3">
            <a href="{{ route('payroll.print-batch', $batch->id) }}" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
            <span>🖨️</span> Print All Slips
        </a>
            </div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Net Disbursement</p>
            <h3 class="text-2xl font-black text-teal-700">₱{{ number_format($batch->total_net, 2) }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Basic Pay</th>
                    <th class="px-6 py-4">Additions</th>
                    <th class="px-6 py-4">Deductions</th>
                    <th class="px-6 py-4 text-teal-700">Net Pay</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($batch->items as $item)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-700 text-sm">{{ $item->employee->first_name }} {{ $item->employee->last_name }}</p>
                        <p class="text-[10px] font-mono text-slate-400">{{ $item->employee->employee_id }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm font-mono">₱{{ number_format($item->basic_pay, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-emerald-600">+₱{{ number_format($item->additions, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-mono text-red-500">-₱{{ number_format($item->deductions, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700 font-mono">₱{{ number_format($item->net_pay, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('payroll.download-slip', $item->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-bold py-2 px-4 rounded-lg shadow-sm transition-all flex items-center gap-2 inline-flex">
                            <span>📥</span> PDF Slip
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection