/**
 * Wizard plugin
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
		doc = $(document);

	// Event binding
	doc.on('click', '.wizard-steps > li', function(event)
	{
		var li = $(this),
			fieldset = li.data('wizard-fieldset');

		// If valid, show
		if (fieldset)
		{
			fieldset.showWizardStep();
		}
	});

	/**
	 * Convert a form into a wizard
	 * @param object options - optional (see defaults for a complete list)
	 */
	$.fn.wizard = function(options)
	{
		// Settings
		var globalSettings = $.extend({}, $.fn.wizard.defaults, options);

		this.filter('form').removeClass('wizard-enabled').each(function(i)
		{
				// Form object
			var form = $(this).addClass('wizard'),

				// Options
				settings = $.extend({}, globalSettings, form.data('wizard-options')),

				// Steps block
				steps = form.find('.wizard-steps'),

				// Steps containers
				fieldsets = form.find('fieldset'),

				// Is the form equalized?
				equalized = form.hasClass('same-height'),

				// Work vars
				current, isWatching, maxHeight = 0;

			// If not valid, exit
			if (fieldsets.length === 0)
			{
				return;
			}

			// Store complete settings
			form.data('wizard-options', settings);

			// Stop DOM watching
			isWatching = $.template.disableDOMWatch();

			// Current fieldset
			current = fieldsets.filter('.current');
			if (current.length === 0)
			{
				fieldsets.eq(0).addClass('current');
			}
			else if (current.length > 1)
			{
				current.not(':last').removeClass('current');
			}

			// Active fieldset
			current = fieldsets.filter('.active');
			if (current.length === 0)
			{
				fieldsets.eq(0).addClass('active');
			}
			else if (current.length > 1)
			{
				current.not(':last').removeClass('active');
			}

			// If first init
			if (steps.length === 0)
			{
				steps = $('<ul class="wizard-steps"></ul>').insertBefore(fieldsets[0]);
			}
			else
			{
				steps.empty();
			}

			// Build steps
			fieldsets.each(function(i)
			{
				var fieldset = $(this),
					classes = [],
					title = fieldset.find('legend').text(),
					controlsWrapper = fieldset.find('.wizard-controls'),
					previousPrev, previousNext,
					step, height;

				// Classes
				if (fieldset.hasClass('completed'))
				{
					classes.push('completed');
				}
				else if (fieldset.hasClass('current'))
				{
					classes.push('current');
				}
				if (fieldset.hasClass('active'))
				{
					classes.push('active');
				}

				// Default title
				if (!title || $.trim(title).length === 0)
				{
					title = settings.defaultTitle.replace('%', i+1);
				}

				// Create step
				step = $('<li'+(classes.length ? ' class="'+classes.join(' ')+'"' : '')+'><span class="wizard-step">'+(i+1)+'</span> '+title+'</li>').appendTo(steps);
				step.data('wizard-fieldset', fieldset);
				fieldset.data('wizard-step', step);

				// Controls
				if (controlsWrapper.length === 0)
				{
					controlsWrapper = $(settings.controlsWrapper).appendTo(fieldset);
				}
				else
				{
					controlsWrapper.find('.wizard-spacer').remove();
				}

				// Previous controls
				previousPrev = controlsWrapper.find('.wizard-prev');
				previousNext = controlsWrapper.find('.wizard-next');

				// Create controls where required
				if (i > 0)
				{
					if (previousPrev.length === 0)
					{
						$(settings.controlPrev).prependTo(controlsWrapper).applySetup().click(function(event)
						{
							event.preventDefault();
							form.showWizardPrevStep();
						});
					}
				}
				else
				{
					previousPrev.remove();
				}
				if (i < fieldsets.length-1)
				{
					if (previousNext.length === 0)
					{
						$(settings.controlNext).appendTo(controlsWrapper).applySetup().click(function(event)
						{
							event.preventDefault();
							form.showWizardNextStep();
						});
					}
				}
				else
				{
					previousNext.remove();
				}

				// Height
				if (equalized)
				{
					maxHeight = Math.max(maxHeight, fieldset.height());
				}
			});

			// Equalize heights
			if (equalized)
			{
				fieldsets.each(function(i)
				{
					var fieldset = $(this),
						controlsWrapper = fieldset.find('.wizard-controls');

					// Equalize using a div to align buttons at bottom
					controlsWrapper.prepend('<div class="wizard-spacer" style="height:'+(maxHeight-fieldset.height())+'px"></div>');
				});
			}

			// Re-enable DOM watching if required
			if (isWatching)
			{
				$.template.enableDOMWatch();
			}

			// First enter event
			fieldsets.filter('.active').trigger('wizardenter');

		}).addClass('wizard-enabled');

		return this;
	};

	/**
	 * Lock the wizard, for instance during AJAX validation
	 */
	$.fn.lockWizard = function()
	{
		return this.each(function(i)
		{
			var form = $(this).closest('.wizard-enabled'),
				inputs;

			// If already locked, do not process
			if (!form.length || form.data('wizard-locked'))
			{
				return;
			}

			// Store list of elements, after they are disabled
			inputs = form.find('input, select, textarea').not(':disabled');
			form.data('wizard-locked', {
				inputs:		$.fn.disableInput ? inputs.disableInput() : inputs.prop('disabled', true).addClass('disabled'),
				buttons:	form.find('.wizard-prev, .wizard-next').not('.disabled').addClass('disabled')
			});
		});
	};

	/**
	 * Test if a wizard is locked
	 * @return boolean true if locked, else false
	 */
	$.fn.isWizardLocked = function()
	{
		return !!this.eq(0).closest('.wizard-enabled').data('wizard-locked');
	};

	/**
	 * Unlock a wizard
	 */
	$.fn.unlockWizard = function()
	{
		return this.each(function(i)
		{
			var form = $(this).closest('.wizard-enabled'),
				data = form.data('wizard-locked');

			// If not locked, do not process
			if (!form.length || !data)
			{
				return;
			}

			// Restore disabled elements
			if ($.fn.enableInput)
			{
				data.inputs.enableInput();
			}
			else
			{
				data.inputs.prop('disabled', false).removeClass('disabled');
			}
			data.buttons.removeClass('disabled');

			// Clear data
			form.removeData('wizard-locked');
		});
	};

	/**
	 * Show a specific step of a wizard (to be called on a fieldset or on any element inside it)
	 * @param boolean force true to force display even if step is not unlocked yet (optional, default: false)
	 */
	$.fn.showWizardStep = function(force)
	{
		return this.each(function(i)
		{
			var fieldset = $(this).closest('fieldset'),
				step = fieldset.data('wizard-step'),
				form = fieldset.closest('.wizard-enabled'),
				settings = form.data('wizard-options'),
				previous, previousIsCurrent, nextStep, newStep, validation, jqv, previousCallback;

			// If not valid
			if (fieldset.length === 0 || !step)
			{
				return;
			}

			// If locked
			if (form.isWizardLocked())
			{
				return;
			}

			// If already active
			if (fieldset.hasClass('active'))
			{
				return;
			}

			// Previously active section
			previous = fieldset.siblings('.active');
			previousIsCurrent = previous.hasClass('current');
			nextStep = previous.nextAll('fieldset').filter(fieldset).length > 0;
			newStep = (previousIsCurrent && nextStep);

			// If not reachable
			if (!previousIsCurrent && !fieldset.hasClass('completed') && !fieldset.hasClass('current') && !force)
			{
				return;
			}

			// Validation
			if (!force && settings.useValidation && $.validationEngine && (!previousIsCurrent || newStep))
			{
				// Run validation
				validation = form.removeClass('validating').validationEngine('validate');

				// Failed validation
				if (validation === false)
				{
					return;
				}
				// AJAX validation (validation === null)
				else if (!validation)
				{
					// Lock wizard
					form.lockWizard();

					/**
					 * Note: there is no way to set an additional option on an already initialized form,
					 * wo we access the options object directly using form.data('jqv').
					 * Not a good habit, but hey, it's the only way...
					 */
					jqv = form.data('jqv');
					previousCallback = jqv.onAjaxFormComplete;

					// Set validation callback
					jqv.onAjaxFormComplete = function(status, f, errors, options)
					{
						// Unlock wizard
						form.unlockWizard();

						// Finalize if valid
						if (status)
						{
							_endStepChange(form, fieldset, step, previous, newStep);
						}

						// Original handler
						if (previousCallback)
						{
							previousCallback.call(this, status, f, errors, options);
						}

						// Restore settings
						jqv.onAjaxFormComplete = previousCallback;
					};

					return;
				}
			}

			// Finalize
			_endStepChange(form, fieldset, step, previous, newStep);
		});
	};

	/**
	 * Internal function to handle the end of the step change process (mostly to enable AJAX validation)
	 */
	function _endStepChange(form, fieldset, step, previous, newStep)
	{
		var event;

		// Check if we can leave the current step
		event = $.Event('wizardleave');
		event.wizard = {
			current: previous[0],
			target: step[0],
			forward: (step.prevAll(previous).length > 0)
		};
		previous.trigger(event);
		if (event.isDefaultPrevented())
		{
			return;
		}

		// Update status
		if (newStep)
		{
			fieldset.prevAll('fieldset').not('.completed').markWizardStepAsComplete();
			fieldset.markWizardStepAsCurrent();
		}

		// Set as active
		step.addClass('active');
		fieldset.addClass('active').trigger('wizardenter');

		// Hide validation messages
		if ($.validationEngine)
		{
			form.validationEngine('hideAll');
		}

		// Previously active section
		step.siblings('.active').removeClass('active');
		previous.removeClass('active');

		// Form event
		form.trigger('wizardchange');
	}

	/**
	 * Show the previous step of a wizard (to be called on the form)
	 * @param boolean force true to force display even if step is not unlocked yet (optional, default: false)
	 */
	$.fn.showWizardPrevStep = function(force)
	{
		return this.each(function(i)
		{
			var form = $(this).closest('.wizard-enabled'),
				active = form.find('fieldset.active'),
				prev = active.prevAll('fieldset').first();

			// If valid
			if (prev.length > 0)
			{
				prev.showWizardStep(force);
			}
		});
	};

	/**
	 * Show the next step of a wizard (to be called on the form)
	 * @param boolean force true to force display even if step is not unlocked yet (optional, default: false)
	 */
	$.fn.showWizardNextStep = function(force)
	{
		return this.each(function(i)
		{
			var form = $(this).closest('.wizard-enabled'),
				active = form.find('fieldset.active'),
				next = active.nextAll('fieldset').first();

			// If valid
			if (next.length > 0)
			{
				next.showWizardStep(force);
			}
		});
	};

	/**
	 * Mark a step as completed (to be called on a fieldset or on any element inside it)
	 */
	$.fn.markWizardStepAsComplete = function()
	{
		return this.each(function(i)
		{
			var fieldset = $(this).closest('fieldset'),
				step = fieldset.data('wizard-step');

			// If not valid
			if (fieldset.length === 0 || !step)
			{
				return;
			}

			// Mark as completed
			step.addClass('completed');
			fieldset.addClass('completed');
		});
	};

	/**
	 * Set a step as current (to be called on a fieldset or on any element inside it)
	 */
	$.fn.markWizardStepAsCurrent = function()
	{
		return this.each(function(i)
		{
			var fieldset = $(this).closest('fieldset'),
				step = fieldset.data('wizard-step');

			// If not valid
			if (fieldset.length === 0 || !step)
			{
				return;
			}

			// Mark as completed
			step.addClass('current');
			fieldset.addClass('current');

			// Previously current section
			step.siblings('.current').removeClass('current');
			fieldset.siblings('.current').removeClass('current');
		});
	};

	/**
	 * Default wizard options
	 */
	$.fn.wizard.defaults = {
		/**
		 * Default title for fieldsets without legend (use % as a placeholder for step's number)
		 * @var string
		 */
		defaultTitle: 'Step %',

		/**
		 * Structure for navigation buttons. Will be ignored for steps with a .wizard-controls block.
		 * @var string
		 */
		controlsWrapper: '<div class="field-block button-height wizard-controls clearfix"></div>',

		/**
		 * Previous button markup - must use class 'wizard-prev'
		 * @var string
		 */
		controlPrev: '<button type="button" class="button glossy mid-margin-right wizard-prev float-left"><span class="button-icon anthracite-gradient"><span class="icon-backward"></span></span>Back</button>',

		/**
		 * Next button markup - must use class 'wizard-next'
		 * @var string
		 */
		controlNext: '<button type="button" class="button glossy mid-margin-right wizard-next float-right">Next<span class="button-icon right-side"><span class="icon-forward"></span></span></button>',

		/**
		 * Enable validation if the plugin is loaded
		 * @var boolean
		 */
		useValidation: true
	};

	// Add template setup function
	$.template.addSetupFunction(function(self, children)
	{
		this.findIn(self, children, '.wizard').wizard();
		return this;
	});

})(jQuery, window, document);