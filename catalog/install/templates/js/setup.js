/**
 * Global template functions
 *
 * Content:
 * 1. Variables declaration
 * 2. Template interface
 * 3. Features detection
 * 4. Touch optimization
 * 5. Position: fixed polyfill
 * 6. Generic functions
 * 7. Custom events
 * 8. DOM watching functions
 * 9. Template setup functions
 * 10. Template setup
 * 11. Viewport resizing handling
 * 12. Template init
 * 13. Event delegation for template elements
 * 14. Tracked elements
 * 15. Custom animations
 * 16. Mobile browser chrome hidding
 * 17. Dependencies
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, window, document, undefined)
{
	/*
	 * undefined is used here as the undefined global variable in ECMAScript 3 is mutable (i.e. it can
	 * be changed by someone else). undefined isn't really being passed in so we can ensure that its value is
	 * truly undefined. In ES5, undefined can no longer be modified.
	 */

	/*
	 * window and document are passed through as local variables rather than as globals, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

	/********************************************************/
	/*               1. Variables declaration               */
	/********************************************************/

		// Objects cache
	var win = $(window),
		doc = $(document),
		bod = $(document.body),

		// Whether auto-watching DOM changes or not
		autoWatch = true,

		// Recursion prevention in setup/clear watcher functions (prevent unnecessary processing)
		watching = true,

		// List of setup functions
		setupFunctions = [],

		// List of clear functions
		clearFunctions = [],

		// Store the timeout id for window.resize
		resizeInt = false,

		// List of media queries sizes with corresponding width of the test element
		mediaQueries = [
			[10, 'mobile-portrait'],
			[20, 'mobile-landscape'],
			[30, 'tablet-portrait'],
			[40, 'tablet-landscape'],
			[50, 'desktop']
		],

		// Height of the test element if a high-res screen is on
		hiresTestHeight = 20,

		// Position:fixed support
		fixedTest, supportFixed = true, fixed = $(),

		// Touchend instead of click support
		touchMoved = false, touchId = 0,

		// Template has been inited
		init = false;

	/********************************************************/
	/*                2. Template interface                 */
	/********************************************************/

	// Public template methods and vars will be created in here
	$.template = {

		/*
		 * Key map for easier code reading
		 */
		keys: {
			tab   : 9,
			enter : 13,
			space : 32,
			left  : 37,
			up    : 38,
			right : 39,
			down  : 40
		},

		/**
		 * Path to the respond.js folder
		 * @var string
		 */
    respondPath: './ext/jquery/',

		/*
		 * Here are stored various informations about the current media queries according to screen size
		 */
		mediaQuery: {

			/**
			 * Current largest media query name (one of 'mobile-portrait', 'mobile-landscape', 'tablet-portrait', 'tablet-landscape', 'desktop')
			 * @var string
			 */
			name: 'mobile-portrait',

			/**
			 * List of all media query active
			 * @var array
			 */
			on: ['mobile-portrait'],

			/**
			 * True if a hi-res screen (i.e. iPhone's Retina screen) is on
			 * @var boolean
			 */
			hires: false,

			/**
			 * Check if the specified media query name is on
			 * @param string name the name of the media query
			 * @return boolean true if on, else false
			 */
			has: function(name)
			{
				return ($.inArray(name, $.template.mediaQuery.on) > -1);
			},

			/**
			 * Check if the specified media query name is the current
			 * @param string name the name of the media query
			 * @return boolean true if on, else false
			 */
			is: function(name)
			{
				return ($.template.mediaQuery.name.indexOf(name.toLowerCase()) === 0);
			},

			/**
			 * Check if the current media query is smaller than the specified one
			 * @param string name the name of the media query
			 * @return boolean true if smaller, false if same or bigger
			 */
			isSmallerThan: function(name)
			{
				return !$.template.mediaQuery.has(name);
			}

		},

		/*
		 * Quick detection for IE7/8, because it requires several special behavors
		 * Yeah I know, browser sniffing is bad...
		 */
		ie7: !!(document.all && !document.querySelector),
		ie8: !!(document.all && document.querySelector && !document.getElementsByClassName),

		/*
		 * Infos about client browser
		 */
		iPhone:		!!navigator.userAgent.match(/iPhone/i),
		iPod:		!!navigator.userAgent.match(/iPod/i),
		iPad:		!!navigator.userAgent.match(/iPad/i),
		android:	!!navigator.userAgent.match(/Android/i)
	};

	// Post-processing
	$.template.iOs =		($.template.iPhone || $.template.iPod || $.template.iPad);
	$.template.touchOs =	($.template.iOs || $.template.android);

	// Normalized viewport size
	$.template.viewportWidth = win.width();
	$.template.viewportHeight = $.template.iPhone ? window.innerHeight : win.height();


	/********************************************************/
	/*                 3. Features detection                */
	/********************************************************/

	/*
	 * Respond.js v1.1.0rc1: min/max-width media query polyfill
	 * (c) Scott Jehl
	 * MIT/GPLv2 Lic
	 * j.mp/respondjs
	 *
	 * Important: the bundled version is slightly modded. Using another version may break compatibility for older browsers.
	 * See the file for more details.
	 */
	yepnope({
		test : Modernizr.mq('(min-width:0)'),
		nope : [$.template.respondPath+'respond.min.js']
	});


	/********************************************************/
	/*                 4. Touch optimization                */
	/********************************************************/

	/*
	 * Basic detection of touchmove events
	 * This allows to test on a 'touchend' event whether the 'touchmove' event was fired
	 * since 'touchstart'
	 */
	if (Modernizr.touch)
	{
		// Listen
		doc.on('touchstart', function(event)
		{
			touchMoved = false;
			++touchId;

		}).on('touchmove', function(event)
		{
			touchMoved = true;
		});
	}

	/**
	 * Function to determine if a touch-screen event (either touch or click) should be processed:
	 * - if this is a 'touchend' event, it checks if there was no 'touchmove' event since last 'touchstart'
	 * - if this is a 'click' event, it checks if the above 'touchend' event was not used
	 * @param DOM target the element on which the event is handled (not necessarily the event target)
	 * @param object event the fired event
	 * @return boolean return true if the event should be processed, else false
	 */
	$.template.processTouchClick = function(target, event)
	{
		// Missing param
		if (!event)
		{
			return true;
		}

		// Event type
		if (event.type === 'touchend')
		{
			// If no move was detected
			if (!touchMoved)
			{
				// Store last touchstart ID for later 'click' event
				$(target).data('touchstart-ID', touchId);

				// Valid event
				return true;
			}
			else
			{
				return false;
			}
		}
		else if (event.type === 'click')
		{
			// If a 'touchend' event was called on the same target since last 'touchstart'
			if ($(target).data('touchstart-ID') === touchId)
			{
				// Already processed
				return false;
			}
			else
			{
				return true;
			}
		}

		// Unknown type
		return true;
	};


	/********************************************************/
	/*              5. Position: fixed polyfill             */
	/********************************************************/

	/*
	 * Position: fixed support
	 * Modernizr fails at detecting it, so we must use a custom detection: a fixed element is created, and on first
	 * scroll the script will check its position to see if it is really fixed or not.
	 */

	// Create element
	fixedTest = $('<div style="position:fixed; top:0"></div>').appendTo(bod);
	function _checkPositionFixed()
	{
		// Top position
		var top = fixedTest.offset().top,
			scroll = doc.scrollTop();
		if (scroll < 2)
		{
			// Do not process if too small, it sometimes causes false positive
			return;
		}

		// Test
		if (top != scroll)
		{
			// Define
			supportFixed = false;

			// Prepare already listed elements
			fixed.css({
				right: 'auto',
				bottom: 'auto'
			});

			// Fonction to fix positions
			var scrollFixed = function()
			{
				_setPositionFixed(fixed);
			};

			// First run
			scrollFixed();

			// Start watching
			doc.on('scroll', scrollFixed);
			win.on('normalized-resize orientationchange', scrollFixed);
		}

		// End detection
		fixedTest.remove();
		doc.off('scroll', _checkPositionFixed);
	}
	doc.on('scroll', _checkPositionFixed);

	/**
	 * Process an elements list and set correct fixed position
	 * @param jQuery elements the jQuery selection of elements
	 * @return void
	 */
	function _setPositionFixed(elements)
	{
		// Current scroll
		var scrollTop = doc.scrollTop(),
			scrollLeft = doc.scrollLeft(),
			width = $.template.viewportWidth,
			height = $.template.viewportHeight;

		// Fix elements
		elements.each(function(i)
		{
			var element = $(this),
				positions = element.data('fixed-position'),
				offsetTop, offsetLeft;

			if (positions.top)
			{
				offsetTop = positions.top.percentage ? positions.top.value*height : positions.top.value;
				element.css('top', (scrollTop+offsetTop)+'px');
			}
			if (positions.left)
			{
				offsetLeft = positions.left.percentage ? positions.left.value*width : positions.left.value;
				element.css('left', (scrollLeft+offsetLeft)+'px');
			}
			if (positions.right)
			{
				if (positions.left)
				{
					element.width(width-offsetLeft-(positions.right.percentage ? positions.right.value*width : positions.right.value));
				}
				else
				{
					element.css('left', (scrollLeft+(positions.right.percentage ? (1-positions.right.value)*width : width-positions.right.value)-element.outerWidth())+'px');
				}
			}
			if (positions.bottom)
			{
				if (positions.top)
				{
					element.height(height-offsetTop-(positions.bottom.percentage ? positions.bottom.value*height : positions.bottom.value));
				}
				else
				{
					element.css('top', (scrollTop+(positions.bottom.percentage ? (1-positions.bottom.value)*height : height-positions.bottom.value)-element.outerHeight())+'px');
				}
			}
		});
	}

	/**
	 * Detect an absolutely positioned element's bounds
	 */
	$.fn.detectFixedBounds = function()
	{
		// Prepare
		this.css({
			top: '',
			right: '',
			bottom: '',
			left: '',
			width: '',
			height: ''
		});

		// Detect
		this.each(function()
		{
			// Prepare
			var element = $(this),
				sides = ['top', 'right', 'bottom', 'left'],
				positions = {},
				i, value;

			// Parse positions
			for (i = 0; i < sides.length; ++i)
			{
				value = element.css(sides[i]);
				if (value.match(/^-?[0-9]+px$/))
				{
					positions[sides[i]] = { value: parseInt(value, 10), percentage: false };
				}
				else if (value.match(/^-?[0-9]+px$/))
				{
					positions[sides[i]] = { value: parseFloat(value)/100, percentage: true };
				}
			}

			// Default positions
			if (!positions.top && !positions.bottom)
			{
				positions.top = { value: 0, percentage: false };
			}
			if (!positions.left && !positions.right)
			{
				positions.left = { value: 0, percentage: false };
			}

			// Store
			element.data('fixed-position', positions);
		});

		// Fix if already detected
		if (!supportFixed)
		{
			// Set bottom and right to auto
			this.css({
				right: 'auto',
				bottom: 'auto'
			});

			// Update position
			_setPositionFixed(this);
		}

		return this;
	};

	/**
	 * Function to register an element whose position needs to be fixed
	 */
	$.fn.enableFixedFallback = function()
	{
		// Store original position
		fixed = fixed.add(this.detectFixedBounds());
	};

	/**
	 * Function to remove an element whose position does not need anymore to be fixed
	 */
	$.fn.disableFixedFallback = function()
	{
		// Clean
		this.css({
			top: '',
			right: '',
			bottom: '',
			left: '',
			width: '',
			height: ''
		});

		// Remove
		fixed = fixed.not(this.removeData('fixed-position'));
	};

	/********************************************************/
	/*                 6. Generic functions                 */
	/********************************************************/

	/**
	 * Parse a css numeric value
	 *
	 * @param jQuery element the element whose property to parse
	 * @param string prop the name of the property
	 * @param int def the default value if parsing fails (default: 0)
	 * @return the parsed css value, or def
	 */
	$.fn.parseCSSValue = function(prop, def)
	{
		var parsed = parseInt(this.css(prop), 10);
		return isNaN(parsed) ? (def || 0) : parsed;
	};

	/**
	 * Test if an element has an inline CSS property set
	 *
	 * @param string prop the name of the property
	 * @return boolean true if set, else false
	 */
	$.fn.hasInlineCSS = function(prop)
	{
		// If empty
		if (this.length === 0)
		{
			return false;
		}

		var regex = new RegExp('(^| |\t|;)'+prop+'\s*:', 'i');
		return regex.test(this.getStyleString());
	};

	/**
	 * Return the element inline style string
	 * Note: for IE, the node.style.cssText is not raw, but as parsed by the browser (http://javascript.gakaa.com/style-csstext.aspx)
	 *
	 * @return string the style string
	 * @url http://stackoverflow.com/questions/4233273/howto-get-cross-browser-literal-style-string-with-javascript
	 */
	$.fn.getStyleString = function()
	{
		if (this.length === 0)
		{
			return '';
		}
		var string = !$.support.style ? this[0].style.cssText.toLowerCase() : this[0].getAttribute('style');
		return (string || '');
	};

	/**
	 * Get immediate siblings matching a selector at the beginning of a selection:
	 * The filter stops as soon as non-matching node is found
	 *
	 * @param string selector any jQuery selector string
	 * @param boolean fromLast use true to filter from the last element (default: false)
	 * @return the matching immediate siblings
	 */
	$.fn.filterFollowing = function(selector, fromLast)
	{
		// Build selection
		var selection = $(),
			next;

		// If no selector or no elements, no need to process
		if (!selector || selector === '')
		{
			return selection.add(this);
		}
		else if (this.length === 0)
		{
			return selection;
		}

		// Run through selection
		next = this[fromLast ? 'last' : 'first']();
		while (next.is(selector))
		{
			selection = selection.add(next);
			next = next[fromLast ? 'prev' : 'next']();
		}

		return selection;
	};

	/**
	 * Get immediate previous siblings matching a selector
	 * Different from prevAll() as it stops as soon as non-matching node is found
	 *
	 * @param string selector any jQuery selector string
	 * @return the matching immediate previous siblings
	 */
	$.fn.prevImmediates = function(selector)
	{
		return this.prevAll().filterFollowing(selector);
	};

	/**
	 * Get immediate next siblings matching a selector
	 * Different from nextAll() as it stops as soon as non-matching node is found
	 *
	 * @param string selector any jQuery selector string
	 * @return the matching immediate next siblings
	 */
	$.fn.nextImmediates = function(selector)
	{
		return this.nextAll().filterFollowing(selector);
	};

	/**
	 * Get immediate children siblings matching a selector
	 * Different from nextAll() as it stops as soon as non-matching node is found
	 *
	 * @param string selector any jQuery selector string
	 * @param boolean fromLast use true to filter from the last element (default: false)
	 * @return the matching immediate next siblings
	 */
	$.fn.childrenImmediates = function(selector, fromLast)
	{
		return this.children().filterFollowing(selector, fromLast);
	};

	/**
	 * Temporary show the element and its parents (use tempShowRevert() to revert to original style)
	 * @return the list of affected elements
	 */
	$.fn.tempShow = function()
	{
		// List of affected elements
		var affected = $();

		// Elements themselves
		this.each(function(i)
		{
			var element = $(this);

			// If the element is hidden
			if (element.css('display') === 'none')
			{
				affected = affected.add(element.show());
			}

			// Parents
			element.parentsUntil('body').each(function()
			{
				var parent = $(this),
					added = false;

				// If the element is hidden
				if (parent.css('display') === 'none')
				{
					affected = affected.add(parent.show());
					added = true;
				}

				// Special case for details content wrapper
				if (this.nodeName.toLowerCase() === 'details' && !this.open)
				{
					// Force open
					parent.prop('open', true).data('tempShowDetails', true);

					// Add to selection if needed
					if (!added)
					{
						affected = affected.add(parent);
					}
				}

				// Next round
				previous = parent;
			});
		});

		return affected;
	};

	/**
	 * Revert elements affected by tempShow() to their orignal state
	 */
	$.fn.tempShowRevert = function()
	{
		// Try to use defaut style, then check for elements that require inline style
		return this.css('display', '').each(function(i)
		{
			var element = $(this);

			// If still not hidden
			if (element.css('display') !== 'none' && !element.data('tempShowDetails'))
			{
				element.css('display', 'none');
			}

			// Special case for details content wrapper
			if (this.nodeName.toLowerCase() === 'details' && element.data('tempShowDetails'))
			{
				// Close again
				element.prop('open', false).removeData('tempShowDetails');
			}
		});
	};

	/********************************************************/
	/*                    7. Custom events                  */
	/********************************************************/

	/*
	 * The sizechange event is fired everytime an object size changes.
	 * The scrollsizechange event is a special event designed to fire when
	 * scrollWidth or scrollHeight change
	 */

	/**
	 * Object to handle the sizechange/scrollsizechange vars
	 * @var object
	 */
	var sizeWatcher = {

		/**
		 * List of elements being watched for the sizechange event
		 * @var jQuery
		 */
		sizeElements: $(),

		/**
		 * List of elements being watched for the widthchange event
		 * @var jQuery
		 */
		widthElements: $(),

		/**
		 * List of elements being watched for the heightchange event
		 * @var jQuery
		 */
		heightElements: $(),

		/**
		 * List of elements being watched for the scrollsizechange event
		 * @var jQuery
		 */
		scrollElements: $(),

		/**
		 * Check interval length, in milliseconds
		 * @var int
		 */
		interval: 250,

		/**
		 * Storage for the timeout id
		 * @var int|boolean
		 */
		timeout: false,

		/**
		 * Function checking each element scroll sizes
		 * @var function
		 */
		watch: function()
		{
			// Check elements
			if ($.isReady)
			{
				// Size check
				sizeWatcher.sizeElements.each(function(i)
				{
					var element = $(this),
						width = element.width(),
						height = element.height(),
						data = element.data('sizecache') || { width: 0, height: 0 };

					// If different
					if (width != data.width || height != data.height)
					{
						// Update data
						element.data('sizecache', {
							width: width,
							height: height
						});

						// Fire event
						element.trigger('sizechange', [width != data.width, height != data.height]);
					}
				});

				// Width check
				sizeWatcher.widthElements.each(function(i)
				{
					var element = $(this),
						width = element.width(),
						data = element.data('widthcache') || 0;

					// If different
					if (width != data)
					{
						// Update data
						element.data('widthcache', width);

						// Fire event
						element.trigger('widthchange', [width]);
					}
				});

				// Height check
				sizeWatcher.heightElements.each(function(i)
				{
					var element = $(this),
						height = element.height(),
						data = element.data('heightcache') || 0;

					// If different
					if (height != data)
					{
						// Update data
						element.data('heightcache', height);

						// Fire event
						element.trigger('heightchange', [height]);
					}
				});

				// Scroll size check
				sizeWatcher.scrollElements.each(function(i)
				{
					var element = $(this),
						width = this.scrollWidth,
						height = this.scrollHeight,
						data = element.data('scrollcache') || { width: 0, height: 0 };

					// If different
					if (width != data.width || height != data.height)
					{
						// Update data
						element.data('scrollcache', {
							width: width,
							height: height
						});

						// Fire event
						element.trigger('scrollsizechange', [width != data.width, height != data.height]);
					}
				});
			}

			// Next check
			sizeWatcher.timeout = setTimeout(sizeWatcher.watch, sizeWatcher.interval);
		},

		/**
		 * Start the watcher if needed
		 * @var function
		 */
		start: function()
		{
			// If not watching yet, start
			if (!sizeWatcher.timeout)
			{
				sizeWatcher.timeout = setTimeout(sizeWatcher.watch, sizeWatcher.interval);
			}
		},

		/**
		 * Stop the watcher if needed
		 * @var function
		 */
		stop: function()
		{
			// If no more elements are being watched, stop
			if (sizeWatcher.sizeElements.length === 0 && sizeWatcher.widthElements.length === 0 &&
				sizeWatcher.heightElements.length === 0 && sizeWatcher.scrollElements.length === 0)
			{
				clearTimeout(sizeWatcher.timeout);
			}
		}

	};

	// Define size change custom event
	$.event.special.sizechange = {

		/**
		 * This method gets called the first time the event is bound to an element.
		 */
		setup: function()
		{
			var element = $(this);

			// Store scroll sizes
			element.data('sizecache', {
				width: element.width(),
				height: element.height()
			});

			// Add element to watched list
			sizeWatcher.sizeElements = sizeWatcher.sizeElements.add(this);

			// Start watcher
			sizeWatcher.start();
		},

		/**
		 * This method gets called when the event is unbound from an element.
		 */
		teardown: function()
		{
			// Remove from watched list
			sizeWatcher.sizeElements = sizeWatcher.sizeElements.not(this);

			// Clear data
			$(this).removeData('sizecache');

			// Stop watcher
			sizeWatcher.stop();
		}

	};

	// Define width change custom event
	$.event.special.widthchange = {

		/**
		 * This method gets called the first time the event is bound to an element.
		 */
		setup: function()
		{
			var element = $(this);

			// Store scroll sizes
			element.data('widthcache', element.width());

			// Add element to watched list
			sizeWatcher.widthElements = sizeWatcher.widthElements.add(this);

			// Start watcher
			sizeWatcher.start();
		},

		/**
		 * This method gets called when the event is unbound from an element.
		 */
		teardown: function()
		{
			// Remove from watched list
			sizeWatcher.widthElements = sizeWatcher.widthElements.not(this);

			// Clear data
			$(this).removeData('widthcache');

			// Stop watcher
			sizeWatcher.stop();
		}

	};

	// Define height change custom event
	$.event.special.heightchange = {

		/**
		 * This method gets called the first time the event is bound to an element.
		 */
		setup: function()
		{
			var element = $(this);

			// Store scroll sizes
			element.data('heightcache', element.height());

			// Add element to watched list
			sizeWatcher.heightElements = sizeWatcher.heightElements.add(this);

			// Start watcher
			sizeWatcher.start();
		},

		/**
		 * This method gets called when the event is unbound from an element.
		 */
		teardown: function()
		{
			// Remove from watched list
			sizeWatcher.heightElements = sizeWatcher.heightElements.not(this);

			// Clear data
			$(this).removeData('heightcache');

			// Stop watcher
			sizeWatcher.stop();
		}

	};

	// Define scroll change custom event
	$.event.special.scrollsizechange = {

		/**
		 * This method gets called the first time the event is bound to an element.
		 */
		setup: function()
		{
			// Store scroll sizes
			$(this).data('scrollcache', {
				width: this.scrollWidth,
				height: this.scrollHeight
			});

			// Add element to watched list
			sizeWatcher.scrollElements = sizeWatcher.scrollElements.add(this);

			// Start watcher
			sizeWatcher.start();
		},

		/**
		 * This method gets called when the event is unbound from an element.
		 */
		teardown: function()
		{
			// Remove from watched list
			sizeWatcher.scrollElements = sizeWatcher.scrollElements.not(this);

			// Clear data
			$(this).removeData('scrollcache');

			// Stop watcher
			sizeWatcher.stop();
		}
	};

	/**
	 * Helper for sizechange event
	 * @param function fn a function to bind to the event, or nothing just to trigger the event
	 */
	$.fn.sizechange = function(fn)
	{
		return (typeof fn === 'function') ? this.on('sizechange', fn) : this.trigger('sizechange');
	};

	/**
	 * Helper for widthchange event
	 * @param function fn a function to bind to the event, or nothing just to trigger the event
	 */
	$.fn.widthchange = function(fn)
	{
		return (typeof fn === 'function') ? this.on('widthchange', fn) : this.trigger('widthchange');
	};

	/**
	 * Helper for heightchange event
	 * @param function fn a function to bind to the event, or nothing just to trigger the event
	 */
	$.fn.heightchange = function(fn)
	{
		return (typeof fn === 'function') ? this.on('heightchange', fn) : this.trigger('heightchange');
	};

	/**
	 * Helper for scrollsizechange event
	 * @param function fn a function to bind to the event, or nothing just to trigger the event
	 */
	$.fn.scrollsizechange = function(fn)
	{
		return (typeof fn === 'function') ? this.on('scrollsizechange', fn) : this.trigger('scrollsizechange');
	};

	/********************************************************/
	/*               8. DOM watching functions              */
	/********************************************************/

	/*
	 * The template has to perform some transformations on any inserted/modified/remove content, so we intercept main
	 * jQuery DOM methods to add a callback to the setup/clear functions.
	 *
	 * This feature is designed so developers won't need to call applySetup and applyClear functions everytime they change the DOM.
	 *
	 * On heavy applications, this may lead to some performance loss, so this feature can be disabled on demand.
	 */
	$.each([

		/*
		 * Each function can have a clear and a setup function
		 * Both can take several options:
		 * - prepare (setup only): if required, perform an initial selection to detect which elements are added/removed
		 * - target: function that returns the target of the clear/setup functions
		 * - self: whether the clear/setup functions should apply to the modified elements
		 * - subs: whether the clear/setup functions should apply to the modified elements children
		 */
		{
			name:	'wrapAll',
			clear:	false,
			setup:	{ prepare: false,
					target: function() { return this.parent(); },
					self: true, subs: false }
		},
		{
			name:	'wrapInner',
			clear:	false,
			setup:	{ prepare: false,
					target: function() { return this.children(); },
					self: true, subs: false }
		},
		{
			name:	'wrap',
			clear:	false,
			setup:	{ prepare: false,
					target: function() { return this.parent(); },
					self: true, subs: false }
		},
		{
			name:	'unwrap',
			clear:	{ target: function() { return this.parent(); },
					self: true, subs: false },
			setup:	false
		},
		{
			name:	'append',
			clear:	false,
			setup:	{ prepare: function() { return this.children(); },
					target: function(prepared) { return this.children().not(prepared); },
					self: true, subs: true }
		},
		{
			name:	'prepend',
			clear:	false,
			setup:	{ prepare: function() { return this.children(); },
					target: function(prepared) { return this.children().not(prepared); },
					self: true, subs: true }
		},
		{
			name:	'before',
			clear:	false,
			setup:	{ prepare: function() { return this.prevAll(); },
					target: function(prepared) { return this.prevAll().not(prepared); },
					self: true, subs: true }
		},
		{
			name:	'after',
			clear:	false,
			setup:	{ prepare: function() { return this.nextAll(); },
					target: function(prepared) { return this.nextAll().not(prepared); },
					self: true, subs: true }
		},
		{
			name:	'remove',
			clear:	{ target: function() { return this; },
					self: true, subs: true },
			setup:	false
		},
		{
			name:	'empty',
			clear:	{ target: function() { return this; },
					self: false, subs: true },
			setup:	false
		},
		{
			name:	'html',
			clear:	{ target: function() { return this; },
					self: false, subs: true },
			setup:	{ prepare: false,
					target: function() { return this; },
					self: true,  subs: false }
		}

	], function()
	{
		// Store original
		var func = this,
			original = $.fn[func.name];

		// New wrapper function
		$.fn[func.name] = function()
		{
			var target,
				prepared = false,
				result;

			if (autoWatch && watching)
			{
				// Clear dynamic elements
				if (func.clear)
				{
					func.clear.target.call(this).applyClear(func.clear.self, func.clear.sub);
				}

				// Preparation for setup
				if (func.setup && func.setup.prepare)
				{
					prepared = func.setup.prepare.call(this);
				}
			}

			// Call original
			watching = false;
			result = original.apply(this, Array.prototype.slice.call(arguments));
			watching = true;

			// Call template setup
			if (autoWatch && watching && func.setup)
			{
				func.setup.target.call(this, prepared).applySetup(func.setup.self, func.setup.sub);
			}

			return result;
		};
	});

	/**
	 * Enable DOM watching
	 * @return void
	 */
	$.template.enableDOMWatch = function()
	{
		autoWatch = true;
	};

	/**
	 * Disable DOM watching
	 * @return boolean whether DOM watching was activated before
	 */
	$.template.disableDOMWatch = function()
	{
		var previous = autoWatch;
		autoWatch = false;
		return previous;
	};

	/********************************************************/
	/*              9. Template setup functions             */
	/********************************************************/

	/**
	 * Add a new global clear function. The function should accept 2 arguments:
	 * - self (whether the target element should be affected or not)
	 * - children (whether the element's children should be affected or not)
	 * The function should also return the jQuery selection, incremented from any added element in the root set
	 * (Note: the function may use the custom method findIn() with the same arguments)
	 *
	 * @param function func the function to be called on a jQuery object
	 * @param boolean priority set to true to call the function before all others (optional, default false)
	 * @return void
	 */
	$.template.addClearFunction = function(func, priority)
	{
		clearFunctions[priority ? 'unshift' : 'push'](func);
	};

	/**
	 * Add a clear function on an element, with same format as $.template.addClearFunction()
	 * This function is primarily intended for removing template replacement elements,
	 * but may be used for any other purpose
	 *
	 * @param function func the function to be added
	 * @param boolean priority set to true to call the function before all others (optional)
	 */
	$.fn.addClearFunction = function(func, priority)
	{
		this.each(function(i)
		{
			var element = $(this),
			functions = element.data('clearFunctions') || [];
			if (!functions.length || $.inArray(func, functions) < 0)
			{
				functions[priority ? 'unshift' : 'push'](func);
				element.addClass('withClearFunctions').data('clearFunctions', functions);
			}
		});

		return this;
	};

	/**
	 * Remove a clear function from the element
	 *
	 * @param function func the function to be cleared
	 */
	$.fn.removeClearFunction = function(func)
	{
		this.each(function()
		{
			var element = $(this),
				functions = element.data('clearFunctions') || [],
				i;

			// Clear
			for (i = 0; i < functions.length; ++i)
			{
				if (functions[i] === func)
				{
					functions.splice(i, 1);
					--i;
				}
			}

			// If any function left
			if (functions.length > 0)
			{
				element.data('clearFunctions', functions);
			}
			else
			{
				element.removeClass('withClearFunctions').removeData('clearFunctions');
			}
		});

		return this;
	};

	/**
	 * Call every clear function over a jQuery object (for instance : $('body').applyClear())
	 *
	 * @param boolean self whether the current element should be affected or not (default: true)
	 * @param boolean children whether the element's children should be affected or not (default: true)
	 */
	$.fn.applyClear = function(self, children)
	{
		var element = this,
			isWatching = $.template.disableDOMWatch();

		// Defaults
		if (self === undefined) self = true;
		if (children === undefined) children = true;

		$.each(clearFunctions, function()
		{
			element = this.call(element, self, children);
		});

		// Re-enable DOM watching if required
		if (isWatching)
		{
			$.template.enableDOMWatch();
		}

		return this;
	};

	/**
	 * Add a new global setup function. The function should accept 2 arguments:
	 * - self (whether the current element should be affected or not)
	 * - children (whether the element's children should be affected or not)
	 * The function should also return the jQuery selection, incremented from any added element in the root set
	 * (Note: the function may use the custom method findIn() with the same arguments)
	 *
	 * @param function func the function to be called on a jQuery object
	 * @param boolean priority set to true to call the function before all others (optional, default false)
	 * @return void
	 */
	$.template.addSetupFunction = function(func, priority)
	{
		setupFunctions[priority ? 'unshift' : 'push'](func);
	};

	/**
	 * Call every template setup function over a jQuery object (for instance : $('body').applySetup())
	 *
	 * @param boolean self whether the current element should be affected or not (default: true)
	 * @param boolean children whether the element's children should be affected or not (default: true)
	 */
	$.fn.applySetup = function(self, children)
	{
		var element = this,
			isWatching = $.template.disableDOMWatch();

		// Defaults
		if (self === undefined) self = true;
		if (children === undefined) children = true;

		$.each(setupFunctions, function()
		{
			this.call(element, self, children);
		});

		// Re-enable DOM watching if required
		if (isWatching)
		{
			$.template.enableDOMWatch();
		}

		return this;
	};

	/**
	 * Custom find method to work with the clear/setup functions arguments self & children
	 * @param boolean self whether the current element should be included in the search or not
	 * @param boolean children whether the element's children should be in the search or not
	 * @param mixed selector any selector for jQuery's find() method
	 * @return the selection
	 */
	$.fn.findIn = function(self, children, selector)
	{
		var element = $(this);

		// Mode
		if (self && children)
		{
			return element.filter(selector).add(element.find(selector));
		}
		else
		{
			return element[self ? 'filter' : 'find'](selector);
		}
	};

	/********************************************************/
	/*                  10. Template setup                  */
	/********************************************************/

	// Main template setup function
	$.template.addSetupFunction(function(self, children)
	{
		// Details polyfill (only if loaded)
		if ($.fn.details)
		{
			this.findIn(self, children, 'details').details();
		}

		// Icons polyfill
		if ($('html').hasClass('no-generatedcontent'))
		{
			// Icons replacement map
			var iconMap = {
					'phone':		'!',
					'mobile':		'"',
					'tag':			'#',
					'directions':	'$',
					'mail':			'%',
					'pencil':		'&',
					'paperclip':	'\'',
					'reply':		'(',
					'replay-all':	')',
					'fwd':			'*',
					'user':			'+',
					'users':		',',
					'add-user':		'-',
					'card':			'.',
					'extract':		'/',
					'marker':		'0',
					'map':			'1',
					'compass':		'2',
					'arrow':		'3',
					'target':		'4',
					'path':			'5',
					'heart':		'6',
					'star':			'7',
					'like':			'8',
					'chat':			'9',
					'speech':		':',
					'quote':		';',
					'printer':		'<',
					'bell':			'=',
					'link':			'>',
					'flag':			'?',
					'gear':			'@',
					'flashlight':	'A',
					'cup':			'B',
					'price-tag':	'C',
					'camera':		'D',
					'moon':			'E',
					'palette':		'F',
					'leaf':			'G',
					'music-note':	'H',
					'bag':			'I',
					'plane':		'J',
					'buoy':			'K',
					'rain':			'L',
					'eye':			'M',
					'clock':		'N',
					'mic':			'O',
					'calendar':		'P',
					'lightning':	'Q',
					'hourglass':	'R',
					'rss':			'S',
					'wifi':			'T',
					'lock':			'U',
					'unlock':		'V',
					'tick':			'W',
					'cross':		'X',
					'minus-round':	'Y',
					'plus-round':	'Z',
					'cross-round':	'[',
					'minus':		'\\',
					'plus':			']',
					'forbidden':	'^',
					'info':			'_',
					'info-round':	'`',
					'question':		'a',
					'question-round':	'b',
					'warning':		'c',
					'redo':			'd',
					'undo':			'e',
					'swap':			'f',
					'revert':		'g',
					'refresh':		'h',
					'list':			'i',
					'list-add':		'j',
					'thumbs':		'k',
					'page-list':	'l',
					'page':			'm',
					'pages':		'n',
					'frame':		'o',
					'pictures':		'p',
					'movie':		'q',
					'music':		'r',
					'folder':		's',
					'drawer':		't',
					'trash':		'u',
					'outbox':		'v',
					'inbox':		'w',
					'download':		'x',
					'cloud':		'y',
					'cloud-upload':	'z',
					'play':			'{',
					'pause':		'|',
					'record':		'~',
					'forward':		'Ä',
					'backward':		'Å',
					'previous':		'Ç',
					'next':			'É',
					'expand':		'Ñ',
					'reduce':		'Ö',
					'volume':		'Ü',
					'loud':			'á',
					'mute':			'à',
					'left-fat':		'â',
					'down-fat':		'ä',
					'up-fat':		'ã',
					'right-fat':	'å',
					'left':			'ç',
					'down':			'é',
					'up':			'è',
					'right':		'ê',
					'left-round':	'ë',
					'down-round':	'í',
					'up-round':		'ì',
					'right-round':	'î',
					'home':			'ï',
					'ribbon':		'ñ',
					'read':			'ó',
					'new-tab':		'ò',
					'search':		'ô',
					'ellipsis':		'ö',
					'bullet-list':	'®',
					'creative-commons':	'©'
				};

			// Font-icons
			this.findIn(self, children, '[class^="icon-"],[class*=" icon-"]').each(function(i)
			{
				// Icon class
				var name = /icon-([^ ]+)/.exec(this.className)[1],
					element = $(this);

				// If valid icon name
				if (iconMap[name])
				{
					// Remove existing icon
					element.children('.icon-font:first').remove();

					// Create replacement
					element.prepend('<span class="font-icon'+(element.is(':empty') ? ' empty' : '')+'">'+iconMap[name]+'</span>');
				}
			});
		}

		// IE7 support
		if ($.template.ie7)
		{
			// Before/after pseudo-elements
			var pseudo = {
					'.bullet-list > li':		{ before: '<span class="bullet-list-before">k</span>' },
					'.info-bubble':				{ before: '<span class="info-bubble-before"></span>' },
					'.select-arrow':			{ before: '<span class="select-arrow-before"></span>', after: '<span class="select-arrow-after"></span>' },
					'.with-left-arrow, .with-right-arrow, .tabs > li > a':
												{ after: '<span class="with-arrow-after"></span>' },
					'#menu':					{ before: '<span id="menu-before"></span>', after: '<span id="menu-after"></span>' },
					'.number-up, .number-down':	{ after: '<span class="number-after"></span>' }
				},

				// Target for other scopes
				target = this;

			// Last-child
			this.findIn(self, children, 'ul, li, dd, p, fieldset, .fieldset, button, .button, input, .input-info, .field-drop, .select, .loader').filter(':last-child').addClass('last-child');

			// Before/after pseudo-elements
			$.each(pseudo, function(key, value)
			{
				var elements = target.findIn(self, children, key);
				if (elements.length > 0)
				{
					// Before
					if (value.before)
					{
						elements.prepend(value.before);
					}

					// After
					if (value.after)
					{
						elements.append(value.after);
					}
				}
			});

			// Button-icons
			var buttonIcons = this.findIn(self, children, '.button-icon');
			buttonIcons.not('.right-side').parent().css('padding-left', '0px').css('border-left', '0');
			buttonIcons.filter('.right-side').before('&nbsp;&nbsp;').parent().css('padding-right', '0px').css('border-right', '0');

			// Buttons in inputs
			var buttons = this.findIn(self, children, '.input').children('.button').not('.compact');
			buttons.each(function(i)
			{
				if (!this.previousSibling)
				{
					$(this).parent().css('padding-left', '0px');
				}
				else if (!this.nextSibling)
				{
					$(this).parent().css('padding-right', '0px');
				}
			});

			// Vertical centered images
			this.findIn(self, children, '.icon > img, .stack, .controls > :first-child').before('<span class="vert-align">&nbsp;</span>');
		}

		// IE8 support
		if ($.template.ie8)
		{
			// Last-child
			this.findIn(self, children, 'ul, li, dd, p, fieldset, .fieldset, button, .button, input, .input-info, .field-drop, .select, .loader').filter(':last-child').addClass('last-child');

			// Font-icons
			this.findIn(self, children, '[class^="icon-"],[class*=" icon-"]').each(function(i)
			{
				// Empty elements
				var element = $(this);
				if (element.is(':empty'))
				{
					element.addClass('font-icon-empty');
				}
			});
		}

		return this;
	});

	// Main template clear function
	$.template.addClearFunction(function(self, children)
	{
		var elements = this;

		// Add replacement elements' targets
		if (self)
		{
			elements.filter('.replacement').each(function(i)
			{
				var replaced = $(this).data('replaced');
				if (replaced)
				{
					elements = elements.add(replaced);
				}
			});
		}

		// Tracking/tracked elements
		elements.findIn(self, children, '.tracking').stopTracking().remove();
		elements.findIn(self, children, '.tracked').getTrackers().stopTracking().remove();

		// Elements with clear functions
		elements.findIn(self, children, '.withClearFunctions').each(function(i)
		{
			var target = this,
				element = $(target),
				functions = element.data('clearFunctions') || [];

			$.each(functions, function(i)
			{
				this.apply(target);
			});

			// Once called, functions are removed
			element.removeClass('withClearFunctions').removeData('clearFunctions');
		});

		return elements;
	});


	/********************************************************/
	/*            11. Viewport resizing handling            */
	/********************************************************/

	/**
	 * Updates the current media query name and the list of activated media queries according to a test element
	 * @param boolean triggerEvents true to trigger events
	 * @return boolean true if the media query changed
	 */
	function _refreshMediaQueriesInfo(triggerEvents)
	{
		// Can't test if not ready
		if (!init)
		{
			return false;
		}

		// Create test element
		var isWatching = $.template.disableDOMWatch(),
			test = $('<div id="mediaquery-checker"></div>').appendTo(bod),
			width = test.width(),
			height = test.height(),
			previousName = $.template.mediaQuery.name,
			changed, previousGroup, newGroup;

		// Clean test element
		test.remove();

		// Re-enable DOM watching if required
		if (isWatching)
		{
			$.template.enableDOMWatch();
		}

		// Check list
		$.template.mediaQuery.on = [];
		$.each(mediaQueries, function(index, value)
		{
			// Add to currently on list
			$.template.mediaQuery.on.push(value[1]);

			// If found
			if (width <= value[0])
			{
				$.template.mediaQuery.name = value[1];
				return false;
			}
		});

		// Hires status
		$.template.mediaQuery.hires = (height >= hiresTestHeight);

		// Detect change
		changed = (previousName != $.template.mediaQuery.name);

		// Events
		if (changed && triggerEvents)
		{
			// Detect groups
			if (previousName.indexOf('-') > -1)
			{
				previousGroup = previousName.split('-').shift();
			}
			if ($.template.mediaQuery.name.indexOf('-') > -1)
			{
				newGroup = $.template.mediaQuery.name.split('-').shift();
			}

			// Quit previous mode
			doc.trigger('quit-query-'+previousName);

			// If changing group
			if (previousGroup && (!newGroup || newGroup != previousGroup))
			{
				// Quit previous group
				doc.trigger('quit-query-'+previousGroup);
			}

			// Change event
			doc.trigger('change-query');

			// If changing group
			if (newGroup && (!previousGroup || previousGroup != newGroup))
			{
				// Enter new group
				doc.trigger('enter-query-'+newGroup);
			}

			// Enter new mode
			doc.trigger('enter-query-'+$.template.mediaQuery.name);
		}

		return changed;
	}

	// Window resizing handling
	function handleResize()
	{
		// Normalized viewport size
		$.template.viewportWidth = win.width();
		$.template.viewportHeight = $.template.iPhone ? window.innerHeight : win.height();

		// Send normalized pre-resize event
		win.trigger('normalized-preresize');

		// Refresh media queries infos
		_refreshMediaQueriesInfo(true);

		// Tracked elements
		bod.refreshInnerTrackedElements();

		// Send normalized resize event
		win.trigger('normalized-resize');

		// Ready to listen again
		resizeInt = false;
	}
	win.on('resize', function()
	{
		// If not set, create a timeout to handle the resize event
		// This is required for some browsers sending this event too often
		if (!resizeInt && $.isReady)
		{
			resizeInt = setTimeout(handleResize, 40);
		}

	}).on('orientationchange', handleResize);

	// Listener for respond.js when all files have been parsed
	doc.on('respond-ready', function()
	{
		_refreshMediaQueriesInfo(true);
	});


	/********************************************************/
	/*                   12. Template init                  */
	/********************************************************/

	// Template init function
	$.template.init = function()
	{
			// Objects
		var menu = $('#menu'),
			menuContent = $('#menu-content'),

			// Used to handle fixed menu on mobiles
			previousScroll = false,

			// Function to watch menu size
			watchMenuSize;

		// If already inited
		if (init)
		{
			return;
		}

		// Template ready
		init = true;

		// Refresh media queries infos
		_refreshMediaQueriesInfo(false);

		// Initial setup
		bod.applySetup();

		// Init queries events
		doc.trigger('init-queries');

		// Trigger enter event
		doc.trigger('enter-query-'+$.template.mediaQuery.name);

		// Open/hide menu
		$('#open-menu').on('touchend click', function(event)
		{
			event.preventDefault();

			// Check if valid touch-click event
			if (!$.template.processTouchClick(this, event))
			{
				return;
			}

			// Close shortcuts
			bod.removeClass('shortcuts-open');

			// If in wide screen mode, show/hide side menu, else open/close drop-down menu
			bod.toggleClass($.template.mediaQuery.is('desktop') || $.template.mediaQuery.is('tablet-landscape') ? 'menu-hidden' : 'menu-open');

			// If mobile layout, handle fixed title bar
			if ($.template.mediaQuery.is('mobile') && bod.hasClass('menu-open') && bod.hasClass('fixed-title-bar'))
			{
				// Store current scroll
				previousScroll = bod.scrollTop();

				// Remove fixed bar class
				bod.removeClass('fixed-title-bar');

				// Scroll to top
				bod.scrollTop(0);
			}
			else if (previousScroll !== false)
			{
				// Restore scroll
				if ($.template.mediaQuery.is('mobile'))
				{
					bod.scrollTop(previousScroll);
				}
				previousScroll = false;

				// Restore class
				bod.addClass('fixed-title-bar');
			}

			// Refresh drop-down menu size if needed
			watchMenuSize();
		});

		// Close drop-down menu
		bod.children().on('click', function(event)
		{
			// Check if open, and if the click is not on the menu or on the open button
			if (bod.hasClass('menu-open') && !$(event.target).closest('#open-menu, #menu').length)
			{
				// Fixed menu on mobile
				if (previousScroll !== false)
				{
					// Restore scroll
					if ($.template.mediaQuery.is('mobile'))
					{
						bod.scrollTop(previousScroll);
					}
					previousScroll = false;

					// Restore class
					bod.addClass('fixed-title-bar');
				}

				// Close menu
				bod.removeClass('menu-open');
			}
		});

		// Open/hide shortcuts
		$('#open-shortcuts').on('touchend click', function(event)
		{
			event.preventDefault();

			// Check if valid touch-click event
			if (!$.template.processTouchClick(this, event))
			{
				return;
			}

			// Fixed menu on mobile
			if (previousScroll !== false && bod.hasClass('menu-open'))
			{
				// Restore scroll
				if ($.template.mediaQuery.is('mobile'))
				{
					bod.scrollTop(previousScroll);
				}
				previousScroll = false;

				// Restore class
				bod.addClass('fixed-title-bar');
			}

			// Close menu and open shortcuts
			bod.removeClass('menu-open').toggleClass('shortcuts-open');
		});

		// When in tablet-portrait mode, we need to update the menu-content height manually
		watchMenuSize = function()
		{
			// Only works if drop-down menu is opened in tablet-portrait mode
			if (!bod.hasClass('menu-open') || !$.template.mediaQuery.is('tablet-portrait'))
			{
				menuContent.css('max-height', '');
				return;
			}

			var siblingsHeight = 0;

			// Get content siblings height
			menuContent.siblings().each(function(i)
			{
				siblingsHeight += $(this).outerHeight();
			});

			// Use available space (menu is 90% of viewport height)
			menuContent.css('max-height', (Math.round(0.9*$.template.viewportHeight)-(menu.outerHeight()-menu.height())-siblingsHeight)+'px');
		};

		// First call
		watchMenuSize();

		// Bind
		win.on('normalized-resize', watchMenuSize);

		// Custom scroll for menu (depends on media query)
		if ($.fn.customScroll)
		{
				// Current state
			var scrollMenu = false,
				scrollContent = false,

				// Handle media query changes
				updateMenuScroll = function()
				{
					// Mobile sizes
					if ($.template.mediaQuery.isSmallerThan('tablet-portrait'))
					{
						if (scrollMenu)
						{
							menu.removeCustomScroll();
							scrollMenu = false;
						}
						if (scrollContent)
						{
							menuContent.removeCustomScroll();
							scrollContent = false;
						}
					}
					// Tablet portrait
					else if ($.template.mediaQuery.is('tablet-portrait'))
					{
						if (scrollMenu)
						{
							menu.removeCustomScroll();
							scrollMenu = false;
						}
						if (!scrollContent)
						{
							menuContent.customScroll();
							scrollContent = true;
						}
					}
					// Tablet landscape and upper
					else
					{
						if (scrollContent)
						{
							menuContent.removeCustomScroll();
							scrollContent = false;
						}
						if (!scrollMenu)
						{
							menu.customScroll();
							scrollMenu = true;
						}
					}
				};

			// First call
			updateMenuScroll();

			// Bind
			doc.on('change-query', updateMenuScroll);
		}

		// Support for webapp mode on iOS
		if (('standalone' in window.navigator) && window.navigator.standalone)
		{
			$(document).on('click', 'a', function (event)
			{
				var link = $(this),
					href = link.attr('href');

				// Do not process anchors
				if (!href || href.indexOf('#') === 0)
				{
					return;
				}

				// Do not process if AJAX navigation link
				if (link.hasClass('navigable-ajax') || link.hasClass('navigable-ajax-loaded'))
				{
					return;
				}

				// Check target
				if (!(/^[a-z+\.\-]+:/i).test(href) || href.indexOf(document.location.protocol+'//'+document.location.host) === 0)
				{
					// Prevent link opening
					event.preventDefault();

					// Open inside the webapp
					document.location.href = href;
				}
			});
		}
	};

	// Initial setup
	doc.ready(function()
	{
		$.template.init();
	});

	/********************************************************/
	/*      13. Event delegation for template elements      */
	/********************************************************/

	/*
	 * Event delegation is used to handle most of the template setup, as it does also apply to dynamically added elements
	 * @see http://api.jquery.com/on/
	 */

	// Close buttons
	doc.on('click', '.close', function(event)
	{
		var close = $(this),
			parent = close.parent();

		event.preventDefault();

		close.remove();
		parent.addClass('closing').fadeAndRemove().trigger('close');
	});

	// Info bubbles
	if (Modernizr.touch)
	{
		doc.on('touchend', '.info-spot', function(event)
		{
			// Check if valid touch-click event
			if (!$.template.processTouchClick(this, event))
			{
				return;
			}

			var info = $(this),
				content = info.children('.info-bubble').html();

			// If any content is found
			if (content && content.length > 0)
			{
				event.preventDefault();

				// If the modal plugin is available
				if ($.modal)
				{
					$.modal.alert(content);
				}
				else
				{
					alert(content);
				}
			}
		});
	}
	else
	{
		// Check to see if the bubble need to open on another side to fit in the screen
		doc.on('mouseenter', '.info-spot', function(event)
		{
			var info = $(this),
				bubble = info.children('.info-bubble');

			// Check available space - horizontal
			if (info.hasClass('on-left'))
			{
				if (bubble.offset().left < 0)
				{
					info.removeClass('on-left')
						.data('info-spot-reverse-x', true);
				}
			}
			else
			{
				if (bubble.offset().left+bubble.outerWidth() > $.template.viewportWidth)
				{
					info.addClass('on-left')
						.data('info-spot-reverse-x', true);
				}
			}

			// Check available space - vertical
			if (info.hasClass('on-top'))
			{
				if (bubble.offset().top < doc.scrollTop())
				{
					info.removeClass('on-top')
						.data('info-spot-reverse-y', true);
				}
			}
			else
			{
				if (bubble.offset().top+bubble.outerHeight() > doc.scrollTop()+$.template.viewportHeight)
				{
					info.addClass('on-top')
						.data('info-spot-reverse-y', true);
				}
			}


		}).on('mouseleave', '.info-spot', function(event)
		{
			var info = $(this);

			// Check if reversed on open
			if (info.data('info-spot-reverse-x'))
			{
				info.toggleClass('on-left');
				info.removeData('info-spot-reverse-x');
			}
			if (info.data('info-spot-reverse-y'))
			{
				info.toggleClass('on-top');
				info.removeData('info-spot-reverse-y');
			}

		});
	}

	/*
	 * CSS pointerEvent polyfill for tooltips
	 * This snippet is from Lea Verou's CSS3 Secrets
	 * @url http://lea.verou.me/css3-secrets/
	 */
	if (!Modernizr.pointerevents)
	{
		doc.on('click mouseover', '.no-pointer-events', function (event)
		{
			// Hide element
			this.style.display = 'none';

			// Get element under cursor position
			var x = event.pageX, y = event.pageY,
				under = document.elementFromPoint(x, y);

			// Unhide
			this.style.display = '';

			// Proper event triggering
			event.stopPropagation();
			event.preventDefault();
			$(under).trigger(event.type);
		});
	}

	/********************************************************/
	/*                 14. Tracked elements                 */
	/********************************************************/

	/*
	 * Tracked elements methods add a convenient way of making an absolutely positioned follow an element in the document flow
	 * @param jquery element the jQuery object of the target element
	 */

	/**
	 * Make the current element track another element
	 *
	 * @param jQuery target the jQuery object of the target element
	 * @param function refreshFunc the function to refresh position (called with tracking element as 'this' and target as argument)
	 *								If none, the tracking element will be aligned with its target
	 */
	$.fn.trackElement = function(target, refreshFunc)
	{
		// Reduce selection if needed
		target = target.eq(0).addClass('tracked');

		// Function
		if (!refreshFunc)
		{
			refreshFunc = function(target) { $(this).offset(target.offset()); };
		}

		var targetDOM = target[0],
			tracking = target.data('tracking-elements') || [];

		this.css({ position:'absolute' }).addClass('tracking').each(function(i)
		{
			var element = $(this),
				tracked = element.data('tracked-element');

			// If already tracking but not the current target
			if (tracked && tracked !== targetDOM)
			{
				// Stop first
				element.stopTracking();
				tracked = null;
			}

			// If not already tracking target
			if (!tracked)
			{
				// Store references
				element.data('tracked-element', targetDOM);
				tracking.push({
					element: this,
					func: refreshFunc
				});

				// Make first call
				refreshFunc.call(this, target);
			}
		});

		// Update target
		target.data('tracking-elements', tracking);

		return this;
	};

	/**
	 * Stop a element from tracking
	 *
	 * @param boolean clearPos if true, will clean position styling (top & left)
	 */
	$.fn.stopTracking = function(clearPos)
	{
		// Remove
		this.each(function()
		{
			var element = $(this),
				tracked = element.data('tracked-element'),
				target, tracking, i;

			// If tracking
			if (tracked)
			{
				target = $(tracked);
				tracking = target.data('tracking-elements') || [];

				// Clear list from element
				for (i = 0; i < tracking.length; ++i)
				{
					if (tracking[i].element === this)
					{
						tracking.splice(i, 1);
						--i;
					}
				}

				// If no more elements are being watched, quit watching
				if (tracking.length === 0)
				{
					target.removeClass('tracked').removeData('tracking-elements');
				}
				else
				{
					target.data('tracking-elements', tracking);
				}

				// Clean data
				element.removeClass('tracking').removeData('tracked-element');

				// Clear position
				element.css({ position: '' });
				if (clearPos)
				{
					element.css({
						top: '',
						left: ''
					});
				}
			}
		});

		return this;
	};

	/**
	 * Updated tracking elements within selection
	 */
	$.fn.refreshTrackedElements = function()
	{
		this.filter('.tracked').each(function(i)
		{
			var target = $(this);
			$.each(target.data('tracking-elements') || [], function(i)
			{
				$(this.element).stop(true, true);
				this.func.call(this.element, target);
			});
		});

		return this;
	};

	// Tracked elements
	win.scroll(function()
	{
		bod.refreshInnerTrackedElements();
	});

	/**
	 * Update tracking elements in selection's inner elements
	 */
	$.fn.refreshInnerTrackedElements = function()
	{
		this.find('.tracked').each(function(i)
		{
			var target = $(this);
			$.each(target.data('tracking-elements') || [], function(i)
			{
				$(this.element).stop(true, true);
				this.func.call(this.element, target);
			});
		});

		return this;
	};

	/**
	 * Returns the jQuery list of tracking elements
	 */
	$.fn.getTrackers = function()
	{
		var list = [];
		$.each($(this).data('tracking-elements') || [], function(i)
		{
			list.push(this.element);
		});
		return $(list);
	};



	/********************************************************/
	/*                 15. Custom animations                */
	/********************************************************/

	/**
	 * Remove an element with folding effect
	 *
	 * @param string|int duration a string (fast, normal or slow) or a number of millisecond. Default: 'normal'. - optional
	 * @param function callback any function to call at the end of the effect. Default: none. - optional
	 */
	$.fn.foldAndRemove = function(duration, callback)
	{
		$(this).slideUp(duration, function()
		{
			// Callback function
			if (callback)
			{
				callback.apply(this);
			}

			$(this).remove();
		});

		return this;
	};

	/**
	 * Remove an element with fading then folding effect
	 *
	 * @param string|int duration a string (fast, normal or slow) or a number of millisecond. Default: 'normal'. - optional
	 * @param function callback any function to call at the end of the effect. Default: none. - optional
	 */
	$.fn.fadeAndRemove = function(duration, callback)
	{
		this.animate({'opacity': 0}, {
			'duration': duration,
			'complete': function()
			{
				var element = $(this).trigger('endfade');

				// No folding required if the element has position: absolute (not in the elements flow)
				if (element.css('position') == 'absolute')
				{
					// Callback function
					if (callback)
					{
						callback.apply(this);
					}

					element.remove();
				}
				else
				{
					element.slideUp(duration, function()
					{
						// Callback function
						if (callback)
						{
							callback.apply(this);
						}

						element.remove();
					});
				}
			}
		});

		return this;
	};

	/**
	 * Shake an element
	 * The jQuery UI's bounce effect messes with margins so let's build ours
	 *
	 * @param int force size (in pixels) of the movement (default: 15)
	 * @param function callback any function to call at the end of the effect. Default: none. - optional
	 */
	$.fn.shake = function(force, callback)
	{
		// Param check
		force = force || 15;

		this.each(function()
		{
			var element = $(this),

				// Initial margins
				leftMargin = element.parseCSSValue('margin-left'),
				rightMargin = element.parseCSSValue('margin-right'),

				// Force tweening
				steps = [
					force,
					Math.round(force*0.8),
					Math.round(force*0.6),
					Math.round(force*0.4),
					Math.round(force*0.2)
				],

				// Final range calculation
				effectMargins = [
					[leftMargin-steps[0], rightMargin+steps[0]],
					[leftMargin+steps[1], rightMargin-steps[1]],
					[leftMargin-steps[2], rightMargin+steps[2]],
					[leftMargin+steps[3], rightMargin-steps[3]],
					[leftMargin-steps[4], rightMargin+steps[4]],
					[leftMargin, leftMargin]
				];

			// Queue animations
			$.each(effectMargins, function(i)
			{
				var options = {
					duration: (i === 0) ? 40 : 80
				};

				// For last step
				if (i === 5)
				{
					options.complete = function()
					{
						// Reset margins
						$(this).css({
							marginLeft: '',
							marginRight: ''
						});

						// Callback
						if (callback)
						{
							callback.apply(this);
						}
					};
				}

				// Queue animation
				element.animate({ marginLeft: this[0]+'px', marginRight: this[1]+'px' }, options);
			});
		});

		return this;
	};

	/********************************************************/
	/*          16. Mobile browser chrome hidding           */
	/********************************************************/

	/*
	 * Normalized hide address bar for iOS & Android
	 * Inspired from Scott Jehl's post: http://24ways.org/2011/raising-the-bar-on-mobile
	 */

	// If there's a hash, stop here
	if (!location.hash)
	{
		// Scroll to 1
		window.scrollTo(0, 1);
		var scrollTop = 1,
			getScrollTop = function()
			{
				return window.pageYOffset || document.compatMode === 'CSS1Compat' && document.documentElement.scrollTop || document.body.scrollTop || 0;
			},

			// Reset to 0 on bodyready, if needed
			bodycheck = setInterval(function()
			{
				if (document.body)
				{
					clearInterval(bodycheck);
					scrollTop = getScrollTop();
					window.scrollTo(0, scrollTop === 1 ? 0 : 1);
				}
			}, 15);

		win.on('load', function()
		{
			setTimeout(function()
			{
				// At load, if user hasn't scrolled more than 20 or so...
				if (getScrollTop() < 20)
				{
					// Reset to hide addr bar at onload
					window.scrollTo(0, scrollTop === 1 ? 0 : 1);
				}
			}, 0);
		});
	}

	/********************************************************/
	/*                   17. Dependencies                   */
	/********************************************************/

	/*
	 * Add some easing functions if jQuery UI is not included
	 */
	if ($.easing.easeOutQuad === undefined)
	{
		$.easing.jswing = $.easing.swing;
		$.extend($.easing,
		{
			def: 'easeOutQuad',
			swing: function (x, t, b, c, d) {
				return $.easing[$.easing.def](x, t, b, c, d);
			},
			easeInQuad: function (x, t, b, c, d) {
				return c*(t/=d)*t + b;
			},
			easeOutQuad: function (x, t, b, c, d) {
				return -c *(t/=d)*(t-2) + b;
			},
			easeInOutQuad: function (x, t, b, c, d) {
				if ((t/=d/2) < 1) { return c/2*t*t + b; }
				return -c/2 * ((--t)*(t-2) - 1) + b;
			}
		});
	}

	/*
	 * Support for mousewheel event
	 * Copyright (c) 2010 Brandon Aaron (http://brandonaaron.net)
	 * Licensed under the MIT License (LICENSE.txt).
	 *
	 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
	 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
	 * Thanks to: Seamus Leahy for adding deltaX and deltaY
	 *
	 * Version: 3.0.4
	 *
	 * Requires: 1.2.2+
	 */

	// List of event names accross browsers
	var types = ['DOMMouseScroll', 'mousewheel'];

	// Event handler function
	function mouseWheelHandler(event)
	{
		var sentEvent = event || window.event,
			orgEvent = sentEvent.originalEvent || sentEvent,
			args = [].slice.call( arguments, 1 ),
			delta = 0,
			deltaX = 0,
			deltaY = 0;
			event = $.event.fix(orgEvent);
			event.type = "mousewheel";

		// Old school scrollwheel delta
		if ( orgEvent.wheelDelta ) { delta = orgEvent.wheelDelta/120; }
		if ( orgEvent.detail     ) { delta = -orgEvent.detail/3; }

		// New school multidimensional scroll (touchpads) deltas
		deltaY = delta;

		// Gecko
		if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
			deltaY = 0;
			deltaX = -1*delta;
		}

		// Webkit
		if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
		if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }

		// Add event and delta to the front of the arguments
		args.unshift(event, delta, deltaX, deltaY);

		return $.event.handle.apply(this, args);
	}

	// Register event
	$.event.special.mousewheel = {
		setup: function()
		{
			if (this.addEventListener)
			{
				for (var i=types.length; i;)
				{
					this.addEventListener(types[--i], mouseWheelHandler, false);
				}
			}
			else
			{
				this.onmousewheel = mouseWheelHandler;
			}
		},

		teardown: function()
		{
			if (this.removeEventListener)
			{
				for (var i=types.length; i;)
				{
					this.removeEventListener(types[--i], mouseWheelHandler, false);
				}
			}
			else
			{
				this.onmousewheel = null;
			}
		}
	};

	// Add methods
	$.fn.extend({
		mousewheel: function(fn)
		{
			return fn ? this.on("mousewheel", fn) : this.trigger("mousewheel");
		},

		unmousewheel: function(fn)
		{
			return this.off("mousewheel", fn);
		}
	});

})(this.jQuery, window, document);