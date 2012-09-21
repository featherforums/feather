var FEATHER = {};

(function($)
{
	/**
	 * Register the leaner modal that works for this theme.
	 */
	$('a.popup-ui').leaner();

	/**
	 * Register the tooltips that appear throughout the theme.
	 */
	$('.tooltip-ui').tooltip({ 'position': 'top', 'offset': 2, 'live': true });
	$('.tooltip-ui-right').tooltip({ 'position': 'right', 'offset': 4, 'live': true });
	$('.tooltip-ui-places').tooltip({ 'position': 'bottomLeft', 'offset': 3, 'width': 350 });
	$('.tooltip-ui-footer').tooltip({ 'position': 'right', 'offset': 5, 'width': 350 });

	/**
	 * Assign the auto complete plugin to our discussion participants input box.
	 */
	(function($)
	{
		var properties = {
			cache: {},
			participants: [],
			elements: {
				input: $('.discussion-participants').find('input#participants'),
				auto: $('.discussion-participants').find('.autocomplete'),
				anyone: $('.participants').find('.anyone'),
				participants: $('.participants'),
				loading: $('<div>').attr('class', 'loading')
			}
		}

		function add(participant)
		{
			// Hide the default 'anyone can participate' message.
    		properties.elements.anyone.hide();

    		// Loop over each participant and if they are the same as the one trying to be
    		// added then forget it! They can only be added once.
    		for(var i = 0; i < properties.participants.length; ++i)
    		{
    			if(properties.participants[i] == participant.value)
    			{
    				return;
    			}
    		}

    		// Add the participants name to the participants array.
    		properties.participants.push(participant.value);

    		// Add the participants avatar to the participants div.
    		properties.elements.participants.append($('<img>').attr({
    			'src': participant.avatar + '?d=mm',
    			'class': 'tooltip-ui',
    			'title': participant.value
    		}).data('name', participant.value).fadeIn('fast'));
		}

		/**
		 * If there is some comma separated names already in the participants input box we'll poll a request
		 * to the API to get all of the users details, we need their avatar.
		 */
		if(properties.elements.input.val().length)
		{
			var array = $.map(properties.elements.input.val().split(','), $.trim);

			properties.elements.input.val('');

			properties.elements.anyone.hide().after(properties.elements.loading);

			$.getJSON(app.base + '/api/v1/user/bunch.json', { data: JSON.stringify(array) }, function(data, status, xhr)
			{
				properties.elements.loading.fadeOut('fast', function()
				{
					for(var i = 0; i < data.length; ++i)
					{
						setTimeout(add, (i + 1) * 150, { value: data[i].username, avatar: data[i].avatar });
					}
				});
			});
		}

		properties.elements.input.autocomplete({
			source: function(request, response)
			{
				if(request.term in properties.cache)
				{
					response(properties.cache[request.term]);

					return;
				}

				$.getJSON(app.base + '/api/v1/user/find.json', request, function(data, status, xhr)
				{
					var store = [];

					for(var i = 0; i < data.length; ++i)
					{
						store.push({
							'value': data[i].username,
							'avatar': data[i].avatar
						});
					}

					properties.cache[request.term] = store;

					response(store);
				});
			},
			appendTo: properties.elements.auto,
			position: { my: "left top", at: "left bottom", collision: "none", offset: "0 14px" },
			autoFocus: true,

			// When we show the results highlight the typed text in the matched results.
			open: function (e, ui)
			{
            	var data = properties.elements.input.data('autocomplete');

            	data.menu.element.find('a').each(function()
            	{
                    $(this).html($(this).text().replace(new RegExp('(' + data.term.split(' ').join('|') + ')', 'gi'), '<strong>$1</strong>'));
                });
        	},

        	// When an item is selected we'll add to the array of participants, then we'll add the
        	// users avatar to the participants div.
        	select: function(e, ui)
        	{
        		e.preventDefault();

        		add(ui.item);

        		properties.elements.input.val('').autocomplete('close');
        	}
		});

		// Attach an event handler to any clicking events of any images inside the participants div,
		// we'll then remove the user from the selected participants.
		properties.elements.participants.find('img').live('click', function()
		{
			var name = $(this).data('name');

			for(var i = 0; i < properties.participants.length; ++i)
			{
				if(properties.participants[i] == name) break;
			}

			properties.participants.splice(i, 1);

			$(this).fadeOut('fast', function()
			{
				// If there are no participants, show the default text.
				if(properties.participants.length == 0) $('.participants').find('.anyone').show();
			});
		});

		// Attach an event handler to the submission of the form so we can inject another form field
		// with a comma separated list of participants.
		$('.manage-discussion').find('form').on('submit', function(e)
		{
			$(this).find('input[name=participants]').after($('<input>').attr({
				name: 'participants',
				type: 'hidden',
				value: properties.participants.join(',')
			}));
		});
	})($);
})(jQuery);