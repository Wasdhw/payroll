@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">System Settings</h2>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-lg">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6">
                <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2">
                    Register New HR Account
                </h3>
                
                <form action="{{ route('settings.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Full Name</label>
                            <input type="text" name="name" class="w-full rounded-xl border-slate-300 text-base py-3 px-4 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                            <input type="email" name="email" class="w-full rounded-xl border-slate-300 text-base py-3 px-4 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Password</label>
                            <input type="password" name="password" class="w-full rounded-xl border-slate-300 text-base py-3 px-4 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-300 text-base py-3 px-4 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <button type="submit" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 rounded-lg transition shadow-md">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-700">Existing HR Accounts</h3>
                </div>
                
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('settings.edit', $user->id) }}" class="text-teal-600 hover:text-teal-800 font-bold text-xs bg-teal-50 px-3 py-1 rounded-md border border-teal-100">
                                    Edit
                                </a>
                                
                                <form action="{{ route('settings.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 px-3 py-1 rounded-md border border-red-100">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($users->isEmpty())
                    <div class="p-6 text-center text-slate-400 text-sm">
                        No other admins found. You are the only one!
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection