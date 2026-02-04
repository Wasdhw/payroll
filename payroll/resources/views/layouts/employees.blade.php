@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2 class="font-bold text-slate-700 text-lg">Employee Management</h2>
        
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" placeholder="Search employees..." class="pl-10 pr-4 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:border-teal-500 w-64">
                <span class="absolute left-3 top-2.5 text-slate-400">🔍</span>
            </div>
            
            <button class="bg-teal-700 hover:bg-teal-800 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition shadow-sm">
                <span>➕</span> Add New
            </button>
        </div>
    </header>

    <div class="p-8">
        
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Employee ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date Hired</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($employees as $emp)
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-teal-800">
                            {{ $emp['id'] }}
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">
                                    {{ substr($emp['name'], 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-700 text-sm">{{ $emp['name'] }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">{{ $emp['department'] }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $emp['position'] }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $emp['date_hired'] }}</td>

                        <td class="px-6 py-4">
                            @if($emp['status'] === 'Active')
                                <span class="badge-active">Active</span>
                            @elseif($emp['status'] === 'On Leave')
                                <span class="badge-leave">On Leave</span>
                            @else
                                <span class="badge-inactive">Inactive</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button class="text-slate-400 hover:text-teal-700 transition font-bold text-xs uppercase">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t border-slate-100 flex justify-between items-center">
                <p class="text-xs text-slate-500">Showing 3 of 3 entries</p>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border border-slate-200 rounded text-xs text-slate-500 hover:bg-slate-50">Prev</button>
                    <button class="px-3 py-1 bg-teal-700 text-white rounded text-xs font-bold">1</button>
                    <button class="px-3 py-1 border border-slate-200 rounded text-xs text-slate-500 hover:bg-slate-50">Next</button>
                </div>
            </div>
        </div>

    </div>
@endsection