/**
 * Tooltip for jQuery by Jason Lewis
 * -----------------------------------------
 * This is a simple tooltip plugin for jQuery which allows tooltips on elements with an arrow
 * at multiple positions of the tooltip.
 *
 * @author		Jason Lewis
 * @copyright	2011 - 2012 Jason Lewis
 * @version		1.0
 * @license 	3-clause BSD
 */
(function($){
	$.fn.tooltip = function(opts){

		var options = $.extend({}, $.fn.tooltip.defaults, opts),
			elements = {
				container: 	$('<div class="tooltip-container"></div>'),
				wrapper: 	$('<div class="tooltip-wrapper"></div>'),
				arrow:		$('<div class="tooltip-arrow"></div>'),
				content:	$('<div class="tooltip-content"></div>')
			},
			binder = options.live ? 'live' : 'bind',
			title = '';

		// Append the tooltip contents to the body
		$('body').append(elements.container.html(elements.wrapper.html(elements.arrow).append(elements.content)));

		// Set some options
		elements.container.css({ maxWidth: options.width + 'px' });

		return this[binder]({
			mouseover: function(){
				var elm = $(this);
				title = elm.attr(options.attr);

				if(title.length > 0){
					elements.content.text(title);
					elm.attr(options.attr, '');

					var dimensions = {
							top: elm.offset().top,
							left: elm.offset().left,
							width: elm.outerWidth(),
							height: elm.outerHeight(),
							actualWidth: elm.outerWidth(true),
							actualHeight: elm.outerHeight(true),
							arrow: {
								width: elements.arrow.outerWidth() / 2,
								height: elements.arrow.outerHeight() / 2
							}
						},
						offsets = {};

					// Determine the position.
					switch(options.position.charAt(0)){
						case 'b':
							elements.container.addClass('arrow-b');
							offsets = {
								top: (dimensions.top + dimensions.height) + dimensions.arrow.height + options.offset,
								left: dimensions.left + (dimensions.width / 2) - (elements.container.outerWidth() / 2)
							};
						break;
						case 't':
							elements.container.addClass('arrow-t');
							offsets = {
								top: (dimensions.top - elements.container.outerHeight(true)) - dimensions.arrow.height - options.offset,
								left: dimensions.left + (dimensions.width / 2) - (elements.container.outerWidth() / 2)
							};
						break;
						case 'l':
							elements.container.addClass('arrow-l');
							offsets = {
								top: dimensions.top + (dimensions.height / 2) - (elements.container.outerHeight() / 2),
								left: dimensions.left - elements.container.outerWidth(true) - dimensions.arrow.width - options.offset
							};
						break;
						case 'r':
							elements.container.addClass('arrow-r');
							offsets = {
								top: dimensions.top + (dimensions.height / 2) - (elements.container.outerHeight() / 2),
								left: dimensions.left + dimensions.width + dimensions.arrow.width + options.offset
							};
						break;
					}

					if(options.position == 'topLeft' || options.position == 'bottomLeft')
					{
						offsets.left += (elements.container.outerWidth() / 2) - (dimensions.width / 2);

						elements.arrow.css('left', elements.arrow.outerWidth());
					}
					else if(options.position == 'topRight' || options.position == 'bottomRight')
					{
						offsets.left = (dimensions.left + dimensions.width) - elements.container.outerWidth();

						elements.arrow.css('left', elements.container.outerWidth() - elements.arrow.outerWidth());
					}
					else if(options.position == 'rightTop' || options.position == 'leftTop')
					{
						offsets.top = dimensions.top;
						elements.arrow.css('top', dimensions.height / 2);
					}

					elements.container.css(offsets).show();
				}
			},
			mouseout: function(){
				elements.container.hide();

				$(this).attr(options.attr, title);
			}
		});

		return this;
	};

	// Default Options
	$.fn.tooltip.defaults = {
		width: 300,
		offset: 0,
		live: false,
		position: 'top',
		attr: 'title'
	};
})(jQuery);