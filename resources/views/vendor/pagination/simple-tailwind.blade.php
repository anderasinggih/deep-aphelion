@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-400 bg-white border border-slate-200 cursor-default leading-5 rounded-xl shadow-sm">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-900 bg-white border border-slate-300 leading-5 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-900 bg-white border border-slate-300 leading-5 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-400 bg-white border border-slate-200 cursor-default leading-5 rounded-xl shadow-sm">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
