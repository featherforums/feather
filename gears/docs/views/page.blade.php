<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Feather Forums - Documentation</title>

		{{ HTML::style('bundles/feather/gears/docs/css/bootstrap.css') }}
		{{ HTML::style('bundles/feather/gears/docs/css/docs.css') }}
	</head>

	<body>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">

					<a class="brand" href="{{ URL::to_route('docs.home') }}">Feather Forums: Documentation</a>

				</div>
			</div>
		</div>

		<div class="container-fluid">

			<div class="row-fluid">

				<div class="span3 well sidebar-nav">
					
					@include('gear: docs contents')

				</div>

				<div class="span9">

					{{ $content }}

				</div>

			</div>

		</div>

		{{ HTML::script('bundles/feather/gears/docs/js/jquery.js') }}
		{{ HTML::script('bundles/feather/gears/docs/js/bootstrap.js') }}
		{{ HTML::script('bundles/feather/gears/docs/js/prettify.js') }}

		<script type="text/javascript">
			$(document).ready(function()
			{
				$('pre').each(function()
				{
					$(this).addClass('prettyprint linenums');
				});

				prettyPrint();
			});
		</script>
	</body>
</html>