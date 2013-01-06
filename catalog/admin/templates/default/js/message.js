/**
 *
 * Block messages plugin
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
	 * Display a message on the target element
	 *
	 * @param string message the text or html message to display
	 * @param object options - optional (see defaults for a complete list)
	 * @return jQuery the messages nodes
	 */
	$.fn.message = function(message, options)
	{
		// Settings
		var globalSettings = $.extend({}, $.fn.message.defaults, options),
			all;

		// Insert message
		all = $();
		this.each(function(i)
		{
			var target = $(this),
				settings = $.extend({}, globalSettings, target.data('message-options')),
				classes = ['message'].concat(settings.classes),
				onTop = (settings.position.toLowerCase() != 'bottom'),
				method = onTop ? (settings.append ? 'prependTo' : 'insertBefore') : (settings.append ? 'appendTo' : 'insertAfter'),
				link = (settings.node.toLowerCase() === 'a') ? ' href="'+settings.link+'"' : '',

				// Extra elements
				simpler = settings.simpler ? ' simpler' : '',
				inset = settings.inset ? ' inset' : '',
				closeOnHover = settings.showCloseOnHover ? ' show-on-parent-hover' : '',
				closeButton = settings.closable ? '<span class="close'+inset+closeOnHover+simpler+'">✕</span>' : '',
				useArrow = (settings.arrow && $.inArray(settings.arrow.toLowerCase(), ['top', 'right', 'bottom', 'left']) > -1),
				arrow = useArrow ? '<span class="block-arrow '+settings.arrow.toLowerCase()+'"><span></span></span>' : '',

				// Other vars
				stripesSize, animatedStripes, darkStripes, stripes = '',
				element, previous, found = false, count;

			// If similar messages should be grouped
			if (settings.groupSimilar)
			{
				// Gather previous messages
				if (settings.append)
				{
					previous = target.childrenImmediates('.message', !onTop).not('.closing');
				}
				else
				{
					previous = target[onTop ? 'prevImmediates' : 'nextImmediates']('.message').not('.closing');
				}

				// Check if a similar message exists
				previous.each(function(i)
				{
					var element = $(this);
					if (element.data('message-text') === message)
					{
						found = element;
						return false;
					}
				});
				if (found)
				{
					// Count
					if (settings.groupCount)
					{
						// Check if count element already exists
						count = found.children('.count');
						if (count.length > 0)
						{
							count.text((parseInt(count.text()) || 1)+1);
						}
						else
						{
							found.append('<span class="count left'+inset+'">2</span>');
						}
					}

					// Effect
					found.shake();

					all = all.add(found);
					return found;
				}
			}

			// Stripes
			if (settings.stripes)
			{
				// Dark or not
				darkStripes = settings.darkStripes ? 'dark-' : '';

				// Size
				stripesSize = (settings.stripesSize === 'big' || settings.stripesSize === 'thin') ? settings.stripesSize+'-' : '';

				// Animated
				animatedStripes = settings.animatedStripes ? ' animated' : '';

				// Final
				stripes = '<span class="'+darkStripes+stripesSize+'stripes'+animatedStripes+'"></span>';
			}

			// Insert
			element = $('<'+settings.node+link+' class="'+classes.join(' ')+simpler+'">'+stripes+message+closeButton+arrow+'</'+settings.node+'>')[method](target);

			// Store message for later comparisons
			element.data('message-text', message);

			// Add to selections
			target.data('messages', (target.data('messages') || $()).add(element));
			all = all.add(element);

			// Effect
			if (settings.animate)
			{
				element.hide().slideDown(settings.animateSpeed);
			}
		});

		return all;
	};

	/**
	 * Clear element's message(s)
	 *
	 * @param string message the message to remove (can be omitted)
	 * @param boolean animate use an animation (foldAndRemove) to remove the messages (default: false)
	 * @return jQuery the chain
	 */
	$.fn.clearMessages = function(message, animate)
	{
		// Params
		if (typeof message === 'boolean')
		{
			animate = message;
			message = '';
		}
		animate = (animate || animate == undefined);

		this.each(function(i)
		{
			var messages = $(this).data('messages'),
				removed;
			if (messages)
			{
				// If specific message only
				if (message && message.length > 0)
				{
					removed = $();
					messages.each(function(i)
					{
						if ($(this).data('message-text') === message)
						{
							removed = removed.add(this);
						}
					});
				}
				else
				{
					// Remove all
					removed = messages;
				}

				// Remove
				removed.addClass('closing')[animate ? 'foldAndRemove' : 'remove']();

				// Update/clear data
				if (removed.length === messages.length)
				{
					$(this).removeData('messages');
				}
				else
				{
					$(this).data('messages', messages.not(removed));
				}
			}
		});

		return this;
	};

	/**
	 * Message function defaults
	 * @var object
	 */
	$.fn.message.defaults = {

		/**
		 * Whether to append the message element or to insert it next to the target
		 * @var boolean
		 */
		append: true,

		/**
		 * Position in or next the target: 'top' or 'bottom'
		 * @var string
		 */
		position: 'top',

		/**
		 * Arrow direction or false for none
		 * @var string|boolean
		 */
		arrow: false,

		/**
		 * Node type for the message (tip: 'p' has bottom-margin, 'a' and 'div' have none)
		 * @var string
		 */
		node: 'p',

		/**
		 * Link when the node type is 'a'
		 * @var string
		 */
		link: '#',

		/**
		 * Extra classes (colors...)
		 * @var array
		 */
		classes: [],

		/**
		 * Enable animated stripes
		 * @var boolean
		 */
		stripes: false,

		/**
		 * True for animated stripes (only on compatible browsers)
		 * @var boolean
		 */
		animatedStripes: true,

		/**
		 * True for dark stripes, false for white stripes
		 * @var boolean
		 */
		darkStripes: true,

		/**
		 * Stripes size: 'big', 'normal' or 'thin'
		 * @var string
		 */
		stripesSize: 'normal',

		/**
		 * Use true to remove rounded corners and bewel
		 * @var boolean
		 */
		simpler: false,

		/**
		 * Enable a close button
		 * @var boolean
		 */
		closable: true,

		/**
		 * Show the close button only on hover
		 * @var boolean
		 */
		showCloseOnHover: true,

		/**
		 * Animate the message's occurrence
		 * @var boolean
		 */
		animate: true,

		/**
		 * Speed of animation (any jQuery valid value)
		 * @var string|int
		 */
		animateSpeed: 'fast',

		/**
		 * Group similar messages
		 * @var boolean
		 */
		groupSimilar: true,

		/**
		 * Display a bubble with the count of grouped messages
		 * @var boolean
		 */
		groupCount: true,

		/**
		 * Should close and count bubbles be inside the message?
		 * @var boolean
		 */
		inset: false

	};

})(jQuery);