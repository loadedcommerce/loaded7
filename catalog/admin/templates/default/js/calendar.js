/**
 *
 * Fluid calendar plugin
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, document, undefined)
{
	/*
	 * undefined is used here as the undefined global variable in ECMAScript 3 is mutable (i.e. it can
	 * be changed by someone else). undefined isn't really being passed in so we can ensure that its value is
	 * truly undefined. In ES5, undefined can no longer be modified.
	 */

	/*
	 * document is passed through as local variable rather than as global, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var doc = $(document),

	 	// List of calendar sizes
		sizes = [
			['medium',	280],
			['large',	622],
			['largest',	780]
		];

	/**
	 * Watch for days being clicked on mini calendars
	 */
	doc.on('touchend click', '.calendar > tbody > tr > td, .calendar > tbody > tr > th', function(event)
	{
		// Check if valid touch-click event
		if (!$.template.processTouchClick(this, event))
		{
			return;
		}

		// Do not process if a link was clicked
		if (event.target.nodeName.toLowerCase() === 'a' || event.isDefaultPrevented())
		{
			return;
		}

			// Clicked cell
		var cell = $(this).closest('th, td'),

			// Events
			events = cell.children('.cal-events'),

			// Position in row
			cellIndex = cell.prevAll().length,

			// Cell row
			row = cell.parent(),

			// Cells count
			rowCells = row.children().length,

			// Parent table
			calendar = row.closest('.calendar'),

			// Already open zoom cells
			zooms = row.siblings('.calendar-zoom'),

			// Target zoom cell
			zoom, zoomCell;

		// Do not process for zoom cells
		if (row.hasClass('calendar-zoom'))
		{
			return;
		}

		// Only for fluid calendars or calendars with events
		if (!calendar.hasClass('fluid') && !calendar.hasClass('with-events'))
		{
			return;
		}

		// If the clicked cell has events
		if (events.length > 0)
		{
			// If there is an existing zoom cell below clicked cell
			zoom = row.next('.calendar-zoom');

			// If no zoom cell
			if (zoom.length === 0)
			{
				// Create
				zoom = $('<tr class="calendar-zoom">'+
							'<td colspan="'+rowCells+'">'+
								'<span class="calendar-zoom-arrow"></span>'+
							'</td>'+
						'</tr>').insertAfter(row);

				// Show with animation
				animateWrapper = true;
			}
			else
			{
				zooms = zooms.not(zoom);
			}

			// Clear
			zoomCell = zoom.children();
			zoomCell.children().not('.calendar-zoom-arrow').remove();

			// Insert content
			zoomCell.append(events.clone(true).css('width', ''));

			// Arrow position
			zoomCell.children('.calendar-zoom-arrow')
					.css('margin-left', Math.round((cellIndex+0.5)*(row.width()/rowCells)-12)+'px') // 12: cell padding + arrow border width
					.data('active-cell-index', cellIndex);
		}

		// Close other zoom rows
		if (zooms.length > 0)
		{
			zooms.remove();
		}
	});

	/**
	 * Enable calendar scrolling
	 * @param object options - optional (see defaults for a complete list)
	 */
	$.fn.fluidCalendar = function(options)
	{
		// Settings
		var globalSettings = $.extend({}, $.fn.fluidCalendar.defaults, options);

		this.each(function(i)
		{
				// Calendar
			var calendar = $(this).closest('table.calendar'),

				// Options
				settings = $.extend({}, globalSettings, calendar.data('calendar-options')),

				// Functions
				refreshSize;

			// Check if valid
			if (calendar.length === 0)
			{
				return;
			}

			// Set size on each resize
			refreshSize = function()
			{
					// Gather elements on each resize to account for content changes
				var thead = calendar.children('thead'),
					headers = thead.children().children(),
					tbody = calendar.children('tbody'),
					rows = tbody.children(),
					regularRows = rows.not('.calendar-zoom'),
					zoomRows = rows.filter('.calendar-zoom'),
					cells = regularRows.children(),
					events = cells.children('.cal-events'),
					nbColumns = headers.length,

					// Actual size
					width = calendar.width(),

					// Columns width (width minus borders divided by number of columns)
					colWidth = Math.floor((width-(nbColumns-1))/nbColumns),

					// Work vars
					sizeClass = false, i, paddingLeft, paddingRight;

				// Size style
				for (i = 0; i < sizes.length; ++i)
				{
					if (width > sizes[i][1])
					{
						if (sizeClass)
						{
							calendar.removeClass(sizeClass);
						}
						sizeClass = sizes[i][0];
						calendar.addClass(sizes[i][0]);
					}
					else
					{
						calendar.removeClass(sizes[i][0]);
					}
				}

				// Set columns size
				headers.css('width', colWidth+'px');

				// Cells height
				cells.css('height', Math.min(colWidth, settings.maxHeight)+'px');

				// Update zoom cells arrows
				zoomRows.each(function(i)
				{
					var arrow = $(this).children().children('.calendar-zoom-arrow');
					arrow.css('margin-left', Math.round((arrow.data('active-cell-index')+0.5)*(width/nbColumns)-12)+'px'); // 12: cell padding + arrow border width
				});

				// Set events lists size
				if (events.length > 0)
				{
					// Get paddings
					paddingLeft = events.eq(0).parseCSSValue('padding-left');
					paddingRight = events.eq(0).parseCSSValue('padding-right');

					// Set size
					events.css('width', (colWidth-paddingLeft-paddingRight)+'px');
				}

				// Callback
				if (settings.onSizeChange)
				{
					settings.onSizeChange.call(calendar[0]);
				}
			};

			// Prepare calendar
			calendar.removeClass('fluid').addClass('with-events').css('width', '100%');

			// First call
			refreshSize();

			// Listen
			calendar.widthchange(refreshSize);

			// Interface
			calendar.data('calendar', {
				refreshSize:	refreshSize
			});
		});

		return this;
	};

	/**
	 * Refresh fluid calendar size. This is useful is the calendar content has been changed.
	 */
	$.fn.fluidCalendarRefresh = function()
	{
		return this.each(function(i)
		{
			var data = $(this).closest('.calendar').data('calendar');
			if (data)
			{
				data.refreshSize();
			}
		});
	};

	/**
	 * Scroll calendar defaults
	 * @var object
	 */
	$.fn.fluidCalendar.defaults = {
		/**
		 * Maximum cell height (used for width screens to limit the calendar vertical expansion)
		 * @var int
		 */
		maxHeight: 130,

		/**
		 * Callback on each calendar resize event
		 * @var function
		 */
		onSizeChange: null
	};

	// Add template setup function
	$.template.addSetupFunction(function(self, children)
	{
		// Auto-scrolling calendars
		this.findIn(self, children, 'table.calendar.fluid').fluidCalendar();

		return this;
	});

})(jQuery, document);