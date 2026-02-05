@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2 class="font-bold text-slate-700 text-lg">Attendance & Incentives</h2>
        <div class="flex items-center gap-3">
            <button class="btn-secondary !w-fit px-4 text-xs">📅 Export Log</button>
            <button class="btn-action !w-fit px-4 text-xs">➕ Log Attendance</button>
        </div>
    </header>

    <div class="p-8">
        <div class="table-container">
            <table class="w-full text-left">
                <thead class="table-head">
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
                    @foreach($attendance as $record)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-700 text-sm">{{ $record['name'] }}</p>
                            <p class="text-[10px] font-mono text-teal-600">{{ $record['id'] }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge-active">Present</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $record['overtime'] }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">₱{{ number_format($record['allowance'], 2) }}</td>
                        <td class="px-6 py-4 text-sm text-teal-600 font-bold">₱{{ number_format($record['incentive'], 2) }}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-xs font-bold text-teal-700 uppercase">Process</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection