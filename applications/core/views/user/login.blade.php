<h2>{{ __('feather core::titles.login') }}</h2>

{{ Form::open(urldecode(URI::full()), 'post') }}

<fieldset>

	<dl>

		<dt>{{ Form::label('username', __('feather core::common.username')) }}</dt>
		<dd>
			{{ Form::text('username', Input::old('username')) }}

			@error('username')
		</dd>

		<dt>{{ Form::label('password', __('feather core::common.password')) }}</dt>
		<dd>
			{{ Form::password('password') }}

			@error('password')
		</dd>
		
		<dt>{{ Form::label('remember', __('feather core::login.labels.remember.title')) }}</dt>
		<dd>
			<div class="decider">
				<label for="remember_yes">
					<span class="yes">
						{{ Form::radio('remember', 1, Input::old('remember', false), array('id' => 'remember_yes')) }} Yes
					</span>
				</label>
				<label for="remember_no">
					<span class="no">
						{{ Form::radio('remember', 0, !Input::old('remember', false), array('id' => 'remember_no')) }} No
					</span>
				</label>
			</div>

			<div class="description">
				{{ __('feather core::login.labels.remember.helper') }}
			</div>
		</dd>

	</dl>
	
</fieldset>

<fieldset class="text-center">
	{{ Form::submit(__('feather core::common.login'), array('class' => 'btn btn-primary btn-big')) }}&nbsp;&nbsp;
	{{ HTML::link_to_route('register', __('feather core::login.labels.register.title'), null, array('class' => 'btn large')) }}
</fieldset>

{{ Form::token() . Form::close() }}