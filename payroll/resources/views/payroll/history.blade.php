@extends('layouts.app')

@section('content')
<div class="px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Payroll Batch History</h2>
            <p class="text-xs text-slate-500">View and manage previously processed and finalized payrolls.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Batch ID</th>
                    <th class="px-6 py-4">Pay Period</th>
                    <th class="px-6 py-4">Total Net Payout</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4">Date Finalized</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($batches as $batch)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-teal-700">{{ $batch->batch_id }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ date('M d', strtotime($batch->period_start)) }} — {{ date('M d, Y', strtotime($batch->period_end)) }}
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700 font-mono">
                        ₱{{ number_format($batch->total_net, 2) }}
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        @if($batch->status === 'Paid')
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></span>
                                Paid
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Pending
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $batch->status === 'Paid' ? $batch->updated_at->format('M d, Y') : '---' }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('payroll.show', $batch->id) }}" class="text-xs font-bold {{ $batch->status === 'Paid' ? 'text-teal-600 border-teal-200 hover:bg-teal-600' : 'text-amber-600 border-amber-200 hover:bg-amber-600' }} hover:text-white px-4 py-2 rounded-lg border transition-all inline-flex items-center gap-2 shadow-sm">
                            <span>📄</span> {{ $batch->status === 'Paid' ? 'View Slips' : 'Review & Finalize' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <div class="text-4xl mb-3 opacity-20">📜</div>
                        <p class="font-bold text-sm text-slate-500">No payroll history found.</p>
                        <p class="text-xs mt-1">Finalize a batch in the Process tab to start your history.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection