(function($)
{
	$.fn.splendid = function(opts)
	{
		var options = $.extend({}, $.fn.splendid.defaults, opts);

		return this.each(function()
		{
			var properties = {
				element: $(this),
				interactions: null,
				selection: {
					start: 0,
					end: 0,
					length: 0
				},
				container: null,
				value: null
			};

			// Create a new interactions instance so we can get caret positions for this element.
			properties.interactions = new $.fn.splendid.interactions(properties.element);

			// When the element loses focus we'll update it's selection property here.
			properties.element.on('focusout', function()
			{
				properties.selection = properties.interactions.getSelection();
			});

			// If the user wants splendid to generate the buttons we need to inject the buttons container into
			// the DOM.
			if(options.generate)
			{
				properties.container = $(options.container);

				properties.element.before(properties.container);
			}

			$.each(options.maps, function(key, value)
			{
				var button = {
						element: null,
						run: {}
					},
					map = this;

				// If someone wants us to generate the buttons for them... then let's do that!
				if(options.generate)
				{
					properties.container.append(button.element = $(options.buttons).html($('<a>')));

					if('text' in this)
					{
						button.element.find.('a').html(this.text);
					}
				}
				else
				{
					button.element = options.buttons.parent().find('[data-map="' + key + '"]');
				}

				// If the button has a class property then assign the class to the button.
				if('class' in this)
				{
					button.element.addClass(this.class);
				}

				button.element.on({
					click: function(event)
					{
						event.preventDefault();

						// Run each of the methods for this button, after, before and replace. If either of
						// these returns false then we won't do any replacements.
						button.run.after = properties.interactions.checkReplacement(map.after);
						button.run.before = properties.interactions.checkReplacement(map.before);
						button.run.replace = properties.interactions.checkReplacement(map.replace);

						if(button.run.before === false || button.run.after === false || button.run.replace === false)
						{
							return;
						}

						properties.value = properties.element.val();

						// Try to replace the selected text, if there is no text selected then insert whatever
						// we have wherever the cursor is.
						if('replace' in map)
						{
							properties.value = [
								properties.value.slice(0, properties.selection.start),
								properties.value.slice(properties.selection.end),
								button.run.replace
							].join('');
						}

						// Try to insert the content after the selected text. We do the after first because
						// otherwise our caret positions would be screwed up.
						if('after' in map)
						{
							properties.value = [
								properties.value.slice(0, properties.selection.end),
								button.run.after,
								properties.value.slice(properties.selection.end)
							].join('');
						}

						// Now insert any content before the selected text.
						if('before' in map)
						{
							properties.value = [
								properties.value.slice(0, properties.selection.start),
								button.run.before,
								properties.value.slice(properties.selection.start)
							].join('');
						}

						properties.element.focus();

						// If there was nothing selected and there is a placeholder mapped to the button
						// we'll insert it.
						if(properties.selection.length == 0 && 'placeholder' in map)
						{
							properties.value = [
								properties.value.slice(0, properties.selection.start + button.run.before.length),
								map.placeholder,
								properties.value.slice(properties.selection.start + button.run.before.length)
							].join('');
						}

						// Update the textarea!
						properties.element.val(properties.value);

						// Set the selection to the placeholder text. That way the user can begin editing it
						// straight away without them having to re-select it.
						if(properties.selection.length == 0 && 'placeholder' in map)
						{
							properties.interactions.setSelection(
								properties.selection.start + button.run.before.length,
								properties.selection.start + button.run.before.length + map.placeholder.length
							);
						}
					}
				});
			});
		});
	};

	/**
	 * The interactions object provides us with caret positions and other lovely
	 * details. All can be returned in a nice object.
	 *
	 * A splendid object is given, which is an input field being wheatified!
	 */
	$.fn.splendid.interactions = function(splendid)
	{
		this.splendid = splendid;
	};

	$.fn.splendid.interactions.prototype.getPosition = function()
	{
		console.log(this.splendid);
	};

	/**
	 * Get the selected text inside of a textarea.
	 */
	$.fn.splendid.interactions.prototype.getSelection = function()
	{
		this.splendid.focus();

		var selection = {
			start: 0,
			end: 0,
			length: 0,
			text: null
		};

		// Most modern browsers provide a lovely way of getting the selection of any input box.
		// Thanks guys, you rock.
		if(typeof this.splendid.get(0).selectionStart == "number" && typeof this.splendid.get(0).selectionEnd == "number")
		{
			selection.start = this.splendid.get(0).selectionStart;
			selection.end = this.splendid.get(0).selectionEnd;
			selection.length = selection.end - selection.start;
		}

		// Oh, hello ugly block of code. This has to be for Internet Explorer. God why can't
		// they just be like everyone else.
		else
		{
			var ie = {
				range: document.selection.createRange(),
				normalized: this.splendid.val().replace(/\r\n/g, "\n"),
				selection: this.splendid.get(0).createTextRange(),
				endRange: this.splendid.get(0).createTextRange()
			};

			ie.selection.moveToBookmark(ie.range.getBookmark());

			// Check if the start and end of the selection are at the very end of the input, since
			// moveStart/moveEnd doesn't return what we want in those cases.
			ie.endRange.collapse(false);

			if(ie.selection.compareEndPoints("StartToEnd", ie.endRange) > -1)
			{
				selection.start = selection.end = this.splendid.val().length;
				selection.length = selection.end - selection.start;
			}
			else
			{
				selection.start = -ie.selection.moveStart("character", -this.splendid.val().length);

                if(ie.selection.compareEndPoints("EndToEnd", ie.endRange) > -1)
                {
					selection.end = this.splendid.val().length;
				}
				else
				{
					selection.end = -ie.selection.moveEnd("character", -this.splendid.val().length);
					selection.end += ie.normalized.slice(0, selection.end).split("\n").length - 1;
				}

				selection.length = selection.end - selection.start;
			}
		}

		selection.text = this.splendid.val().slice(selection.start, selection.end);

		return selection;
	};

	/**
	 * Set the selection inside the textbox.
	 */
	$.fn.splendid.interactions.prototype.setSelection = function(start, end)
	{
		// Again, modern browsers rock for how easy this is!
		if(typeof this.splendid.get(0).selectionStart == "number" && typeof this.splendid.get(0).selectionEnd == "number")
		{
			this.splendid.get(0).selectionStart = start;
			this.splendid.get(0).selectionEnd = end;
		}
		else
		{
			var ie = {
				range: this.splendid.get(0).createTextRange()
			};

			ie.range.collapse(true);
			ie.range.moveStart('character', start);
			ie.range.moveEnd('character', end - start);
			ie.range.select();
		}
	};

	/**
	 * Check for any user prompts within the replacement text.
	 */
	$.fn.splendid.interactions.prototype.checkReplacement = function(replacement)
	{
		if(typeof replacement === 'function')
		{
			replacement = replacement();
		}

		var prompts = {
			matches: null,
			details: null,
			response: null
		};


			while(prompts.matches = /\[\@(.*?)\]/g.exec(replacement))
			{
				// Split the prompt into it's details, so we have the title and any placeholder
				// text to go into the prompt.
				if(prompts.details = /\[\@([^:\]]+):?(.*?)?\]/g.exec(prompts.matches[0]))
				{
					if((prompts.response = prompt(prompts.details[1], (typeof prompts.details[2] != 'undefined') ? prompts.details[2] : '')) !== false)
					{
						replacement = replacement.replace(prompts.details[0], prompts.response);
					}
					else
					{
						return false;
					}
				}
		}

		return replacement;
	};

	$.fn.splendid.defaults = {
		container: '<ul class="splendid-container">',
		buttons: '<li class="splendid-button">',
		maps: {},
		generate: false
	};
})(jQuery);

(function($)
{
	$('.discussion-textarea').find('textarea').splendid({
		generate: true,
		maps: {
			bold: {
				placeholder: 'Bold',
				before: '**',
				after: '**',
				class: 'bold'
			},
			italic: {
				placeholder: 'Italic',
				before: '*',
				after: '*',
				class: 'italic'
			},
			ul: {
				placeholder: 'Unordered List',
				before: '- ',
				class: 'ul'
			},
			ol: {
				placeholder: 'Ordered List',
				before: '1. ',
				class: 'ol'
			},
		    picture: {
				replace: '![[@Alt Text]]([@URL:http://] "[@Title]")',
				class: 'picture'
			},
			link: {
				placeholder: 'Link',
				before: '[',
				after: ']([@URL:http://] "[@Title]")',
				class: 'link'
			},
			quote: {
				before: '> ',
				class: 'quote'
			},
			code: {
		    	before : '~~~~\n',
		    	after : '\n~~~~',
		        class: 'code'
		    }
	    }
	});
})(jQuery);