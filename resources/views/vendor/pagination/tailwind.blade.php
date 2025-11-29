@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile Simple Pagination (Hidden on larger screens if we want full pagination everywhere, but let's keep it for very small screens or replace it) --}}
        {{-- Actually, user wants mobile view to show pagination. The default tailwind view hides the full links on mobile. 
             Let's make the full pagination visible on all screens, but maybe simplify it or make it scrollable if too many links.
             For now, let's just use the full pagination block and make it responsive. --}}
        
        <div class="flex-1 flex items-center justify-between">
            <div class="hidden sm:block">
                <p class="text-xs text-slate-600 leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-bold text-slate-900">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-bold text-slate-900">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-bold text-slate-900">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div class="flex-1 flex justify-center sm:justify-end">
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-2 py-1.5 text-xs sm:px-2 sm:py-2 sm:text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-default rounded-l-md leading-4 sm:leading-5" aria-hidden="true">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-1.5 text-xs sm:px-2 sm:py-2 sm:text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-l-md leading-4 sm:leading-5 hover:text-slate-400 focus:z-10 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 active:bg-slate-100 active:text-slate-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 -ml-px text-xs sm:text-sm font-medium text-slate-700 bg-white border border-slate-200 cursor-default leading-4 sm:leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 -ml-px text-xs sm:text-sm font-bold text-white bg-slate-900 border border-slate-900 cursor-default leading-4 sm:leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 -ml-px text-xs sm:text-sm font-medium text-slate-700 bg-white border border-slate-200 leading-4 sm:leading-5 hover:text-slate-500 focus:z-10 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 active:bg-slate-100 active:text-slate-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-1.5 sm:px-2 sm:py-2 -ml-px text-xs sm:text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-r-md leading-4 sm:leading-5 hover:text-slate-400 focus:z-10 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 active:bg-slate-100 active:text-slate-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-2 py-1.5 sm:px-2 sm:py-2 -ml-px text-xs sm:text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-default rounded-r-md leading-4 sm:leading-5" aria-hidden="true">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
