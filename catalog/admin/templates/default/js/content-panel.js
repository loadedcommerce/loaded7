/**
 * Content panel plugin
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, document)
{
	/*
	 * document is passed through as local variable rather than as a global, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

	// Objects cache
	var doc = $(document);

	// Navigable menus
	doc.on('click', '.open-on-panel-content, .open-on-panel-navigation', function(event)
	{
		var link = $(event.target).closest('a'),
			contentPanel = link.closest('.content-panel'),
			href;

		// If not valid, exit
		if (!link.length || !contentPanel.length)
		{
			return;
		}

		// Target
		href = link.attr('href');
		if ($.trim(href).length === 0 || href == '#')
		{
			return;
		}

		// Stop normal behavior
		event.preventDefault();

		// Load content
		contentPanel[$(this).hasClass('open-on-panel-content') ? 'loadPanelContent' : 'loadPanelNavigation'](href);
	});

	/**
	 * Load navigation panel content with AJAX
	 * @param string url the url of the content to load
	 * @param object options any additional options for the AJAX call
	 */
	$.fn.loadPanelNavigation = function(url, options)
	{
		return this.each(function(i)
		{
			var contentPanel = $(this).closest('.content-panel'),
				panelNavigation = contentPanel.children('.panel-navigation');

			// Load content
			loadPanelContent(url, contentPanel, panelNavigation, true, options);
		});
	};

	/**
	 * Refresh the navigation panel content if it was previously loaded at least once
	 */
	$.fn.refreshPanelNavigation = function()
	{
		return this.each(function(i)
		{
			var contentPanel = $(this).closest('.content-panel'),
				panelNavigation = contentPanel.children('.panel-navigation'),
				url = panelNavigation.data('content-panel-url');

			// Load content if url is set
			if (url)
			{
				loadPanelContent(url, contentPanel, panelNavigation, true, panelNavigation.data('content-panel-options'));
			}
		});
	};

	/**
	 * Load content panel content with AJAX
	 * @param string url the url of the content to load
	 * @param object options any additional options for the AJAX call
	 */
	$.fn.loadPanelContent = function(url, options)
	{
		return this.each(function(i)
		{
			var contentPanel = $(this).closest('.content-panel'),
				panelContent = contentPanel.children('.panel-content');

			// Load content
			loadPanelContent(url, contentPanel, panelContent, false, options);
		});
	};

	/**
	 * Refresh the content panel content if it was previously loaded at last once
	 */
	$.fn.refreshPanelContent = function()
	{
		return this.each(function(i)
		{
			var contentPanel = $(this).closest('.content-panel'),
				panelContent = contentPanel.children('.panel-content'),
				url = panelContent.data('content-panel-url');

			// Load content if url is set
			if (url)
			{
				loadPanelContent(url, contentPanel, panelContent, false, panelContent.data('content-panel-options'));
			}
		});
	};

	/**
	 * Load content into a panel
	 * @param string url the url of the content to load
	 * @param jQuery wrapper the main block
	 * @param jQuery panel the panel in which to load content
	 * @param boolean isNav indicate if the panel is the navigation panel
	 * @param object options any additional options for the AJAX call
	 */
	function loadPanelContent(url, wrapper, panel, isNav, options)
	{
		// If not valid, exit
		if (!wrapper.length || !panel.length)
		{
			return;
		}

			// Gather options
		var settings = $.extend({}, wrapper.data('panel-options'), panel.data('panel-options')),

			// Ajax options
			ajaxOptions = $.extend({}, settings.ajax, options, {

				url: url,
				success: function(data, textStatus, jqXHR)
				{
					var back;

					// Insert content if text/html
					if ( typeof data === 'string' )
					{
						// Back button
						back = target.closest('.panel-content').data('panel-back-button');
						if (back)
						{
							target.children().not(back).remove();
							target.append(data);
						}
						else
						{
							target.html(data);
						}
					}

					// Callback in settings
					if (settings.ajax && settings.ajax.success)
					{
						settings.ajax.success.call(target[0], data, textStatus, jqXHR);
					}

					// Callback in options
					if (options && options.success)
					{
						options.success.call(target[0], data, textStatus, jqXHR);
					}
				}

			}),

			// Actual target
			loadTarget = panel.children('.panel-load-target:first'),
			target = loadTarget.length ? loadTarget : panel;

		// Pre-callback
		if (settings.onStartLoad)
		{
			if (settings.onStartLoad.call(target[0], settings, ajaxOptions) === false)
			{
				return;
			}
		}

		// Display panel (for mobile devices)
		wrapper[isNav ? 'removeClass' : 'addClass']('show-panel-content');

		// Load content
		$.ajax(ajaxOptions);

		// Store url and options
		panel.data('content-panel-url', url);
		panel.data('content-panel-options', options);
	}

	/**
	 * Enable content panel JS features
	 */
	$.fn.contentPanel = function()
	{
		return this.each(function(i)
		{
			var contentPanel = $(this).closest('.content-panel'),
				panelContent = contentPanel.children('.panel-content'),
				loadTarget, back, setMode;

			// If already initialized
			if (contentPanel.hasClass('enabled-panels'))
			{
				return;
			}

			// If valid
			if (contentPanel.length > 0 && panelContent.length > 0)
			{
				// Enable sliding panels on mobile
				contentPanel.addClass('enabled-panels');

				// Actual content block
				loadTarget = panelContent.children('.panel-load-target:first');

				// Create back button
				back = $('<div class="back"><span class="back-arrow"></span>Back</div>');
				if (loadTarget.length)
				{
					back.insertBefore(loadTarget);
				}
				else
				{
					back.prependTo(panelContent);
				}
				panelContent.data('panel-back-button', back);

				// Behavior
				back.click(function(event)
				{
					contentPanel.removeClass('show-panel-content');
				});

				// If not forced into permanent mobile mode
				if (!contentPanel.hasClass('mobile-panels'))
				{
					// Function to toggle mobile/desktop views
					setMode = function()
					{
						contentPanel[contentPanel.innerWidth() < 500 ? 'addClass' : 'removeClass']('mobile-panels');
					};

					// First run
					setMode();

					// Watch for size changes
					contentPanel.widthchange(setMode);
				}
			}
		});
	};

	// Add to template setup function
	$.template.addSetupFunction(function(self, children)
	{
		this.findIn(self, children, '.content-panel').contentPanel();

		return this;
	});

})(jQuery, document);