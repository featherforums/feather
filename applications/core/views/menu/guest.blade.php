<ul>
	<li class="dark link">{{ HTML::link(URL::to_route('login') . '?return=' . URI::current(), 'Sign In') }}</li>
	<li class="dark link">{{ HTML::link(URL::to_route('register') . '?return=' . URI::current(), 'Create Account') }}</li>
</ul>