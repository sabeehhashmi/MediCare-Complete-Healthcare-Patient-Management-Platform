{{-- resources/views/front/partials/pagination.blade.php --}}

@if ($paginator->hasPages())
<div class="pagination-area wow animate fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
    {{-- Previous Page Link --}}
    <div class="paginations-button">
        @if ($paginator->onFirstPage())
            <a href="javascript:void(0);" class="disabled" aria-disabled="true">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M7.86133 9.28516C7.14704 7.49944 3.57561 5.71373 1.43276 4.99944C3.57561 4.28516 6.7899 3.21373 7.86133 0.713728" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
                Prev
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M7.86133 9.28516C7.14704 7.49944 3.57561 5.71373 1.43276 4.99944C3.57561 4.28516 6.7899 3.21373 7.86133 0.713728" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
                Prev
            </a>
        @endif
    </div>

    {{-- Pagination Elements --}}
    <ul class="paginations">
        {{-- Previous Page Link (as number) - Optional --}}
        @if($paginator->currentPage() > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}">01</a>
            </li>
            @if($paginator->currentPage() > 2)
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            @endif
        @endif

        {{-- Current Page --}}
        <li class="page-item active" aria-current="page">
            <a class="page-link" href="javascript:void(0);">{{ str_pad($paginator->currentPage(), 2, '0', STR_PAD_LEFT) }}</a>
        </li>

        {{-- Next Page Link (as number) --}}
        @if($paginator->hasMorePages())
            @if($paginator->currentPage() + 1 < $paginator->lastPage())
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            @endif
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ str_pad($paginator->lastPage(), 2, '0', STR_PAD_LEFT) }}</a>
            </li>
        @endif
    </ul>

    {{-- Next Page Link --}}
    <div class="paginations-button">
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                Next
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M1.42969 9.28613C2.14397 7.50042 5.7154 5.7147 7.85826 5.00042C5.7154 4.28613 2.50112 3.21471 1.42969 0.714705" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
            </a>
        @else
            <a href="javascript:void(0);" class="disabled" aria-disabled="true">
                Next
                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M1.42969 9.28613C2.14397 7.50042 5.7154 5.7147 7.85826 5.00042C5.7154 4.28613 2.50112 3.21471 1.42969 0.714705" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
            </a>
        @endif
    </div>
</div>
@endif