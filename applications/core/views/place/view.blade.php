<div class="place">
	<h2>
		{{ HTML::link_to_route('place', $place->name, array($place->id, $place->slug)) }}

		<span class="discussion-counter" title="Discussions">{{ number_format($place->total->discussions) }}</span>
	</h2>

	@include('feather::place.partial.children')

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

			@include('feather::place.partial.discussion')

		@endforeach

		{{ $place->pagination }}

	@else

		<li class="empty">

			<div class="alert">
				{{ __('feather::place.no_discussions', array('place' => $place->name)) }}
			</div>
			
		</li>

	@endif

</ul>