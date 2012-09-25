{{ $preview }}

<h2>{{ __('feather core::titles.start_discussion') }}</h2>

<div class="manage-discussion">
	{{ Form::open(null, 'post') }}

	<fieldset>

		<dl>

			<dt>{{ Form::label('place', __('feather core::discussion.labels.place.title')) }}</dt>
			<dd>
				<select name="place" id="place">
					<option value="">Please select one</option>
					{{ $places }}
				</select>

				<div class="description">
					{{ __('feather core::discussion.labels.place.helper') }}
				</div>

				@error('place')
			</dd>

			<dt>{{ Form::label('title', __('feather core::discussion.labels.title.title')) }}</dt>
			<dd>
				{{ Form::text('title', Input::old('title')) }}

				@error('title')
			</dd>

			<dt>{{ Form::label('participants', __('feather core::discussion.labels.participants.title')) }}</dt>
			<dd>
				<div class="discussion-participants">
					{{ Form::text('participants', Input::old('participants'), array('autocomplete' => 'off')) }}

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

			{{ Form::textarea('body', Input::old('body')) }}

			@event('view: after discussion.body')
		</div>

		@error('body')
		
	</fieldset>

	<fieldset>
		<div class="halves">
			<div class="half-left">
				{{ Form::submit(__('feather core::discussion.start_discussion'), array('name' => 'start', 'class' => 'btn btn-primary btn-big')) }}
				{{ Form::submit(__('feather core::discussion.save_draft'), array('name' => 'draft', 'class' => 'btn btn-inverse tooltip-ui-right', 'title' => __('feather core::discussion.labels.save_draft.helper'))) }}
			</div>

			<div class="half-right text-right">
				{{ Form::submit(__('feather core::discussion.preview_discussion'), array('name' => 'preview', 'class' => 'btn btn-inverse')) }}
			</div>
		</div>
	</fieldset>

	{{ Form::token() . Form::close() }}
</div>