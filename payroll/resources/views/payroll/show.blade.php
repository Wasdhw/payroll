@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- AUTO-PRINT SCRIPT: This triggers the download if you just finalized the batch --}}
@if(session('success') && $batch->status === 'Paid')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Automatically triggers the PDF download route
        window.location.href = "{{ route('payroll.pdf', $batch->id) }}";
    });
</script>
@endif

<div class="px-8 py-8" x-data="{ confirmFinalize: false }">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="{{ route('payroll.history') }}" class="text-xs font-bold text-slate-400 hover:text-teal-600 transition-colors mb-2 inline-block">← Back to History</a>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Batch: {{ $batch->batch_id }}</h2>
                
                {{-- Dynamic Badge --}}
                @if($batch->status === 'Paid')
                    <span class="bg-emerald-100 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase border border-emerald-200 shadow-sm">
                        FINALIZED
                    </span>
                @else
                    <span class="bg-amber-100 text-amber-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase border border-amber-200 animate-pulse">
                        PENDING REVIEW
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500">Period: {{ date('M d', strtotime($batch->period_start)) }} - {{ date('M d, Y', strtotime($batch->period_end)) }}</p>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Net Disbursement</p>
                <h3 class="text-2xl font-black text-teal-700">₱{{ number_format($batch->total_net, 2) }}</h3>
            </div>

            @if($batch->status === 'Paid')
                {{-- Manual Print Button (if auto-print fails or you need another copy) --}}
                <a href="{{ route('payroll.pdf', $batch->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                    <span>🖨️</span> Print All Slips
                </a>
            @else
                <button @click="confirmFinalize = true" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                    <span>✅</span> Finalize & Mark All Paid
                </button>
            @endif
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div x-show="confirmFinalize" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center" @click.away="confirmFinalize = false">
            <div class="h-16 w-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-emerald-100 shadow-inner">💰</div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Finalize this Batch?</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                Confirming will mark the entire batch as <span class="text-emerald-600 font-bold">PAID</span>. 
                The PDF file will download automatically.
            </p>
            <div class="flex gap-3">
                <button @click="confirmFinalize = false" class="flex-1 py-3 px-4 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <a href="{{ route('payroll.finalize', $batch->id) }}" class="flex-1 py-3 px-4 bg-teal-700 text-white font-bold rounded-xl hover:bg-teal-800 transition-all shadow-lg text-center">
                    Confirm Paid
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Payment Status</th>
                    <th class="px-6 py-4">Net Pay</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-mono">
                @foreach($batch->items as $item)
                <tr class="hover:bg-slate-50 transition group">
                    <td class="px-6 py-4 font-sans">
                        <p class="font-bold text-slate-700 text-sm">{{ $item->employee->first_name }} {{ $item->employee->last_name }}</p>
                        <p class="text-[10px] text-slate-400">{{ $item->employee->employee_id }}</p>
                    </td>
                    <td class="px-6 py-4 font-sans">
                        @if($item->is_paid)
                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border border-emerald-100">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Paid
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border border-slate-200">
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span> Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700 font-mono">₱{{ number_format($item->net_pay, 2) }}</td>
                    <td class="px-6 py-4 text-right font-sans">
                        <a href="{{ route('payroll.download-slip', $item->id) }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-[10px] font-bold py-2 px-4 rounded-lg shadow-sm transition-all inline-flex items-center gap-2">
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