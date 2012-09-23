<script type="text/javascript">
	var RecaptchaOptions = {
		theme : 'custom',
		custom_theme_widget : 'recaptcha_widget'
	}
</script>

<dt>{{ Form::label('recaptcha_response_field', 'Security Confirmation') }}</dt>
<dd>
	<div id="recaptcha_widget" style="display:none">

		<div id="recaptcha_image"></div>

		<div id="recaptcha_info">
			<span class="recaptcha_only_if_image">Enter the words above:</span>
			<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>
		</div>

		{{ Form::text('recaptcha_response_field', null, array('tabindex' => 2)) }}

		@error('recaptcha_response_field')

		<ul class="join-recaptcha">
			<li class="refresh tooltip-ui" title="Get another image.">
				<a href="javascript:Recaptcha.reload()"></a>
			</li>
			<li class="audio tooltip-ui recaptcha_only_if_image" title="Listen to the words.">
				<a href="javascript:Recaptcha.switch_type('audio')"></a>
			</li>
			<li class="image tooltip-ui recaptcha_only_if_audio" title="See the words.">
				<a href="javascript:Recaptcha.switch_type('image')"></a>
			</li>
			<li class="help tooltip-ui" title="Need help?">
				<a href="javascript:Recaptcha.showhelp()"></a>
			</li>
		</ul>

	</div>

	{{ HTML::script('http://www.google.com/recaptcha/api/challenge?k=' . Feather\Config::get('gear: recaptcha keys.public')) }}
</dd>