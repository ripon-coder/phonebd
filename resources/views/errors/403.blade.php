@extends('layouts.app')

@section('title', 'Access Denied — PhoneBD')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mb-5">
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight sm:text-3xl md:text-3xl">
            Access Denied
        </h1>
        <p class="mt-3 text-sm md:text-base text-slate-500">
            Sorry, you don't have permission to access this page. If you believe this is an error, please contact support.
        </p>

        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Go back home
            </a>
            <button onclick="history.back()" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-sm text-slate-700 bg-white hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Go Back
            </button>
        </div>
    </div>
</div>
@endsection
