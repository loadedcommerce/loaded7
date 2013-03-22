/**
 * Slider & progress plugin
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, document, undefined)
{
	/*
	 * undefined is used here as the undefined global variable in ECMAScript 3 is mutable (i.e. it can
	 * be changed by someone else). undefined isn't really being passed in so we can ensure that its value is
	 * truly undefined. In ES5, undefined can no longer be modified.
	 */

	/*
	 * document is passed through as local variable rather than as global, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

	// Objects cache
	var doc = $(document);

	/**
	 * Internal function to parse the marks options
	 * @param mixed value the mark option value
	 * @param boolean|string label true or pattern to automaticaly create label
	 * @param string labelAlign alignement default for label
	 * @param int min the min value
	 * @param int max the max value
	 * @param boolean useExtremes true to create marks at start and end of slider (if relevant)
	 * @param boolean insetExtremes true to set alignement (if not set) to inset marks matching min and max (if relevant)
	 * @param boolean horizontal whether marks are on the horizontal axis or not
	 * @return object the final marks array
	 */
	function _parseMarksOption(value, label, labelAlign, min, max, useExtremes, insetExtremes, horizontal)
	{
		var i, step;

		// Mode
		if (typeof value === 'string')
		{
			value = parseFloat(value, 10);
		}
		if (typeof value === 'number' && value > 0)
		{
			// Create marks
			step = value;
			value = [];
			if (useExtremes)
			{
				for (i = min; i <= max; i+=step)
				{
					value.push(i);
				}
			}
			else
			{
				for (i = min+step; i < max; i+=step)
				{
					value.push(i);
				}
			}
		}

		// Other formats
		if (typeof value !== 'object')
		{
			return [];
		}

		// Format points
		for (i = 0; i < value.length; ++i)
		{
			// Convert to object if needed
			if (typeof value[i] !== 'object')
			{
				value[i] = { value: value[i] };
			}

			// Add label
			if (value[i].label == undefined)
			{
				value[i].label = label ? ((label === true) ? value[i].value : label.replace('[value]', value[i].value)) : '';
			}

			// Add alignement
			if (value[i].align == undefined)
			{
				if (insetExtremes && value[i].value === min)
				{
					value[i].align = horizontal ? 'right' : 'bottom';
				}
				else if (insetExtremes && value[i].value === max)
				{
					value[i].align = horizontal ? 'left' : 'top';
				}
				else
				{
					value[i].align = labelAlign;
				}
			}
		}

		return value;
	};

	/**
	 * Internal function to parse auto-spacing options
	 * @param int|boolean config the provided configuration
	 * @param int|boolean global the global option default
	 * @return the parsed configuration
	 */
	function _parseAutoSpacing(config, global)
	{
		// Inheritance
		if (config === null)
		{
			config = global;
		}

		// Format
		if (typeof config !== 'number' && typeof config !== 'boolean')
		{
			config = parseInt(config, 10);
			if (isNaN(config))
			{
				config = 0;
			}
		}

		return config;
	};

	/**
	 * Build the track for a progress/slider
	 * @param object options the options
	 */
	$.fn.buildTrack = function(options)
	{
		this.each(function()
		{
				// Target
			var track = $(this),

				// Final settings
				settings = $.extend({}, $.fn.buildTrack.defaults, options),

				// List of classes
				classes = [],

				// Is the track horizontal ?
				horizontal = (settings.orientation.toLowerCase() !== 'vertical'),

				// Track size
				size,

				// Extra css styles
				style = {},

				// Marks lists
				innerMarks	= (settings.innerMarks)
								? _parseMarksOption(settings.innerMarks, null, null, settings.min, settings.max, false) : [],
				topMarks	= (settings.topMarks && horizontal)
								? _parseMarksOption(settings.topMarks, settings.topLabel, settings.topLabelAlign, settings.min, settings.max, true, settings.insetExtremes, true) : [],
				rightMarks	= (settings.rightMarks && !horizontal)
								? _parseMarksOption(settings.rightMarks, settings.rightLabel, settings.rightLabelAlign, settings.min, settings.max, true, settings.insetExtremes, false) : [],
				bottomMarks	= (settings.bottomMarks && horizontal)
								? _parseMarksOption(settings.bottomMarks, settings.bottomLabel, settings.bottomLabelAlign, settings.min, settings.max, true, settings.insetExtremes, true) : [],
				leftMarks	= (settings.leftMarks && !horizontal)
								? _parseMarksOption(settings.leftMarks, settings.leftLabel, settings.leftLabelAlign, settings.min, settings.max, true, settings.insetExtremes, false) : [],

				// Marks selections
				iMarks = $(),	// Inner
				tMarks = $(),	// Top
				rMarks = $(),	// Right
				bMarks = $(),	// Bottom
				lMarks = $(),	// Left
				hMarks = $(),	// Horizontal
				vMarks = $(),	// Vertical

				// Infos about marks labels
				topLabels = false,
				rightLabels = false,
				bottomLabels = false,
				leftLabels = false,

				// Auto-spacing config
				autoSpacingTop		= _parseAutoSpacing(settings.autoSpacingTop, settings.autoSpacing),
				autoSpacingRight	= _parseAutoSpacing(settings.autoSpacingRight, settings.autoSpacing),
				autoSpacingBottom	= _parseAutoSpacing(settings.autoSpacingBottom, settings.autoSpacing),
				autoSpacingLeft		= _parseAutoSpacing(settings.autoSpacingLeft, settings.autoSpacing),
				autoSpacing = (autoSpacingTop !== false || autoSpacingRight !== false || autoSpacingBottom !== false || autoSpacingLeft !== false),

				// Work vars
				position, label, labelAlign;

			// Orientation
			if (!horizontal)
			{
				classes.push('vertical');
			}

			// Classes
			if (typeof settings.classes === 'string')
			{
				classes.push(settings.classes);
			}
			else if (typeof settings.classes === 'object')
			{
				classes = classes.concat(settings.classes);
			}

			// Size
			if (settings.size)
			{
				// Unit
				if (typeof settings.size === 'number')
				{
					settings.size += 'px';
				}
				style[horizontal ? 'width' : 'height'] = settings.size;
			}
			else if (horizontal)
			{
				classes.push('full-width');
				autoSpacingLeft = false;
				autoSpacingRight = false;
			}

			// Slider base
			track.addClass(classes.join(' '));
			track.css($.extend(style, settings.css));
			size = horizontal ? track.innerWidth() : track.innerHeight();

			// Watch size for fluid elements
			if (!track[0].style.width || !track[0].style.width.match(/[0-9\.]+(px|em)/i))
			{
				track.sizechange(function()
				{
					// Refresh size cache
					size = horizontal ? track.innerWidth() : track.innerHeight();

					// Marks
					hMarks.each(function(i)
					{
						var element = $(this),
						value = element.data('mark-value');

						element.css('left', Math.round((value-settings.min)/(settings.max-settings.min)*(size-1)+1)+'px');
					});
					vMarks.each(function(i)
					{
						var element = $(this),
						value = element.data('mark-value');

						element.css('bottom', Math.round((value-settings.min)/(settings.max-settings.min)*(size-1)+1)+'px');
					});

					// Listener
					if (track.onSizechange)
					{
						track.onSizechange();
					}

					// Auto spacing
					addSpacings();
				});
			}

			// Create required marks
			for (i = 0; i < innerMarks.length; ++i)
			{
				position = Math.round((innerMarks[i].value-settings.min)/(settings.max-settings.min)*(size-1))+1;
				iMarks = iMarks.add($('<span class="inner-mark" style="'+(horizontal ? 'left' : 'bottom')+': '+position+'px"></span>').appendTo(track).data('mark-value', innerMarks[i].value));
			}
			for (i = 0; i < topMarks.length; ++i)
			{
				position = Math.round((topMarks[i].value-settings.min)/(settings.max-settings.min)*(size-1))+1;
				if (topMarks[i].label !== undefined && topMarks[i].label !== '')
				{
					labelAlign = (topMarks[i].align === 'left' || topMarks[i].align === 'right') ? ' align-'+topMarks[i].align : '';
					label = '<span class="mark-label'+labelAlign+'">'+topMarks[i].label+'</span>';
					topLabels = true;
				}
				else
				{
					label = '';
				}
				tMarks = tMarks.add($('<span class="top-mark" style="left: '+position+'px">'+label+'</span>').appendTo(track).data('mark-value', topMarks[i].value));
			}
			for (i = 0; i < rightMarks.length; ++i)
			{
				position = Math.round((rightMarks[i].value-settings.min)/(settings.max-settings.min)*(size-1))+1;
				if (rightMarks[i].label !== undefined && rightMarks[i].label !== '')
				{
					labelAlign = (rightMarks[i].align === 'top' || rightMarks[i].align === 'bottom') ? ' align-'+rightMarks[i].align : '';
					label = '<span class="mark-label'+labelAlign+'">'+rightMarks[i].label+'</span>';
					rightLabels = true;
				}
				else
				{
					label = '';
				}
				rMarks = rMarks.add($('<span class="right-mark" style="bottom: '+position+'px">'+label+'</span>').appendTo(track).data('mark-value', rightMarks[i].value));
			}
			for (i = 0; i < bottomMarks.length; ++i)
			{
				position = Math.round((bottomMarks[i].value-settings.min)/(settings.max-settings.min)*(size-1))+1;
				if (bottomMarks[i].label !== undefined && bottomMarks[i].label !== '')
				{
					labelAlign = (bottomMarks[i].align === 'left' || bottomMarks[i].align === 'right') ? ' align-'+bottomMarks[i].align : '';
					label = '<span class="mark-label'+labelAlign+'">'+bottomMarks[i].label+'</span>';
					bottomLabels = true;
				}
				else
				{
					label = '';
				}
				bMarks = bMarks.add($('<span class="bottom-mark" style="left: '+position+'px">'+label+'</span>').appendTo(track).data('mark-value', bottomMarks[i].value));
			}
			for (i = 0; i < leftMarks.length; ++i)
			{
				position = Math.round((leftMarks[i].value-settings.min)/(settings.max-settings.min)*(size-1))+1;
				if (leftMarks[i].label !== undefined && leftMarks[i].label !== '')
				{
					labelAlign = (leftMarks[i].align === 'top' || leftMarks[i].align === 'bottom') ? ' align-'+leftMarks[i].align : '';
					label = '<span class="mark-label'+labelAlign+'">'+leftMarks[i].label+'</span>';
					leftLabels = true;
				}
				else
				{
					label = '';
				}
				lMarks = lMarks.add($('<span class="left-mark" style="bottom: '+position+'px">'+label+'</span>').appendTo(track).data('mark-value', leftMarks[i].value));
			}

			// Concat for further processing
			hMarks = tMarks.add(bMarks);
			vMarks = lMarks.add(rMarks);
			if (horizontal)
			{
				hMarks = hMarks.add(iMarks);
			}
			else
			{
				vMarks = vMarks.add(iMarks);
			}

			// Add spacing margins
			function addSpacings()
			{
				// If enabled
				if (autoSpacing)
				{
					// Init
					var sliderOffset = track.offset(),
						bounds = {
							top:		sliderOffset.top,
							right:		sliderOffset.left+track.outerWidth(),
							bottom:		sliderOffset.top+track.outerHeight(),
							left:		sliderOffset.left
						},
						labelBounds = {
							top:		bounds.top,
							right:		bounds.right,
							bottom:		bounds.bottom,
							left:		bounds.left
						},
						enableTop		= (autoSpacingTop !== false),
						enableRight		= (autoSpacingRight !== false),
						enableBottom	= (autoSpacingBottom !== false),
						enableLeft		= (autoSpacingLeft !== false),
						extraTop		= (typeof autoSpacingTop === 'number'		? autoSpacingTop	: 0),
						extraRight		= (typeof autoSpacingRight === 'number'		? autoSpacingRight	: 0),
						extraBottom		= (typeof autoSpacingBottom === 'number'	? autoSpacingBottom	: 0),
						extraLeft		= (typeof autoSpacingLeft === 'number'		? autoSpacingLeft	: 0),
						css;

					// Check marks
					if (horizontal)
					{
						if (topMarks.length > 0)
						{
							// Mark size
							labelBounds.top = enableTop ? bounds.top-8 : bounds.top;
							if (topLabels)
							{
								// Check bounds
								tMarks.children('.mark-label').each(function(i)
								{
									var label = $(this),
										offset = label.offset(),
										width, textWidth,
										left, right;

									// Exact width (text extent inside fixed width element)
									if (enableLeft || enableRight)
									{
										width = label.width();
										if (!label.hasClass('align-left') && !label.hasClass('align-right'))
										{
											label.css('width', 'auto');
											textWidth = Math.min(width, label.width());
											left = offset.left+Math.round((width/2)-(textWidth/2));
											right = offset.left+Math.round((width/2)+(textWidth/2));
											label.css('width', '');
										}
										else
										{
											left = offset.left;
											right = offset.left+width;
										}
									}

									// Bounds
									labelBounds.top		= enableTop		? Math.min(offset.top, labelBounds.top)	: labelBounds.top;
									labelBounds.right	= enableRight	? Math.max(right, labelBounds.right)	: labelBounds.right;
									labelBounds.left	= enableLeft	? Math.min(left, labelBounds.left)		: labelBounds.left;
								});
							}
						}
						if (bottomMarks.length > 0)
						{
							// Mark size
							labelBounds.bottom = enableBottom ? bounds.bottom+8 : bounds.bottom;
							if (bottomLabels)
							{
								// Check bounds
								bMarks.children('.mark-label').each(function(i)
								{
									var label = $(this),
										offset = label.offset(),
										width, textWidth,
										left, right;

									// Exact width (text extent inside fixed width element)
									if (enableLeft || enableRight)
									{
										width = label.width();
										if (!label.hasClass('align-left') && !label.hasClass('align-right'))
										{
											label.css('width', 'auto');
											textWidth = Math.min(width, label.width());
											left = offset.left+Math.round((width/2)-(textWidth/2));
											right = offset.left+Math.round((width/2)+(textWidth/2));
											label.css('width', '');
										}
										else
										{
											left = offset.left;
											right = offset.left+width;
										}
									}

									// Bounds
									labelBounds.right	= enableRight	? Math.max(right, labelBounds.right)						: labelBounds.right;
									labelBounds.bottom	= enableBottom	? Math.max(offset.top+label.height(), labelBounds.bottom)	: labelBounds.bottom;
									labelBounds.left	= enableLeft	? Math.min(left, labelBounds.left)							: labelBounds.left;

									// Reset
									label.css('width', '');
								});
							}
						}
					}
					else
					{
						if (leftMarks.length > 0)
						{
							// Mark size
							labelBounds.left = enableLeft ? bounds.left-8 : bounds.left;
							if (leftLabels)
							{
								// Check bounds
								lMarks.children('.mark-label').each(function(i)
								{
									var label = $(this),
										offset = label.offset();

									// Bounds
									labelBounds.top		= enableTop		? Math.min(offset.top, labelBounds.top)						: labelBounds.top;
									labelBounds.bottom	= enableBottom	? Math.max(offset.top+label.height(), labelBounds.bottom)	: labelBounds.bottom;
									labelBounds.left	= enableLeft	? Math.min(offset.left, labelBounds.left)					: labelBounds.left;
								});
							}
						}
						if (rightMarks.length > 0)
						{
							// Mark size
							labelBounds.right = enableRight ? bounds.right+8 : bounds.right;
							if (rightLabels)
							{
								// Check bounds
								rMarks.children('.mark-label').each(function(i)
								{
									var label = $(this),
										offset = label.offset();

									// Bounds
									labelBounds.top		= enableTop		? Math.min(offset.top, labelBounds.top)						: labelBounds.top;
									labelBounds.right	= enableRight	? Math.max(offset.left+label.width(), labelBounds.right)	: labelBounds.right;
									labelBounds.bottom	= enableBottom	? Math.max(offset.top+label.height(), labelBounds.bottom)	: labelBounds.bottom;
								});
							}
						}
					}

					// Final values
					css = {
						marginTop:		(labelBounds.top < bounds.top)			? ((bounds.top-labelBounds.top)+extraTop)+'px'			: (extraTop > 0 ? extraTop+'px' : ''),
						marginRight:	(labelBounds.right > bounds.right)		? ((labelBounds.right-bounds.right)+extraRight)+'px'	: (extraRight > 0 ? extraRight+'px' : ''),
						marginBottom:	(labelBounds.bottom > bounds.bottom)	? ((labelBounds.bottom-bounds.bottom)+extraBottom)+'px'	: (extraBottom > 0 ? extraBottom+'px' : ''),
						marginLeft:		(labelBounds.left < bounds.left)		? ((bounds.left-labelBounds.left)+extraLeft)+'px'		: (extraLeft > 0 ? extraLeft+'px' : '')
					};

					// Centering
					if (settings.autoSpacingCenterVertical && enableTop && enableBottom && css.marginTop !== css.marginBottom)
					{
						if (css.marginTop === '')
						{
							css.marginTop = css.marginBottom;
						}
						else if (css.marginBottom === '')
						{
							css.marginBottom = css.marginTop;
						}
						else
						{
							css.marginTop = (parseInt(css.marginTop, 10) > parseInt(css.marginBottom, 10)) ? css.marginTop : css.marginBottom;
							css.marginBottom = css.marginTop;
						}
					}
					if (settings.autoSpacingCenterHorizontal && enableLeft && enableRight && css.marginLeft !== css.marginRight)
					{
						if (css.marginLeft === '')
						{
							css.marginLeft = css.marginRight;
						}
						else if (css.marginRight === '')
						{
							css.marginRight = css.marginLeft;
						}
						else
						{
							css.marginLeft = (parseInt(css.marginLeft, 10) > parseInt(css.marginRight, 10)) ? css.marginLeft : css.marginRight;
							css.marginRight = css.marginLeft;
						}
					}

					// Set
					track.css(css);
				}
			}

			// First setup
			addSpacings();
		});

		return this;
	};

	/**
	 * Default track options
	 */
	$.fn.buildTrack.defaults = {

		/**
		 * Width/height of the track (any css value, int will be used as pixels), or false for fluid size
		 * @var int|string|boolean
		 */
		size: 250,

		/**
		 * Class or list of class for the bar
		 * @var string|array
		 */
		classes: null,

		/**
		 * Any extra css styles, in a key/value map
		 * @var object
		 */
		css: {},

		/**
		 * Orientation of the track ('horizontal' or 'vertical')
		 * @var string
		 */
		orientation: 'horizontal',

		/**
		 * Inner marks, may be:
		 * - a number: marks will be created each multiple of this number, starting from 'min'
		 * - an array: a list of points where marks should be created.
		 * @var string|array
		 */
		innerMarks: null,

		/**
		 * Top marks, may be:
		 * - a number: marks will be created each multiple of this number, starting from 'min'
		 * - an array: a list of points where marks should be created. Each point can be either a number or an object
		 * 			   with a value and two optional properties, 'label' and 'align': { value: x, label: 'label', align: 'center' }.
		 * 			   If the point is a number or if 'label' is not set, an automatic label will be created if topLabel is set.
		 * 			   If the point is a number or if 'align' is not set, the value of topLabelAlign will be used.
		 * @var string|array
		 */
		topMarks: null,

		/**
		 * Automatic label for top marks. Use true to display mark value, or a string with [value] as a placeholder
		 * for each mark value, for instance: '[value]%'
		 * @var boolean|string
		 */
		topLabel: null,

		/**
		 * Alignement for top marks labels: 'left', 'center' or 'right'
		 * @var string
		 */
		topLabelAlign: 'center',

		/**
		 * Right marks, may be:
		 * - a number: marks will be created each multiple of this number, starting from 'min'
		 * - an array: a list of points where marks should be created. Each point can be either a number or an object
		 * 			   with a value and an optional properties, 'label': { value: x, label: 'label' }.
		 * 			   If the point is a number or if 'label' is not set, an automatic label will be created if rightLabel is set.
		 * @var string|array
		 */
		rightMarks: null,

		/**
		 * Automatic label for right marks. Use true to display mark value, or a string with [value] as a placeholder
		 * for each mark value, for instance: '[value]%'
		 * @var boolean|string
		 */
		rightLabel: null,

		/**
		 * Alignement for right marks labels: 'top', 'center' or 'bottom'
		 * @var string
		 */
		rightLabelAlign: 'center',

		/**
		 * Bottom marks, may be:
		 * - a number: marks will be created each multiple of this number, starting from 'min'
		 * - an array: a list of points where marks should be created. Each point can be either a number or an object
		 * 			   with a value and two optional properties, 'label' and 'align': { value: x, label: 'label', align: 'center' }.
		 * 			   If the point is a number or if 'label' is not set, an automatic label will be created if bottomLabel is set.
		 * 			   If the point is a number or if 'align' is not set, the value of bottomLabelAlign will be used.
		 * @var string|array
		 */
		bottomMarks: null,

		/**
		 * Automatic label for top marks. Use true to display mark value, or a string with [value] as a placeholder
		 * for each mark value, for instance: '[value]%'
		 * @var boolean|string
		 */
		bottomLabel: null,

		/**
		 * Alignement for bottom marks labels: 'left', 'center' or 'right'
		 * @var string
		 */
		bottomLabelAlign: 'center',

		/**
		 * Left marks, may be:
		 * - a number: marks will be created each multiple of this number, starting from 'min'
		 * - an array: a list of points where marks should be created. Each point can be either a number or an object
		 * 			   with a value and an optional properties, 'label': { value: x, label: 'label' }.
		 * 			   If the point is a number or if 'label' is not set, an automatic label will be created if leftLabel is set.
		 * @var string|array
		 */
		leftMarks: null,

		/**
		 * Automatic label for left marks. Use true to display mark value, or a string with [value] as a placeholder
		 * for each mark value, for instance: '[value]%'
		 * @var boolean|string
		 */
		leftLabel: null,

		/**
		 * Alignement for left marks labels: 'top', 'center' or 'bottom'
		 * @var string
		 */
		leftLabelAlign: 'center',

		/**
		 * Automaticaly set alignement (if not set) to inset the labels of marks matching min and max
		 * @var boolean
		 */
		insetExtremes: false,

		/**
		 * Will add spacing margins to the track, matching space required by marks and labels - true to enable, false to disable.
		 * Setting a numeric value will enabled auto-spacing and add this as an extra margin on top of calculated one.
		 * @var boolean
		 */
		autoSpacing: false,

		/**
		 * Auto top spacing: true to enable, false to disable, null to inherit from autoSpacing.
		 * Setting a numeric value will enable auto-spacing and add this as an extra margin on top of calculated one.
		 * @var boolean|int
		 */
		autoSpacingTop: null,

		/**
		 * Auto right spacing: true to enable, false to disable, null to inherit from autoSpacing.
		 * Setting a numeric value will enable auto-spacing and add this as an extra margin on top of calculated one.
		 * @var boolean|int
		 */
		autoSpacingRight: null,

		/**
		 * Auto bottom spacing: true to enable, false to disable, null to inherit from autoSpacing.
		 * Setting a numeric value will enable auto-spacing and add this as an extra margin on top of calculated one.
		 * @var boolean|int
		 */
		autoSpacingBottom: null,

		/**
		 * Auto left spacing: true to enable, false to disable, null to inherit from autoSpacing.
		 * Setting a numeric value will enable auto-spacing and add this as an extra margin on top of calculated one.
		 * @var boolean|int
		 */
		autoSpacingLeft: null,

		/**
		 * When auto-spacing, equalize top and bottom margins to the highest - for instance to keep alignement on baseline
		 * @var boolean
		 */
		autoSpacingCenterVertical: false,

		/**
		 * When auto-spacing, equalize left and right margins to the highest - for instance to center in a block
		 * @var boolean
		 */
		autoSpacingCenterHorizontal: false

	};

	/**
	 * Internal function for validating options, so we can work on it without worrying about format
	 * @param object options the options object
	 * @return void
	 */
	function _validateOptions(options)
	{
		var list, i;

		// If set
		if (options)
		{
			// Inputs number and types
			if (options.inputs)
			{
				options.inputs = _validateInputs(options.inputs, true);
			}
			if (options.input)
			{
				options.input = _validateInputs(options.input, false);
			}

			// Values number
			if (options.values)
			{
				if (typeof options.values !== 'object' || options.values.length < 2)
				{
					options.values = null;
				}
			}

			// Tooltip position
			if (options.tooltip && typeof options.tooltip === 'object')
			{
				if (options.tooltip.length === 1)
				{
					options.tooltip = [options.tooltip[0], options.tooltip[0]];
				}
				else if (options.tooltip.length === 0)
				{
					options.tooltip = null;
				}
			}
		}

		return options;
	};

	/**
	 * Start watching an input related to a slider
	 * @param int index the index of the input in the cursors list
	 * @param function setValue the value to set slider's value
	 * @param int min the minimum value
	 * @param int max the maximum value
	 */
	function _watchSliderInput()
	{
		var input = $(this),
			last;

		// Watch keydown
		input.on('keydown.slider', function(event)
		{
			// If up and down
			if (event.which === 38 || event.which === 40)
			{
				var value = parseFloat(input.val());

				// Check if numeric
				if (!isNaN(value))
				{
					value += (event.shiftKey) ? ((event.which === 38) ? 10 : -10) : ((event.which === 38) ? 1 : -1);
					input.val(value);
				}
			}
		});

		// Watch keyup
		input.on('keyup.slider', function(event)
		{
			var value = input.val();

			// Only trigger change if the content has changed
			if (value === last)
			{
				return;
			}

			// Update slider
			input.trigger('change');

			// Store for next check
			last = value;
		});
	};

	/**
	 * End watching an input related to a slider
	 */
	function _endWatchSliderInput()
	{
		$(this).off('keydown.slider').off('keyup.slider');
	};

	/**
	 * Validate the inputs option
	 * @param string|array inputs the given option
	 * @param boolean multiple true for 2 inputs, false for one
	 * @return jQuery|array|boolean a jQuery selection if one input is required, else an array with the inputs, or false if invalid
	 */
	function _validateInputs(inputs, multiple)
	{
		var i, list;

		// Empty
		if (!inputs)
		{
			return false;
		}
		else if (typeof inputs === 'string')
		{
			inputs = $(inputs).filter('input');
		}
		else if (typeof inputs === 'object')
		{
			// Array
			if (!(inputs instanceof jQuery))
			{
				// Format array values
				list = inputs;
				inputs = $();
				for (i = 0; i < list.length; ++i)
				{
					// Type
					if (typeof list[i] === 'string')
					{
						list[i] = $(list[i]);
					}

					// Format
					if (list[i] instanceof jQuery)
					{
						inputs = inputs.add(list[i]);
					}
				}
			}

			// Validation
			inputs = inputs.filter('input');
		}
		else
		{
			return false;
		}

		// Number of required inputs
		if (!multiple)
		{
			return (inputs.length > 0) ? inputs.eq(0) : false;
		}
		else
		{
			return (inputs.length > 1) ? [inputs.eq(0), inputs.eq(1)] : false;
		}
	}

	/**
	 * Create a slider in the target element, or next to the target element if it is an input
	 * Options may be set using the inline html5 data-slider-options attribute:
	 * <div data-slider-options="{'max':200}"></div>
	 * @param object options
	 */
	$.fn.slider = function(options)
	{
		// Validation of options
		_validateOptions(options);

		this.each(function()
		{
				// Target
			var target = $(this),

				// Is the target an input ?
				isInput = (this.nodeName.toLowerCase() === 'input'),

				// Slider size
				size,

				// Inline options
				userOptions = $.extend({}, options, _validateOptions(target.data('slider-options'))),

				// Final settings
				settings = $.extend({}, $.fn.slider.defaults, userOptions),

				// Is the slider horizontal ?
				horizontal = (settings.orientation.toLowerCase() !== 'vertical'),

				// List of classes
				barClasses = ['slider-bar'], tooltipClasses = ['message', 'inner-tooltip'],

				// Bar mode
				barModeMin = (settings.barMode.toLowerCase() !== 'max'),

				// Is it a range
				range = (settings.inputs !== null || settings.values !== null),

				// Are there inputs
				inputs = range ? settings.inputs : (isInput ? target : settings.input),

				// Objects
				bar, cursor1, cursor2, cursors,

				// Tell if dragging and hovering
				dragging = false, hovering = false,

				// Do we use tooltips ?
				useTooltips = (settings.tooltip !== null && settings.tooltip !== false),

				// Enable/disabled change event listeners
				useEvents = true,

				// Stripes
				stripesSize, animatedStripes, darkStripes,

				// Work vars
				value, finalValue, init = false, tooltip;

			// Prepare inputs
			if (inputs)
			{
				if (range)
				{
					// Hide if required
					if (settings.hideInput)
					{
						inputs[0].hide();
						inputs[1].hide();
					}
					else
					{
						if (inputs[0].prop('type').toLowerCase() !== 'hidden')
						{
							inputs[0].focus(function()
							{
								_watchSliderInput.call(this, 0, setValue, settings.min, settings.max);

							}).blur(function()
							{
								_endWatchSliderInput.call(this);
								inputs[0].val(value[0]);
							});
						}
						if (inputs[1].prop('type').toLowerCase() !== 'hidden')
						{
							inputs[1].focus(function()
							{
								_watchSliderInput.call(this, 1, setValue, settings.min, settings.max);

							}).blur(function()
							{
								_endWatchSliderInput.call(this);
								inputs[1].val(value[1]);
							});
						}
					}

					// Watch changes
					inputs[0].change(function(event)
					{
						if (useEvents)
						{
							useEvents = false;
							setValue(inputs[0].val(), null);
							useEvents = true;
						}
					});
					inputs[1].change(function(event)
					{
						if (useEvents)
						{
							useEvents = false;
							setValue(null, $(this).val());
							useEvents = true;
						}
					});
				}
				else
				{
					// Hide if required
					if (settings.hideInput)
					{
						inputs.hide();
					}
					else if (inputs.prop('type').toLowerCase() !== 'hidden')
					{
						inputs.focus(function()
						{
							_watchSliderInput.call(this, 0, setValue, settings.min, settings.max);

						}).blur(function()
						{
							_endWatchSliderInput.call(this);
							inputs.val(value);
						});
					}

					// Watch changes
					inputs.change(function(event)
					{
						if (useEvents)
						{
							useEvents = false;
							setValue($(this).val());
							useEvents = true;
						}
					});
				}
			}

			// Retrieve and normalize values
			if (range)
			{
				if (inputs && !userOptions.values)
				{
					value = [
						normalizeValue(inputs[0].val(), settings.min),
						normalizeValue(inputs[1].val(), settings.max)
					];
				}
				else
				{
					value = [
						normalizeValue(settings.values[0], settings.min),
						normalizeValue(settings.values[1], settings.max)
					];
				}

				// Final values
				finalValue = [
					finalizeValue(value[0]),
					finalizeValue(value[1])
				];
			}
			else
			{
				if (inputs && !userOptions.values)
				{
					value = normalizeValue(inputs.val(), settings.min);
				}
				else
				{
					value = normalizeValue(settings.value, settings.min);
				}

				// Final values
				finalValue = finalizeValue(value);
			}

			// Slider base
			if (isInput)
			{
				target = $('<span></span>').insertAfter(target);
			}
			target.addClass('slider').buildTrack(settings);
			size = horizontal ? target.innerWidth() : target.innerHeight();

			// Bar
			if (typeof settings.barClasses === 'string')
			{
				barClasses.push(settings.barClasses);
			}
			else if (typeof settings.barClasses === 'object')
			{
				barClasses = barClasses.concat(settings.barClasses);
			}
			bar = $('<span class="'+barClasses.join(' ')+'"></span>');
			bar[settings.innerMarksOverBar ? 'prependTo' : 'appendTo'](target);

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
				bar.append('<span class="'+darkStripes+stripesSize+'stripes'+animatedStripes+'"></span>');
			}

			// Build cursor tooltip
			function buildTooltip(position)
			{
				// Mode auto
				if (!$.inArray(position, ['top', 'right', 'bottom', 'left']))
				{
					position = horizontal ? 'top' : 'right';
				}

				switch (position)
				{
					case 'right':
						return '<span class="'+tooltipClasses.join(' ')+' right"><span class="tooltip-value"></span><span class="block-arrow left"><span></span></span></span>';
						break;

					case 'bottom':
						return '<span class="'+tooltipClasses.join(' ')+' bottom"><span class="tooltip-value"></span><span class="block-arrow top"><span></span></span></span>';
						break;

					case 'left':
						return '<span class="'+tooltipClasses.join(' ')+' left"><span class="tooltip-value"></span><span class="block-arrow right"><span></span></span></span>';
						break;

					default:
						return '<span class="'+tooltipClasses.join(' ')+'"><span class="tooltip-value"></span><span class="block-arrow"><span></span></span></span>';
						break;
				}
			}

			// Tooltip
			tooltip = '';
			if (useTooltips)
			{
				// User classes
				if (typeof settings.tooltipClass === 'string')
				{
					tooltipClasses.push(settings.tooltipClass);
				}
				else if (typeof settings.tooltipClass === 'object')
				{
					tooltipClasses = tooltipClasses.concat(settings.tooltipClass);
				}

				// Position format
				if (typeof settings.tooltip !== 'object')
				{
					settings.tooltip = [settings.tooltip, settings.tooltip];
				}

				// Code
				tooltip = buildTooltip(settings.tooltip[0]);
			}

			// Cursors
			cursor1 = $('<span class="slider-cursor'+(settings.knob ? ' knob' : '')+'">'+tooltip+'</span>').appendTo(target);
			cursors = cursor1;
			if (range)
			{
				// Tooltip
				if (useTooltips && settings.tooltip[0] !== settings.tooltip[1])
				{
					tooltip = buildTooltip(settings.tooltip[1]);
				}

				// Create
				cursor1.data('slider-range-index', 0);
				cursor2 = $('<span class="slider-cursor'+(settings.knob ? ' knob' : '')+'">'+tooltip+'</span>').appendTo(target).data('slider-range-index', 1);
				cursors = cursors.add(cursor2);
			}

			// Behaviour
			if (useTooltips && settings.tooltipOnHover)
			{
				if (!Modernizr.touch)
				{
					target.hover(function(event)
					{
						if (!dragging)
						{
							cursors.children('.inner-tooltip').stop(true).fadeTo('fast', 1);
						}
						hovering = true;

					}, function(event)
					{
						if (!dragging)
						{
							cursors.children('.inner-tooltip').stop(true).fadeTo('fast', 0);
						}
						hovering = false;

					});
				}
				cursors.children('.inner-tooltip').hide();
			}

			// Function to normalize value according to min/max and step
			function normalizeValue(value, def)
			{
				// Format
				if (typeof value !== 'number')
				{
					value = parseFloat(value, 10) || def;
				}

				// Range
				value = Math.max(settings.min, Math.min(settings.max, value));

				// If cursor should stick to rounded values
				if (!dragging || settings.stickToRound)
				{
					value = roundValue(value);
				}

				// If cursor should stick to steps
				if (!dragging || settings.stickToStep)
				{
					value = applyStep(value);
				}

				return value;
			};

			// Apply step interval
			function applyStep(value)
			{
				var i, last = false;

				if (settings.step)
				{
					if (typeof settings.step === 'object')
					{
						for (i = 0; i < settings.step.length; ++i)
						{
							// If lower than next step
							if (value < settings.step[i])
							{
								// If no previous value, use this step
								if (last === false)
								{
									return settings.step[i];
								}
								else
								{
									// Return closest value
									return (value-last < settings.step[i]-value) ? last : settings.step[i];
								}
							}

							// Store for next round
							last = settings.step[i];
						}

						// Higher than all steps, use last one
						return last;
					}
					else
					{
						return Math.round((value-settings.min)/settings.step)*settings.step+settings.min;
					}
				}

				return value;
			}

			// Round value
			function roundValue(value)
			{
				// Round
				if (settings.round === true || settings.round === 0)
				{
					value = Math.round(value);
				}
				else if (settings.round > 0)
				{
					value = Math.round(value*Math.pow(10, settings.round))/Math.pow(10, settings.round);
				}

				return value;
			}

			// Return final value
			function finalizeValue(value)
			{
				// If cursor does not stick to rounded values
				if (dragging && !settings.stickToRound)
				{
					value = roundValue(value);
				}

				// If cursor does not stick to steps
				if (dragging && !settings.stickToStep)
				{
					value = applyStep(value);
				}

				return value;
			}

			// Function to set slider value
			function setValue(val1, val2)
			{
				var empty1 = (val1 === undefined || val1 === null),
					empty2 = (val2 === undefined || val2 === null),
					changed = false,
					temp, change1, change2,
					changed1, changed2,
					focus1, focus2;

				// Normalize values
				val1 = empty1 ? (range ? value[0] : value) : normalizeValue(val1, settings.min);
				if (range)
				{
					val2 = empty2 ? value[1] : normalizeValue(val2, settings.max);

					// If val2 is smaller than val1
					if (val2 < val1)
					{
						// If one value is not set, normalize
						if (empty2)
						{
							val1 = val2;
						}
						else if (empty1)
						{
							val2 = val1;
						}
						// If both are defined, change order
						else
						{
							temp = val2;
							val2 = val1;
							val1 = temp;
						}
					}
				}

				// Update values, inputs and cursors
				if (range)
				{
					// Detect change
					change1 = (value[0] != val1);
					change2 = (value[1] != val2);
					if (change1 || change2 || !init)
					{
						// Store
						value = [val1, val2];

						// Final values
						finalVal1 = (change1 || !init) ? finalizeValue(val1) : finalValue[0];
						finalVal2 = (change2 || !init) ? finalizeValue(val2) : finalValue[1];

						// Detect change
						changed1 = (finalValue[0] != finalVal1);
						changed2 = (finalValue[1] != finalVal2);
						changed = (changed1 || changed2 || !init);
						if (changed)
						{
							finalValue = [finalVal1, finalVal2];
						}

						// Cursors
						updateCursor(cursor1, value[0], finalValue[0]);
						updateCursor(cursor2, value[1], finalValue[1]);

						// Bar
						updateBar(value[0], value[1]);

						// Inputs
						if (inputs)
						{
							// Do the inputs have focus?
							focus1 = inputs[0].is(':focus');
							focus2 = inputs[1].is(':focus');

							// Update value
							if (!focus1)
							{
								inputs[0].val(finalValue[0]);
							}
							if (!focus2)
							{
								inputs[1].val(finalValue[1]);
							}

							// Prevent recursion
							if (init && useEvents)
							{
								useEvents = false;
								if (changed1 && !focus1)
								{
									inputs[0].trigger('change');
								}
								if (changed2 && !focus2)
								{
									inputs[1].trigger('change');
								}
								useEvents = true;
							}
						}

						// Callback
						if (init && settings.onChange)
						{
							settings.onChange.call(target[0], finalValue[0], finalValue[1]);
						}
					}
				}
				else
				{
					// Detect change
					change = (value != val1 || !init);
					if (change)
					{
						// Store
						value = val1;

						// Final values
						finalVal1 = finalizeValue(val1);

						// Detect change
						changed = (finalValue != finalVal1 || !init);
						if (changed)
						{
							finalValue = finalVal1;
						}

						// Cursor
						updateCursor(cursor1, value, finalValue);

						// Bar
						updateBar(barModeMin ? settings.min : value, barModeMin ? value : settings.max);

						// Input
						if (inputs)
						{
							// Does the input has focus?
							if (!inputs.is(':focus'))
							{
								// Update value
								inputs.val(finalVal1);

								// Prevent recursion
								if (init && useEvents)
								{
									useEvents = false;
									inputs.trigger('change');
									useEvents = true;
								}
							}
						}

						// Callback
						if (init && settings.onChange)
						{
							settings.onChange.call(target[0], finalVal1);
						}
					}
				}

				return changed;
			};

			// Watch size for fluid elements
			if (!target[0].style.width || !target[0].style.width.match(/[0-9\.]+(px|em)/i))
			{
				target.sizechange(function()
				{
					// Disable animation
					init = false;

					// Refresh size cache
					size = horizontal ? target.innerWidth() : target.innerHeight();

					// Mode
					if (range)
					{
						// Cursors
						updateCursor(cursor1, value[0], finalValue[0]);
						updateCursor(cursor2, value[1], finalValue[1]);

						// Bar
						updateBar(value[0], value[1]);
					}
					else
					{
						// Cursor
						updateCursor(cursor1, value, finalValue);

						// Bar
						updateBar(barModeMin ? settings.min : value, barModeMin ? value : settings.max);
					}

					// Re-enable animation
					init = true;
				});
			};

			// Tell if the cursor move should be animated
			function isAnimated()
			{
				return (settings.animate && init && (!dragging || (settings.step && settings.stickToStep)));
			};

			// Update one cursor position
			function updateCursor(cursor, value, finalValue)
			{
				var animated = isAnimated(),
					tooltip = cursor.children('.inner-tooltip'),
					animated = isAnimated(),
					tooltipValue,
					prop = {};

				// Move
				cursor.stop(true);
				if (horizontal)
				{
					cursor.stop(true)[animated ? 'animate' : 'css']({
						left: Math.round((value-settings.min)/(settings.max-settings.min)*(size-cursor.outerWidth(true)))+'px'
					}, animated ? settings.animateSpeed : null);
				}
				else
				{
					cursor.stop(true)[animated ? 'animate' : 'css']({
						bottom: Math.round((value-settings.min)/(settings.max-settings.min)*(size-cursor.outerHeight(true)))+'px'
					}, animated ? settings.animateSpeed : null);
				}

				// Display value
				if (typeof settings.tooltipFormat === 'string' && settings.tooltipFormat.length > 0)
				{
					tooltipValue = settings.tooltipFormat.replace('[value]', finalValue);
				}
				else if (typeof settings.tooltipFormat === 'function')
				{
					tooltipValue = settings.tooltipFormat(finalValue);
				}
				else
				{
					tooltipValue = finalValue;
				}

				// Tootip
				if (useTooltips)
				{
					tooltip.children('.tooltip-value').text(tooltipValue);
					if (!tooltip.hasClass('left') && !tooltip.hasClass('right'))
					{
						tooltip.css('margin-left', -Math.round(tooltip.outerWidth()/2)+'px');
					}
				}
				else
				{
					// Basic info
					cursor.prop('title', finalValue);
				}
			};

			// Update bar position
			function updateBar(start, end)
			{
				var animated = isAnimated();

				// Set position
				if (horizontal)
				{
					bar.stop(true)[animated ? 'animate' : 'css']({
						left: Math.round((start-settings.min)/(settings.max-settings.min)*size)+'px',
						right: Math.round((settings.max-end)/(settings.max-settings.min)*size)+'px'
					}, animated ? settings.animateSpeed : null);
				}
				else
				{
					bar.stop(true)[animated ? 'animate' : 'css']({
						bottom: Math.round((start-settings.min)/(settings.max-settings.min)*size)+'px',
						top: Math.round((settings.max-end)/(settings.max-settings.min)*size)+'px'
					}, animated ? settings.animateSpeed : null);
				}
			}

			// First setup
			setValue(null, null);

			// Clickable track
			if (settings.clickableTrack)
			{
				target.click(function(event)
				{
					// Only handle clicks on the track
					if (event.target !== this && event.target !== bar[0])
					{
						return;
					}

					var offset = target.offset(),
						position = horizontal ? event.pageX-offset.left : offset.top+size-event.pageY,
						posValue = settings.min+((position/size)*(settings.max-settings.min)),
						closeToFirst;

					// Mode
					if (range)
					{
						// Find closest cursor
						closeToFirst = (posValue < (value[0]+value[1])/2);
						setValue(closeToFirst ? posValue : null, closeToFirst ? null : posValue);
					}
					else
					{
						setValue(posValue);
					}
				});
			}

			// Cursors handling
			cursors.on('touchstart mousedown', function(event)
			{
				// Get initial values
				var element = $(this).addClass('dragging'),
					touchEvent = (event.type === 'touchstart'),
					offsetHolder = touchEvent ? event.originalEvent.touches[0] : event,
					mouseX = offsetHolder.pageX,
					mouseY = offsetHolder.pageY,
					initialPosition = horizontal ? element.parseCSSValue('left') : element.parseCSSValue('bottom'),
					tooltip = element.children('.inner-tooltip'),
					effectOnTooltip = false,
					ieSelectStart;

				// Stop text selection
				event.preventDefault();
				ieSelectStart = document.onselectstart;
				document.onselectstart = function()
				{
					return false;
				}

				// Start dragging
				dragging = true;

				// Show tooltip for touch devices
				if (useTooltips && settings.tooltipOnHover && Modernizr.touch)
				{
					tooltip.fadeIn();
				}

				// Zoom on tooltip
				if (useTooltips && settings.tooltipBiggerOnDrag && tooltip.hasClass('compact'))
				{
					tooltip.removeClass('compact');
					if (!tooltip.hasClass('left') && !tooltip.hasClass('right'))
					{
						tooltip.css('margin-left', -Math.round(tooltip.outerWidth()/2)+'px');
					}
					effectOnTooltip = true;
				}

				// Watch mouse/finger move
				function watchMouse(event)
				{
					var availableSpace = size-(horizontal ? element.outerWidth(true) : element.outerHeight(true)),
						offsetHolder = touchEvent ? event.originalEvent.touches[0] : event,
						position = Math.max(0, Math.min(availableSpace, initialPosition+(horizontal ? offsetHolder.pageX-mouseX : mouseY-offsetHolder.pageY))),
						value = settings.min+((position/availableSpace)*(settings.max-settings.min));

					// Cursor value
					setValue(
						(!range || element.data('slider-range-index') == 0) ? value : null,
						(range && element.data('slider-range-index') == 1) ? value : null
					);
				};
				doc.on(touchEvent ? 'touchmove' : 'mousemove', watchMouse);

				// Watch for mouseup/touchend
				function endDrag()
				{
					doc.off(touchEvent ? 'touchmove' : 'mousemove', watchMouse);
					doc.off(touchEvent ? 'touchend' : 'mouseup', endDrag);

					// End dragging
					dragging = false;
					element.removeClass('dragging');

					// Finalize position if needed
					if (settings.step && !settings.stickToStep)
					{
						if (range)
						{
							setValue(value[0], value[1]);
						}
						else
						{
							setValue(value);
						}
					}

					// Restore tooltip size
					if (effectOnTooltip)
					{
						tooltip.addClass('compact');
						if (!tooltip.hasClass('left') && !tooltip.hasClass('right'))
						{
							tooltip.css('margin-left', -Math.round(tooltip.outerWidth()/2)+'px');
						}
					}

					// Tooltips hide if out of cursor
					if (useTooltips && settings.tooltipOnHover && !hovering)
					{
						tooltip.fadeOut();
					}

					// Re-enable text selection
					if (ieSelectStart)
					{
						document.onselectstart = ieSelectStart;
					}
				};
				doc.on(touchEvent ? 'touchend' : 'mouseup', endDrag);
			});

			// Set as inited
			init = true;

			// Create interface
			target.data('slider', {

				setValue: setValue

			});
		});

		return this;
	};

	/**
	 * Default slider options
	 */
	$.fn.slider.defaults = {
		/**
		 * Minimum value
		 * @var number
		 */
		min: 0,

		/**
		 * Maximum value
		 * @var number
		 */
		max: 100,

		/**
		 * True to round value, a number to set the float length, or false to not round at all
		 * @var boolean|int
		 */
		round: true,

		/**
		 * Will stick cursor to the closest rounded value when dragging
		 * @var boolean
		 */
		stickToRound: false,

		/**
		 * Size of each interval between min and max, or a list of points to snap the cursor to
		 * @var number|array
		 */
		step: null,

		/**
		 * Will stick cursor to the closest step value when dragging
		 * @var boolean
		 */
		stickToStep: true,

		/**
		 * Start value (ignored in range mode)
		 * @var number
		 */
		value: 50,

		/**
		 * Start values (if set and has 2 elements, enable range)
		 * @var array(number, number)
		 */
		values: null,

		/**
		 * Input or jQuery selector for input in which to retrieve/save the slider value (ignored in range mode)
		 * @var jQuery|string
		 */
		input: null,

		/**
		 * Inputs or jQuery selector for inputs in which to retrieve/save the slider values (if set and has 2 elements, enable range)
		 * @var array(jQuery|string, jQuery|string)|string
		 */
		inputs: null,

		/**
		 * Use true to hide related input(s)
		 * @var boolean
		 */
		hideInput: true,

		/**
		 * Orientation of the slider ('horizontal' or 'vertical')
		 * @var string
		 */
		orientation: 'horizontal',

		/**
		 * Class or list of class for the bar
		 * @var string|array
		 */
		barClasses: null,

		/**
		 * Mode of progress bar (only for single value) : 'min' to range from left to cursor, 'max' to range from right to cursor
		 * @var string
		 */
		barMode: 'min',

		/**
		 * Set to true to show inner marks over the bar
		 * @var boolean
		 */
		innerMarksOverBar: false,

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
		 * Set to true to use knobs as handles
		 * @var boolean
		 */
		knob: false,

		/**
		 * Animate cursors moves
		 * @var boolean
		 */
		animate: true,

		/**
		 * Speed for animations (any jquery valid value)
		 * @var string|int
		 */
		animateSpeed: 'fast',

		/**
		 * Position to show the tooltip with current value, 'auto' for 'top' if the lider is horizontal and 'right' if vertical,
		 * or false to disable. For range sliders, use an array of two values - one for each cursor
		 * @var string|array|boolean
		 */
		tooltip: 'auto',

		/**
		 * Format of tooltip text: null for bare value, pattern string (with [value] as a placeholder)
		 * or a function(value)
		 * @var string|function
		 */
		tooltipFormat: null,

		/**
		 * Always show tooltip or show only on hover
		 * @var boolean
		 */
		tooltipOnHover: true,

		/**
		 * Make tooltip bigger on drag (only if it has class 'compact')
		 * @var boolean
		 */
		tooltipBiggerOnDrag: true,

		/**
		 * Class or list of class for the tooltip
		 * @var string|array
		 */
		tooltipClass: ['compact', 'black-gradient', 'glossy'],

		/**
		 * Enable quick value selection by clicking on the background
		 * @var boolean
		 */
		clickableTrack: true,

		/**
		 * Callback when the slider value is changed, takes one param for value, two for ranges
		 * @var function
		 */
		onChange: null
	};

	/**
	 * Set slider current value(s)
	 * @param number val1 the value for single value sliders, or the first handle value for ranges
	 * @param number val2 the value for the second handle (only for ranges)
	 */
	$.fn.setSliderValue = function(val1, val2)
	{
		return this.each(function()
		{
			var target = $(this),
				data = target.data('slider');

			// If valid
			if (data)
			{
				data.setValue(val1, val2);
			}
		});
	};

	/**
	 * Create a progress bar in the target element
	 * Options may be set using the inline html5 data-progress-options attribute:
	 * <div data-progress-options="{'max':200}"></div>
	 * @param int val the progress value (can be ommitted and defined in options or available as text inside the target)
	 * @param object options
	 */
	$.fn.progress = function(val, options)
	{
		// Arguments
		if (typeof val === 'object' && val !== null)
		{
			options = val;
			val = null;
		}

		this.each(function()
		{
				// Target
			var target = $(this),
				value = val,

				// Progress size
				size,

				// Inline options
				userOptions = $.extend({}, options, target.data('progress-options')),

				// Final settings
				settings = $.extend({}, $.fn.progress.defaults, userOptions),

				// Is the progress horizontal ?
				horizontal = (settings.orientation.toLowerCase() !== 'vertical'),

				// List of classes
				barClasses = ['progress-bar'],

				// Bar mode
				barModeMin = (settings.barMode.toLowerCase() !== 'max'),

				// Bar object
				bar,

				// Value text wrappers
				texts = $(),

				// Stripes
				stripes = false,

				// Work vars
				showText, init = false,

				// Function to normalize value according to min/max and step
				normalizeValue = function(value)
				{
					// Format
					if (typeof value !== 'number')
					{
						value = parseFloat(value, 10) || settings.min;
					}

					// Range
					value = Math.max(settings.min, Math.min(settings.max, value));

					// Round
					value = roundValue(value);

					// Steps
					value = applyStep(value);

					return value;
				},

				// Apply step interval
				applyStep = function(value)
				{
					var i, last = false;

					if (settings.step)
					{
						if (typeof settings.step === 'object')
						{
							for (i = 0; i < settings.step.length; ++i)
							{
								// If lower than next step
								if (value < settings.step[i])
								{
									// If no previous value, use this step
									if (last === false)
									{
										return settings.step[i];
									}
									else
									{
										// Return closest value
										return (value-last < settings.step[i]-value) ? last : settings.step[i];
									}
								}

								// Store for next round
								last = settings.step[i];
							}

							// Higher than all steps, use last one
							return last;
						}
						else
						{
							return Math.round((value-settings.min)/settings.step)*settings.step+settings.min;
						}
					}

					return value;
				},

				// Round value
				roundValue = function(value)
				{
					// Round
					if (settings.round === true || settings.round === 0)
					{
						value = Math.round(value);
					}
					else if (settings.round > 0)
					{
						value = Math.round(value*Math.pow(10, settings.round))/Math.pow(10, settings.round);
					}

					return value;
				},

				// Function to set progress value
				setValue = function(val, showValue)
				{
					var rawValue = val,
						changed = false,
						temp, change;

					// Parse
					if (typeof val !== 'number')
					{
						if (val && val.length > 0)
						{
							val = parseFloat(val);
							if (isNaN(val))
							{
								val = settings.value;
							}
						}
						else
						{
							val = settings.value;
						}
					}

					// Normalize value
					val = normalizeValue(val);

					// Detect change
					change = (value != val || !init);
					if (change)
					{
						// Store
						value = val;

						// Values
						if (showValue === true || ((showValue === null || showValue == undefined) && settings.showValue))
						{
							texts.text(rawValue);
						}
						else
						{
							texts.empty();
						}

						// Update bar
						updateBar(barModeMin ? settings.min : value, barModeMin ? value : settings.max);
					}

					return change;
				},

				// Tell if the cursor move should be animated
				isAnimated = function()
				{
					return (settings.animate && init);
				},

				// Update bar
				updateBar = function(start, end)
				{
					var animated = isAnimated();

					// Set position
					if (horizontal)
					{
						bar.stop(true)[animated ? 'animate' : 'css']({
							left: Math.round((start-settings.min)/(settings.max-settings.min)*size)+'px',
							right: Math.round((settings.max-end)/(settings.max-settings.min)*size)+'px'
						}, animated ? settings.animateSpeed : null);
					}
					else
					{
						bar.stop(true)[animated ? 'animate' : 'css']({
							bottom: Math.round((start-settings.min)/(settings.max-settings.min)*size)+'px',
							top: Math.round((settings.max-end)/(settings.max-settings.min)*size)+'px'
						}, animated ? settings.animateSpeed : null);
					}
				},

				// Show stripes
				showStripes = function(extraOptions)
				{
					var stripesSettings = extraOptions ? settings : $.extend({}, settings, extraOptions),
						stripesSize, animatedStripes, darkStripes;

					// If not already set
					if (!stripes)
					{
						// Dark or not
						darkStripes = stripesSettings.darkStripes ? 'dark-' : '';

						// Size
						stripesSize = (stripesSettings.stripesSize === 'big' || stripesSettings.stripesSize === 'thin') ? stripesSettings.stripesSize+'-' : '';

						// Animated
						animatedStripes = stripesSettings.animatedStripes ? ' animated' : '';

						// Final
						stripes = $('<span class="'+darkStripes+stripesSize+'stripes'+animatedStripes+'"></span>').appendTo(bar);

						// Animation
						if (init)
						{
							stripes.hide().fadeIn();
						}
					}
				},

				// Hide stripes
				hideStripes = function()
				{
					// If set
					if (stripes)
					{
						// Animation
						stripes.fadeOut(function()
						{
							$(this).remove();
						});
						stripes = false;
					}
				},

				// Change bar classes
				changeBarColor = function(color, glossy)
				{
					// Remove previous colors
					bar.removeClass('silver-gradient black-gradient anthracite-gradient grey-gradient white-gradient red-gradient orange-gradient green-gradient blue-gradient');

					// Set new one
					bar.addClass(color);

					// Glossy
					if (glossy === true || glossy === false)
					{
						bar[glossy ? 'addClass' : 'removeClass']('glossy');
					}
				};

			// Value not given
			if (value === null || value == undefined)
			{
				// Check if set as inner text
				value = $.trim(target.text());
			}
			target.empty();

			// Track size
			if (settings.style && (settings.style === 'thin' || settings.style === 'large'))
			{
				target.addClass(settings.style);
			}

			// Should we display the value as text?
			showText = (horizontal && !target.hasClass('thin')) || (!horizontal && target.hasClass('large'));

			// Background text
			if (showText)
			{
				texts = texts.add($('<span class="progress-text"></span>').appendTo(target));
			}

			// Progress base
			target.addClass('progress').buildTrack(settings);
			size = horizontal ? target.innerWidth() : target.innerHeight();

			// Bar
			if (typeof settings.barClasses === 'string')
			{
				barClasses.push(settings.barClasses);
			}
			else if (typeof settings.barClasses === 'object')
			{
				barClasses = barClasses.concat(settings.barClasses);
			}
			bar = $('<span class="'+barClasses.join(' ')+'"></span>');
			bar[settings.innerMarksOverBar ? 'prependTo' : 'appendTo'](target);

			// Main text
			if (showText)
			{
				texts = texts.add($('<span class="progress-text"></span>').appendTo(bar));
			}

			// Stripes
			if (settings.stripes)
			{
				showStripes();
			}

			// Watch size for fluid elements
			if (!target[0].style.width || !target[0].style.width.match(/[0-9\.]+(px|em)/i))
			{
				target.on(horizontal ? 'widthchange' : 'heightchange', function()
				{
					// Disable animation
					init = false;

					// Refresh size cache
					size = horizontal ? target.innerWidth() : target.innerHeight();

					// Bar
					updateBar(barModeMin ? settings.min : value, barModeMin ? value : settings.max);

					// Re-enable animation
					init = true;
				});
			};

			// First setup
			setValue(value);

			// Set as inited
			init = true;

			// Create interface
			target.data('progress', {

				setValue: setValue,
				showStripes: showStripes,
				hideStripes: hideStripes,
				changeBarColor: changeBarColor

			});
		});

		return this;
	};

	/**
	 * Default progress options
	 */
	$.fn.progress.defaults = {

		/**
		 * Minimum value
		 * @var number
		 */
		min: 0,

		/**
		 * Maximum value
		 * @var number
		 */
		max: 100,

		/**
		 * True to round value, a number to set the float length, or false to not round at all
		 * @var boolean|int
		 */
		round: true,

		/**
		 * Size of each interval between min and max, or a list of points to snap the progress bar to
		 * @var number|array
		 */
		step: null,

		/**
		 * Progress value (only used if not passed as parameter or given as text in the target element)
		 * @var number
		 */
		value: 0,

		/**
		 * Orientation of the progress ('horizontal' or 'vertical')
		 * @var string
		 */
		orientation: 'horizontal',

		/**
		 * Track size ('thin', 'large' or empty for normal)
		 * @var string
		 */
		style: null,

		/**
		 * True to show value, false to hide
		 * @var boolean
		 */
		showValue: true,

		/**
		 * Class or list of class for the bar
		 * @var string|array
		 */
		barClasses: null,

		/**
		 * Mode of progress bar : 'min' to range from left to value, 'max' to range from value to right
		 * @var string
		 */
		barMode: 'min',

		/**
		 * Set to true to show inner marks over the bar
		 * @var boolean
		 */
		innerMarksOverBar: false,

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
		 * Animate bar moves
		 * @var boolean
		 */
		animate: true,

		/**
		 * Speed for animations (any jquery valid value)
		 * @var string|int
		 */
		animateSpeed: 'fast'
	};

	/**
	 * Set progress current value
	 * @param number value the value, may contain an unit
	 * @param boolean|null showValue true to show text, false to hide, or leave empty to use original settings value (optional)
	 */
	$.fn.setProgressValue = function(value, showValue)
	{
		return this.each(function()
		{
			var target = $(this),
				data = target.data('progress');

			// If valid
			if (data)
			{
				data.setValue(value, showValue);
			}
		});
	};

	/**
	 * Show stripes on progress bar
	 * @param object options any option to override the inital settings (see progress() defaults for more details) (optional)
	 */
	$.fn.showProgressStripes = function(options)
	{
		return this.each(function()
		{
			var target = $(this),
				data = target.data('progress');

			// If valid
			if (data)
			{
				data.showStripes(options);
			}
		});
	};

	/**
	 * Hide stripes on progress bar
	 */
	$.fn.hideProgressStripes = function()
	{
		return this.each(function()
		{
			var target = $(this),
				data = target.data('progress');

			// If valid
			if (data)
			{
				data.hideStripes();
			}
		});
	};

	/**
	 * Change progress bar color (only works with gradients)
	 * @param string color the new gradient color class
	 * @param boolean glossy true of false, or leave empty to keep current style (optional)
	 */
	$.fn.changeProgressBarColor = function(color, glossy)
	{
		return this.each(function()
		{
			var target = $(this),
				data = target.data('progress');

			// If valid
			if (data)
			{
				data.changeBarColor(color, glossy);
			}
		});
	};

})(jQuery, document);