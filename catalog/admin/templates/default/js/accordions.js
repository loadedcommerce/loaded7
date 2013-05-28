/**
 *
 * Accordions plugin
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
		doc = $(document);

	// Event binding
	doc.on('click', '.accordion > dt', function(event)
	{
		var dt = $(this),
			dl = dt.parent();

		// Equalize height when needed
		if (dl.hasClass('same-height'))
		{
			dl.refreshAccordion();
		}

		// Check if closed
		if (dt.hasClass('closed'))
		{
			// Close previous
			dl.children('dt').not('.closed').each(function(i)
			{
				$(this).addClass('closed').next('dd').stop(true).slideUp();
			});

			// Open
			dt.removeClass('closed');
			dt.next('dd').stop(true).slideDown();
		}
	});

	/**
	 * Refresh accordions height
	 */
	$.fn.refreshAccordion = function()
	{
		this.each(function(i)
		{
				// Accordions
			var dl = $(this).closest('.accordion'),
				sections = dl.children('dd'),

				// Hidden parents
				hidden,

				// Processing vars
				datas = [],
				maxHeight = 0;

			// If not found or not valid
			if (dl.length === 0 || !dl.hasClass('same-height') || sections.length === 0)
			{
				return;
			}

			// Reveal hidden parents if needed for correct height processing
			hidden = dl.tempShow();

			// Gather sections blocks and infos
			sections.each(function(i)
			{
				var section = $(this).show(),
					topMargin, height;

				// Get total height
				height = section.css('height', '').outerHeight();

				// Get first element's top-margin, because it affects the block height if it is negative
				topMargin = Math.min(0, section.children(':first').parseCSSValue('margin-top'));

				// Check if this is the tallest element
				maxHeight = Math.max(maxHeight, height+topMargin);

				// Store for equalization loop below
				datas[i] = [height, topMargin];
			});

			// Set equalized height
			sections.each(function(i)
			{
				var section = $(this),
					dt = section.prev(),
					height = datas[i][0],
					topMargin = datas[i][1];

				// Set height depending on margins
				section.height(maxHeight-(height-section.height())-topMargin);

				// Hide if not current one
				if (dt && dt.hasClass('closed'))
				{
					section.hide();
				}
			});

			// Hide previously hidden parents
			hidden.tempShowRevert();
		});

		return this;
	};

	// Add template setup function
	$.template.addSetupFunction(function(self, children)
	{
		var accordions = this.findIn(self, children, '.accordion');

		// Equalize height when needed
		accordions.filter('.same-height').refreshAccordion();

		// Show only active tab
		accordions.each(function(i)
		{
			var dts = $(this).children('dt'),
				active;

			// Active section
			active = dts.filter('.open');
			if (active.length === 0)
			{
				active = dts.not('.closed').first();
			}
			if (active.length === 0)
			{
				active = dts.first();
			}

			// Tag and show/hide
			active.removeClass('closed').next('dd').show();
			active.siblings('dt').addClass('closed').next('dd').hide();
		});

		return this;
	});

})(jQuery, window, document);