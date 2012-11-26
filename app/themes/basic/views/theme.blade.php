<!DOCTYPE html>

<html>

	<head>
		<title>
			@event('view: before template.title') Title
		</title>

		{{ Basset::show('basic.css') }}
	</head>

	<body>

		<div class="container">

			<div class="header group">

				<div class="user">
					
				</div>

				<div class="logo"><img src="http://feather.dev/feather/themes/basic/img/heading.png" /></div>

				<ul class="navigation">
					<li><a href="">Hello World</a></li>
				</ul>

			</div>

			<div class="body">
				<ul class="breadcrumbs"></ul>

				<div class="content">
					
				</div>
			</div>

			<div class="footer group">
				<p class="powered">Powered by Feather</p>

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

		

	</body>

</html>