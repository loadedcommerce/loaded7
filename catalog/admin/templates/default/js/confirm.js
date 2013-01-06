/**
 *
 * Confirmation plugin
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
	var doc = $(document);

	/**
	 * Display the confirm message
	 * @param jQuery target the clicked element
	 * @param event event the initial event
	 * @return void
	 */
	$.confirm = function(target, event)
	{
			// Options
		var settings = $.extend({}, $.confirm.defaults, target.data('confirm-options')),

			// Mode
			modeTooltip = (settings.tooltip || !$.modal),

			// Has the user made his choice?
			choose = false,

			// Callbacks
			onShow, onRemove,

			// Message
			message,

			// Buttons
			buttons, confirmButton, cancelButton,

			// Functions
			confirmFunc, cancelFunc;

		// Prevent default
		event.preventDefault();
		event.stopPropagation();

		// If already confirmed, run
		if (target.data('confirmed'))
		{
			call = true;

			// Function on confirm
			if (settings.onConfirm)
			{
				if (settings.onConfirm.call(target[0]) !== false)
				{
					_runDefault(target);
				}
			}
			else
			{
				_runDefault(target);
			}

			return;
		}

		// Callback on show
		if (settings.onShow)
		{
			onShow = function()
			{
				settings.onShow.call(target[0], $(this));
			};
		}

		// Callback on remove
		onRemove = function()
		{
			// Abort callback
			if (!choose && settings.onAbort)
			{
				settings.onAbort.call(target[0]);
			}

			// Remove callback
			if (settings.onRemove)
			{
				settings.onRemove.call(target[0]);
			}
		};

		// Function on confirm button
		confirmFunc = function()
		{
			var call = true;

			// Mark as done
			choose = true;

			// Function on confirm
			if (settings.onConfirm)
			{
				if (settings.onConfirm.call(target[0]) === false)
				{
					call = false;
				}
			}

			// Remove message
			if (modeTooltip)
			{
				target.removeTooltip();
			}
			else
			{
				$(this).getModalWindow().closeModal();
			}

			// Run original event
			if (call)
			{
				_runDefault(target);
			}

			// Should the element remind confirmation?
			if (settings.remind)
			{
				target.data('confirmed', true);
			}
		};

		// Function on cancel button
		if (settings.cancel)
		{
			cancelFunc = function()
			{
				// Mark as done
				choose = true;

				// Function on cancel
				if (settings.onCancel)
				{
					settings.onCancel.call(target[0]);
				}

				// Remove message
				if (modeTooltip)
				{
					target.removeTooltip();
				}
				else
				{
					$(this).getModalWindow().closeModal();
				}
			};
		}

		// Tooltip mode
		if (modeTooltip)
		{
			// Message
			message = $('<div class="with-small-padding align-center"><div class="mid-margin-bottom">'+settings.message+'</div></div>');

			// Confirm button
			confirmButton = $('<button type="button" class="'+['button'].concat(settings.confirmClasses).join(' ')+'">'+settings.confirmText+'</button>').click(confirmFunc);

			// Cancel button
			if (settings.cancel)
			{
				// Create
				cancelButton = $('<button type="button" class="'+['button'].concat(settings.cancelClasses).join(' ')+'">'+settings.cancelText+'</button>').click(cancelFunc);

				// Insert
				if (settings.cancelFirst)
				{
					cancelButton.addClass('mid-margin-right').appendTo(message);
					confirmButton.appendTo(message);
				}
				else
				{
					confirmButton.addClass('mid-margin-right').appendTo(message);
					cancelButton.appendTo(message);
				}
			}
			else
			{
				// Full-width
				confirmButton.addClass('full-width').appendTo(message);
			}

			// Show tooltip
			target.tooltip(message, $.extend({}, settings.tooltipOptions, {
				lock:				true,
				exclusive:			true,
				onShow:				onShow,
				onRemove:			onRemove,
				onAbort:			onRemove,
				removeOnBlur:		true,
				noPointerEvents:	false
			}));
		}
		else
		{
			// Buttons
			buttons = {};

			// Cancel button - after
			if (settings.cancel && settings.cancelFirst)
			{
				buttons[settings.cancelText] = {
					classes :	settings.cancelClasses.join(' '),
					click :		cancelFunc
				};
			}

			// Confirm
			buttons[settings.confirmText] = {
				classes :	settings.confirmClasses.join(' '),
				click :		confirmFunc
			};

			// Cancel button - after
			if (settings.cancel && !settings.cancelFirst)
			{
				buttons[settings.cancelText] = {
					classes :	settings.cancelClasses.join(' '),
					click :		cancelFunc
				};
			}

			// Open modal
			$.modal($.extend({}, $.modal.defaults.confirmOptions, {

				content:			settings.message,
				buttons:			buttons,
				onOpen:				onShow,
				onClose:			onRemove

			}));
		}
	};

	/**
	 * Run the target default action
	 * @param jQuery target the target element
	 * @return void
	 */
	function _runDefault(target)
	{
		var actions = $.confirm.defaults.actions,
			name;

		// Run through actions
		for (name in actions)
		{
			if (actions.hasOwnProperty(name) && typeof actions[name] === 'function' && target.is(name))
			{
				actions[name](target);
				return;
			}
		}

		// Not found
		console.log('No default action specified for this target ('+target[0].nodeName+')');
	};

	/**
	 * Confirmation defaults
	 * @var object
	 */
	$.confirm.defaults = {
		/**
		 * Default message
		 * @var string
		 */
		message: 'Are you sure?',

		/**
		 * Text of confirm button
		 * @var string
		 */
		confirmText: 'Confirm',

		/**
		 * Classes of confirm button
		 * @var string
		 */
		confirmClasses: ['blue-gradient', 'glossy'],

		/**
		 * Display cancel button?
		 * @var boolean
		 */
		cancel: true,

		/**
		 * Text of cancel button
		 * @var string
		 */
		cancelText: 'Cancel',

		/**
		 * Classes of cancel button
		 * @var string
		 */
		cancelClasses: [],

		/**
		 * Display cancel button before confirm
		 * @var boolean
		 */
		cancelFirst: true,

		/**
		 * Use tooltip (true) or confirm (false)
		 * @var boolean
		 */
		tooltip: true,

		/**
		 * Tooltip options
		 * @var object
		 */
		tooltipOptions: {},

		/**
		 * Confirm once or every time?
		 * @var boolean
		 */
		remind: false,

		/**
		 * Default actions depending on node type
		 * This list can be extended with further selectors and functions: $.extend($.confirm.defaults.actions, { selector: function(target) { ... } })
		 * @var object
		 */
		actions: {

			// Links
			'a': function(target)
			{
				document.location.href = target[0].href;
			},

			// Submit buttons
			'[type="submit"]': function()
			{
				target.closest('form').submit();
			}

		},

		/**
		 * Callback when message is shown: function(modalOrTooltip)
		 * Scope: the target element
		 * @var function
		 */
		onShow: null,

		/**
		 * Callback when confirm
		 * Note: the function may return false to prevent the target's default action (ie: opening a link)
		 * Scope: the target element
		 * @var function
		 */
		onConfirm: null,

		/**
		 * Callback when cancel (no called if cancel button is disabled)
		 * Scope: the target element
		 * @var function
		 */
		onCancel: null,

		/**
		 * Callback when message is removed (with or without active confirmation)
		 * Scope: the tooltip/modal
		 * @var function
		 */
		onRemove: null,

		/**
		 * Callback when the user closes the confirmation without make a choice (for instance, click outside the tooltip)
		 * Scope: the target element
		 * @var function
		 */
		onAbort: null
	};

	// Event binding
	doc.on('click', '.confirm', function(event)
	{
		// Show confirmation
		$.confirm($(this), event);

	});

})(jQuery, document);