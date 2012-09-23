@if($place->parent)

	@assign($depth, 1)

	@assign($difference, 0 - $place->depth)

	<ul class="children">

		@foreach($place->children as $key => $child)
			
			{{-- Only show the children up to the 5th level, 3 levels of dropdown. --}}
			@if($child->depth + $difference < 5)

				{{ ($child->depth + $difference > $depth) ? '<ul class="nested">' : null }}

				{{ ($child->depth + $difference < $depth) ? str_repeat('</ul></li>', ($depth - ($child->depth + $difference))) : null }}

				<li{{ ($child->parent and $child->depth + $difference == 1) ? ' class="parent-child"' : null }}>
					{{ HTML::link_to_route('place', $child->name, array($child->id, $child->slug)) }}

					@event('view.before: place.discussion.counter')

					<span class="discussion-counter" title="Discussions">{{ number_format($child->total->discussions) }}</span>

					@event('view.after: place.discussion.counter')

					{{ ($child->parent and ($child->depth + $difference) == 1) ? '<span class="dropdown-arrow"></span>' : null }}

				{{ (!$child->parent and $key < count($place->children) - 1) ? '</li>' : null }}

			@endif

		@endforeach

	{{ str_repeat('</li></ul>', $depth) }}
@endif