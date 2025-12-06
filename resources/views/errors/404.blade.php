@extends('layouts.app')

@section('title', 'Page Not Found — PhoneBD')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto">
        <p class="text-sm font-bold text-blue-600 uppercase tracking-widest">404 Error</p>
        <h1 class="mt-2 text-2xl font-extrabold text-slate-900 tracking-tight sm:text-3xl md:text-4xl">
            Page not found.
        </h1>
        <p class="mt-3 text-sm md:text-base text-slate-500">
            Sorry, we couldn't find the page you're looking for. It might have been moved, deleted, or you may have mistyped the address.
        </p>

        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Go back home
            </a>
            <a href="{{ route('search.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-sm text-slate-700 bg-white hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Search Devices
            </a>
        </div>
    </div>
    
    <div class="mt-8 w-full max-w-md">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-lg blur opacity-50 group-hover:opacity-75 transition duration-200"></div>
            <form action="{{ route('search.index') }}" method="GET" class="relative bg-white rounded-lg shadow-sm">
                <input 
                    type="text" 
                    name="q" 
                    class="block w-full rounded-lg border-slate-200 pl-4 pr-10 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900 placeholder:text-slate-400" 
                    placeholder="Try searching for a phone model..."
                    autocomplete="off"
                >
                <div class="absolute inset-y-0 right-0 flex py-2 pr-2">
                    <button type="submit" class="p-1 px-2 rounded-md text-slate-400 hover:text-blue-600 hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
