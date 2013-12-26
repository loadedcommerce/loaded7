/**
 *
 * Responsive tables plugin
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
	 * document is passed through as local variable rather than as global, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var win = $(window),
		doc = $(document),

		// Responsive classes
		responsiveClasses = [
			'hide-on-mobile-portrait',
			'hide-on-mobile-landscape',
			'hide-on-mobile',
			'hide-on-tablet-portrait',
			'hide-on-tablet-landscape',
			'hide-on-tablet',
			'forced-display'
		];

	/**
	 * Enable responsive tables: add classes to cells
	 */
	$.fn.responsiveTable = function()
	{
		// Init generic vars
		var classesList = responsiveClasses.join(' '),
			classesSelectors = '.'+responsiveClasses.join(', .');

		this.each(function(i)
		{
			var table = $(this).closest('table'),
				thead = table.children('thead'),
				cells = table.children('tbody').children().children();

			// Check if valid
			if (table.length === 0 || thead.length === 0)
			{
				return;
			}

			// Global class
			table.addClass('responsive-table-on');

			// Clear cells classes
			cells.removeClass(classesSelectors);

			// Copy headers responsive classes
			thead.children().children().each(function(i)
			{
				var header = $(this),
					classes = [],
					c;

				// Find classes
				for (c = 0; c < responsiveClasses.length; ++c)
				{
					if (header.hasClass(responsiveClasses[c]))
					{
						classes.push(responsiveClasses[c]);
					}
				}

				// If any classes found
				if (classes.length > 0)
				{
					cells.filter(':nth-child('+(i+1)+')').addClass(classes.join(' '));
				}
			});
		});

		return this;
	};

	// Add template setup function
	$.template.addSetupFunction(function(self, children)
	{
		this.findIn(self, children, '.responsive-table').responsiveTable();
	});

})(jQuery, window, document);