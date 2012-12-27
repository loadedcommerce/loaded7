/**
 *
 * Modal window plugin
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

		// Viewport dimensions
		viewportWidth = $.template.viewportWidth,
		viewportHeight = $.template.viewportHeight;

	// Update on viewport resize
	win.on('normalized-resize orientationchange', function()
	{
		// Previous viewport dimensions
		var previousWidth = viewportWidth,
			previousHeight = viewportHeight,
			widthChange, heightChange;

		// New dimensions
		viewportWidth = $.template.viewportWidth;
		viewportHeight = $.template.viewportHeight;

		// Size changes
		widthChange = Math.round((viewportWidth-previousWidth)/2);
		heightChange = Math.round((viewportHeight-previousHeight)/2);

		// Check windows size/position
		$.modal.all.each(function(i)
		{
			var modal = $(this),
				data = modal.data('modal');

			// If valid
			if (data)
			{
				// Update max-sizes
				data.updateMaxSizes();

				// Redefine position relative to screen center
				data.setPosition(
					modal.parseCSSValue('left')+widthChange,
					modal.parseCSSValue('top')+heightChange
				);
			}
		});
	});

	/**
	 * Return the modal windows root div
	 * @return jQuery the jQuery object of the root div
	 */
	function getModalRoot()
	{
		var root = $('#modals');
		if (root.length == 0)
		{
			// Create element
			root = $('<div id="modals"></div>').appendTo(document.body);

			// Add to position:fixed fallback
			if ($.fn.enableFixedFallback)
			{
				root.enableFixedFallback();
			}
		}

		return root;
	};

	/**
	 * Opens a new modal window
	 * @param object options an object with any of the $.modal.defaults options.
	 * @return object the jQuery object of the new window
	 */
	$.modal = function(options)
	{
		var settings = $.extend({}, $.modal.defaults, options),
			root = getModalRoot(),

			// Elements
			modal, barBlock, contentBg, contentBlock,
			actionsBlock = false, buttonsBlock = false,

			// Max sizes
			maxWidth, maxHeight,

			// Blocker layer
			wasBlocked, blocker = false,

			// DOM content
			dom, domHidden = false, placeholder,

			// Vars for handleResize and handleMove
			modalX = 0,
			modalY = 0,
			contentWidth = 0,
			contentHeight = 0,
			mouseX = 0,
			mouseY = 0,
			resized,
			handleResize, endResize,
			handleMove, endMove,

			// Vars for markup building
			title = settings.title ? '<h3>'+settings.title+'</h3>' : '',
			titleBar = (settings.titleBar || (settings.titleBar === null && title.length > 0)) ? '<div class="modal-bar">'+title+'</div>' : '',
			sizeParts = new Array(), contentWrapper,
			spacingClass = '',

			/**
			 * Remove DOM content
			 * @return void
			 */
			removeDom = function()
			{
				// If DOM content is on
				if (dom)
				{
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

					// Reset
					dom = false;
					domHidden = false;
					placeholder = false;
				}
			},

			/**
			 * Set content, eventually wrapping it in beforeContent/afterContent if needed
			 * @param string|jQuery content the conntent to append
			 * @return void
			 */
			setContent = function(content)
			{
				var domWrapper;

				// Not available for iframes
				if (settings.useIframe)
				{
					return;
				}

				// Remove existing content
				removeDom();
				contentBlock.empty();

				if (typeof(content) !== 'string')
				{
					// Use dom content
					dom = settings.content;

					// This is required to handle DOM insertion when using beforeContent/afterContent
					content = '<span class="modal-dom-wrapper"></span>';

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
				}

				// Insert
				contentBlock.append(settings.beforeContent+content+settings.afterContent);

				// DOM
				if (dom)
				{
					// Retrieve placeholder
					domWrapper = contentBlock.find('.modal-dom-wrapper');

					// Insert
					dom.insertAfter(domWrapper);

					// Remove placeholder
					domWrapper.remove();
				}
			},

			/**
			 * Set window content-block size
			 * @param int|boolean width the width to set, true to keep current or false for fluid width (false only works if not iframe)
			 * @param int|boolean height the height to set, true to keep current or false for fluid height (false only works if not iframe)
			 * @return void
			 */
			setContentSize = function(width, height)
			{
				var scrollX, scrollY, css = {};

				// If nothing changes
				if (width === true && height === true)
				{
					return;
				}

				// Mode
				if (settings.useIframe)
				{
					if (typeof width === 'number')
					{
						contentBlock.prop('width', Math.min(width, maxWidth));
					}
					if (typeof height === 'number')
					{
						contentBlock.prop('height', Math.min(height, maxHeight));
					}
				}
				else
				{
					// If width change
					if (width !== true)
					{
						// Apply width first
						contentBlock.css({
							width: width ? width+'px' : ''
						});

						// Refresh tabs if any
						if ($.fn.refreshInnerTabs)
						{
							contentBlock.refreshInnerTabs();
						}

						// Check if everything fits
						scrollX = contentBlock.prop('scrollWidth');
						if (scrollX > width)
						{
							contentBlock.css({
								width: scrollX+'px'
							});
						}
					}

					// Then set height
					if (width !== true)
					{
						contentBlock.css({
							height: height ? height+'px' : ''
						});
					}

					// Check if everything fits
					scrollY = contentBlock.prop('scrollHeight');
					if (scrollY > height)
					{
						contentBlock.css({
							height: scrollY+'px'
						});
					}
				}
			},

			/**
			 * Set modal position
			 * @param int x the horizontal position
			 * @param int y the vertical position
			 * @return void
			 */
			setPosition = function(x, y, animate)
			{
				// Set position
				modal[animate ? 'animate' : 'css']({
					left:	Math.min(Math.max(0, x), viewportWidth-modal.outerWidth()),
					top:	Math.min(Math.max(0, y), viewportHeight-modal.outerHeight())
				});
			},

			/**
			 * Load ajax content or set iframe url
			 * @param string url the url to load
			 * @param object options options for AJAX loading (ignored if using iFrame)
			 * @return void
			 */
			loadContent = function(url, options)
			{
				// Mode
				if (settings.useIframe)
				{
					contentBlock.prop('src', url);
				}
				else
				{
					// Settings with local scope callbacks
					var ajaxOptions = $.extend({}, $.modal.defaults.ajax, options, {

						// Handle loaded content
						success: function(data, textStatus, jqXHR)
						{
							// Set content
							setContent(data);

							// Resize
							if (ajaxOptions.resize || ajaxOptions.resizeOnLoad)
							{
								setContentSize(true, false);
							}

							// Call user callback
							if (options.success)
							{
								options.success.call(this, data, textStatus, jqXHR);
							}
						}

					});

					// If no error callback
					if (!ajaxOptions.error && ajaxOptions.errorMessage)
					{
						ajaxOptions.error = function(jqXHR, textStatus, errorThrown)
						{
							setContent(ajaxOptions.errorMessage);
							if (ajaxOptions.resize || ajaxOptions.resizeOnMessage)
							{
								setContentSize(true, false);
							}
						}
					}

					// If loading message
					if (ajaxOptions.loadingMessage)
					{
						setContent(ajaxOptions.loadingMessage);
						if (ajaxOptions.resize || ajaxOptions.resizeOnMessage)
						{
							setContentSize(true, false);
						}
					}

					// Load content
					$.ajax(url, ajaxOptions);
				}
			},

			/**
			 * Set the modal title, creating/removing elements as needed
			 * @param string title the new title, or false/empty string for no title
			 * @return void
			 */
			setTitle = function(title)
			{
				var h3;

				// If no title bar, quit
				if (settings.titleBar === false)
				{
					return;
				}

				// If set
				if (typeof title === 'string' && title.length > 0)
				{
					// If the is no title bar yet
					if (!barBlock)
					{
						// Create
						barBlock = $('<div class="modal-bar"><h3>'+title+'</h3></div>').prependTo(modal);

						// If there are action leds, move them to the title bar
						if (actionsBlock)
						{
							actionsBlock.detach().prependTo(barBlock);
						}
					}
					else
					{
						// Find the title tag
						h3 = barBlock.children('h3');
						if (h3.length === 0)
						{
							h3 = $('<h3></h3>').appendTo(barBlock);
						}

						// Set title
						h3.html(title);
					}
				}
				else
				{
					// If there is already a title bar
					if (barBlock)
					{
						// If there are action leds, move them to the modal
						if (actionsBlock)
						{
							actionsBlock.detach().prependTo(modal);
						}

						// Remove bar
						barBlock.remove();
						barBlock = false;
					}
				}
			},

			/**
			 * Close the modal
			 * @return void
			 */
			closeModal = function()
			{
				// Close callback
				if (settings.onClose)
				{
					if (settings.onClose.call(modal[0]) === false)
					{
						return;
					}
				}

				// Blocker
				if (blocker)
				{
					blocker.removeClass('visible');
				}

				// Fade then remove
				modal.stop(true).animate({
					'opacity': 0,
					'marginTop': '-30px'
				}, 300, function()
				{
					// Dom
					if (dom)
					{
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
					}

					// Remove
					modal.remove();

					// Blocker
					if (blocker)
					{
						blocker.remove();
						if (root.children('.modal-blocker').length === 0)
						{
							root.removeClass('with-blocker');

							// Update position for fixed elements fallback
							if ($.fn.detectFixedBounds)
							{
								root.detectFixedBounds();
							}
						}
					}
				});

				// Remaining modals
				$.modal.all = modal.siblings('.modal');
				if ($.modal.all.length == 0)
				{
					// No more modals
					$.modal.current = false;
				}
				else
				{
					// Refresh current
					$.modal.current = $.modal.all.last();
				}
			},

			/**
			 * Update content-block max siezs, according to viewport size and pre-defined max width/height
			 * @return void
			 */
			updateMaxSizes = function()
			{
				var viewportMaxWidth = viewportWidth-(2*settings.maxSizeMargin)-(modal.outerWidth()-contentBlock.width()),
					viewportMaxHeight = viewportHeight-(2*settings.maxSizeMargin)-(modal.outerHeight()-contentBlock.height()),

					// Minimum sizes
					minWidth, minHeight;

				// maxWidth and maxHeight are set outside this function's scope, because they are used in setContentSize()

				// Get lowest values
				maxWidth = settings.maxWidth ? Math.min(settings.maxWidth, viewportMaxWidth) : viewportMaxWidth;
				maxHeight = settings.maxHeight ? Math.min(settings.maxHeight, viewportMaxHeight) : viewportMaxHeight;

				// Update content-block
				if (settings.useIframe)
				{
					contentBlock.prop('width', Math.min(settings.width, maxWidth));
					contentBlock.prop('height', Math.min(settings.height, maxHeight));
				}
				else
				{
					// Minimum size also needs to be within viewport range
					minWidth = settings.minWidth ? Math.min(settings.minWidth, viewportMaxWidth) : viewportMaxWidth;
					minHeight = settings.minHeight ? Math.min(settings.minHeight, viewportMaxHeight) : viewportMaxHeight;

					// Update
					contentBlock.css({
						maxWidth: maxWidth+'px',
						maxHeight: maxHeight+'px',
						minWidth: minWidth+'px',
						minHeight: minHeight+'px'
					});
				}
			};

		// Blocker
		if (settings.blocker)
		{
			// Create
			wasBlocked = root.hasClass('with-blocker');
			blocker = $('<div class="modal-blocker"></div>').appendTo(root.addClass('with-blocker'));

			// Update position for fixed elements fallback
			if (!wasBlocked && $.fn.detectFixedBounds)
			{
				root.detectFixedBounds();
			}

			// Make it visible
			if (settings.blockerVisible)
			{
				// Adding the class afterwards will trigger the CSS animation
				blocker.addClass('visible');
			}
		}

		// If iframe
		if (settings.useIframe)
		{
			// Content size
			if (!settings.width)
			{
				settings.width = settings.maxWidth || settings.minWidth || 120;
			}
			if (!settings.height)
			{
				settings.height = settings.maxHeight || settings.minHeight || 120;
			}

			// Bloc style
			contentWrapper = '<iframe class="modal-iframe" src="'+(settings.url || '')+'" frameborder="0" width="'+settings.width+'" height="'+settings.height+'"></iframe>';
		}
		else
		{
			// Content size
			if (settings.minWidth)
			{
				sizeParts.push('min-width:'+settings.minWidth+'px;');
			}
			if (settings.minHeight)
			{
				sizeParts.push('min-height:'+settings.minHeight+'px;');
			}
			if (settings.width)
			{
				sizeParts.push('width:'+settings.width+'px; ');
			}
			if (settings.height)
			{
				sizeParts.push('height:'+settings.height+'px; ');
			}
			if (settings.maxWidth)
			{
				sizeParts.push('max-width:'+settings.maxWidth+'px; ');
			}
			if (settings.maxHeight)
			{
				sizeParts.push('max-height:'+settings.maxHeight+'px; ');
			}

			// Bloc style
			contentWrapper = '<div class="modal-content'+
							 (settings.scrolling ? ' modal-scroll' : '')+
							 ((settings.contentAlign !== 'left') ? ' align-'+settings.contentAlign : '')+
							 '" style="'+sizeParts.join(' ')+'"></div>';
		}

		// Insert window
		modal = $('<div class="modal"></div>').appendTo(root);
		barBlock = (titleBar.length > 0) ? $(titleBar).appendTo(modal) : false;
		contentBg = settings.contentBg ? $('<div class="modal-bg"></div>').appendTo(modal) : false;
		contentBlock = $(contentWrapper).appendTo(contentBg || modal);

		// Set contents
		if (!settings.useIframe && settings.content)
		{
			setContent(settings.content);
		}

		// Custom scroll
		if (!settings.useIframe && $.fn.customScroll)
		{
			contentBlock.customScroll();
		}

		// If resizable
		if (settings.resizable)
		{
			// Set new size
			handleResize = function(event)
			{
					// Mouse offset
				var offsetX = event.pageX-mouseX,
					offsetY = event.pageY-mouseY,

					// New size
					newWidth = Math.max(settings.minWidth, contentWidth+(resized.width*offsetX)),
					newHeight = Math.max(settings.minHeight, contentHeight+(resized.height*offsetY)),

					// Position correction
					correctX = 0,
					correctY = 0;

				// If max sizes are defined
				if (settings.maxWidth && newWidth > settings.maxWidth)
				{
					correctX = newWidth-settings.maxWidth;
					newWidth = settings.maxWidth;
				}
				if (settings.maxHeight && newHeight > settings.maxHeight)
				{
					correctY = newHeight-settings.maxHeight;
					newHeight = settings.maxHeight;
				}

				// Set size
				setContentSize(newWidth, newHeight);

				// Position
				setPosition(modalX+(resized.left*(offsetX+correctX)), modalY+(resized.top*(offsetY+correctY)));
			};

			// Callback on end of resize
			endResize = function(event)
			{
				doc.off('mousemove', handleResize)
				   .off('mouseup', endResize);
			};

			// Create resize handlers
			$('<div class="modal-resize-nw"></div>').appendTo(modal).data('modal-resize', {
				top: 1, left: 1,
				height: -1, width: -1

			}).add(
				$('<div class="modal-resize-n"></div>').appendTo(modal).data('modal-resize', {
					top: 1, left: 0,
					height: -1, width: 0
				})
			).add(
				$('<div class="modal-resize-ne"></div>').appendTo(modal).data('modal-resize', {
					top: 1, left: 0,
					height: -1, width: 1
				})
			).add(
				$('<div class="modal-resize-e"></div>').appendTo(modal).data('modal-resize', {
					top: 0, left: 0,
					height: 0, width: 1
				})
			).add(
				$('<div class="modal-resize-se"></div>').appendTo(modal).data('modal-resize', {
					top: 0, left: 0,
					height: 1, width: 1
				})
			).add(
				$('<div class="modal-resize-s"></div>').appendTo(modal).data('modal-resize', {
					top: 0, left: 0,
					height: 1, width: 0
				})
			).add(
				$('<div class="modal-resize-sw"></div>').appendTo(modal).data('modal-resize', {
					top: 0, left: 1,
					height: 1, width: -1
				})
			).add(
				$('<div class="modal-resize-w"></div>').appendTo(modal).data('modal-resize', {
					top: 0, left: 1,
					height: 0, width: -1
				})
			).mousedown(function(event)
			{
				// Detect positions
				contentWidth = contentBlock.width();
				contentHeight = contentBlock.height();
				var position = modal.position();
				modalX = position.left;
				modalY = position.top;

				// Mouse
				mouseX = event.pageX;
				mouseY = event.pageY;
				resized = $(this).data('modal-resize');

				// Prevent text selection
				event.preventDefault();

				doc.on('mousemove', handleResize)
				   .on('mouseup', endResize);

			}).on('selectstart', _preventTextSelectionIE); // Prevent text selection for IE7
		}

		// If movable
		if (settings.draggable)
		{
			// Set position
			handleMove = function(event)
			{
				// New position
				setPosition(modalX+(event.pageX-mouseX), modalY+(event.pageY-mouseY));
			};

			// Callback on end of move
			endMove = function(event)
			{
				doc.off('mousemove', handleMove)
				   .off('mouseup', endMove);
			};

			// Watch
			// Delegating the event to the modal allows the remove/add the title bar without handling this each time
			modal.on('mousedown', '.modal-bar', function(event)
			{
				// Detect positions
				var position = modal.position();
				modalX = position.left;
				modalY = position.top;
				mouseX = event.pageX;
				mouseY = event.pageY;

				// Prevent text selection
				event.preventDefault();

				// Listeners
				doc.on('mousemove', handleMove)
				   .on('mouseup', endMove);

			}).on('selectstart', '.modal-bar', _preventTextSelectionIE); // Prevent text selection for IE7
		}

		// Put in front
		modal.mousedown(function()
		{
			modal.putModalOnFront();
		});

		// Action leds
		$.each(settings.actions, function(name, config)
		{
			// Format
			if (typeof(config) === 'function')
			{
				config = {
					click: config
				};
			}

			// Button zone
			if (!actionsBlock)
			{
				actionsBlock = $('<ul class="modal-actions children-tooltip"></ul>').prependTo(barBlock || modal)
								.data('tooltip-options', settings.actionsTooltips);
			}

			// Insert
			$('<li'+(config.color ? ' class="'+config.color+'-hover"' : '')+'><a href="#" title="'+name+'">'+name+'</a></li>').appendTo(actionsBlock).children('a').click(function(event)
			{
				event.preventDefault();
				config.click.call(this, $(this).closest('.modal'), event);
			});
		});

		// Bottom buttons
		$.each(settings.buttons, function(name, config)
		{
			// Format
			if (typeof(config) === 'function')
			{
				config = {
					click: config
				};
			}

			// Button zone
			if (!buttonsBlock)
			{
				buttonsBlock = $('<div class="modal-buttons align-'+settings.buttonsAlign+(settings.buttonsLowPadding ? ' low-padding' : '')+'"></div>').insertAfter(contentBlock);
			}
			else
			{
				// Spacing
				spacingClass = ' mid-margin-left';
			}

			// Insert
			$('<button type="button" class="button'+(config.classes ? ' '+config.classes : '')+spacingClass+'">'+name+'</button>').appendTo(buttonsBlock).click(function(event)
			{
				config.click.call(this, $(this).closest('.modal'), event);
			});
		});

		// Update max sizes
		updateMaxSizes();

		// Interface
		modal.data('modal', {
			contentBlock:		contentBlock,
			setContent:			setContent,
			load:				loadContent,
			setContentSize:		setContentSize,
			setPosition:		setPosition,
			setTitle:			setTitle,
			close:				closeModal,
			updateMaxSizes:		updateMaxSizes
		});

		// Center and display effect
		modal.centerModal().css({
			'opacity': 0,
			'marginTop': '-30px'
		}).animate({
			'opacity': 1,
			'marginTop': '10px'
		}, 200).animate({
			'marginTop': 0
		}, 100);

		// Store as current
		$.modal.current = modal;
		$.modal.all = root.children('.modal');

		// Callback
		if (settings.onOpen)
		{
			settings.onOpen.call(modal[0]);
		}

		// If content url
		if (!settings.useIframe && settings.url)
		{
			loadContent(settings.url, settings.ajax);
		}

		return modal;
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
	 * Shortcut to the current window, or false if none
	 * @var jQuery|boolean
	 */
	$.modal.current = false;

	/**
	 * jQuery selection of all open modal windows
	 * @var jQuery
	 */
	$.modal.all = $();

	/**
	 * Display an alert message
	 * @param string message the message, as text or html
	 * @param object options same as $.modal() (optional)
	 * @return jQuery the new window
	 */
	$.modal.alert = function(message, options)
	{
		options = options || {};
		$.modal($.extend({}, $.modal.defaults.alertOptions, options, {

			content: message

		}));
	};

	/**
	 * Display a prompt
	 * @param string message the message, as text or html
	 * @param function callback the function called with the user value: function(value). Can return false to prevent close.
	 * @param function cancelCallback a callback for when the user closes the modal or click on Cancel. Can return false to prevent close.
	 * @param object options same as $.modal() (optional)
	 * @return jQuery the new window
	 */
	$.modal.prompt = function(message, callback, cancelCallback, options)
	{
		// Params
		if (typeof cancelCallback !== 'function')
		{
			options = cancelCallback;
			cancelCallback = null;
		}
		options = options || {};

		// Cancel callback
		var isSubmitted = false, onClose;
		if (cancelCallback)
		{
			onClose = options.onClose;
			options.onClose = function(event)
			{
				// Check
				if (!isSubmitted && cancelCallback.call(this) === false)
				{
					return false;
				}

				// Previous onClose, if any
				if (onClose)
				{
					onClose.call(this, event);
				}
			};
		}

		// Open modal
		$.modal($.extend({}, $.modal.defaults.promptOptions, options, {

			content:	'<div class="margin-bottom">'+message+'</div><div class="input full-width"><input type="text" name="prompt-value" id="prompt-value" value="" class="input-unstyled full-width"></div>',
			buttons:	{

				'Cancel' : {
					classes :	'glossy',
					click :		function(modal) { modal.closeModal(); }
				},

				'Submit' : {
					classes :	'blue-gradient glossy',
					click :		function(modal)
					{
						// Mark as sumbmitted to prevent the cancel callback to fire
						isSubmitted = true;

						// Callback
						if (callback.call(modal[0], modal.find('input:first').val()) === false)
						{
							return;
						}

						// Close modal
						modal.closeModal();
					}
				}

			}

		}));
	};

	/**
	 * Display a confirm prompt
	 * @param string message the message, as text or html
	 * @param function confirmCallback the function called when hitting confirm
	 * @param function cancelCallback the function called when hitting cancel or closing the modal
	 * @param object options same as $.modal() (optional)
	 * @return jQuery the new window
	 */
	$.modal.confirm = function(message, confirmCallback, cancelCallback, options)
	{
		options = options || {};

		// Cancel callback
		var isConfirmed = false,
			onClose = options.onClose;
		options.onClose = function(event)
		{
			// Cancel callback
			if (!isConfirmed)
			{
				cancelCallback.call(this);
			}

			// Previous onClose, if any
			if (onClose)
			{
				onClose.call(this, event);
			}
		};

		// Open modal
		$.modal($.extend({}, $.modal.defaults.confirmOptions, options, {

			content:	message,
			buttons:	{

				'Cancel' : {
					classes:	'glossy',
					click:		function(modal) { modal.closeModal(); }
				},

				'Confirm' : {
					classes:	'blue-gradient glossy',
					click:		function(modal)
					{
						// Mark as sumbmitted to prevent the cancel callback to fire
						isConfirmed = true;

						// Callback
						confirmCallback.call(modal[0]);

						// Close modal
						modal.closeModal();
					}
				}

			}

		}));
	};

	/**
	 * Wraps the selected elements content in a new modal window.
	 * Some options can be set using the inline html5 data-modal-options attribute:
	 * <div data-modal-options="{'title':'Modal window title'}">Modal content</div>
	 * @param object options same as $.modal()
	 * @return jQuery the new window
	 */
	$.fn.modal = function(options)
	{
		var modals = $();

		this.each(function()
		{
			var element = $(this);
			modals.add($.modal($.extend({}, options, element.data('modal-options'), { content: element })));
		});

		return modals;
	};

	/**
	 * Use this method to retrieve the content div in the modal window
	 */
	$.fn.getModalContentBlock = function()
	{
		if (this.hasClass('.modal-content'))
		{
			return this;
		}

		var data = this.getModalWindow().data('modal');
		return data ? data.contentBlock : $();
	};

	/**
	 * Use this method to retrieve the modal window from any element within it
	 */
	$.fn.getModalWindow = function()
	{
		return this.closest('.modal');
	};

	/**
	 * Set window content (only if not using iframe)
	 * @param string|jQuery content the content to put: HTML or a jQuery object
	 * @param boolean resize use true to resize window to fit content (height only)
	 */
	$.fn.setModalContent = function(content, resize)
	{
		this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = (modal.length > 0) ? modal.data('modal') : false;

			// If valid
			if (data)
			{
				data.setContent(content);

				// Resizing
				if (resize)
				{
					data.setContentSize(true, false);
				}
			}
		});

		return this;
	};

	/**
	 * Set window content-block size
	 * @param int|boolean width the width to set, true to keep current or false for fluid width (false only works if not iframe)
	 * @param int|boolean height the height to set, true to keep current or false for fluid height (false only works if not iframe)
	 */
	$.fn.setModalContentSize = function(width, height)
	{
		this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = (modal.length > 0) ? modal.data('modal') : false;

			// If valid
			if (data)
			{
				data.setContentSize(width, height);
			}
		});

		return this;
	}

	/**
	 * Load AJAX content
	 * @param string url the content url
	 * @param object options (see defaults.ajax for details)
	 */
	$.fn.loadModalContent = function(url, options)
	{
		var settings = $.extend({}, $.modal.defaults.ajax, options)

		this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = (modal.length > 0) ? modal.data('modal') : false;

			// If valid
			if (data)
			{
				data.load(url, settings);
			}
		});

		return this;
	}

	/**
	 * Set modal title
	 * Note: if the option titleBar was set to false on opening, this will have no effect
	 * @param string title the new title (may contain HTML), or an empty string to remove the title
	 */
	$.fn.setModalTitle = function(title)
	{
		this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = (modal.length > 0) ? modal.data('modal') : false;

			// If valid
			if (data)
			{
				data.setTitle(title);
			}
		});

		return this;
	}

	/**
	 * Center the modal
	 * @param boolean animate true to animate the window movement
	 */
	$.fn.centerModal = function(animate)
	{
		this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = (modal.length > 0) ? modal.data('modal') : false;

			// If valid
			if (data)
			{
				data.setPosition(Math.round((viewportWidth-modal.outerWidth())/2), Math.round((viewportHeight-modal.outerHeight())/2), animate);
			}
		});

		return this;
	};

	/**
	 * Set the modal postion in screen, and make sure the window does not go out of the viewport
	 * @param int x the horizontal position
	 * @param int y the vertical position
	 * @param boolean animate true to animate the window movement
	 */
	$.fn.setModalPosition = function(x, y, animate)
	{
		this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = (modal.length > 0) ? modal.data('modal') : false;

			// If valid
			if (data)
			{
				data.setPosition(x, y, animate);
			}
		});

		return this;
	};

	/**
	 * Put modal on front
	 */
	$.fn.putModalOnFront = function()
	{
		if ($.modal.all.length > 1)
		{
			var root = getModalRoot();
			this.each(function()
			{
				var modal = $(this).getModalWindow();
				if (modal.next('.modal').length > 0)
				{
					modal.detach().appendTo(root);
				}
			});
		}

		return this;
	};

	/**
	 * Closes the window
	 */
	$.fn.closeModal = function()
	{
		return this.each(function()
		{
			var modal = $(this).getModalWindow(),
				data = modal.data('modal');

			// If valid
			if (data)
			{
				data.close();
			}
		});
	};

	/**
	 * Default modal window options
	 */
	$.modal.defaults = {
		/**
		 * Add a blocking layer to prevent interaction with background content
		 * @var boolean
		 */
		blocker: true,

		/**
		 * Color the blocking layer (translucid black)
		 * @var boolean
		 */
		blockerVisible: true,

		/**
		 * HTML before the content
		 * @var string
		 */
		beforeContent: '',

		/**
		 * HTML after the content
		 * @var string
		 */
		afterContent: '',

		/**
		 * Content of the window: HTML or jQuery object
		 * @var string|jQuery|boolean
		 */
		content: false,

		/**
		 * Add a white background behind content
		 * @var boolean
		 */
		contentBg: true,

		/**
		 * Alignement of contents ('left', 'center' or 'right') ignored for iframes
		 * @var string
		 */
		contentAlign: 'left',

		/**
		 * Uses an iframe for content instead of a div
		 * @var boolean
		 */
		useIframe: false,

		/**
		 * Url for loading content or iframe src
		 * @var string|boolean
		 */
		url: false,

		/**
		 * Options for ajax loading
		 * @var objects
		 */
		ajax: {

			/**
			 * Any message to display while loading, or leave empty to keep current content
			 * @var string|jQuery
			 */
			loadingMessage: null,

			/**
			 * The message to display if a loading error happened. May be a function: function(jqXHR, textStatus, errorThrown)
			 * Ignored if error callback is set
			 * @var string|jQuery
			 */
			errorMessage: 'Error while loading content. Please try again.',

			/**
			 * Use true to resize window on loading message and when content is loaded. To define separately, use options below:
			 * @var boolean
			 */
			resize: false,

			/**
			 * Use true to resize window on loading message
			 * @var boolean
			 */
			resizeOnMessage: false,

			/**
			 * Use true to resize window when content is loaded
			 * @var boolean
			 */
			resizeOnLoad: false

		},

		/**
		 * Show the title bar (use null to auto-detect when title is not empty)
		 * @var boolean|null
		 */
		titleBar: null,

		/**
		 * Title of the window, or false for none
		 * @var string|boolean
		 */
		title: false,

		/**
		 * Enable window moving
		 * @var boolean
		 */
		draggable: true,

		/**
		 * Enable window resizing
		 * @var boolean
		 */
		resizable: true,

		/**
		 * If  true, enable content vertical scrollbar if content is higher than 'height' (or 'maxHeight' if 'height' is undefined)
		 * @var boolean
		 */
		scrolling: true,

		/**
		 * Actions leds on top left corner, with text as key and function on click or config object as value
		 * Ex:
		 *
		 *  {
		 * 		'Close' : function(modal) { modal.closeModal(); }
		 *  }
		 *
		 * Or:
		 *
		 * 	{
		 *  	'Close' : {
		 *  		color :		'red',
		 *  		click :		function(modal) { modal.closeModal(); }
		 *  	}
		 *  }
		 * @var boolean
		 */
		actions: {
			'Close' : {
				color: 'red',
				click: function(modal) { modal.closeModal(); }
			}
		},

		/**
		 * Configuration for action tooltips
		 * @var object
		 */
		actionsTooltips: {
			spacing: 5,
			classes: ['black-gradient'],
			animateMove: 5
		},

		/**
		 * Map of bottom buttons, with text as key and function on click or config object as value
		 * Ex:
		 *
		 *  {
		 * 		'Close' : function(modal) { modal.closeModal(); }
		 *  }
		 *
		 * Or:
		 *
		 * 	{
		 *  	'Close' : {
		 *  		classes :	'blue-gradient glossy huge full-width',
		 *  		click :		function(modal) { modal.closeModal(); }
		 *  	}
		 *  }
		 * @var object
		 */
		buttons: {
			'Close': {
				classes :	'blue-gradient glossy big full-width',
				click :		function(modal) { modal.closeModal(); }
			}
		},

		/**
		 * Alignement of buttons ('left', 'center' or 'right')
		 * @var string
		 */
		buttonsAlign: 'right',

		/**
		 * Use low padding for buttons block
		 * @var boolean
		 */
		buttonsLowPadding: false,

		/**
		 * Function called when opening window
		 * Scope: the modal window
		 * @var function
		 */
		onOpen: false,

		/**
		 * Function called when closing window.
		 * Note: the function may return false to prevent close.
		 * Scope: the modal window
		 * @var function
		 */
		onClose: false,

		/**
		 * Minimum margin to viewport border around window when the max-size is reached
		 * @var int
		 */
		maxSizeMargin: 10,

		/**
		 * Minimum content height
		 * @var int
		 */
		minHeight: 16,

		/**
		 * Minimum content width
		 * @var int
		 */
		minWidth: 200,

		/**
		 * Maximum content width, or false for no limit
		 * @var int|boolean
		 */
		maxHeight: false,

		/**
		 * Maximum content height, or false for no limit
		 * @var int|boolean
		 */
		maxWidth: false,

		/**
		 * Initial content height, or false for fluid size
		 * @var int|boolean
		 */
		height: false,

		/**
		 * Initial content width, or false for fluid size
		 * @var int|boolean
		 */
		width: false,

		/**
		 * Default options for alert() method
		 * @var object
		 */
		alertOptions: {
			contentBg:		false,
			contentAlign:	'center',
			minWidth:		120,
			width:			false,
			maxWidth:		260,
			resizable:		false,
			actions:		{},
			buttons:		{

				'Close' : {
					classes :	'blue-gradient glossy big full-width',
					click :		function(modal) { modal.closeModal(); }
				}

			},
			buttonsAlign:	'center',
			buttonsLowPadding: true
		},

		/**
		 * Default options for prompt() method
		 * @var object
		 */
		promptOptions: {
			width:			false,
			maxWidth:		260,
			resizable:		false,
			actions:		{}
		},

		/**
		 * Default options for confirm() method
		 * @var object
		 */
		confirmOptions: {
			contentAlign:	'center',
			minWidth:		120,
			width:			false,
			maxWidth:		260,
			buttonsAlign:	'center'
		}
	};

})(jQuery, window, document);