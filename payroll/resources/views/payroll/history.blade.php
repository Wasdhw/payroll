@extends('layouts.app')

@section('content')
<div class="px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Payroll Batch History</h2>
            <p class="text-xs text-slate-500">View and manage previously processed payrolls.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Batch ID</th>
                    <th class="px-6 py-4">Pay Period</th>
                    <th class="px-6 py-4">Total Gross Payout</th>
                    <th class="px-6 py-4">Total Net Payout</th>
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
                    <td class="px-6 py-4 text-sm font-medium text-slate-500">₱{{ number_format($batch->total_gross, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700">₱{{ number_format($batch->total_net, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('payroll.show', $batch->id) }}" class="text-xs font-bold text-teal-600 hover:text-white hover:bg-teal-600 px-4 py-2 rounded-lg border border-teal-200 transition-all">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        <p>No payroll history found. Save a batch in the Process tab to see it here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection