@extends('layouts.app')

@section('title', 'Internal Server Error — PhoneBD')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto">
        <p class="text-sm font-bold text-red-600 uppercase tracking-widest">500 Error</p>
        <h1 class="mt-2 text-2xl font-extrabold text-slate-900 tracking-tight sm:text-3xl md:text-4xl">
            Server Error.
        </h1>
        <p class="mt-3 text-sm md:text-base text-slate-500">
            Oops! Something went wrong on our end. We're working quickly to fix it. Please try refreshing the page or come back later.
        </p>

        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </button>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-sm text-slate-700 bg-white hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back Home
            </a>
        </div>
    </div>
</div>
@endsection
