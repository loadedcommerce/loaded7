/**
 * Auto-resizing textareas plugin
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
		bod = $(document.body);

	/**
	 * Helper function to build a pre element identical to the textarea
	 * @param jQuery textarea the target textarea
	 * @return jQuery the generated pre element
	 */
	function buildTextareaPre(textarea)
	{
			// Box-sizing type
		var boxSized = ( textarea.css('box-sizing') === 'border-box' || textarea.css('-webkit-box-sizing') === 'border-box' || textarea.css('-moz-box-sizing') === 'border-box' ),
			boxPadding = boxSized ? 'padding-bottom:'+(textarea.parseCSSValue('padding-top')+textarea.parseCSSValue('padding-bottom'))+'px; ' : '',

			// Some browsers break lines a little bit before the padding box
			breakPadding = 0;
			if ( $.browser.mozilla )
			{
				breakPadding = 8;
			}
			else if ( $.browser.opera )
			{
				breakPadding = 4;
			}
			else if ( $.browser.msie )
			{
				breakPadding = 2;
			}

		return $('<pre style="position: absolute; '+
						'top:0; '+
						'left:0; '+
						'padding:0; '+
						'visibility:hidden; '+
						boxPadding+
						'width:'+(textarea.width()-breakPadding)+'px; '+
						'font-size:'+textarea.css('font-size')+'; '+
						'font-family:'+textarea.css('font-family')+'; '+
						'line-height:'+textarea.css('line-height')+'; '+
						'min-height:'+textarea.css('line-height')+'; '+
						'letter-spacing:'+textarea.css('letter-spacing')+'; '+
					'">'+formatPreText(textarea.val())+'</pre>').appendTo(bod);
	}

	/**
	 * Helper function to format the text from the textarea before inserting
	 * into the pre element
	 * @param string text the text to format
	 * @return string the formatted text
	 */
	function formatPreText(text)
	{
		// Add an invisible char if the text starts or ends with a new line
		if (/^[\r\n]/.test(text))
		{
			text = "\u00a0"+text;
		}
		if (/[\r\n]$/.test(text))
		{
			text += "\u00a0";
		}

		return text;
	}

	/**
	 * Resize a textarea to fit the content
	 */
	function resizeTextarea()
	{
		var textarea = $(this).css({ overflow: 'hidden', resize: 'none' }),

			// Pre to get actual size
			pre = buildTextareaPre(textarea);

		// Set size
		textarea.height((pre.innerHeight())+'px');

		// Remove pre
		pre.remove();
	}

	// Template setup function
	$.template.addSetupFunction(function(self, children)
	{
		var elements = this.findIn(self, children, 'textarea.autoexpanding').widthchange(resizeTextarea);

		// Timeout to handle browser initial redraw
		setTimeout(function()
		{
			elements.each(resizeTextarea);

		}, 40);

		return this;
	});

	// Listener
	doc.on('focus', 'textarea.autoexpanding', function()
	{
			// Target
		var textarea = $(this),

			// Pre to get actual size
			pre = buildTextareaPre(textarea),

			// Function to update size
			updatePre = function()
			{
				// Update content - IE7 is buggy with PRE tags
				// http://www.quirksmode.org/bugreports/archives/2004/11/innerhtml_and_t.html
				if ($.template.ie7)
				{
					pre.remove();
					pre = buildTextareaPre(textarea);
				}
				else
				{
					pre.text(formatPreText(textarea.val()));
				}

				// Refresh size
				textarea.height((pre.innerHeight())+'px');
			},

			// Blur handling
			onBlur = function()
			{
				// Remove pre
				pre.remove();

				// Stop listening
				textarea.off(this.addEventListener ? 'input' : 'keyup', updatePre)
						.off('blur', onBlur);
			};

		// Start listening
		textarea.on('input keyup', updatePre)
				.on('blur', onBlur);
	});

})(jQuery, window, document);