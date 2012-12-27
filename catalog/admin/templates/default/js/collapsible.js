/**
 *
 * Collapsible menus plugin
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, window, document)
{
	/*
	 * window and document are passed through as local variables rather than as globals, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var doc = $(document),

		// Global animation switch
		animate = true;

	// Navigable menus
	doc.on('click', '.collapsible li, .collapsible li > span, .collapsible li > a', function(event)
	{
		// Only work if the element is the event's target
		if (event.target !== this)
		{
			return;
		}

			// Clicked element
		var clicked = $(this),

			// LI element
			li = $(this).closest('li'),

			// Sub-menu
			submenu = li.children('ul:first'),

			// Root menu
			root = li.closest('.collapsible'),

			// Other vars
			load, current, url, height;

		// If there is a submenu
		if (submenu.length > 0)
		{
			// If already open
			if (li.hasClass('collapsible-open'))
			{
				// Close
				li.removeClass('collapsible-open');
				if (animate)
				{
					submenu.stop(true).css('overflow', 'hidden').animate({

						height: '0px'

					}, function()
					{
						submenu.css({
							overflow: '',
							height: ''
						}).hide();
					});
				}
				else
				{
					submenu.stop(true).hide();
				}

				// Arrow
				if (li.hasClass('with-left-arrow') || li.hasClass('with-right-arrow'))
				{
					li.removeClass('arrow-up').addClass('arrow-down');
				}

				// Close event
				li.trigger('collapsible-close');
			}
			else
			{
				// Open parents if required
				$(li.parentsUntil('.collapsible', 'li').not('.collapsible-open').get().reverse()).click();

				// If only one menu should be open on each level
				if (root.hasClass('as-accordion'))
				{
					li.siblings('.collapsible-open').click();
				}

				// Mark as open
				li.addClass('collapsible-open');
				if (animate)
				{
					// Get final size
					height = submenu.stop(true).css({

						display: 'block',
						height: ''

					}).height();

					// Animate
					submenu.css({

						overflow: 'hidden',
						height: '0px'

					}).animate({

						height: height+'px'

					}, function()
					{
						submenu.css({
							overflow: '',
							height: ''
						});
					});
				}
				else
				{
					submenu.stop(true).css({
						overflow: '',
						height: ''
					}).show();
				}

				// Arrow
				if (li.hasClass('with-left-arrow') || li.hasClass('with-right-arrow'))
				{
					li.removeClass('arrow-down').addClass('arrow-up');
				}

				// Open event
				li.trigger('collapsible-open');
			}

			// Prevent default behavior
			event.preventDefault();
		}
		else if (clicked.hasClass('collapsible-ajax'))
		{
			// If already loading, do nothing
			if (li.children('.load').length)
			{
				return;
			}

			// Get target url
			url = clicked.is('a') ? clicked.attr('href') : clicked.data('collapsible-url');

			// If valid
			if (url && typeof url === 'string' && $.trim(url).length > 0 && url.substr(0, 1) !== '#')
			{
				// Load indicator
				load = $('<div class="load"></div>').appendTo(li);

				// Show load
				if (animate)
				{
					height = load.height();
					load.css({

						overflow: 'hidden',
						height: '0px'

					}).animate({

						height: height+'px'

					}, function()
					{
						submenu.css({
							overflow: '',
							height: ''
						});
					});
				}

				// Load submenu
				$.ajax(url, {
					error: function(jqXHR, textStatus, errorThrown)
					{
						// If notification system is enabled
						if (window.notify)
						{
							window.notify('Menu loading failed with the status "'+textStatus+'"');
						}

						// Remove load
						if (animate)
						{
							load.stop(true).css({

								overflow: 'hidden'

							}).animate({

								height: '0px'

							}, function()
							{
								load.remove();
							});
						}
						else
						{
							load.remove();
						}
					},
					success: function(data, textStatus, jqXHR)
					{
						// Remove ajax marker, mark as loaded
						clicked.removeClass('collapsible-ajax').addClass('collapsible-ajax-loaded');

						// Append data
						li.append(data);

						// Finally open the clicked element
						clicked.click();

						// Remove load
						if (animate)
						{
							load.stop(true).css({

								overflow: 'hidden'

							}).animate({

								height: '0px'

							}, function()
							{
								load.remove();
							});
						}
						else
						{
							load.remove();
						}
					}
				});

				// Prevent default behavior
				event.preventDefault();
			}
		}
		else if (clicked.hasClass('collapsible-ajax-loaded'))
		{
			// Probably an ajax menu who loaded nothing, prevent default behavior
			event.preventDefault();
		}
	});

	// Add to template setup function
	$.template.addSetupFunction(function(self, children)
	{
		// Style arrows
		this.findIn(self, children, '.collapsible li').addClass('arrow-down');

		// Current open menu element
		this.findIn(self, children, '.collapsible-current').each(function(i)
		{
			var closest = $(this).closest('ul').closest('li, .collapsible'),
				child;

			// Check if in a submenu
			if (closest.length > 0 && !closest.hasClass('collapsible'))
			{
				// Disable animation
				animate = false;

				// Is there a span or a link?
				child = closest.children('a, span').first();
				if (child.length > 0)
				{
					child.click();
				}
				else
				{
					closest.click();
				}

				// Enable animation
				animate = true;
			}
		})

		return this;
	});

})(jQuery, window, document);