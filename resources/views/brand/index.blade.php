@extends('layouts.app', [
    'title' => 'All Mobile Brands in Bangladesh',
    'meta_description' => 'Browse all mobile phone brands available in Bangladesh. Find the latest prices and specs for Samsung, Apple, Xiaomi, Realme, and more.',
])

@section('og_image', asset('images/og-default.jpg'))
@section('og_type', 'website')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "{{ route('home') }}"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Brands"
  }]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "All Mobile Brands",
  "description": "Browse all mobile phone brands available in Bangladesh.",
  "url": "{{ route('brands.index') }}"
}
</script>
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex mb-3 text-sm text-slate-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 font-medium text-slate-900 md:ml-2">All Brands</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-sm shadow-sm border border-slate-200 p-3">
        <div class="flex items-center justify-between mb-3">
            <h1 class="text-lg md:text-2xl font-bold text-slate-900 tracking-tight">All Brands</h1>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($brands as $brand)
                <a href="{{ route('brands.show', $brand->slug) }}"
                    class="group flex flex-col items-center justify-center bg-white border border-slate-100 rounded-sm p-3 hover:border-slate-900 hover:shadow-md transition-all duration-200">
                    <div
                        class="w-16 h-16 mb-4 relative grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                        @if ($brand->image)
                            <img src="{{ asset('storage/' . $brand->image) }}" class="w-full h-full object-contain"
                                alt="{{ $brand->name }}">
                        @else
                            <span
                                class="text-3xl font-bold text-slate-300 group-hover:text-slate-500">{{ substr($brand->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <span
                        class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors uppercase tracking-wide text-center">{{ $brand->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
