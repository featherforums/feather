{{-- TODO: Make this theme related as it contains theme things, like tooltips. --}}
<li class="discussion group">
	<div class="place">
		{{ HTML::link_to_route('place', $discussion->place->name, array($discussion->place->id, $discussion->place->slug), array('title' => $discussion->place->description, 'class' => 'btn btn-soft tooltip-ui-places')) }}
	</div>

	<div class="meta">

		@event('view.before: place.discussion.title')

		@if($discussion->private)
			<span class="label label-private">Private</span>
		@endif

		@if($discussion->draft)
			<span class="label label-draft">Draft</span>
		@endif

		{{ HTML::link_to_route('discussion', $discussion->title, array($discussion->id, $discussion->slug, null), array('class' => 'title')) }}

		@event('view.after: place.discussion.title')

		@event('view.before: place.discussion.meta')

		<p>
		@if($discussion->recent)
			Last reply by <a href="">Dayle</a>, on <span title="24th June, 2012 at 6:45am">23th June</span>
		@elseif($discussion->author)
			Started by {{ HTML::link_to_route('profile', $discussion->author->username, array($discussion->author->slug)) }}, <a href="{{ URL::to_route('discussion.latest', array($discussion->id, $discussion->slug)) }}">{{ Feather\Date::meta($discussion->created_at) }}</a>
		@endif
		</p>

		<div class="options">
			@if(Feather\Auth::activated())
				{{ HTML::link('', '', array('class' => 'watch tooltip-ui', 'title' => 'Watch Discussion')) }}
			@endif

			@if(Feather\Auth::is(array('admin', 'moderator')))
			{{ HTML::link('', '', array('class' => 'tools tooltip-ui', 'title' => 'Discussion Tools')) }}
			@endif
		</div>

		@event('view.after: place.discussion.meta')
	</div>

	<div class="stats">
		
		@event('view.before: place.discussion.stats')

		<div class="views">
			<span class="title">Views</span>
			<span class="number" title="{{ $discussion->views }} views">{{ $discussion->short_views }}</span>
		</div>
		<div class="replies">
			<span class="title">Replies</span>
			<span class="number" title="{{ $discussion->replies }} replies">{{ $discussion->short_replies }}</span>
		</div>
		<div class="watchers">
			<span class="title">Watchers</span>
			<span title="0 watchers" class="number">0</span>
		</div>

		@event('view.after: place.discussion.stats')

	</div>
</li>