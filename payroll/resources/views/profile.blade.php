@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6 lg:px-8">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Account Settings</h2>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             x-transition
             class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="bg-emerald-100 p-1 rounded-full">✅</span>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid gap-8">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-700">Profile Information</h3>
            </div>
            
            <div class="p-8">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full border-slate-300 rounded-xl text-sm px-4 py-3 shadow-sm focus:ring-teal-500 focus:border-teal-500 transition-all hover:border-teal-300">
                            @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full border-slate-300 rounded-xl text-sm px-4 py-3 shadow-sm focus:ring-teal-500 focus:border-teal-500 transition-all hover:border-teal-300">
                            @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-md hover:shadow-lg active:translate-y-0.5">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-700">Security</h3>
            </div>            
            <div class="p-8">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Current Password</label>
                            <input type="password" name="current_password" 
                                   class="w-full border-slate-300 rounded-xl text-sm px-4 py-3 shadow-sm focus:ring-teal-500 focus:border-teal-500 transition-all hover:border-teal-300">
                            @error('current_password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New Password</label>
                            <input type="password" name="password" 
                                   class="w-full border-slate-300 rounded-xl text-sm px-4 py-3 shadow-sm focus:ring-teal-500 focus:border-teal-500 transition-all hover:border-teal-300">
                            @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full border-slate-300 rounded-xl text-sm px-4 py-3 shadow-sm focus:ring-teal-500 focus:border-teal-500 transition-all hover:border-teal-300">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-all shadow-md hover:shadow-lg active:translate-y-0.5">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection