<div class="place">
	<h1>
		{{ HTML::link_to_route('place', $place->name, array($place->id, $place->slug)) }}

		<span class="discussion-counter" title="Discussions">{{ number_format($place->total->discussions) }}</span>
	</h1>

	@include('feather core::place.partial.children')

	@if($place->description)
		<div class="place-description">
			{{ $place->description }}
		</div>
	@endif
</div>

<ul class="discussions">

	@if(count($place->discussions))

		<li class="extra"><h3>Discussions</h3></li>
	
		@foreach($place->discussions as $discussion)

			@include('feather core::place.partial.discussion')

		@endforeach

		{{ $place->pagination }}

	@else

		<li class="empty">
			<h3>{{ __('feather core::place.no_discussions', array('place' => $place->name)) }}</h3>
		</li>

	@endif

</ul>