@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-10 px-4">
    
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800">Edit User Account</h2>
        <a href="{{ route('settings.index') }}" class="text-sm text-slate-500 hover:text-teal-700">← Back to Settings</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('settings.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Full Name</label> 
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-slate-300 text-base py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-slate-300 text-base py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <div class="border-t border-slate-100 my-4"></div>
                
                <div>
                    <label class="block text-xs font-bold text-teal-600 uppercase mb-1">Change Password (Optional)</label>
                    <p class="text-xs text-slate-400 mb-2"> Leave blank if you don't want to change it.</p>
                    <br>
                    <input type="password" name="password" placeholder="New Password" class="w-full rounded-lg border-slate-300 text-base py-2 px-3 focus:ring-teal-500 focus:border-teal-500 mb-2">
                    <br>
                    <input type="password" name="password_confirmation" placeholder="Confirm New Password" class="w-full rounded-lg border-slate-300 text-base py-2 px-3 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('settings.index') }}" class="px-4 py-2 text-sm font-bold text-slate-500 hover:bg-slate-50 rounded-lg">Cancel</a>
                    <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection