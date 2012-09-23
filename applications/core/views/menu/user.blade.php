<ul>
	<li class="link attn has-options">
		{{ HTML::link_to_route('profile', $feather->user->username, array($feather->user->slug)) }}

		<ul>
			<li>
				{{ HTML::link('something', 'Profile') }}
			</li>
			<li>
				{{ HTML::link('something', 'Settings') }}
			</li>
			<li>
				{{ HTML::link('something', 'My Discussions') }}
			</li>
			<li>
				{{ HTML::link('something', 'Watched Discussions') }}
			</li>
		</ul>
	</li>

	@if(Feather\Auth::is('admin'))
	<li class="link">
		<a href="{{ URL::to('') }}">Admin Dashboard</a>
	</li>
	@endif

	<li class="link">
		<a href="{{ URL::to('admin/dashboard') }}">You have 0 private conversations</a>
	</li>
	
	<li class="link">{{ HTML::link(URL::to_route('logout') . '?return=' . URI::current(), 'Sign Out') }}</li>

</ul>