<h2>{{ __('feather core::titles.register') }}</h2>

{{ Form::open(URI::full(), 'post') }}

<div class="halves group">

	<div class="half-left">

		<fieldset>

			<dl>

				<dt>{{ Form::label('username', __('feather core::common.username')) }}</dt>
				<dd>
					{{ Form::text('username', Input::old('username'), array('autocomplete' => 'off', 'tabindex' => 1)) }}

					@error('username')
				</dd>

				<dt>{{ Form::label('email', __('feather core::common.email')) }}</dt>
				<dd>
					{{ Form::text('email', Input::old('email'), array('autocomplete' => 'off', 'tabindex' => 1)) }}

					@error('email')
				</dd>

				@event('view: before register.rules')

				@if(Feather\Config::get('feather: db.registration.rules', 0))

				<dt></dt>
				<dd>
					<label for="rules">
						{{ Form::checkbox('rules', 1, Input::had('rules'), array('id' => 'rules')) }}&nbsp;

						<h4>{{ __('feather core::register.labels.rules.helper', array('link' => HTML::link_to_route('rules', 'community rules', array(), array('class' => 'font-bold popup-ui')))) }}</h4>
					</label>
					
					@error('rules')
				</dd>

				@endif

				@event('view: after register.rules')

			</dl>

		</fieldset>

	</div>

	<div class="half-right">

		<fieldset>

			<dl>
				<dt>{{ Form::label('password', __('feather core::common.password')) }}</dt>
				<dd>
					{{ Form::password('password', array('autocomplete' => 'off', 'tabindex' => 1)) }}

					@error('password')
				</dd>

				<dt>{{ Form::label('password_confirmation', __('feather core::register.labels.password_confirmation.title')) }}</dt>
				<dd>
					{{ Form::password('password_confirmation', array('autocomplete' => 'off', 'tabindex' => 1)) }}

					@error('password_confirmation')
				</dd>
			</dl>

		</fieldset>

	</div>

</div>

<fieldset class="text-center">
	{{ Form::submit(__('feather core::common.register'), array('class' => 'btn btn-primary btn-big', 'tabindex' => 2)) }}
</fieldset>

{{ Form::token() . Form::close() }}