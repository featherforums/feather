<ul class="nav nav-list">
	<li class="nav-header">Contents</li>

	<li>
		<ul class="nav nav-list">
			<li class="nav-header">Installation</li>

			<li class="divider"></li>

			<li class="nav-header">Authentication</li>

			<li>
				<ul class="nav nav-list">
					<li class="nav-header">Single Sign On</li>

					<li class="{{ navigation('auth/single-sign-on/introduction') }}">
						{{ HTML::link_to_route('docs.page', 'Introduction', array('auth/single-sign-on/introduction')) }}
					</li>

					<li class="{{ navigation('auth/single-sign-on/oneconnect') }}">
						{{ HTML::link_to_route('docs.page', 'OneConnect', array('auth/single-sign-on/oneconnect')) }}
					</li>

					<li class="{{ navigation('auth/single-sign-on/harmonize') }}">
						{{ HTML::link_to_route('docs.page', 'Harmonize', array('auth/single-sign-on/harmonize')) }}
					</li>
				</ul>
			</li>

			<li class="divider"></li>

			<li class="{{ navigation('migrations') }}">
				{{ HTML::link_to_route('docs.page', 'Migrations', array('migrations')) }}
			</li>

			<li class="divider"></li>

			<li class="nav-header">Gears</li>

			<li class="{{ navigation('gears/events') }}">
				{{ HTML::link_to_route('docs.page', 'Events', array('gears/events')) }}
			</li>
		</ul>
	</li>
</ul>