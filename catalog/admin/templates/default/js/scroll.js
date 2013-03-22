/**
 * Custom scroll plugin
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
	 * document is passed through as local variable rather than as global, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var doc = $(document),

		// Check if device has a touch screen
		touch = $('html').hasClass('touch');

	/**
	 * Enable custom scroll bar
	 */
	$.fn.customScroll = function(options)
	{
		var globalSettings = $.extend({}, $.fn.customScroll.defaults, options);

		// For elements already scrolling, refresh
		this.filter('.custom-scroll').refreshCustomScroll();

		// Initial setup for others
		this.not('.custom-scroll').addClass('custom-scroll').each(function(i)
		{
			var element = $(this),

				// CSS position
				cssPos = element.css('position'),

				// Type of node for scrollbars
				scrollbarNodeType = element.is('ul, ol') ? 'li' : 'div',

				// Local settings, if inline options are found
				settings = $.extend({}, globalSettings, element.data('scroll-options')),

				// Work vars init
				scrollingH = (element[0].scrollWidth > element.innerWidth()),
				scrollingV = (element[0].scrollHeight > element.innerHeight()),
				scrollH = element.scrollLeft(),
				scrollV = element.scrollTop(),

				// References
				hscrollbar, hscroller,
				vscrollbar, vscroller,

				// Create and refresh functions
				createH = false, createV = false,
				refreshH = false, refreshV = false,

				// Scrollbars visibility
				hiddenH = false,
				hiddenV = false,

				// Check if scrollbar position was already set once
				init = false;

			// The plugin needs position relative, absolute or fixed
			if (cssPos !== 'relative' && cssPos !== 'absolute' && cssPos !== 'fixed')
			{
				element.css('position', 'relative');
			}

			// Format
			if (typeof settings.padding !== 'object')
			{
				settings.padding = {
					top: settings.padding,
					right: settings.padding,
					bottom: settings.padding,
					left: settings.padding
				};
			}
			settings.padding = $.extend({ top: 0, right: 0, bottom: 0, left: 0 }, settings.padding);

			/*
			 * Horizontal scrolling
			 */
			if (settings.horizontal)
			{
				/**
				 * Create horizontal scrollbar
				 */
				createH = function()
				{
					// Create elements
					hscrollbar = $('<'+scrollbarNodeType+' class="custom-hscrollbar"></'+scrollbarNodeType+'>').appendTo(element);
					hscroller = $('<div></div>').appendTo(hscrollbar);

					// Prevent click events from scrollbar
					hscrollbar.click(function(event)
					{
						event.stopPropagation();
					});

					// Prevent text selection for IE7
					hscroller.on('selectstart', _preventTextSelectionIE);

					// Scroller handling
					hscroller.on('mousedown', function(event)
					{
						// Get initial values
						var mouseX = event.pageX,
							hscrollerLeft = hscroller.parseCSSValue('left');

						// Stop text selection
						event.preventDefault();

						// Watch mouse move
						function watchMouse(event)
						{
							var availableSpace = hscrollbar.width()-hscroller.innerWidth(),
								hscrollerPos = Math.max(0, Math.min(availableSpace, hscrollerLeft+(event.pageX-mouseX)));

							// Scroller new position
							hscrollbar[0].style.display = 'none';
							scrollH = (availableSpace > 0) ? Math.round((hscrollerPos/availableSpace)*(element[0].scrollWidth-element.innerWidth())) : 0;
							hscrollbar[0].style.display = 'block';

							// Move
							if (settings.animate && init)
							{
								// Scroll
								element.stop(true).animate({ scrollLeft: scrollH }, {
									step: function()
									{
										$(this).refreshInnerTrackedElements();
									}
								});
							}
							else
							{
								// Scroll
								element.stop(true).scrollLeft(scrollH).refreshInnerTrackedElements();
							}

							// Update scrollbars
							if (refreshH) refreshH();
							if (refreshV) refreshV();
						};
						doc.on('mousemove', watchMouse);

						// Watch for mouseup
						function endDrag()
						{
							doc.off('mousemove', watchMouse);
							doc.off('mouseup', endDrag);
						};
						doc.on('mouseup', endDrag);
					});
				};

				// Init
				createH();

				/**
				 * Refresh horizontal scrollbar and scroll positions/sizes
				 */
				refreshH = function()
				{
					// If disabled
					if (hiddenH)
					{
						return;
					}

					// If scrollbar was removed by a random script
					if (!hscrollbar[0].parentNode)
					{
						createH();
					}

						// Element height
					var elementWidth = element.width(), elementInnerWidth = element.innerWidth(),

						// Margin if vertical scrollbar is enabled too
						vMargin = (settings.vertical && scrollingV && !hiddenV) ? settings.cornerWidth : 0,

						// Scroolbar width
						width = (settings.usePadding ? elementWidth : elementInnerWidth)-settings.padding.top-settings.padding.bottom-vMargin,

						// Minimum scroller width
						minWidth = (width > settings.minScrollerSize*1.5) ? settings.minScrollerSize : Math.round(width/1.5),

						// Available space for scroller
						available = width-minWidth,

						// Scroller size
						size = Math.round(available*(elementWidth/element[0].scrollWidth))+minWidth,

						// Scroller position
						position = Math.round((width-size)*(scrollH/(element[0].scrollWidth-elementInnerWidth)));

					// Reveal scrollbar (hidden in refresh()
					hscrollbar.show();

					// Set scrollbar style
					hscrollbar.stop(true)[(settings.animate && init) ? 'animate' : 'css']({

						// Position
						top: (element.innerHeight()-(settings.usePadding ? element.parseCSSValue('padding-bottom')+settings.padding.top : settings.padding.bottom)-settings.width+scrollV)+'px',
						left: ((settings.usePadding ? element.parseCSSValue('padding-left')+settings.padding.right : settings.padding.left)+scrollH)+'px',

						// Size
						width: width+'px',
						height: settings.width+'px',

						// Opacity
						opacity: (element.data('scroll-focus') || !settings.showOnHover) ? 1 : 0

					});

					// Set scroller style
					hscroller.stop(true)[(settings.animate && init) ? 'animate' : 'css']({

						// Position
						left: position+'px',

						// Size
						width: Math.round(size)+'px'

					});
				};
			}

			/*
			 * Vertical scrolling
			 */
			if (settings.vertical)
			{
				/**
				 * Create horizontal scrollbar
				 */
				createV = function()
				{
					// Create elements
					vscrollbar = $('<'+scrollbarNodeType+' class="custom-vscrollbar"></'+scrollbarNodeType+'>').appendTo(element);
					vscroller = $('<div></div>').appendTo(vscrollbar);

					// Prevent click events from scrollbar
					vscrollbar.click(function(event)
					{
						event.stopPropagation();
					});

					// Prevent text selection for IE7
					vscroller.on('selectstart', _preventTextSelectionIE);

					// Scroller handling
					vscroller.on('mousedown', function(event)
					{
						// Get initial values
						var mouseY = event.pageY,
							vscrollerTop = vscroller.parseCSSValue('top');

						// Prevent text selection
						event.preventDefault();

						// Watch mouse move
						function watchMouse(event)
						{
							// Scroller new position
							var availableSpace = vscrollbar.height()-vscroller.innerHeight(),
								vscrollerPos = Math.max(0, Math.min(availableSpace, vscrollerTop+(event.pageY-mouseY)));

							// Scroller new position
							vscrollbar[0].style.display = 'none';
							scrollV = (availableSpace > 0) ? Math.round((vscrollerPos/availableSpace)*(element[0].scrollHeight-element.innerHeight())) : 0;
							vscrollbar[0].style.display = 'block';

							// Move
							if (settings.animate && init)
							{
								// Scroll
								element.stop(true).animate({ scrollTop: scrollV }, {
									step: function()
									{
										$(this).refreshInnerTrackedElements();
									}
								});
							}
							else
							{
								// Scroll
								element.stop(true).scrollTop(scrollV).refreshInnerTrackedElements();
							}

							// Update scrollbars
							if (refreshH) refreshH();
							if (refreshV) refreshV();
						};
						doc.on('mousemove', watchMouse);

						// Watch for mouseup
						function endDrag(event)
						{
							event.preventDefault();

							doc.off('mousemove', watchMouse);
							doc.off('mouseup', endDrag);
						};
						doc.on('mouseup', endDrag);
					});
				};

				// Init
				createV();

				/**
				 * Refresh vertical scrollbar and scroll positions/sizes
				 */
				refreshV = function()
				{
					// If disabled
					if (hiddenV)
					{
						return;
					}

					// If scrollbar was removed by a random script
					if (!vscrollbar[0].parentNode)
					{
						createV();
					}

						// Element height
					var elementHeight = element.height(), elementInnerHeight = element.innerHeight(),

						// Margin if horizontal scrollbar is enabled too
						hMargin = (settings.horizontal && scrollingH && !hiddenH) ? settings.cornerWidth : 0,

						// Scroolbar height
						height = (settings.usePadding ? elementHeight : elementInnerHeight)-settings.padding.top-settings.padding.bottom-hMargin,

						// Minimum scroller height
						minHeight = (height > settings.minScrollerSize*1.5) ? settings.minScrollerSize : Math.round(height/1.5),

						// Available space for scroller
						available = height-minHeight,

						// Scroller size
						size = available*(elementHeight/element[0].scrollHeight)+minHeight,

						// Scroller position
						position = Math.round((height-size)*(scrollV/(element[0].scrollHeight-elementInnerHeight)));

					// Reveal scrollbar (hidden in refresh()
					vscrollbar.show();

					// Set scrollbar style
					vscrollbar.stop(true)[(settings.animate && init) ? 'animate' : 'css']({

						// Position
						top: ((settings.usePadding ? element.parseCSSValue('padding-top')+settings.padding.top : settings.padding.top)+scrollV)+'px',
						left: (element.innerWidth()-(settings.usePadding ? element.parseCSSValue('padding-right')+settings.padding.right : settings.padding.right)-settings.width+scrollH)+'px',

						// Size
						height: height+'px',
						width: settings.width+'px',

						// Opacity
						opacity: (element.data('scroll-focus') || !settings.showOnHover) ? 1 : 0

					});

					// Set scroller style
					vscroller.stop(true)[(settings.animate && init) ? 'animate' : 'css']({

						// Position
						top: position+'px',

						// Size
						height: Math.round(size)+'px'

					});
				};
			}

			/**
			 * Move function
			 * @param int deltaX move on the horizontal axis
			 * @param int deltaY move on the vertical axis
			 * @param boolean doNotAnimate true to skip animation
			 * @return object an object with two keys reporting effective movement { x:0, y:0 }
			 */
			function move(deltaX, deltaY, doNotAnimate)
			{
				// Store initial values
				var initScrollH = scrollH,
					initScrollV = scrollV;

				// New scroll values
				scrollH = Math.max(0, Math.min(scrollH+deltaX, element[0].scrollWidth-element.innerWidth()));
				scrollV = Math.max(0, Math.min(scrollV-deltaY, element[0].scrollHeight-element.innerHeight()));

				// Move
				if (settings.animate && !doNotAnimate && init)
				{
					// Scroll
					element.stop(true).animate({
						scrollLeft: scrollH,
						scrollTop: scrollV
					}, {
						step: function()
						{
							element.refreshInnerTrackedElements();
						}
					});
				}
				else
				{
					// Scroll
					element.scrollLeft(scrollH)
						   .scrollTop(scrollV)
						   .refreshInnerTrackedElements();
				}

				// Update scrollbars
				if (refreshH && deltaX != 0)
				{
					refreshH();
				}
				if (refreshV && deltaY != 0)
				{
					refreshV();
				}

				// Send report
				return {
					x: scrollH-initScrollH,
					y: scrollV-initScrollV
				};
			};

			/**
			 * Handle mouse wheel
			 * @param int deltaX scroll increment on the horizontal axis
			 * @param int deltaY scroll increment on the vertical axis
			 * @param boolean doNotAnimate true to skip animation
			 * @return object an object with two keys reporting effective movement { x:0, y:0 }
			 */
			// Handle mouse wheel
			function mousewheel(deltaX, deltaY, doNotAnimate)
			{
				/*
				 * Some mouse wheels send really small custom scroll deltas when using a custom driver,
				 * for instance 0.05 instead of 1, so we use a minimum value here to prevent these mouses
				 * to scroll too slow
				 */
				if (deltaX != 0)
				{
					deltaX = (deltaX > 0) ? Math.max(deltaX, settings.minWheelScroll) : Math.min(deltaX, -settings.minWheelScroll);
				}
				if (deltaY != 0)
				{
					deltaY = (deltaY > 0) ? Math.max(deltaY, settings.minWheelScroll) : Math.min(deltaY, -settings.minWheelScroll);
				}

				// Move
				return move(deltaX*settings.speed, deltaY*settings.speed, doNotAnimate);
			};

			// Global refresh function
			function refresh()
			{
				// Hide scrollbars to prevent erroneous values
				if (refreshH)
				{
					hscrollbar.hide();
				}
				if (refreshV)
				{
					vscrollbar.hide();
				}

				// Scrolling status
				scrollingH = (element[0].scrollWidth > element.innerWidth());
				scrollingV = (element[0].scrollHeight > element.innerHeight());

				// Update positions
				scrollH = element.scrollLeft();
				scrollV = element.scrollTop();

				// Horizontal scroll status
				if (refreshH)
				{
					hiddenH = (!scrollingH && settings.autoHide);
					refreshH();
				}

				// Vertical scroll status
				if (refreshV)
				{
					hiddenV = (!scrollingV && settings.autoHide);
					refreshV();
				}
			};

			// Store for further calls
			element.data('custom-scroll', {

				// Configuration
				settings: settings,

				// Objects
				hscrollbar:	function() { return hscrollbar;	},
				hscroller:	function() { return hscroller;	},
				vscrollbar:	function() { return vscrollbar;	},
				vscroller:	function() { return vscroller;	},

				// Functions
				refresh: refresh,
				refreshH: refreshV,
				refreshV: refreshV,
				move: move,
				mousewheel: mousewheel

			});

			// First call
			refresh();

			// Fade effect
			if (settings.showOnHover)
			{
				// Initial hiding
				if (hscrollbar) hscrollbar.css({ opacity: 0 });
				if (vscrollbar) vscrollbar.css({ opacity: 0 });

				// Watch
				if (touch)
				{
					element.on('touchstart', _handleScrolledMouseEnter)
						   .on('touchend', _handleScrolledMouseLeave)
				}
				else
				{
					element.on('mouseenter', _handleScrolledMouseEnter)
						   .on('mouseleave', _handleScrolledMouseLeave);
				}
			}

			// Mark as inited
			init = true;

		}).on('mousewheel', _handleMouseWheel)
		  .on('scroll sizechange scrollsizechange', _handleScroll)
		  .on('touchstart', _handleTouchScroll);

		return this;
	};

	/**
	 * Remove custom scroll
	 */
	$.fn.removeCustomScroll = function()
	{
		this.filter('.custom-scroll')
			.off('mousewheel', _handleMouseWheel)
			.off('scroll sizechange scrollsizechange', _handleScroll)
		  	.off('touchstart', _handleTouchScroll)
			.off('touchstart', _handleScrolledMouseEnter)
			.off('touchend', _handleScrolledMouseLeave)
			.off('mouseenter', _handleScrolledMouseEnter)
			.off('mouseleave', _handleScrolledMouseLeave)
			.removeData('scroll-options').removeData('touch-scrolling')
			.removeClass('custom-scroll')
			.children('.custom-hscrollbar, .custom-vscrollbar').remove()
			.scrollLeft(0)
			.scrollTop(0);

		return this;
	};

	/**
	 * Internal function: used to prevent text selection under IE (event distint from 'mousedown')
	 *
	 * @return void
	 */
	function _preventTextSelectionIE(event)
	{
		event.preventDefault();
	}

	/**
	 * Internal function: handle fade in effect on mouse hover
	 *
	 * @return void
	 */
	function _handleScrolledMouseEnter()
	{
		var element = $(this),
			object = element.data('custom-scroll');

		// If valid
		if (object)
		{
			element.data('scroll-focus', true);
			if (object.hscrollbar()) object.hscrollbar().animate({ opacity: 1 });
			if (object.vscrollbar()) object.vscrollbar().animate({ opacity: 1 });
		}
	};

	/**
	 * Internal function: handle fade out effect on mouse leave
	 *
	 * @return void
	 */
	function _handleScrolledMouseLeave()
	{
		var element = $(this),
			object = element.data('custom-scroll');

		// If valid
		if (object)
		{
			element.removeData('scroll-focus');
			if (object.hscrollbar()) object.hscrollbar().animate({ opacity: 0 });
			if (object.vscrollbar()) object.vscrollbar().animate({ opacity: 0 });
		}
	};

	/**
	 * Internal function: handle mousewheel event
	 *
	 * @param object event the event object
	 * @param float delta the vertical delta (historical)
	 * @param float deltaX the vertical delta
	 * @param float deltaY the horizontal delta
	 * @return void
	 */
	function _handleMouseWheel(event, delta, deltaX, deltaY)
	{
		if (object = $(this).data('custom-scroll'))
		{
			// Send scroll
			var movement = object.mousewheel(deltaX, deltaY);

			// If the element scrolled
			if (movement.x != 0 || movement.y != 0 || !object.settings.continuousWheelScroll)
			{
				// Prevent parents from scrolling
				event.preventDefault();
			}
		}
	};

	/**
	 * Internal function: handle scroll event
	 */
	function _handleScroll(event)
	{
		$(this).refreshCustomScroll();
	};

	/**
	 * Internal function: handle touch scroll
	 */
	function _handleTouchScroll(event)
	{
		// Init
		var element = $(this),
			object = element.data('custom-scroll'),
			posX = event.originalEvent.touches[0].pageX, /* jQuery event normalization does not preserve touch events properties */
			posY = event.originalEvent.touches[0].pageY,
			moveFunc, endFunc, movement;

		// If not already touching
		if (object && !element.data('touch-scrolling'))
		{
			// Handle moves
			moveFunc = function(event)
			{
				// Mark as touching
				element.data('touch-scrolling', true);

				// Movement
				var newX = event.originalEvent.touches[0].pageX,
					newY = event.originalEvent.touches[0].pageY;

				// Scroll
				movement = object.move(posX-newX, newY-posY, true);

				// If the element scrolled
				if (movement.x !== 0 || movement.y !== 0 || !object.settings.continuousTouchScroll)
				{
					// Prevent parents from scrolling
					event.preventDefault();
				}

				// Store for next move
				posX = newX;
				posY = newY;
			};

			// Handle end of touch event
			endFunc = function(event)
			{
				// Stop watching
				element.off('touchmove', moveFunc);
				element.off('touchend touchcancel', endFunc);

				// Clear data
				element.removeData('touch-scrolling');
			};

			// Start watching
			element.on('touchmove', moveFunc);
			element.on('touchend touchcancel', endFunc);
		}
	}

	/**
	 * Tell whether the element has custom scrolling
	 * @return boolean true if scrolling, else false
	 */
	$.fn.hasCustomScroll = function()
	{
		return this.data('custom-scroll') ? true : false;
	};

	/**
	 * Refreshes custom scroll bar position
	 */
	$.fn.refreshCustomScroll = function()
	{
		this.each(function(i)
		{
			var object = $(this).data('custom-scroll');
			if (object)
			{
				object.refresh();
			}
		});

		return this;
	};

	/**
	 * Refreshes custom scroll bar position
	 * @param int deltaX the move on the X axis
	 * @param int deltaY the move on the Y axis
	 * @param boolean doNotAnimate true to skip animation
	 */
	$.fn.moveCustomScroll = function(deltaX, deltaY, doNotAnimate)
	{
		this.each(function(i)
		{
			var object = $(this).data('custom-scroll');
			if (object)
			{
				object.move(deltaX, deltaY, doNotAnimate);
			}
		});

		return this;
	};

	/**
	 * Scroll all custom-scroll parent if required to reveal the element
	 */
	$.fn.scrollToReveal = function()
	{
		this.each(function(i)
		{
			var element = $(this),
				scrollParents = element.parents('.custom-scroll');

			// Check for each scroll parent
			scrollParents.each(function(i)
			{
				var scrollParent = $(this),
					scrollOffset, offset,
					parent, object,
					width, height,
					viewWidth, viewHeight, paddings,
					scrollX = 0, scrollY = 0;

				// Scroll object
				object = scrollParent.data('custom-scroll');
				if (!object)
				{
					return;
				}

				// DOM element
				parent = scrollParent[0];

				// Element position
				offset = element.offset();
				scrollOffset = scrollParent.offset();
				offset.top -= scrollOffset.top+scrollParent.parseCSSValue('border-top-width');
				offset.left -= scrollOffset.left+scrollParent.parseCSSValue('border-left-width');

				// Size
				width = element.outerWidth();
				height = element.outerHeight();

				// Paddings
				paddings = {
					top:	object.settings.usePadding ? scrollParent.parseCSSValue('padding-top') : 0,
					right:	object.settings.usePadding ? scrollParent.parseCSSValue('padding-right') : 0,
					bottom:	object.settings.usePadding ? scrollParent.parseCSSValue('padding-bottom') : 0,
					left:	object.settings.usePadding ? scrollParent.parseCSSValue('padding-left') : 0
				};

				// Visible range
				viewWidth = scrollParent.innerWidth();
				viewHeight = scrollParent.innerHeight();

				// Horizontal scroll
				if (offset.left < paddings.left)
				{
					scrollX = paddings.left-offset.left;
				}
				else if (offset.left+width > viewWidth-paddings.right)
				{
					scrollX = viewWidth-paddings.right-offset.left-width;
				}

				// Vertical scroll
				if (offset.top < paddings.top)
				{
					scrollY = paddings.top-offset.top;
				}
				else if (offset.top+height > viewHeight-paddings.bottom)
				{
					scrollY = viewHeight-paddings.bottom-offset.top-height;
				}

				// If any scroll is required
				if (scrollX !== 0 || scrollY !== 0)
				{
					object.move(scrollX, scrollY);
				}
			});
		});

		return this;
	};

	/**
	 * Custom scroll function defaults
	 * @var object
	 */
	$.fn.customScroll.defaults = {
		/**
		 * Horizontal scrolling
		 * @var boolean
		 */
		horizontal: false,

		/**
		 * Vertical scrolling
		 * @var boolean
		 */
		vertical: true,

		/**
		 * Whether to use or ignore element's padding in the scrollbar position
		 * @var boolean
		 */
		usePadding: false,

		/**
		 * Padding around scrollbar (can be a single value if regular, or an object
		 * with 'top', 'right', 'bottom' and 'left' - unset values will be set to 0)
		 * @var int|object
		 */
		padding: 6,

		/**
		 * Scrollbar's width in pixels
		 * @var int
		 */
		width: 8,

		/**
		 * Size of empty space in the corner of both scrollbars when they are enabled
		 * @var int
		 */
		cornerWidth: 10,

		/**
		 * Scroller minimum size, in pixels (will automatically be resized for scrollbars smaller than this value)
		 * @var int
		 */
		minScrollerSize: 30,

		/**
		 * Minimun wheel scroll increment (prevent mouses with custom driver to scroll too slowly)
		 * @var float
		 */
		minWheelScroll: 0.25,

		/**
		 * Use true to let the parent element scroll when the target can not scroll no more (on mouse wheel)
		 * @var boolean
		 */
		continuousWheelScroll: true,

		/**
		 * Use true to let the parent element scroll when the target can not scroll no more (on touch move)
		 * @var boolean
		 */
		continuousTouchScroll: true,

		/**
		 * Speed: move for each mouse scroll
		 * @var int
		 */
		speed: 48,

		/**
		 * Animate scroll movement
		 * @var boolean
		 */
		animate: false,

		/**
		 * Show scrollbars only on hover
		 * @var boolean
		 */
		showOnHover: true,

		/**
		 * Hide useless scrollbars
		 * @var boolean
		 */
		autoHide: true
	};

	// Add to template setup function
	$.template.addSetupFunction(function(self, children)
	{
		// Custom scroll
		this.findIn(self, children, '.scrollable').customScroll();

		return this;
	});

})(jQuery, document);