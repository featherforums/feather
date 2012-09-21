<!DOCTYPE html>

<html>

	<head>
		<title>
			@event('view: before template.title') {{ $title }} &ndash; {{ $app->title }}
		</title>

		{{ Asset::container('theme')->styles() }}

		<script type="text/javascript">
			var app = { base: '{{ URL::to_route('feather') }}' };
		</script>
	</head>

	<body>

		<div class="container">

			<div class="header group">

				<div class="user">
					@if(Feather\Auth::online())
						@include('feather::menus.user')
					@else
						@include('feather::menus.guest')
					@endif
				</div>

				<div class="logo"><img src="http://feather.dev/bundles/feather/themes/basic/img/heading.png" /></div>

				<ul class="navigation">
					<li class="{{ URI::is('*(/places)?') ? 'selected' : '' }}">
						{{ HTML::link_to_route('feather', 'Discussions') }}
					</li>
					<li class="{{ URI::is('*/activity(/*)?') ? 'selected' : '' }}">
						{{ HTML::link('activity', 'Recent Activity') }}
					</li>
					<li class="{{ URI::is('*/members(/*)?') ? 'selected' : '' }}">
						{{ HTML::link('members', 'Members') }}
					</li>

					@if(Feather\Auth::activated())
					<li class="important">
						{{ HTML::link_to_new_discussion('Start A New Discussion') }}
					</li>
					@endif
				</ul>

			</div>

			<div class="body">
				<ul class="breadcrumbs">
					{{ Feather\Breadcrumbs::trail() }}
				</ul>

				@if($alert)
					<div class="alert alert-{{ $alert->type }}">
						{{ $alert->message }}
					</div>
				@endif

				<div class="content">
					{{ $content }}
				</div>
			</div>

			<div class="footer group">
				<p class="powered">Powered by {{ HTML::link('feather', 'Feather') }}</p>

				<ul class="stats">
					<li>
						<span class="unit">3,457</span>
						Registered Members
					</li>
					<li>
						<span class="unit">2,702</span>
						Discussions
					</li>
				</ul>
			</div>

		</div>

		{{ Asset::container('theme')->scripts() }}

	</body>

</html>