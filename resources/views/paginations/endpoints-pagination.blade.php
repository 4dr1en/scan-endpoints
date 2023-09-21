<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation">
            <span>
                @if ($paginator->onFirstPage())
                    <span>Previous</span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev">Previous</button>
                @endif
            </span>

			<span class="grid container">
				@foreach ($elements as $element)
					{{-- "Three Dots" Separator --}}
					@if (is_string($element))
						<span aria-disabled="true">
							<span class="">{{ $element }}</span>
						</span>
					@endif

					{{-- Array Of Links --}}
					@if (is_array($element))
						@foreach ($element as $page => $url)
							<span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
								@if ($page == $paginator->currentPage())
									<span aria-current="page">
										<span class="">{{ $page }}</span>
									</span>
								@else
									<button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
										{{ $page }}
									</button>
								@endif
							</span>
						@endforeach
					@endif
				@endforeach
			</span>
 
            <span>
                @if ($paginator->onLastPage())
                    <span>Next</span>
                @else
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next">Next</button>
                @endif
            </span>
        </nav>
    @endif
</div>