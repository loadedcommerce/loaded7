/**
 *
 * Tooltip plugin
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

		// Current exclusive tooltip
		exclusive = false;

	/**
	 * Internal reference: the div holding standard tooltips
	 * @var jQuery
	 */
	var _standardTooltipsDiv = false;

	/**
	 * Internal function: retrieve the div holding standard tooltips
	 *
	 * @return jQuery the div selection
	 */
	function _getStandardTooltipsDiv()
	{
		if (!_standardTooltipsDiv)
		{
			_standardTooltipsDiv = $('<div id="tooltips"></div>').appendTo(document.body);
		}

		return _standardTooltipsDiv;
	};

	/**
	 * Internal reference: the div holding tooltips over modals and notifications
	 * @var jQuery
	 */
	var _overTooltipsDiv = false;

	/**
	 * Internal function: retrieve the div holding tooltips over modals and notifications
	 *
	 * @return jQuery the div selection
	 */
	function _getOverTooltipsDiv()
	{
		if (!_overTooltipsDiv)
		{
			_overTooltipsDiv = $('<div id="tooltips-over"></div>').appendTo(document.body);
		}

		return _overTooltipsDiv;
	};

	/**
	 * Check if a content is valid
	 * @param mixed content the value to check
	 * @return boolean true if valid, else false
	 */
	function _isValidContent(content)
	{
		return ((content instanceof jQuery) || typeof content === 'function' || (typeof content === 'string' && $.trim(content).length > 0));
	};

	/**
	 * Parse the content or try to extract it from the element
	 * @param mixed content (see tooltip() for details)
	 * @param jQuery target the target element
	 * @return string|jQuery|boolean the content, or false if none
	 */
	function _parseContent(content, target)
	{
		var title, children;

		// If valid
		if (_isValidContent(content))
		{
			return content;
		}

		// Test if content set as data-tooltip-content
		content = target.data('tooltip-content');
		if (_isValidContent(content))
		{
			return content;
		}

		// Test if there is a stored title
		title = target.data('tooltip-title');
		if (title)
		{
			return title.value;
		}

		// Check title attribute
		if (target[0].title && $.trim(target[0].title).length > 0)
		{
			content = target[0].title;
			target[0].title = '';
			target.data('tooltip-title', {
				value:		content,
				element:	target[0]
			});

			return content;
		}

		// For elements with an unique child, use the child title
		children = target.children();
		if (children.length === 1 && children[0].title && $.trim(children[0].title).length > 0)
		{
			content = children[0].title;
			children[0].title = '';
			target.data('tooltip-title', {
				value:		content,
				element:	children[0]
			});

			return content;
		}

		// No content
		return false;
	};

	/**
	 * Restore element's title is needed
	 * @param jQuery target the target element
	 * @return void
	 */
	function _restoreTitle(target)
	{
		// Test if there is a stored title
		var title = target.data('tooltip-title');
		if (title)
		{
			title.element.title = title.value;
			target.removeData('tooltip-title');
		}
	};

	/**
	 * Display a tooltip over an element. If the page is not yet ready, delay the tooltip until it is ready.
	 *
	 * @var string|function|jQuery content a text or html content to display, or a function to run on the element to get the content
	 * (can be omitted, auto-detect if not defined or empty)
	 * @var object options an object with any options for the tooltip - optional (see defaults for more details). If not set, the function
	 * will try to retrieve any option of an existing or delayed tooltip on the same element, so when changing the content of a tooltip
	 * just call the function without options
	 */
	$.fn.tooltip = function(content, options)
	{
		// Settings
		var globalSettings = $.extend({}, $.fn.tooltip.defaults, options),

			// If no options were given
			noOptions = false;

		// Options format
		if (typeof content === 'object' && !(content instanceof jQuery))
		{
			options = content;
			content = '';
		}
		if (!options || typeof options !== 'object')
		{
			noOptions = true;
			options = {};
		}

		// Initial setup
		this.each(function(i)
		{
				// Tooltip target
			var target = $(this),

				// Is the target a replacement element?
				replacement = target.data('replaced'),

				// Inline settings
				inlineOptions = target.data('tooltip-options') || (replacement ? (replacement.data('tooltip-options') || {}) : {}),

				// Check if a tooltip is delayed for creation
				awaiting = target.data('tooltip-awaiting'),

				// Ajax promise (if any) and loaded data
				promise = false,

				// Functions
				onMouseleave, onBlur, onClick;

			/*
			 * If the document is not ready or we want some delay
			 */
			if (!$.isReady || (!options.ignoreDelay && (options.delay > 0 || inlineOptions.delay > 0)))
			{
				var delay = inlineOptions.delay || options.delay || 40,

					// Options
					thisOptions = options,

					// Timeout ID
					timeout,

					// Functions
					abort;

				// Parse content
				content = _parseContent(content, target);

				// If there is already a delayed tooltip
				if (awaiting)
				{
					// Stop timeout
					if (awaiting.abort() === false)
					{
						return;
					}

					// Merge options
					if (noOptions)
					{
						thisOptions = $.extend({}, awaiting.options);
					}
				}

				// Close on mouseleave
				if (thisOptions.removeOnMouseleave)
				{
					// Callback function
					onMouseleave = function(event)
					{
						// Abort tooltip
						abort();
					}

					// Bind
					target.on('mouseleave', onMouseleave);
				}

				// Close on click anywhere else
				if (thisOptions.removeOnBlur)
				{
					// Callback function
					onBlur = function(event)
					{
						// Abort tooltip
						abort();
					}

					// Bind
					doc.on('click touchend', onBlur);
				}

				// Function to abort tooltip
				abort = function(force, doNotRestore)
				{
					// Callback
					if (thisOptions.onAbort)
					{
						if (settings.onAbort.call(tooltip[0], target) === false && !force)
						{
							return false;
						}
					}

					// Stop timeout
					clearTimeout(timeout);

					// Clear data
					target.removeData('tooltip-awaiting');

					// Listeners
					if (onMouseleave)
					{
						target.off('mouseleave', onMouseleave);
					}
					if (onBlur)
					{
						doc.off('click touchend', onBlur);
					}

					// Stored title
					if (!doNotRestore)
					{
						_restoreTitle(target);
					}
				};

				// Store
				target.data('tooltip-awaiting', {
					options: thisOptions,
					abort: abort
				});

				// Delay
				timeout = setTimeout(function()
				{
					abort(false, true);
					target.tooltip(content, $.extend(thisOptions, { ignoreDelay: true }));

				}, delay);
			}
			/*
			 * Show tooltip
			 */
			else
			{
					// Check if a tooltip already exists
				var previous = target.data('tooltip'),

					// Previous tooltip settings
					previousSettings = {},

					// If there is a previous tooltip, do not animate
					skipAnimation = false,

					// Options from the delayed tooltip
					awaitingOptions = {},

					// Options
					settings,

					// Objects
					div, tooltip, arrow, optionHolder,

					// Dom working
					dom, domHidden = false, placeholder,

					// Work vars
					noPointerEvents, arrowOffset, animValues, removeAnimValues,

					// Functions
					updatePosition, removeTooltip, endRemove;

				// If a tooltip already exists
				if (previous)
				{
					// If blocking, exit
					if (previous.settings.lock && (noOptions || !options.fromAjax))
					{
						return;
					}

					// Remove
					if (previous.removeTooltip(false, true) === false)
					{
						return;
					}

					// Retrieve previous settings
					if (noOptions)
					{
						previousSettings = previous.settings;
					}

					// Skip animation
					skipAnimation = true;
				}

				// If another tooltip is awaiting
				if (awaiting)
				{
					// If blocking, exit
					if (awaiting.options.lock)
					{
						return;
					}

					// Abort
					if (awaiting.abort() === false)
					{
						return;
					}

					// Retrieve options
					if (noOptions)
					{
						awaitingOptions = awaiting.options;
					}
				}

				// Check for tooltip alignement classes
				if (!options.position && !previousSettings.position && !awaitingOptions.position)
				{
					optionHolder = target.closest('.tooltip-top, .tooltip-right, .tooltip-bottom, .tooltip-left');
					if (optionHolder.length > 0)
					{
						awaitingOptions.position = /tooltip-(top|right|bottom|left)/.exec(optionHolder[0].className)[1];
					}
				}

				// Final settings
				settings = $.extend({}, globalSettings, inlineOptions, previousSettings, awaitingOptions);

				// Ajax loading
				if (settings.ajax && !settings.fromAjax)
				{
					// Mode
					if (typeof settings.ajax === 'object')
					{
						promise = settings.ajax;
					}
					else
					{
						promise = $.ajax(settings.ajax, settings.ajaxOptions);
					}

					// Prevent loading again by next tooltip
					settings.fromAjax = true;

					// On success
					promise.done(function(data)
					{
						// Check if tooltip is still visible
						var current = target.data('tooltip');
						if (current)
						{
							target.tooltip(data, settings);
						}
					});

					// On error
					promise.fail(function()
					{
						// Check if tooltip is still visible
						var current = target.data('tooltip');
						if (current)
						{
							target.tooltip(settings.ajaxErrorMessage, settings);
						}
					});
				}

				// If content is a function
				if (typeof content === 'function')
				{
					content = content.apply(this);
				}

				// Parse content
				content = _parseContent(content, target);
				if (content === false)
				{
					// No content, abort
					return;
				}
				if (content instanceof jQuery)
				{
					// Use dom element
					dom = content;
					content = '';
				}

				// Init
				div = (target.closest('.notification, .modal').length > 0) ? _getOverTooltipsDiv() : _getStandardTooltipsDiv();
				animateDistance = (settings.animate && !skipAnimation) ? settings.animateMove : 0;

				// If exclusive, remove existing one
				if (settings.exclusive && exclusive)
				{
					// The remove animation is skipped to prevent callbacks to fire in the wrong order
					if (exclusive.removeTooltip(false, true) === false)
					{
						return;
					}
				}

				// Create element
				noPointerEvents = settings.noPointerEvents ? ' no-pointer-events' : '';
				tooltip = $('<div class="message '+settings.classes.join(' ')+noPointerEvents+'">'+content+'</div>')
							.appendTo(div)
							.data('tooltip-target', target);

				// Dom content
				if (dom)
				{
					// If hidden
					if (!dom.is(':visible'))
					{
						domHidden = true;
						dom.show();
					}

					// Check if already in the document
					if (dom.parent().length > 0)
					{
						placeholder = $('<span style="display:none"></span>').insertBefore(dom);
						dom.detach();
					}

					// Insert
					tooltip.append(dom);
				}

				// Arrow
				switch (settings.position.toLowerCase())
				{
					case 'right':
						arrow = $('<span class="block-arrow left"><span></span></span>').appendTo(tooltip);
						arrowOffset = arrow.parseCSSValue('margin-top');
						break;

					case 'bottom':
						arrow = $('<span class="block-arrow top"><span></span></span>').appendTo(tooltip);
						arrowOffset = arrow.parseCSSValue('margin-left');
						break;

					case 'left':
						arrow = $('<span class="block-arrow right"><span></span></span>').appendTo(tooltip);
						arrowOffset = arrow.parseCSSValue('margin-top');
						break;

					default:
						arrow = $('<span class="block-arrow"><span></span></span>').appendTo(tooltip);
						arrowOffset = arrow.parseCSSValue('margin-left');
						break;
				}

				// Function to update position
				updatePosition = function(target)
				{
					var targetpos = target.offset(),
						targetWidth = target.outerWidth(),
						targetHeight = target.outerHeight(),
						tooltipWidth = tooltip.outerWidth(),
						tooltipHeight = tooltip.outerHeight(),
						docWidth = $.template.viewportWidth,
						docHeight = $.template.viewportHeight,
						top, left, offset,
						arrowExtraOffset = 0;

					switch (settings.position)
					{
						case 'right':
							// Default position
							top = targetpos.top+Math.round(targetHeight/2)-Math.round(tooltipHeight/2);
							left = targetpos.left+targetWidth+settings.spacing;

							// Bounds check - horizontal
							if (left+tooltipWidth > docWidth-settings.screenPadding)
							{
								// Revert
								left = targetpos.left-tooltipWidth-settings.spacing;
								animateDistance *= -1;
								arrow.removeClass('left').addClass('right');
							}
							else
							{
								arrow.removeClass('right').addClass('left');
							}

							// Bounds check - vertical
							if (top < settings.screenPadding+doc.scrollLeft())
							{
								offset = settings.screenPadding-top;
								arrowExtraOffset = -Math.min(offset, Math.round(tooltipHeight/2)-settings.arrowMargin);
								top += offset;
							}
							else if (top+tooltipHeight > docHeight-settings.screenPadding)
							{
								offset = docHeight-settings.screenPadding-tooltipHeight-top;
								arrowExtraOffset = Math.min(-offset, Math.round(tooltipHeight/2)-settings.arrowMargin);
								left += offset;
							}

							// Animation init
							left -= animateDistance;
							break;

						case 'bottom':
							// Default position
							top = targetpos.top+targetHeight+settings.spacing;
							left = targetpos.left+Math.round(targetWidth/2)-Math.round(tooltipWidth/2);

							// Bounds check - horizontal
							if (left < settings.screenPadding)
							{
								offset = settings.screenPadding-left;
								arrowExtraOffset = -Math.min(offset, Math.round(tooltipWidth/2)-settings.arrowMargin);
								left += offset;
							}
							else if (left+tooltipWidth > docWidth-settings.screenPadding)
							{
								offset = docWidth-settings.screenPadding-tooltipWidth-left;
								arrowExtraOffset = Math.min(-offset, Math.round(tooltipWidth/2)-settings.arrowMargin);
								left += offset;
							}

							// Bounds check - vertical
							if (top+tooltipHeight > docHeight-settings.screenPadding+doc.scrollTop())
							{
								// Revert
								top = targetpos.top-tooltipHeight-settings.spacing;
								animateDistance *= -1;
								arrow.removeClass('top').addClass('bottom');
							}
							else
							{
								arrow.removeClass('bottom').addClass('top');
							}

							// Animation init
							top -= animateDistance;
							break;

						case 'left':
							// Default position
							top = targetpos.top+Math.round(targetHeight/2)-Math.round(tooltipHeight/2);
							left = targetpos.left-tooltipWidth-settings.spacing;

							// Bounds check - horizontal
							if (left < settings.screenPadding+doc.scrollLeft())
							{
								// Revert
								left = targetpos.left+targetWidth+settings.spacing;
								animateDistance *= -1;
								arrow.removeClass('right').addClass('left');
							}
							else
							{
								arrow.removeClass('left').addClass('right');
							}

							// Bounds check - vertical
							if (top < settings.screenPadding)
							{
								offset = settings.screenPadding-top;
								arrowExtraOffset = -Math.min(offset, Math.round(tooltipHeight/2)-settings.arrowMargin);
								top += offset;
							}
							else if (top+tooltipHeight > docHeight-settings.screenPadding)
							{
								offset = docHeight-settings.screenPadding-tooltipHeight-top;
								arrowExtraOffset = Math.min(-offset, Math.round(tooltipHeight/2)-settings.arrowMargin);
								left += offset;
							}

							// Animation init
							left += animateDistance;
							break;

						default:
							// Default position
							top = targetpos.top-tooltipHeight-settings.spacing;
							left = targetpos.left+Math.round(targetWidth/2)-Math.round(tooltipWidth/2);

							// Bounds check - horizontal
							if (left < settings.screenPadding)
							{
								offset = settings.screenPadding-left;
								arrowExtraOffset = -Math.min(offset, Math.round(tooltipWidth/2)-settings.arrowMargin);
								left += offset;
							}
							else if (left+tooltipWidth > docWidth-settings.screenPadding)
							{
								offset = docWidth-settings.screenPadding-tooltipWidth-left;
								arrowExtraOffset = Math.min(-offset, Math.round(tooltipWidth/2)-settings.arrowMargin);
								left += offset;
							}

							// Bounds check - vertical
							if (top < settings.screenPadding+doc.scrollTop())
							{
								// Revert
								top = targetpos.top+targetHeight+settings.spacing;
								animateDistance *= -1;
								arrow.removeClass('bottom').addClass('top');
							}
							else
							{
								arrow.removeClass('top').addClass('bottom');
							}

							// Animation init
							top += animateDistance;
							break;
					}

					// Set positions
					tooltip.offset({
						top: top,
						left: left
					});
					if (settings.position === 'left' || settings.position === 'right')
					{
						arrow.css('margin-top', (arrowExtraOffset === 0) ? '' : (arrowOffset+arrowExtraOffset)+'px');
					}
					else
					{
						arrow.css('margin-left', (arrowExtraOffset === 0) ? '' : (arrowOffset+arrowExtraOffset)+'px');
					}
				}

				// Watch movement (will set position)
				tooltip.trackElement(target, updatePosition);

				// Show animation
				if (settings.animate)
				{
					// Prepare
					animValues = {
						opacity: 1
					};
					removeAnimValues = {
						opacity: 0
					};

					// Move
					if (animateDistance != 0)
					{
						switch (settings.position)
						{
							case 'right':
								animValues.left = '+='+animateDistance+'px';
								removeAnimValues.left = '-='+animateDistance+'px';
								break;

							case 'bottom':
								animValues.top = '+='+animateDistance+'px';
								removeAnimValues.top = '-='+animateDistance+'px';
								break;

							case 'left':
								animValues.left = '-='+animateDistance+'px';
								removeAnimValues.left = '+='+animateDistance+'px';
								break;

							default:
								animValues.top = '-='+animateDistance+'px';
								removeAnimValues.top = '+='+animateDistance+'px';
								break;
						}

						// Reset initial animation distance for further positioning
						animateDistance = 0;
					}

					// If no previous tip was replaced
					if (!skipAnimation)
					{
						// Here we go!
						tooltip.css({ opacity: 0 }).animate(animValues, settings.animateSpeed);
					}
				}

				// Remove
				removeTooltip = function(force, skipAnimation)
				{
					// Callback
					if (settings.onRemove)
					{
						if (settings.onRemove.call(tooltip[0], target) === false && !force)
						{
							return false;
						}
					}

					// Listeners
					if (onMouseleave)
					{
						target.off('mouseleave', onMouseleave);
					}
					if (onBlur)
					{
						doc.off('click touchend', onBlur);
					}
					if (onClick)
					{
						tooltip.off('click touchend', onClick);
					}

					// Clear data
					target.removeData('tooltip');

					// If exclusive, clear data
					if (settings.exclusive)
					{
						exclusive = false;
					}

					// Animation
					if (settings.animate && !skipAnimation)
					{
						// Remove
						tooltip.addClass('tooltip-removed').animate(removeAnimValues, settings.animateSpeed, endRemove);
					}
					else
					{
						// Finalize
						endRemove();
					}

					return true;
				};

				// Finalize remove
				endRemove = function()
				{
					// Stored title
					_restoreTitle(target);

					// If pulled from the dom
					if (placeholder)
					{
						dom.detach().insertAfter(placeholder);
						placeholder.remove();
					}

					// If hidden
					if (domHidden)
					{
						dom.hide();
					}

					// Remove
					tooltip.remove();
				};

				// Store
				target.data('tooltip', {
					element: tooltip,
					settings: settings,
					updatePosition: updatePosition,
					removeTooltip: removeTooltip
				});

				// If exclusive, store
				if (settings.exclusive)
				{
					exclusive = {
						removeTooltip: removeTooltip,
						dom: dom
					};
				}

				// Close on mouseleave
				if (settings.removeOnMouseleave)
				{
					// Callback function
					onMouseleave = function(event)
					{
						// Remove tooltip
						removeTooltip();
					}

					// Bind
					target.on('mouseleave', onMouseleave);
				}

				// Close on click anywhere else
				if (settings.removeOnBlur)
				{
					// Prevent inner click propagation
					tooltip.on('click touchend', function(event)
					{
						event.preventDefault();
					});

					// Callback function
					onBlur = function(event)
					{
						// Do not process if default is prevented (most probably trigerred from inside the tooltip)
						if (event.isDefaultPrevented())
						{
							return;
						}

						// Remove tooltip
						removeTooltip();
					}

					// Bind
					doc.on('click touchend', onBlur);
				}

				// Close on click on tooltip
				if (settings.removeOnClick && !settings.noPointerEvents)
				{
					// Callback function
					onClick = function(event)
					{
						// Remove tooltip
						removeTooltip();
					}

					// Bind
					tooltip.on('click touchend', onClick);
				}

				// Callback
				if (settings.onShow)
				{
					settings.onShow.call(tooltip[0], target);
				}
			}
		});

		return this;
	};

	/**
	 * Remove tooltip
	 * @param boolean force use true to close tooltips even when the onClose/onAbort callback functions return false (optional, default: false)
	 * @param boolean skipAnimation use true to disable the close animation (optional, default: false)
	 */
	$.fn.removeTooltip = function(force, skipAnimation)
	{
		this.each(function(i)
		{
			var target = $(this),
				tooltip = target.data('tooltip'),
				awaiting = target.data('tooltip-awaiting'),
				title;

			// If found
			if (tooltip)
			{
				// Remove
				if (tooltip.removeTooltip(force, skipAnimation) === false)
				{
					return;
				}
			}

			// If there is a delayed tooltip
			if (awaiting)
			{
				// Abort
				if (awaiting.abort(force) === false)
				{
					return;
				}
			};
		});

		return this;
	};

	/**
	 * Open a tooltip menu on click on any element
	 * @var string|function|jQuery content a text or html content to display, or a function to run on the element to get the content
	 * @var object options an object with any options for the tooltip - optional (see defaults for more details)
	 * @var string eventName the event on which to open the menu - optional (default: 'click')
	 */
	$.fn.menuTooltip = function(content, options, eventName)
	{
		// Parameters
		eventName = eventName || 'click';

		// Bind event
		this.on(eventName, function(event)
		{
			event.preventDefault();
			event.stopPropagation();

			// Open menu
			$(this).tooltip(content, $.extend({

				lock:				true,
				exclusive:			true,
				removeOnBlur:		true,
				noPointerEvents:	false

			}, options));
		});
	};

	/**
	 * Tooltip function defaults
	 * @var object
	 */
	$.fn.tooltip.defaults = {
		/**
		 * Position: 'top', 'right', 'bottom' or 'left'
		 * @var string
		 */
		position: 'top',

		/**
		 * Space between tooltip and the target element
		 * @var int
		 */
		spacing: 10,

		/**
		 * Extra classes (colors...)
		 * @var array
		 */
		classes: [],

		/**
		 * Prevent the tooltip from interacting with mouse
		 * @var boolean
		 */
		noPointerEvents: true,

		/**
		 * When true, prevent any other tooltip to show on the same target
		 * @var boolean
		 */
		lock: false,

		/**
		 * When true, will close any other open exclusive tooltip before showing
		 * @var boolean
		 */
		exclusive: false,

		/**
		 * Animate show/hide
		 * @var boolean
		 */
		animate: true,

		/**
		 * Animate movement (positive value will move outwards)
		 * @var int
		 */
		animateMove: 10,

		/**
		 * Animate speed (time (ms) value or jQuery spped string)
		 * @var int|string
		 */
		animateSpeed: 'fast',

		/**
		 * Delay before showing the tooltip
		 * @var int
		 */
		delay: 0,

		/**
		 * Ajax content loading: url to load or Promise object returned by an $.ajax() call
		 * @var string|object
		 */
		ajax: null,

		/**
		 * Options for the ajax call (same as $.ajax())
		 * @var object
		 */
		ajaxOptions: {},

		/**
		 * Message to display in tooltip if ajax request fails (text or html)
		 * @var string
		 */
		ajaxErrorMessage: 'Error while loading data',

		/**
		 * Minimum distance from screen border
		 * @var int
		 */
		screenPadding: 10,

		/**
		 * Minimum spacing of tooltip arrow from border when tooltip is moved to fit in screen
		 * @var int
		 */
		arrowMargin: 10,

		/**
		 * Hide the tooltip when the mouse hovers out of the target element
		 * @var boolean
		 */
		removeOnMouseleave: false,

		/**
		 * Hide the tooltip when the user clicks anywhere else in the page
		 * @var boolean
		 */
		removeOnBlur: false,

		/**
		 * Hide the tooltip when the user clicks on the tooltip (only works if noPointerEvents is false)
		 * @var boolean
		 */
		removeOnClick: false,

		/**
		 * Callback on tooltip opening: function(target)
		 * Scope: the tooltip
		 * @var function
		 */
		onShow: null,

		/**
		 * Callback on tooltip remove: function(target)
		 * Note: the function may return false to prevent close.
		 * Scope: the tooltip
		 * @var function
		 */
		onRemove: null,

		/**
		 * Callback on delayed tooltip abort: function(target)
		 * Note: the function may return false to prevent abort.
		 * Scope: the target
		 * @var function
		 */
		onAbort: null
	};

	// Event binding
	if (!Modernizr || !Modernizr.touch)
	{
		doc.on('mouseenter', '.with-tooltip, .children-tooltip > *', function(event)
		{
			var element = $(this),
				parent = element.parent(),
				options = {
					delay:				100,
					removeOnMouseleave:	true
				};

			// Configuration for tooltips triggered by a parent element
			if (parent.hasClass('children-tooltip'))
			{
				options = $.extend(options, parent.data('tooltip-options'));
			}

			// Show tooltip
			element.tooltip(options);

		});
	}

})(jQuery, window, document);