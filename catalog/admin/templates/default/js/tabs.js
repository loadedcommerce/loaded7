/**
 *
 * Tabs plugin
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
	 * window and document are passed through as local variables rather than as globals, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var win = $(window),
		doc = $(document),

		// Indicate that a global refresh is on
		globalRefresh = false;

	// Event binding
	doc.on('click', '.tabs > li > a', function(event)
	{
		var link = $(this),
			tab = link.parent(),
			current;

		// Check if real tab link
		if (!this.href || this.href.indexOf('#') < 0)
		{
			return;
		}

		event.preventDefault();

		// If disabled, stop here
		if (tab.hasClass('disabled'))
		{
			return;
		}

		// Set as active and add animation class (the later the best for performance on document init)
		tab.addClass('active').closest('.standard-tabs, .swipe-tabs, .side-tabs').addClass('tabs-animated tab-opened');

		// Desactivate current active tab
		current = tab.siblings('.active').removeClass('active');

		// Refresh tabs
		link.refreshTabs();
	});

	/**
	 * Refresh tabs contained in an element
	 */
	$.fn.refreshInnerTabs = function()
	{
		// Tabs (processed in reverse order to start from deepest elements
		$(this.find('.standard-tabs, .swipe-tabs, .side-tabs').get().reverse()).refreshTabs();

		return this;
	};

	/**
	 * Refresh tabs: equalize height, show current tab...
	 */
	$.fn.refreshTabs = function()
	{
		var wrappers = $(),
			parents;

		this.each(function(i)
		{
				// Tabs wrapper
			var wrapper = $(this).closest('.standard-tabs, .swipe-tabs, .side-tabs'),

				// Tabs group
				tabs = wrapper.children('.tabs'),

				// Links of tabs
				tabsLinks = tabs.children().children('a[href^="#"]'),
				tabsLi = tabsLinks.parent(),

				// Tabs content
				tabsContent = wrapper.children('.tabs-content'),

				// Back button
				back = tabsContent.children('.tabs-back'),

				// Hidden parents
				hidden,

				// Processing vars
				equalized, active, activeId = false, activeHref,
				blocks = $(), newActive = false, datas = [],
				maxHeight = 0, tabsHeight;

			// IE7 has an issue with href attribute
			if ($.template.ie7)
			{
				tabsLinks = $();
				tabs.children().children('a').each(function(i)
				{
					if (this.href.indexOf('#') > -1)
					{
						tabsLinks = tabsLinks.add(this);
					}
				});
				tabsLi = tabsLinks.parent();
			}

			// If not found or not valid
			if (wrapper.length === 0 || tabs.length === 0 || tabsLinks.length === 0 || tabsContent.length === 0)
			{
				return;
			}

			// Add to wrappers list
			wrappers = wrappers.add(wrapper);

			// Create back button if needed
			if (back.length === 0)
			{
				back = $('<span class="tabs-back with-left-arrow top-bevel-on-light dark-text-bevel">Back</span>').prependTo(tabsContent).click(function(event)
				{
					// If the currently open tab contains a inner-tabs group
					var innerTabs = tabsContent.children('.tab-active:first').children('.inner-tabs.tab-opened'),
						backButton;
					if (innerTabs.length > 0)
					{
						// Click the back button
						backButton = innerTabs.children('.tabs-content').children('.tabs-back');
						if (backButton.length > 0)
						{
							backButton.click();
							return;
						}
					}

					// Return to tabs
					wrapper.removeClass('tab-opened');

					// Set wrapper correct size - will be ignored on standard/side tabs
					wrapper.height(tabs.outerHeight());

				}).applySetup(true, true);
			}

			// Reveal hidden parents if needed for correct height processing
			hidden = wrapper.tempShow();

			// Mode
			equalized = wrapper.hasClass('same-height');

			// Save height to prevent document scrolling
			if (equalized)
			{
				wrapper.css('min-height', wrapper.height()+'px');
			}

			// Active tab
			active = tabsLi.filter('.active:first');
			if (active.length === 0)
			{
				active = tabsLi.not('.disabled').first();
			}
			if (active.length > 0)
			{
				activeHref = active.addClass('active').children('a').attr('href');
				activeId = activeHref.substring(activeHref.indexOf('#')+1);
			}

			// Gather tabs content blocks and infos
			tabsLinks.each(function(i)
			{
				var linkHref = this.href,
					block = $(linkHref.substring(linkHref.indexOf('#'))),
					topMargin, height;

				// If found
				if (block.length > 0)
				{
					blocks = blocks.add(block.show());
					if (equalized)
					{
						// Get total height
						height = block.css('height', '').outerHeight();

						// Get first element's top-margin, because it affects the block height if it is negative
						topMargin = Math.min(0, block.children(':first').parseCSSValue('margin-top'));

						// Check if this is the tallest element
						maxHeight = Math.max(maxHeight, height+topMargin);

						// Store for equalization loop below
						datas[i] = [height, topMargin];
					}
				}
			});

			// Set equalized height
			if (equalized)
			{
				blocks.each(function(i)
				{
					var block = $(this),
						height = datas[i][0],
						topMargin = datas[i][1];

					// Set height depending on margins
					block.height(maxHeight-(height-block.height())-topMargin);
				});
			}

			// Toggle classes and hide non-active tabs
			blocks.each(function(i)
			{
				var block = $(this),
					isActive = block.hasClass('tab-active');

				// Hide if not current one
				if (this.id != activeId)
				{
					if (isActive)
					{
						block.removeClass('tab-active').trigger('hidetab');
					}
					block.hide();
				}
				else if (!isActive)
				{
					// Postponed call
					newActive = block.addClass('tab-active');
				}
			});

			// Send show event after hide events
			if (newActive)
			{
				// First time
				if (!newActive.data('tabshown'))
				{
					newActive.trigger('showtabinit');
					newActive.data('tabshown', true);
				}

				// Standard event
				newActive.trigger('showtab');
			}

			// Tabs height
			tabsHeight = tabs.height();

			// Content minimum size - ignored on standard tabs
			tabsContent.css('min-height', tabsHeight-1);	// 1 is the top border's size

			// For side-tabs, check if the content is smaller than the tabs
			if (wrapper.hasClass('side-tabs'))
			{
				wrapper[tabsContent.height() === (tabsHeight-1) ? 'addClass' : 'removeClass']('tabs-fullheight');
			}

			// Set wrapper correct size - ignored on standard/side tabs
			wrapper.height(wrapper.hasClass('tab-opened') ? tabsContent.outerHeight() : tabsHeight);

			// Restore height
			if (equalized)
			{
				wrapper.css('min-height', '');
			}

			// Hide previously hidden parents
			hidden.tempShowRevert();
		});

		// If not in a global refresh, update parent tabs
		if (!globalRefresh)
		{
			parents = wrappers.parent().closest('.standard-tabs, .swipe-tabs, .side-tabs').filter('.same-height').not(wrappers);
			if (parents.length > 0)
			{
				parents.refreshTabs();
			}
		}

		return this;
	};

	/**
	 * Add a tab
	 * @param string id the tab id
	 * @param string title the title of the tab
	 * @param string content the content of the tab
	 * @param boolean noPadding use true to prevent adding padding on the tab content block (optional, default: false)
	 */
	$.fn.addTab = function(id, title, content, noPadding)
	{
		this.each(function(i)
		{
				// Tabs wrapper
			var wrapper = $(this).closest('.standard-tabs, .swipe-tabs, .side-tabs'),

				// Tabs group
				tabs = wrapper.children('.tabs'),

				// Tabs content
				tabsContent = wrapper.children('.tabs-content');

			// If not found or not valid
			if (wrapper.length === 0 || tabs.length === 0 || tabsContent.length === 0)
			{
				return;
			}

			// Create elements (and a little IE7 debug)
			$('<li><a href="#'+id+'">'+title+'</a></li>').appendTo(tabs).prev().removeClass('last-child');
			tabsContent.append('<div id="'+id+'"'+(noPadding ? '' : ' class="with-padding"')+'>'+content+'</div>');

			// Refresh tabs
			wrapper.refreshTabs();
		});

		return this;
	};

	/**
	 * Remove a tab: use it either on the tab or the content block. The tab should be valid for the method to work
	 */
	$.fn.removeTab = function()
	{
		this.each(function(i)
		{
				// Target element
			var target = $(this),

				// Closest parent
				parent = target.closest('.tabs, .tabs-content'),

				// Type
				isTab = parent.hasClass('tabs'),

				// Wrapper
				wrapper = parent.closest('.standard-tabs, .swipe-tabs, .side-tabs'),

				// Processing vars
				linkHref;

			// If not found or not valid
			if (parent.length === 0 || wrapper.length === 0)
			{
				return;
			}

			// If the target is a tab
			if (isTab)
			{
				// Find tab link
				target = target.is('a') ? target : target.children('a');
				linkHref = target.attr('href');

				// If not valid
				if (target.length === 0 || linkHref.indexOf('#') < 0)
				{
					return;
				}

				// Remove
				$(linkHref.substring(linkHref.indexOf('#'))).remove();
				if ($.template.ie7)
				{
					target.parent().prev().addClass('last-child');
				}
				target.parent().remove();
			}
			else
			{
				// Get content block
				while (target.length > 0 && !target.parent().hasClass('tabs-content'))
				{
					target = target.parent();
				}

				// Remove
				if ($.template.ie7)
				{
					wrapper.children('.tabs').children().children('a').each(function(i)
					{
						if (this.href.indexOf('#') > -1 && this.href.split('#')[1] == target.attr('id'))
						{
							$(this).parent().prev().addClass('last-child').next().remove();
						}
					});
				}
				else
				{
					wrapper.children('.tabs').find('a[href="#'+target.attr('id')+'"]').parent().remove();
				}
				target.remove();
			}

			// Refresh tabs
			wrapper.refreshTabs();
		});

		return this;
	};

	// Add template setup function
	$.template.addSetupFunction(function(self, children)
	{
		// Global mode
		globalRefresh = true;

		// Tabs (processed in reverse order to start from deepest elements
		$(this.findIn(self, children, '.standard-tabs, .swipe-tabs, .side-tabs').get().reverse()).addClass('tabs-active').refreshTabs();

		// End of global mode
		globalRefresh = false;

		return this;
	});

	// Handle screen resizing
	win.on('normalized-resize', function(event)
	{
		// Tabs (processed in reverse order to start from deepest elements
		$($('.standard-tabs, .swipe-tabs, .side-tabs').get().reverse()).refreshTabs();
	});

})(jQuery, window, document);