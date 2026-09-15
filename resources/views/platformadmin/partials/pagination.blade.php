@if ($paginator->hasPages())
    <nav class="platform-arrow-pagination" role="navigation" aria-label="Pagination">
        <div class="pagination-arrows-wrapper">
            {{-- Tombol Pertama (First Page) jika lebih dari 2 halaman --}}
            @if ($paginator->lastPage() > 2)
                @if ($paginator->onFirstPage())
                    <span class="btn-pag-arrow disabled" aria-disabled="true" title="Halaman Pertama">
                        <i class="fas fa-angles-left"></i>
                    </span>
                @else
                    <a href="{{ $paginator->url(1) }}" class="btn-pag-arrow" title="Halaman Pertama">
                        <i class="fas fa-angles-left"></i>
                    </a>
                @endif
            @endif

            {{-- Tombol Sebelumnya (Previous Page) --}}
            @if ($paginator->onFirstPage())
                <span class="btn-pag-arrow disabled" aria-disabled="true" title="Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn-pag-arrow" title="Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Tombol Berikutnya (Next Page) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn-pag-arrow" title="Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="btn-pag-arrow disabled" aria-disabled="true" title="Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif

            {{-- Tombol Terakhir (Last Page) jika lebih dari 2 halaman --}}
            @if ($paginator->lastPage() > 2)
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="btn-pag-arrow" title="Halaman Terakhir">
                        <i class="fas fa-angles-right"></i>
                    </a>
                @else
                    <span class="btn-pag-arrow disabled" aria-disabled="true" title="Halaman Terakhir">
                        <i class="fas fa-angles-right"></i>
                    </span>
                @endif
            @endif
        </div>
    </nav>
@endif
