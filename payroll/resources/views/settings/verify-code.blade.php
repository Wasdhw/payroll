@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <h3 class="font-bold text-slate-800 text-xl mb-2 text-center">Verify Email Address</h3>
        <p class="text-sm text-slate-500 mb-6 text-center">
            A 6-digit verification code has been sent to <strong>{{ session('pending_hr_account')['email'] ?? 'the email' }}</strong>. 
            Please enter it below to create the account.
        </p>

        @if (session('success'))
            <div class="mb-4 bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.verify.submit') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">6-Digit Code</label>
                    <input type="text" name="code" class="w-full rounded-xl border-slate-300 text-base py-3 px-4 focus:ring-teal-500 focus:border-teal-500 text-center tracking-widest text-xl @error('code') border-red-500 bg-red-50 @enderror" placeholder="123456" required autofocus>
                    @error('code') <p class="text-red-500 text-xs mt-1 font-bold text-center">{{ $message }}</p> @enderror
                </div>
                
                <button type="submit" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 rounded-lg transition shadow-md mt-4">
                    Verify & Create Account
                </button>
            </div>
        </form>

        <form action="{{ route('settings.index') }}" method="GET" class="mt-4">
            <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-lg transition">
                Cancel Registration
            </button>
        </form>
    </div>
</div>
@endsection