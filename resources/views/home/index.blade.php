@extends('layouts.app', [
    'title' => 'Mobile Specs & Prices',
    'meta_description' => 'Find the latest mobile prices, full specifications, reviews, and comparisons in Bangladesh. PhoneBD delivers fast, accurate, and user-friendly mobile information to help you choose the right device.',
])

@section('og_image', asset('images/fallback-img.png'))
@section('og_type', 'website')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "PhoneBD",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "sameAs": [
    "https://www.facebook.com/phonebd",
    "https://twitter.com/phonebd"
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "PhoneBD",
  "url": "{{ url('/') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ route('search.index') }}?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
@endpush


@section('content')

    <x-home.categories :categories="$categories" />

    <x-home.browse-by-price />
    
    <x-home.latest-phones :latest-phones="$latestPhones" />

    <x-home.popular-brands :brands="$brands" />

    <x-home.dynamic-pages :dynamic-pages="$dynamicPages" />

    <x-home.official-phones :official-phones="$officialPhones" />

    <x-home.unofficial-phones :unofficial-phones="$unofficialPhones" />

    <x-home.upcoming-phones :upcoming-phones="$upcomingPhones" />

@endsection
