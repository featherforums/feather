<h2>{{ $error['title'] }}</h2>

<div class="alert alert-{{ $error['type'] }}">
	{{ $error['error'] }}
</div>

{{ HTML::Link_to_route('feather', '&larr; Back to places') }}