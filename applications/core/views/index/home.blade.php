@if(empty($places))

	<h1>There's nothing here</h1>

	<p>
		You may not have the correct permissions to view any places or discussions. Or <em>someone</em> is a little lazy and hasn't created any yet.
	</p>
@else

	<ul class="discussions">

	@foreach($places as $place)

		<li class="place">
			<h1>
				@event('view.before: place.title')

				{{ HTML::link_to_route('place', $place->name, array($place->id, $place->slug)) }}

				@event('view.after: place.title')

				@event('view.before: place.discussion.counter')

				<span class="discussion-counter" title="Discussions">{{ number_format($place->total->discussions) }}</span>

				@event('view.after: place.discussion.counter')
			</h1>
			
			@include('feather core::place.partial.children')
		</li>

		@if(count($place->discussions))

			@foreach($place->discussions as $discussion)

				@include('feather core::place.partial.discussion')

			@endforeach

			<li class="more">
				<a href="{{ URL::to_route('place', array($place->id, $place->slug)) }}" class="btn btn-soft btn-fat">See {{ number_format($place->total->remaining) }} more discussion{{ $place->total->remaining == 1 ? null : 's' }} <span>&rarr;</span></a>
			</li>

		@else

			<li class="empty">

				<h3>{{ __('feather core::place.no_discussions', array('place' => $place->name)) }}</h3>
				
			</li>

		@endif

	@endforeach

	</ul>

@endif