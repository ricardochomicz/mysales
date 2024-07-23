@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Link da Página Anterior --}}
            <li class="page-item" @class(['disabled' => $paginator->onFirstPage()]) aria-disabled="{{ $paginator->onFirstPage() }}" aria-label="@lang('pagination.previous')">
                <button type="button" class="page-link" wire:click="previousPage" @if($paginator->onFirstPage()) disabled @endif rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</button>
            </li>

            {{-- Elementos da Paginação --}}
            @foreach ($elements as $element)
                {{-- "Três Pontos" Separador --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array de Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item" @class(['active' => $page == $paginator->currentPage()]) aria-current="{{ $page == $paginator->currentPage() ? 'page' : '' }}">
                            <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Link da Próxima Página --}}
            <li class="page-item" @class(['disabled' => !$paginator->hasMorePages()]) aria-disabled="{{ !$paginator->hasMorePages() }}" aria-label="@lang('pagination.next')">
                <button type="button" class="page-link" wire:click="nextPage" @if(!$paginator->hasMorePages()) disabled @endif rel="next" aria-label="@lang('pagination.next')">&rsaquo;</button>
            </li>
        </ul>
    </nav>
@endif
