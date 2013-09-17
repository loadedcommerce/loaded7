/**
 *
 * Notification plugin
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

	// Objects cache
	var doc = $(document);

	/**
	 * Internal reference: the div holding top notifications
	 * @var jQuery
	 */
	var _topNotificationDiv = false;

	/**
	 * Internal function: retrieve the div holding top notifications
	 *
	 * @return jQuery the div selection
	 */
	function _getTopNotificationDiv()
	{
		if (!_topNotificationDiv)
		{
			_topNotificationDiv = $('<div id="top-notifications"></div>').appendTo(document.body);
		}

		return _topNotificationDiv;
	}

	/**
	 * Internal reference: the div holding bottom notifications
	 * @var jQuery
	 */
	var _bottomNotificationDiv = false;

	/**
	 * Internal function: retrieve the div holding bottom notifications
	 *
	 * @return jQuery the div selection
	 */
	function _getBottomNotificationDiv()
	{
		if (!_bottomNotificationDiv)
		{
			_bottomNotificationDiv = $('<div id="bottom-notifications"></div>').appendTo(document.body);
		}

		return _bottomNotificationDiv;
	}

	/**
	 * Internal function: output archived notifications
	 *
	 * @param jQuery element the archive element
	 * @param int max the max number of output messages
	 * @param object settings the notify options
	 * @return boolean true if there are some more notifications, else false
	 */
	function _releaseNotices(element, max, settings)
	{
		var archive = element.data('notifications-archive') || [],
			releaseCount = (max > 0) ? Math.min(archive.length, max) : archive.length,
			i, archived;

		// Release messages
		for (i = 0; i < releaseCount; ++i)
		{
			archived = archive.shift();
			window.notify(archived.title, archived.message, $.extend(archived.options, { delay: i*100 }), true);
		}

		// If archive is now empty, clear waiting message block
		if (archive.length === 0)
		{
			element.remove();
			return false;
		}
		else
		{
			// Update number of waiting messages
			archiveSize = archive.length;
			element.text((archiveSize > 1) ? settings.textSeveralMore.replace('%d', archiveSize) : settings.textOneMore);
			return true;
		}
	};

	/**
	 * Internal function: activate notification auto close
	 *
	 * @param jQuery element the notification element
	 * @param int delay delay before the notification closes
	 * @return void
	 */
	function _activateNoticeAutoClose(element, delay)
	{
		// Timer
		element.data('notification-timeout', setTimeout(function() { element.fadeAndRemove().trigger('close'); }, delay));

		// Prevent closing when hover
		element.hover(function()
		{
			clearTimeout(element.data('notification-timeout'));

		}, function()
		{
			element.data('notification-timeout', setTimeout(function() { element.fadeAndRemove().trigger('close'); }, delay));
		});
	};

	/**
	 * Display a notification. If the page is not yet ready, delay the notification until it is ready.
	 *
	 * @var string title the title - can be omitted
	 * @var string message a text or html message to display
	 * @var object options an object with any options for the message - optional (see defaults for more details)
	 * @var boolean rotate force deletion of older element before inserting new one - optional (internal mostly, but can be used if relevant)
	 * @return void
	 */
	window.notify = function(title, message, options, rotate)
	{
		// Parameters
		if (message == undefined || typeof message === 'object')
		{
			options = message || {};
			message = title;
			title = '';
		}

		// Defaults
		options = options || {};
		rotate = rotate || false;

		// If the document is not ready or we want some delay
		if (!$.isReady || (options != undefined && options.delay > 0))
		{
			// Delay action
			var delay = (options != undefined) ? (options.delay || 40) : 40;
			setTimeout(function() { window.notify(title, message, $.extend(options, { delay: 0 }), rotate); }, delay);
		}
		else
		{
			// Position defaults
			if (!options.vPos)
			{
				options.vPos = window.notify.defaults.vPos;
			}
			if (!options.hPos)
			{
				options.hPos = window.notify.defaults.hPos;
			}

			var settings = $.extend({},
							// Global defaults
							window.notify.defaults,
							// Defaults for vertical position
							window.notify.defaults[options.vPos.toLowerCase()],
							// Defaults for horizontal position
							window.notify.defaults[options.hPos.toLowerCase()],
							// Defaults for final position
							window.notify.defaults[options.vPos.toLowerCase()+options.hPos.toLowerCase()],
							// User options
							options);

			// System notification
			if (settings.system && window.notify.hasNotificationPermission())
			{
				var notifTitle = ($.trim(title).length > 0) ? title : document.title,
					notification = window.webkitNotifications.createNotification(settings.icon || '', notifTitle, message);

				// Display event
				if (settings.autoClose || settings.onDisplay)
				{
					notification.ondisplay = function()
					{
						// Callback
						if (settings.onDisplay)
						{
							settings.onDisplay.call(notification);
						}

						// Auto-close after delay
						if (settings.autoClose)
						{
							setTimeout(function () { notification.cancel(); }, settings.closeDelay);
						}
					};
				}

				// Click event
				if ((settings.link && settings.link.length > 0) || settings.onClick)
				{
					notification.onclick = function()
					{
						// Callback
						if (settings.onClick)
						{
							settings.onClick.call(notification);
						}

						// Redirection
						if (settings.link && settings.link.length > 0)
						{
							notification.cancel();
							document.location.href = settings.link;
						}
					};
				}

				// Close event
				if (settings.onClose)
				{
					notification.onclose = function()
					{
						// Callback
						settings.onClose.call(notification);
					};
				}

				// Error handling
				notification.onerror = function()
				{
					// If a callback is provided
					if (settings.onError)
					{
						if (settings.onError.call(notification) === false)
						{
							return;
						}
					}

					// Fallback on standard notification
					window.notify(title, message, $.extend(settings, { system: false }), rotate);
				};

				// Show notification
				notification.show();

				// Done for now
				return;
			}

			var classes = ['notification'].concat(settings.classes),
				listId = 'notifications-'+settings.vPos.toLowerCase()+'-'+settings.hPos.toLowerCase(),
				list = $('#'+listId),
				icon = (settings.icon && settings.icon.length > 0) ? '<img class="notification-icon'+(settings.iconOutside ? ' outside' : '')+'" src="'+settings.icon+'">' : '',
				iconArrowSide = (settings.hPos.toLowerCase() === 'left') ? 'right' : 'left',
				iconArrow = (icon.length > 0 && settings.iconOutside) ? '<span class="block-arrow '+iconArrowSide+'"><span></span></span>' : '',
				closeButton = settings.closeButton ? '<span class="close'+(settings.showCloseOnHover ? ' show-on-parent-hover' : '')+'">✕</span>' : '',
				elementTitle = ($.trim(title).length > 0) ? '<h3>'+title+'</h3>' : '',
				altTitle = (settings.title.length > 0) ? ' title="'+settings.title+'"' :'',
				wrapperOpen = (settings.link.length > 0) ? '<a href="'+settings.link+'"'+altTitle+'>' :'<div'+altTitle+'>',
				wrapperClose = (settings.link.length > 0) ? '</a>' :'</div>',
				postponed = false,
				more = list.children('.more-notifications'),
				siblings, block, element, effectMargins;

			// Target list
			if (list.length === 0)
			{
				// Create list
				list = $('<ul id="'+listId+'"></ul>').appendTo((settings.vPos.toLowerCase() === 'top') ? _getTopNotificationDiv() : _getBottomNotificationDiv());
			}

			// List of current messages
			siblings = list.children().not('.closing').not('.more-notifications');

			// If grouping similar messages, check if another one is still displayed
			if (settings.groupSimilar)
			{
				siblings.each(function(i)
				{
					var previous = $(this),
						data = previous.data('notification-data'),
						extras, timeout, archive, archiveSize;

					// Compare messages
					if (data.title === title.toLowerCase() && data.message === message.toLowerCase())
					{
						// Get or create the extra messages block
						extras = previous.children('.extra-notifications');
						if (extras.length > 0)
						{
							// Retrieve existing archive
							archive = extras.data('notifications-archive') || [];
						}
						else
						{
							// Create element and archive
							extras = $('<p class="extra-notifications"></p>').appendTo(previous);
							archive = [];
						}

						// Stop autoClose
						timeout = previous.data('notification-timeout');
						if (timeout)
						{
							clearTimeout(timeout);
							previous.off('mouseenter');
							previous.off('mouseleave');
						}

						// Re-apply if required
						if (!settings.stickGrouped && (timeout || settings.autoClose))
						{
							_activateNoticeAutoClose(previous, settings.closeDelay);
						}

						// Add message to queue
						archive.push({
							title: title,
							message: message,
							options: options
						});

						// Number of waiting messages
						archiveSize = archive.length;
						extras.text((archiveSize > 1) ? settings.textSeveralSimilars.replace('%d', archiveSize) : settings.textOneSimilar);

						// Save archive
						extras.data('notifications-archive', archive);

						// Effect
						if (!previous.is(':animated'))
						{
							previous.shake((doc.width() >= 768) ? 15 : 5);
						}

						// Done
						postponed = true;
						return false;
					}
				});
			}

			// Check if we exceed the simultaneous messages limit
			if (!postponed && settings.limit > 0 && siblings.length >= settings.limit)
			{
				// If rotation
				if (rotate || settings.rotate)
				{
					// Remove first
					siblings.eq(0).addClass('closing').foldAndRemove();
				}
				else
				{
					// Get or create the waiting messages block
					if (more.length > 0)
					{
						// Retrieve existing archive
						archive = more.data('notifications-archive') || [];
					}
					else
					{
						// Create element and archive
						more = $('<li class="notification more-notifications"></li>').appendTo(list);
						archive = [];

						// Behavior
						more.click(function(event)
						{
							_releaseNotices(more, settings.releaseLimit, settings);
						});
					}

					// Add message to queue
					archive.push({
						title: title,
						message: message,
						options: options
					});

					// Number of waiting messages
					archiveSize = archive.length;
					more.text((archiveSize > 1) ? settings.textSeveralMore.replace('%d', archiveSize) : settings.textOneMore);

					// Save archive
					more.data('notifications-archive', archive);

					// Done
					postponed = true;
				}
			}

			// If put in a waiting list, exit
			if (postponed)
			{
				return;
			}

			// If no title
			if ($.trim(title).length === 0)
			{
				classes.push('no-title');
			}

			// Append message
			element = $('<li class="'+classes.join(' ')+'">'+icon+iconArrow+wrapperOpen+elementTitle+message+wrapperClose+closeButton+'</li>');
			if (more.length > 0)
			{
				element.insertBefore(more).hide().slideDown();
			}
			else
			{
				element.appendTo(list).hide().fadeIn('slow');
			}

			// Display callback
			if (settings.onDisplay)
			{
				settings.onDisplay.call(element[0]);
			}

			// Function on click
			if (settings.onClick)
			{
				element.children().not('.close').on('click', settings.onClick);
			}

			// Save some data
			element.data('notification-data', {
				title: title.toLowerCase(),
				message: message.toLowerCase(),
				closeDelay: settings.closeDelay
			});

			// Watch close button
			element.on('close', function()
			{
				// Mark as closing to prevent similar messages to be added
				element.addClass('closing');

				// Check if queued messages
				var more = list.children('.more-notifications');
				if (more.length > 0)
				{
					// Change fade effect to folding
					element.stop(true).foldAndRemove();

					// Release next notification
					_releaseNotices(more, 1, settings);
				}
			});
			if (settings.onClose)
			{
				element.on('close', settings.onClose);
			}

			// If closing
			if (settings.autoClose)
			{
				_activateNoticeAutoClose(element, settings.closeDelay);
			}
		}
	};

	/**
	 * Check if the Notification API is available
	 * @return boolean true if available, else false
	 */
	window.notify.hasNotificationAPI = function()
	{
		return !!window.webkitNotifications;
	};

	/**
	 * Check if the Notification API permission is set
	 * @return boolean true if available, else false
	 */
	window.notify.isNotificationPermissionSet = function()
	{
		return (window.notify.hasNotificationAPI() && window.webkitNotifications.checkPermission() != 1); // 1 is PERMISSION_NOT_ALLOWED
	};

	/**
	 * Check if the Notification API permission is granted
	 * @return boolean true if available, else false
	 */
	window.notify.hasNotificationPermission = function()
	{
		return (window.notify.hasNotificationAPI() && window.webkitNotifications.checkPermission() == 0); // 0 is PERMISSION_ALLOWED
	};

	/**
	 * Display a message asking the user to grant permission to use the notification API
	 * Note: require the developr.message lib is required if target is not defined
	 * @param jQuery|string target the element which will be clicked to trigger the notification, or a string for a message that will be created on top of #main
	 * @param function callback a function to be called when the permission is set, granted or not (optional)
	 * @return void
	 */
	window.notify.showNotificationPermission = function(target, callback)
	{
		var message = false;

		// If not available or already granted
		if (!window.notify.hasNotificationAPI())
		{
			return;
		}

		// If no target, create a message
		if (typeof target === 'string')
		{
			if (!$.fn.message)
			{
				return;
			}

			message = $('#main').message(target, {
				node:		'a',
				classes:	['align-center', 'green-gradient'],
				simpler:	true,
				inset:		true
			});
			target = message;
		}

		// Behavior
		target.click(function(event)
		{
			// Only for target element (should no be triggered by the close button)
			if (event.target !== this)
			{
				return;
			}

			event.preventDefault();
			window.webkitNotifications.requestPermission(function()
			{
				// Remove message if needed
				if (message)
				{
					message.fadeAndRemove();
				}

				// User callback
				if (callback && typeof callback === 'function')
				{
					callback();
				}
			});
		});
	};

	/**
	 * Notify function defaults
	 * @var object
	 */
	window.notify.defaults = {
		/**
		 * Use system notification if available, else fallback on standard notifications
		 * @var boolean
		 */
		system: false,

		/**
		 * Vertical position ('top' or 'bottom')
		 * @var string
		 */
		vPos: 'top',

		/**
		 * Horizontal position ('left', 'center' or 'right')
		 * Note: ignored in mobile screens (the notification takes the full screen width)
		 * @var string
		 */
		hPos: 'right',

		/**
		 * Extra classes (colors...)
		 * @var array
		 */
		classes: [],

		/**
		 * Link on the notification
		 * @var string
		 */
		link: '',

		/**
		 * Title on hover
		 * @var string
		 */
		title: '',

		/**
		 * Icon path
		 * @var string
		 */
		icon: '',

		/**
		 * Icon should show out of the notification block? (ignored for mobile layouts)
		 * @var boolean
		 */
		iconOutside: true,

		/**
		 * Add a close button to the notification
		 * @var boolean
		 */
		closeButton: true,

		/**
		 * Show the close button only on hover
		 * @var boolean
		 */
		showCloseOnHover: true,

		/**
		 * Notice will close after (closeDelay) ms
		 * @var boolean
		 */
		autoClose: true,

		/**
		 * Delay before notification closes
		 * @var int
		 */
		closeDelay: 8000,

		/**
		 * Delay before showing the notification
		 * @var int
		 */
		delay: 0,

		/**
		 * Group similar notifications in a stack
		 * @var boolean
		 */
		groupSimilar: true,

		/**
		 * Prevent autoClose on grouped notifications
		 * @var boolean
		 */
		stickGrouped: false,

		/**
		 * Text when one similar notification is found
		 * @var boolean
		 */
		textOneSimilar: 'One similar notification',

		/**
		 * Text when several similar notifications are found
		 * Note: use %d in your string to get the final count
		 * @var boolean
		 */
		textSeveralSimilars: '%d similar notifications',

		/**
		 * Maximum number of notifications displayed at the same time in one stack
		 * Note: use 0 for no limit, but use with caution!
		 * @var int
		 */
		limit: 7,

		/**
		 * Force rotation (remove older messages) when reaching limit
		 * @var boolean
		 */
		rotate: false,

		/**
		 * Text when one similar notification is found
		 * @var boolean
		 */
		textOneMore: 'One more notification',

		/**
		 * Text when several similar notifications are found
		 * Note: use %d in your string to get the final count
		 * @var boolean
		 */
		textSeveralMore: '%d more notifications',

		/**
		 * Number of notifications released when clicking on an similiar/archive block
		 * Note: use 0 for no limit, but use with caution!
		 * @var int
		 */
		releaseLimit: 5,

		/**
		 * Options for top notifications
		 * @var object
		 */
		top: {},

		/**
		 * Options for bottom notifications
		 * @var object
		 */
		bottom: {},

		/**
		 * Options for left notifications
		 * @var object
		 */
		left: {},

		/**
		 * Options for center notifications
		 * @var object
		 */
		center: {},

		/**
		 * Options for right notifications
		 * @var object
		 */
		right: {},

		/**
		 * Options for top left notifications
		 * @var object
		 */
		topleft: {},

		/**
		 * Options for top center notifications
		 * @var object
		 */
		topcenter: {},

		/**
		 * Options for top right notifications
		 * @var object
		 */
		topright: {},

		/**
		 * Options for bottom left notifications
		 * @var object
		 */
		bottomleft: {},

		/**
		 * Options for bottom center notifications
		 * @var object
		 */
		bottomcenter: {},

		/**
		 * Options for bottom right notifications
		 * @var object
		 */
		bottomright: {},

		/**
		 * Callback when the notification is shown
		 * @var function
		 */
		onDisplay: null,

		/**
		 * Callback when the notification is clicked
		 * @var function
		 */
		onClick: null,

		/**
		 * Callback when the notification is closed
		 * @var function
		 */
		onClose: null,

		/**
		 * Callback (if using the Notification API system only) if the notification triggers an error.
		 * By default, the lib will fallback on a standard notification, the callback may return false to prevent this.
		 * @var function
		 */
		onError: null
	};

})(jQuery, window, document);