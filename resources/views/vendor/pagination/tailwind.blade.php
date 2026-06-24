@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        <!-- Mobile View -->
        <div class="flex items-center justify-center gap-2.5 flex-1 sm:hidden select-none">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center justify-center w-10 h-10 text-xs font-semibold text-slate-300 bg-slate-50/50 border border-slate-150 rounded-xl cursor-default">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center justify-center w-10 h-10 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            @endif

            <span class="px-4 py-2 bg-slate-100/50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-700 min-w-[90px] text-center shadow-inner">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center justify-center w-10 h-10 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="relative inline-flex items-center justify-center w-10 h-10 text-xs font-semibold text-slate-300 bg-slate-50/50 border border-slate-150 rounded-xl cursor-default">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </div>

        <!-- Desktop View -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
            <div>
                <span class="relative z-0 inline-flex items-center gap-1.5">
                    {{-- First Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-300 bg-slate-50/50 border border-slate-150 rounded-xl cursor-default">
                            <i class="fa-solid fa-angles-left text-[10px]"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->url(1) }}" rel="first" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300" title="First Page">
                            <i class="fa-solid fa-angles-left text-[10px]"></i>
                        </a>
                    @endif

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-300 bg-slate-50/50 border border-slate-150 rounded-xl cursor-default">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300" title="Previous Page">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-400 cursor-default">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-extrabold text-white bg-primary-500 border border-primary-500 rounded-xl shadow-md shadow-primary-500/20 cursor-default transition-all duration-300">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300" title="Next Page">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    @else
                        <span aria-disabled="true" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-300 bg-slate-50/50 border border-slate-150 rounded-xl cursor-default">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </span>
                    @endif

                    {{-- Last Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="last" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 active:scale-95 transition-all duration-300" title="Last Page">
                            <i class="fa-solid fa-angles-right text-[10px]"></i>
                        </a>
                    @else
                        <span aria-disabled="true" class="relative inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-300 bg-slate-50/50 border border-slate-150 rounded-xl cursor-default">
                            <i class="fa-solid fa-angles-right text-[10px]"></i>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
