<?php

/*
|--------------------------------------------------------------------------
| Theme Styles
|--------------------------------------------------------------------------
|
| Register the themes styles.
|
*/

Asset::container('theme')->add('theme', 'css/theme.css');

/*
|--------------------------------------------------------------------------
| Theme Scripts
|--------------------------------------------------------------------------
|
| Register the themes scripts.
|
*/

Asset::container('theme')->add('jquery', 'js/jquery.js')
						 ->add('jquery-ui', 'js/jquery-ui.js')
			   			 ->add('leaner', 'js/leaner.js')
			   			 ->add('tooltip', 'js/tooltip.js')
			   			 ->add('theme', 'js/theme.js');