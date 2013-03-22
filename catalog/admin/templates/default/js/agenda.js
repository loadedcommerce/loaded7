/**
 * Scrolling agenda plugin
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, undefined)
{
	/*
	 * undefined is used here as the undefined global variable in ECMAScript 3 is mutable (i.e. it can
	 * be changed by someone else). undefined isn't really being passed in so we can ensure that its value is
	 * truly undefined. In ES5, undefined can no longer be modified.
	 */

	/**
	 * Enable agenda scrolling
	 * @param object options - optional (see defaults for a complete list)
	 */
	$.fn.scrollAgenda = function(options)
	{
		// Settings
		var globalSettings = $.extend({}, $.fn.scrollAgenda.defaults, options);

		this.each(function(i)
		{
				// Agenda
			var agenda = $(this).closest('.agenda').addClass('scrolling-agenda'),

				// Options
				settings = $.extend({}, globalSettings, agenda.data('agenda-options')),

				// Events columns
				columns = agenda.children('.agenda-wrapper').children('.agenda-events'),
				nbColumns = columns.length,

				// Size & number of visible columns
				colSize, colCount = -1,

				// Visible columns range
				range = [-1, -1],

				// Functions
				refreshColumns, setFirstColumn, scrollPrevious, scrollNext;

			// Check if valid
			if (nbColumns < 1)
			{
				return;
			}

			// Create scroll arrows
			columns.children('.agenda-header').each(function(i)
			{
				var header = $(this);

				// Previous
				if (i > 0 && header.children('.agenda-previous').length === 0)
				{
					$('<a href="#" title="'+settings.prevTitle+'" class="agenda-previous"><span class="icon-left-round"></span></a>').prependTo(header).applySetup();
				}

				// Next
				if (i < nbColumns-1 && header.children('.agenda-next').length === 0)
				{
					$('<a href="#" title="'+settings.nextTitle+'" class="agenda-next"><span class="icon-right-round"></span></a>').prependTo(header).applySetup();
				}
			});

			// Behavior
			agenda.on('click', '.agenda-previous', function(event)
			{
				event.preventDefault();

				// If scrolling
				if (scrollPrevious())
				{
					return;
				}

			}).on('click', '.agenda-next', function(event)
			{
				event.preventDefault();

				// If scrolling
				if (scrollNext())
				{
					return;
				}
			});

			// Set first visible column
			columns.eq(settings.first).addClass('agenda-visible-column');

			// Function to refresh visible columns
			refreshColumns = function()
			{
				var firstCol,
					oldCount = colCount;

				// Mode
				if (settings.auto)
				{
					colCount = Math.max(1, Math.floor(agenda.width()/settings.minWidth));
				}
				else
				{
					colCount = settings.columns;
				}

				// Column size
				colSize = 100/colCount;

				// First visible column
				firstCol = columns.filter('.agenda-visible-column:first');
				if (firstCol.length === 0)
				{
					// Not active yet, get first one
					firstCol = columns.eq(0);
				}
				else
				{
					// Check if enough columns to fill slots
					if (firstCol.nextAll('.agenda-events').length < colCount-1)
					{
						firstCol = columns.eq(-colCount);
					}
				}

				// If size change
				if (colCount !== oldCount && settings.onSizeChange)
				{
					settings.onSizeChange.call(agenda[0], colCount);
				}

				// Refresh
				setFirstColumn(firstCol, false);
			};

			// Set first column, and position others
			setFirstColumn = function(firstCol, animate)
			{
				var oldRangeStart = range[0],
					oldRangeEnd = range[1],
					prevCols;

				// firstCol argument type
				if (typeof firstCol === 'number')
				{
					firstCol = columns.eq(firstCol);
				}
				else if (typeof firstCol === 'string')
				{
					firstCol = columns.filter(firstCol);
				}

				// Animate argument
				if (animate === undefined || animate === null)
				{
					animate = settings.animate;
				}

				// Check validity
				if (!(firstCol instanceof jQuery) || firstCol.length === 0)
				{
					return;
				}

				/**
				 * Note: since v1.8 (and earlier in FF), jQuery has an issue retrieving left/right values when they use percentages.
				 * I included several workarounds below that may be removed anytime when the issue is fixed by jQuery.
				 */

				// Trim
				firstCol = firstCol.first();

				/* Workaround */
				firstCol.data('agenda-fixed-left', false)
						.data('agenda-fixed-right', false)
						.data('agenda-initial-left', firstCol[0].style.left ? parseFloat(firstCol[0].style.left) : false)
						.data('agenda-initial-right', firstCol[0].style.right ? parseFloat(firstCol[0].style.right) : false);
				/* End workaround */

				// Init
				firstCol.addClass('agenda-visible-column agenda-visible-first').stop(true)[animate ? 'animate' : 'css']({
					left:	'0%',
					right:	(100-colSize)+'%',
					marginLeft: '-1px'			// This is to hide left border
				}

				/* Workaround */
				,{
					step: function(now, fx)
					{
						var value;
						if (fx.prop === 'left' || fx.prop === 'right')
						{
							if (!firstCol.data('agenda-fixed-'+fx.prop))
							{
								value = firstCol.data('agenda-initial-'+fx.prop);
								if (value)
								{
									console.log('init', fx.prop, value);
									fx.now = value+((now-fx.start)/(fx.end-fx.start)*(fx.end-value));
									firstCol.css(fx.prop, fx.now);
									fx.start = value;
								}
								firstCol.data('agenda-fixed-'+fx.prop, true);
							}
						}
						console.log(fx.prop, now);
					}
				}
				/* End workaround */

				);
				firstCol[(colCount === 1) ? 'addClass' : 'removeClass']('agenda-visible-last');

				// Previous columns
				prevCols = firstCol.prevAll('.agenda-events');
				prevCols.removeClass('agenda-visible-column agenda-visible-first agenda-visible-last').each(function(i)
				{
					var column = $(this);

					/* Workaround */
					column.data('agenda-fixed-left', false)
							.data('agenda-fixed-right', false)
							.data('agenda-initial-left', column[0].style.left ? parseFloat(column[0].style.left) : false)
							.data('agenda-initial-right', column[0].style.right ? parseFloat(column[0].style.right) : false);
					/* End workaround */

					// Position
					column.stop(true)[animate ? 'animate' : 'css']({
						left:	(-colSize*(i+1))+'%',
						right:	(100+(colSize*i))+'%',
						marginLeft: '0px'
					}

					/* Workaround */
					,{
						step: function(now, fx)
						{
							var value;
							if (fx.prop === 'left' || fx.prop === 'right')
							{
								if (!column.data('agenda-fixed-'+fx.prop))
								{
									value = column.data('agenda-initial-'+fx.prop);
									if (value)
									{
										fx.now = value+((now-fx.start)/(fx.end-fx.start)*(fx.end-value));
										column.css(fx.prop, fx.now);
										fx.start = value;
									}
									column.data('agenda-fixed-'+fx.prop, true);
								}
							}
						}
					}
					/* End workaround */

					);
				});

				// Next columns
				firstCol.nextAll('.agenda-events').removeClass('agenda-visible-first').each(function(i)
				{
					var column = $(this);

					/* Workaround */
					column.data('agenda-fixed-left', false)
							.data('agenda-fixed-right', false)
							.data('agenda-initial-left', column[0].style.left ? parseFloat(column[0].style.left) : false)
							.data('agenda-initial-right', column[0].style.right ? parseFloat(column[0].style.right) : false);
					/* End workaround */

					// Visible class
					column[(i < colCount-1) ? 'addClass' : 'removeClass']('agenda-visible-column');
					column[(i === colCount-2) ? 'addClass' : 'removeClass']('agenda-visible-last');

					// Position
					column.stop(true)[animate ? 'animate' : 'css']({
						left:	(colSize*(i+1))+'%',
						right:	(100-(colSize*(i+2)))+'%',
						marginLeft: '0px'
					}

					/* Workaround */
					,{
						step: function(now, fx)
						{
							var value;
							if (fx.prop === 'left' || fx.prop === 'right')
							{
								if (!column.data('agenda-fixed-'+fx.prop))
								{
									value = column.data('agenda-initial-'+fx.prop);
									if (value)
									{
										fx.now = value+((now-fx.start)/(fx.end-fx.start)*(fx.end-value));
										column.css(fx.prop, fx.now);
										fx.start = value;
									}
									column.data('agenda-fixed-'+fx.prop, true);
								}
							}
						}
					}
					/* End workaround */

					);
				});

				// Update range
				range = [prevCols.length, prevCols.length+colCount-1];

				// Detect range change
				if ((oldRangeStart != range[0] || oldRangeEnd != range[1]) && settings.onRangeChange)
				{
					settings.onRangeChange.call(agenda[0], range[0], range[1]);
				}
			};

			// First setup
			refreshColumns();

			// Watch size change
			agenda.widthchange(refreshColumns);

			// Function to scroll to previous column
			scrollPrevious = function()
			{
				var prev, firstCol;

				// First visible column
				firstCol = columns.filter('.agenda-visible-column:first');
				if (firstCol.length === 0)
				{
					firstCol = columns.eq(0);
				}

				// Is there a previous column?
				prev = firstCol.prev('.agenda-events');
				if (prev.length > 0)
				{
					setFirstColumn(prev, settings.animate);
					return true;
				}

				return false;
			};

			// Function to scroll to next column
			scrollNext = function()
			{
				var next, firstCol;

				// First visible column
				firstCol = columns.filter('.agenda-visible-column:first');
				if (firstCol.length === 0)
				{
					firstCol = columns.eq(0);
				}

				// Is there a enough columns to scroll?
				next = firstCol.nextAll('.agenda-events');
				if (next.length >= colCount)
				{
					setFirstColumn(next.first(), settings.animate);
					return true;
				}

				return false;
			};

			// Interface
			agenda.data('agenda', {
				setFirstColumn:	setFirstColumn,
				scrollPrevious:	scrollPrevious,
				scrollNext:		scrollNext
			});
		});

		return this;
	};

	/**
	 * Set the first visible column
	 * @param int|string|jQuery firstCol either an index (starting from 0), an CSS selector or a jQuery object
	 * @param boolean|null animate true to animate the movement, false to disable animation, null to use settings value (optional, default: null)
	 */
	$.fn.scrollAgendaFirstColumn = function(firstCol, animate)
	{
		return this.each(function(i)
		{
			var data = $(this).closest('.agenda').data('agenda');
			if (data)
			{
				data.setFirstColumn(firstCol, animate);
			}
		});
	};

	/**
	 * Scroll an agenda to previous column
	 */
	$.fn.scrollAgendaToPrevious = function()
	{
		return this.each(function(i)
		{
			var data = $(this).closest('.agenda').data('agenda');
			if (data)
			{
				data.scrollPrevious();
			}
		});
	};

	/**
	 * Scroll an agenda to next column
	 */
	$.fn.scrollAgendaToNext = function()
	{
		return this.each(function(i)
		{
			var data = $(this).closest('.agenda').data('agenda');
			if (data)
			{
				data.scrollNext();
			}
		});
	};

	/**
	 * Scroll agenda defaults
	 * @var object
	 */
	$.fn.scrollAgenda.defaults = {
		/**
		 * Should the plugin automatically determine the number of visible columns?
		 * @var boolean
		 */
		auto: true,

		/**
		 * Number of visible columns if auto is false
		 * @var int
		 */
		columns: 1,

		/**
		 * Minimum columns width for auto detection
		 * @var int
		 */
		minWidth: 200,

		/**
		 * Animate column movement
		 * @var boolean
		 */
		animate: true,

		/**
		 * First visible column - starts at 0 (only used at startup)
		 * @var int
		 */
		first: 0,

		/**
		 * Previous arrow title
		 * @var string
		 */
		prevTitle: 'Previous',

		/**
		 * Next arrow title
		 * @var string
		 */
		nextTitle: 'Next',

		/**
		 * Callback fired each time the number of visible columns change (including at startup)
		 * The function gets one parameter, the new number of visible columns
		 * @var function
		 */
		onSizeChange: null,

		/**
		 * Callback fired each time the visible range changes
		 * The function gets 2 parameters: first and last visible columns' index (start at 0)
		 * @var function
		 */
		onRangeChange: null
	};

	// Add template setup function
	$.template.addSetupFunction(function(self, children)
	{
		// Auto-scrolling agendas
		this.findIn(self, children, '.auto-scroll').scrollAgenda();

		return this;
	});

})(jQuery);