{{ $preview }}

<h2>{{ __('feather core::titles.edit_discussion', array('discussion' => $discussion->title)) }}</h2>

<div class="manage-discussion">
	{{ Form::open(null, 'put') }}

	<fieldset>

		<dl>

			<dt>{{ Form::label('place', __('feather core::discussion.labels.place.title')) }}</dt>
			<dd>
				<select name="place" id="place">
					{{ $places }}
				</select>

				<div class="description">
					{{ __('feather core::discussion.labels.place.helper') }}
				</div>

				@error('place')
			</dd>

			@if($discussion->draft)
			<dt>{{ Form::label('title', __('feather core::discussion.labels.title.title')) }}</dt>
			<dd>
				{{ Form::text('title', Input::old('title', $discussion->title)) }}

				@error('title')
			</dd>
			@endif

			<dt>{{ Form::label('participants', __('feather core::discussion.labels.participants.title')) }}</dt>
			<dd>
				<div class="discussion-participants">
					{{ Form::text('participants', Input::old('participants', $participants), array('autocomplete' => 'off')) }}

					<div class="autocomplete"></div>
				</div>

				<div class="description">
					{{ __('feather core::discussion.labels.participants.helper') }}
				</div>

				<div class="participants">
					<span class="anyone label label-inverse">{{ __('feather core::discussion.anyone_can_participate') }}</span>
				</div>
			</dd>

		</dl>

		<div class="discussion-textarea">
			@event('view: before discussion.body')

			{{ Form::textarea('body', Input::old('body', $discussion->body)) }}

			@event('view: after discussion.body')
		</div>

		@error('body')
		
	</fieldset>

	<fieldset>
		<div class="halves">
			<div class="half-left">

				@if($discussion->draft)

					{{ Form::submit(__('feather core::discussion.start_discussion'), array('name' => 'start', 'class' => 'btn btn-primary btn-big')) }}

					{{ Form::submit(__('feather core::discussion.save_draft'), array('name' => 'draft', 'class' => 'btn btn-inverse tooltip-ui-right', 'title' => __('feather core::discussion.labels.save_draft.helper'))) }}

				@else

					{{ Form::submit(__('feather core::discussion.save_discussion'), array('name' => 'save', 'class' => 'btn btn-primary btn-big')) }}

				@endif

			</div>

			<div class="half-right text-right">
				{{ Form::submit(__('feather core::discussion.preview_discussion'), array('name' => 'preview', 'class' => 'btn btn-inverse')) }}
			</div>
		</div>
	</fieldset>

	{{ Form::token() . Form::close() }}
</div>