/**
 * Form inputs styling plugin
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
  var win = $(window),
    doc = $(document);

  /**
   * Convert switches, checkboxes and radios
   * @param object options an object with any of the $.fn.styleCheckable.defaults options.
   */
  $.fn.styleCheckable = function(options)
  {
    // Settings
    var globalSettings = $.extend({}, $.fn.styleCheckable.defaults, options);

    return this.each(function(i)
    {
      var element = $(this),
        settings = $.extend({}, globalSettings, element.data('checkable-options')),
        checked = this.checked ? ' checked' : '',
        disabled = this.disabled ? ' disabled' : '',
        replacement = element.data('replacement'),
        title = (this.title && this.title.length > 0) ? ' title="'+this.title+'"' : '',
        tabIndex = (this.tabIndex > 0) ? this.tabIndex : 0,
        isWatching;

      // If already set
      if (replacement)
      {
        return;
      }

      // Stop DOM watching
      isWatching = $.template.disableDOMWatch();

      // Create replacement
      if (element.hasClass('switch'))
      {
        replacement = $('<span class="'+this.className.replace(/validate\[.*\]/, '')+checked+disabled+' replacement"'+title+' tabindex="'+tabIndex+'">'+
                  '<span class="switch-on"><span>'+(element.data('text-on') || settings.textOn)+'</span></span>'+
                  '<span class="switch-off"><span>'+(element.data('text-off') || settings.textOff)+'</span></span>'+
                  '<span class="switch-button"></span>'+
                '</span>');
      }
      else
      {
        replacement = $('<span class="'+this.className.replace(/validate\[.*\]/, '')+checked+disabled+' replacement"'+title+' tabindex="'+tabIndex+'">'+
                  '<span class="check-knob"></span>'+
                '</span>');
      }

      // Prevent the element from being focusable by keyboard
      this.tabIndex = -1;

      // Insert
      replacement.insertAfter(element).data('replaced', element);

      // Store reference
      element.data('replacement', replacement);

      // Add clear function
      element.addClearFunction(_removeCheckableReplacement);

      // Move select inside replacement, and remove styling
      element.detach().appendTo(replacement).data('initial-classes', this.className);
      this.className = (this.className.indexOf('validate[') > -1) ? this.className.match(/validate\[.*\]/)[0] : '';

      // Re-enable DOM watching if required
      if (isWatching)
      {
        $.template.enableDOMWatch();
      }
    });
  };

  /*
   * Options for styled switches, checkboxes and radios
   */
  $.fn.styleCheckable.defaults = {
    /**
     * Default text for ON value
     * @var string
     */
    textOn: 'ON',

    /**
     * Default text for OFF value
     * @var string
     */
    textOff: 'OFF'
  };

  /**
   * Convert selects
   * @param object options an object with any of the $.fn.styleSelect.defaults options.
   */
  $.fn.styleSelect = function(options)
  {
    // Settings
    var globalSettings = $.extend({}, $.fn.styleSelect.defaults, options);

    return this.each(function(i)
    {
      var element = $(this),
        settings = $.extend({}, globalSettings, element.data('select-options')),
        replacement = element.data('replacement'),
        disabled = this.disabled ? ' disabled' : '',
        showAsMultiple = ((this.multiple || element.hasClass('multiple')) && !element.hasClass('multiple-as-single')),
        isSized = (element.attr('size') > 1),
        title = (this.title && this.title.length > 0) ? ' title="'+this.title+'"' : '',
        tabIndex = (this.tabIndex > 0) ? this.tabIndex : 0,
        select, dropDown, text, isWatching, values;

      // If already set
      if (replacement)
      {
        return;
      }

      // Stop DOM watching
      isWatching = $.template.disableDOMWatch();

      // Create replacement
      if (showAsMultiple)
      {
        // Create
        select = $('<span class="'+this.className.replace(/validate\[.*\]/, '').replace(/(\s*)select(\s*)/, '$1selectMultiple$2')+disabled+' replacement"'+title+' tabindex="'+tabIndex+'">'+
                '<span class="drop-down"></span>'+
              '</span>')
        .insertAfter(element)
        .data('replaced', element);

        // Register
        element.data('replacement', select);

        // If the number of visible options is set
        if (isSized && !element.getStyleString().match(/height\s*:\s*[0-9]+/i))
        {
          // Set height
          dropDown = select.children('.drop-down');
          dropDown.height(element.hasClass('check-list') ? (this.size*37)-1 : this.size*26);

          // Enable scroll
          if ($.fn.customScroll)
          {
            dropDown.customScroll({
              padding: 4,
              showOnHover: false,
              usePadding: true
            });
          }
        }

        // Load options
        _refreshSelectValues(select, element);

        // Custom event to refresh values list
        element.on('change silent-change update-select-list', function(event)
        {
          _refreshSelectValues(select, element);
        });
      }
      else
      {
        // Create
        select = $('<span class="'+this.className.replace(/validate\[.*\]/, '')+disabled+' replacement"'+title+' tabindex="'+tabIndex+'">'+
                '<span class="select-value"></span>'+
                '<span class="select-arrow">'+($.template.ie7 ? '<span class="select-arrow-before"></span><span class="select-arrow-after"></span>' : '')+'</span>'+
                '<span class="drop-down"></span>'+
              '</span>')
        .insertAfter(element)
        .data('replaced', element);

        // Gather selected values texts
        values = [];
        element.find(':selected').each(function(i)
        {
          values.push($(this).text());
        });

        // Update displayed value
        if (settings.staticValue)
        {
          select.children('.select-value').html(settings.staticValue);
        }
        else if (this.multiple)
        {
          switch (values.length)
          {
            case 1:
              _updateSelectValueText(select.children('.select-value'), values, element.data('single-value-text'), settings.singleValueText);
              break;

            case this.options.length:
              _updateSelectValueText(select.children('.select-value'), values, element.data('all-values-text'), settings.allValuesText);
              break;

            default:
              _updateSelectValueText(select.children('.select-value'), values, element.data('multiple-values-text'), settings.multipleValuesText);
              break;
          }
        }
        else
        {
          select.children('.select-value').html((values.length > 0) ? values.pop() : '&nbsp;');
        }

        // Register
        element.data('replacement', select);

        // Refresh size mode
        _refreshSelectSize(select, this, select.children('.drop-down'));
      }

      // Prevent the element from being focusable by keyboard
      this.tabIndex = -1;

      // Move select inside replacement, and remove styling
      element.detach().prependTo(select).data('initial-classes', this.className);
      this.className = (this.className.indexOf('validate[') > -1) ? this.className.match(/validate\[.*\]/)[0] : '';

      // Add clear function
      element.addClearFunction(_removeSelectReplacement);

      // Store settings
      select.data('select-settings', settings);

      /*
       * To avoid triggering the default select UI, the select is hidden if:
       * - it is displayed as multiple (even if simple) OR
       * - it is multiple (no overlaying UI in most OS) OR
       * - The setting styledList is on AND
       *      - This is not a touch device OR
       *      - This is a touch device AND the setting styledOnTouch is:
       *          - true OR
       *          - null and the select has the class 'check-list'
       *
       * Ew. Now I need to get another brain.
       */
      if (showAsMultiple ||
        this.multiple ||
        (settings.styledList &&
          (!$.template.touchOs ||
          ($.template.touchOs &&
            (settings.styledOnTouch === true ||
            (settings.styledOnTouch === null && select.hasClass('check-list')))))))
      {
        select.addClass('select-styled-list');
      }

      // Re-enable DOM watching if required
      if (isWatching)
      {
        $.template.enableDOMWatch();
      }
    });
  };

  /*
   * Options for styled selects
   */
  $.fn.styleSelect.defaults = {
    /**
     * False to use system's drop-down UI, true to use style's drop-downs
     * @var boolean
     */
    styledList: true,

    /**
     * For touch devices: false to use system's drop-down UI, true to use style's drop-downs, or null to guess (true for check-list style, false for others)
     * Note: only works if styledList is true
     * @var boolean|null
     */
    styledOnTouch: null,

    /**
     * When focused, should the arrow down key open the drop-down or just scroll values?
     * @var boolean
     */
    openOnKeyDown: true,

    /**
     * Text for multiple select with no value selected
     * @var string
     */
    noValueText: '',

    /**
     * Static text, always displayed no matter the value
     * @var string|boolean
     */
    staticValue: false,

    /**
     * Text for multiple select with one value selected, or false to just display the selected value
     * @var string|boolean
     */
    singleValueText: false,

    /**
     * Text for multiple select with multiple values selected, or false to just display the selected list
     * Tip: use %d as a placeholder for the number of values
     * @var string|boolean
     */
    multipleValuesText: '%d selected',

    /**
     * Text for multiple select with all values selected, or false to just display the selected list
     * Tip: use %d as a placeholder for the number of values
     * @var string|boolean
     */
    allValuesText: 'All',

    /**
     * Enable search field when open - use null to automatically use when list has more than searchIfMoreThan elements
     * @var boolean|null
     */
    searchField: null,

    /**
     * Minimum number of elements to trigger a search field, if searchField is null
     * @var int
     */
    searchIfMoreThan: 25,

    /**
     * Helper text for seach field
     * @var string
     */
    searchText: 'Search'
  };

  /**
   * Convert file inputs
   * @param object options an object with any of the $.fn.styleFile.defaults options.
   */
  $.fn.styleFile = function(options)
  {
    // Settings
    var globalSettings = $.extend({}, $.fn.styleFile.defaults, options);

    return this.each(function(i)
    {
      var element = $(this).addClass('file'),
        settings = $.extend({}, globalSettings, element.data('file-options')),
        blackInput = (element.hasClass('black-input') || element.closest('.black-inputs').length > 0) ? ' anthracite-gradient' : '',
        multiple = !!this.multiple,
        disabled = this.disabled ? ' disabled' : '',
        isWatching;

      // If already set
      if (element.parent().hasClass('file'))
      {
        return;
      }

      // Stop DOM watching
      isWatching = $.template.disableDOMWatch();

      // Create styling
      styling = $('<span class="input '+this.className.replace(/validate\[.*\]/, '')+disabled+'">'+
              '<span class="file-text">'+element.val()+'</span>'+
              '<span class="button compact'+blackInput+'">'+(multiple ? settings.textMultiple : settings.textSingle)+'</span>'+
            '</span>');

      // Insert
      styling.insertAfter(element);

      // Add clear function
      element.addClearFunction(_removeInputStyling);

      // Move select inside styling
      element.detach().appendTo(styling);

      // Re-enable DOM watching if required
      if (isWatching)
      {
        $.template.enableDOMWatch();
      }
    });
  };

  /*
   * Options for styled switches, checkboxes and radios
   */
  $.fn.styleFile.defaults = {
    /**
     * Button text - single file
     * @var string
     */
    textSingle: 'Select file',

    /**
     * Button text - multiple files
     * @var string
     */
    textMultiple: 'Select files'
  };

  /**
   * Set the value of a number input
   * @param number value the value to set
   */
  $.fn.setNumber = function(value)
  {
    return this.each(function(i)
    {
      var input;

      // Detect input
      if (this.nodeName.toLowerCase() === 'input')
      {
        input = $(this);
      }
      else
      {
        input = $(this).children('input:first');
        if (input.length === 0)
        {
          return;
        }
      }

      // Set value
      input.val(_formatNumberValue(value, _getNumberOptions(input)));
    });
  };

  /**
   * Increment/decrement the value of a number input
   * @param boolean up true if the value should be incremented, false for decremented
   * @param boolean shift whether to use shiftIncrement or not (optional, default: false)
   */
  $.fn.incrementNumber = function(up, shift)
  {
    return this.each(function(i)
    {
      var input, options, value;

      // Detect input
      if (this.nodeName.toLowerCase() === 'input')
      {
        input = $(this);
      }
      else
      {
        input = $(this).children('input:first');
        if (input.length === 0)
        {
          return;
        }
      }

      // Options
      options = _getNumberOptions(input);

      // Remove format
      value = _unformatNumberValue(input.val(), options);

      // Check if numeric
      if (isNaN(value))
      {
        value = 0;
      }

      // Increment value
      value += up ? (shift ? options.shiftIncrement : options.increment) : (shift ? -options.shiftIncrement : -options.increment);

      // Set value
      input.val(_formatNumberValue(value, options));
    });
  };

  /**
   * Helper function: load and format number input options
   * @param jQuery input the target input
   * @return object the options object
   */
  function _getNumberOptions(input)
  {
    var options = input.data('number-options'),
      temp;

    // If not set yet or not formatted
    if (!options || !options.formatted)
    {
      // Extend
      options = $.extend({}, $.fn.setNumber.defaults, options);

      // Validate
      if (typeof options.min !== 'number')
      {
        options.min = null;
      }
      if (typeof options.max !== 'number')
      {
        options.max = null;
      }
      if (options.min !== null && options.max !== null)
      {
        if (options.min > options.max)
        {
          temp = options.max;
          options.max = options.min;
          options.min = temp;
        }
      }
      if (!options.precision)
      {
        options.precision = 1;
      }

      // Set as ready
      options.formatted = true;
      input.data('number-options', options);
    }

    return options;
  }

  /**
   * Helper function: remove user format of a number value according to options
   * @param value the value
   * @param object options the validated options
   * @return number the valid value
   */
  function _unformatNumberValue(value, options)
  {
    if (typeof value === 'string')
    {
      if (options.thousandsSep.length)
      {
        value = value.replace(options.thousandsSep, '');
      }
      if (options.decimalPoint !== '.')
      {
        value = value.replace(options.decimalPoint, '.');
      }
      value = parseFloat(value);
      if (isNaN(value))
      {
        value = 0;
      }
    }

    return value;
  }

  /**
   * Helper function: format a number value according to options
   * @param value the value
   * @param object options the validated options
   * @return number|string the valid value
   */
  function _formatNumberValue(value, options)
  {
    var parts, decimalPlaces;

    // Remove format
    value = _unformatNumberValue(value, options);

    // Round value
    value = Math.round(value/options.precision)*options.precision;

    // Precision bug on float values
    if (options.precision < 1)
    {
      decimalPlaces = options.precision.toString().length-2;
      value = Math.round(value*Math.pow(10, decimalPlaces))/Math.pow(10, decimalPlaces);
    }

    // Check min/max
    if (options.min !== null)
    {
      value = Math.max(value, options.min);
    }
    if (options.max !== null)
    {
      value = Math.min(value, options.max);
    }

    // If not standard
    if (options.thousandsSep.length || options.decimalPoint !== '.')
    {
      // Format value
      parts = value.toString().split('.');

      // Thousands separator
      if (options.thousandsSep.length && parts[0].length > 3)
      {
        parts[0] = parts[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, options.thousandsSep);
      }

      return parts.join(options.decimalPoint);
    }

    return value;
  }

  /*
   * Options for number inputs
   */
  $.fn.setNumber.defaults = {
    /**
     * Minimum value (null for none)
     * @var number|null
     */
    min: null,

    /**
     * Maximum value (null for none)
     * @var number|null
     */
    max: null,

    /**
     * Increment of up/down arrows and keys
     * @var number
     */
    increment: 1,

    /**
     * Increment of up/down arrows and keys when holding shift key
     * @var number
     */
    shiftIncrement: 10,

    /**
     * Precision of the value: the user input will be rounded using it.
     * For instance, use 1 for rounded nombers, 0.25 to user quarter increments...
     * @var number
     */
    precision: 1,

    /**
     * Character used for decimal point
     * @var string
     */
    decimalPoint: '.',

    /**
     * Character used for thousands separator
     * @var string
     */
    thousandsSep: ''
  };

  /**
   * Helper function to check if an element is an input/select/textarea/button and may be disabled
   * @param jQuery element the element to check
   * @return boolean true if the element may be disabled, else false
   */
  function mayBeDisabled(element)
  {
    var nodeName = element[0].nodeName.toLowerCase();
    return (nodeName === 'input' || nodeName === 'select' || nodeName === 'textarea' || nodeName === 'button');
  }

  /**
   * Enable a form input, and update the styled UI
   */
  $.fn.enableInput = function()
  {
    return this.each(function(i)
    {
      var element = $(this),
        replacement, replaced;

      // Inputs
      if (mayBeDisabled(element))
      {
        // Enable
        element.prop('disabled', false);

        // Style
        replacement = element.data('replacement');
        if (replacement)
        {
          replacement.removeClass('disabled');
        }

        // Number inputs
        if (element.parent().hasClass('number'))
        {
          element.parent().removeClass('disabled');
        }
      }
      // Replacements
      else
      {
        // Look for input
        replaced = element.data('replaced');
        if (replaced && mayBeDisabled(replaced))
        {
          // Enable input
          replaced.prop('disabled', false);

          // Style
          element.removeClass('disabled');
        }
        // Number inputs
        else if (element.hasClass('number'))
        {
          element.removeClass('disabled');
          element.children('input').prop('disabled', false);
        }
      }
    });
  };

  /**
   * Disable a form input, and update the styled UI
   */
  $.fn.disableInput = function()
  {
    return this.each(function(i)
    {
      var element = $(this),
        replacement, replaced;

      // Inputs
      if (mayBeDisabled(element))
      {
        // Disable
        element.prop('disabled', true);

        // Style
        replacement = element.data('replacement');
        if (replacement)
        {
          replacement.addClass('disabled');
        }

        // Number inputs
        if (element.parent().hasClass('number'))
        {
          element.parent().addClass('disabled');
        }
      }
      // Replacements
      else
      {
        // Look for input
        replaced = element.data('replaced');
        if (replaced && mayBeDisabled(replaced))
        {
          // Disable input
          replaced.prop('disabled', true);

          // Style
          element.addClass('disabled');
        }
        // Number inputs
        else if (element.hasClass('number'))
        {
          element.addClass('disabled');
          element.children('input').prop('disabled', true);
        }
      }
    });
  };

  // Add to template setup function
  $.template.addSetupFunction(function(self, children)
  {
    var elements = this;

    // Switches, checkboxes and radios
    elements.findIn(self, children, 'input.switch, input.checkbox, input.radio').each(function(i)
    {
      // Style element
      $(this).styleCheckable();

      // If in the root target, add to selection
      if (self && elements.is(this))
      {
        elements = elements.add(this);
      }
    });

    // Checkables in buttons
    elements.findIn(self, children, 'label.button').children(':radio, :checkbox').each(function(i)
    {
      // Style element
      if (this.checked)
      {
        $(this).parent().addClass('active');
      }
    });

    // File inputs
    elements.findIn(self, children, '.file').filter('input[type="file"]').styleFile();

    // Placeholder polyfill
    if (!Modernizr.input.placeholder)
    {
      elements.findIn(self, children, 'input[placeholder][type!="password"]').each(function(i)
      {
        var input = $(this),
          placeholder = input.attr('placeholder');

        // Mark and add data for validation plugin
        input.addClass('placeholder').attr('data-validation-placeholder', placeholder);

        // Fill if empty
        if ($.trim(input.val()) === '')
        {
          input.val(placeholder);
        }
      });
    }

    // Selects
    elements.findIn(self, children, 'select.select').each(function(i)
    {
      // Style element
      $(this).styleSelect();

      // If in the root target, add to selection
      if (self && elements.is(this))
      {
        elements = elements.add(this);
      }
    });

    return elements;
  });

  /********************************************************/
  /*                   Helper functions                   */
  /********************************************************/

  /**
   * Open a select drop-down list
   *
   * @param jQuery select the replacement select
   * @param boolean onHover whether the select was open on hover or not (optional, default: none)
   * @param event the opening event (optional)
   * @return void
   */
  function _openSelect(select, onHover, event)
  {
    var replaced = select.data('replaced'),
      settings = select.data('select-settings') || {},
      list,
      clone,
      inheritParent,
      scrollParents,
      position, listOffset,
      winHeight, listHeight, optionHeight,
      listExtra, availableHeight,
      searchWrapper, search = false, searchFocus = false,
      date = new Date(), time = date.getTime(),
      isWatching, updateList, onChange, onBlur;

    // Prevent event default
    if (event)
    {
      event.preventDefault();
    }

    // Do not handle if disabled
    if (select.closest('.disabled').length > 0 || (replaced && replaced.is(':disabled')))
    {
      return;
    }

    // Do not handle if the OS UI should be used
    if (replaced && !select.hasClass('select-styled-list'))
    {
      return;
    }

    // If already open
    if (select.hasClass('select-cloned'))
    {
      return;
    }

    // List of scrolling parents
    scrollParents = select.parents('.custom-scroll');

    // Add class if the select is in a top-level element
    if (select.closest('.modal, .notification, .tooltip').length)
    {
      select.addClass('over');
    }
    else
    {
      select.removeClass('over');
    }

    // Stop DOM watching
    isWatching = $.template.disableDOMWatch();

    // Clone
    clone = select.clone().addClass('select-clone').css('width', select.width()+'px');
    clone[0].tabIndex = -1;
    clone.children('select').remove();
    clone.appendTo(document.body).trackElement(select);

    // Some browsers report wrong values for the first call to determine the select position - hope to fix that soon...
    setTimeout(function()
    {
      select.refreshTrackedElements();
    }, 1);

    // Store reference
    select.data('clone', clone);

    // Hide - need to add an internal marker as it makes the select lose focus in some browsers */
    select.data('select-hiding', true).addClass('select-cloned')
    setTimeout(function()
    {
      select.removeData('select-hiding');
    }, 40);

    // Refernce
    list = clone.children('.drop-down');

    // Fill options list
    if (replaced)
    {
      clone.data('replaced', replaced);
      _refreshSelectValues(clone, replaced);

      // Listen for changes
      onChange = function(event)
      {
        // Refresh list
        _refreshSelectValues(clone, replaced);

        // Update displayed value
        _updateSelectText(clone, replaced, select.data('select-settings'));
      };
      replaced.on('change silent-change update-select-list', onChange);
    }

    /*
     * Inherited classes checks
     */

    // Glossy
    if (!select.is('.glossy'))
    {
      inheritParent = select.closest('.glossy');
      if (inheritParent.length > 0)
      {
        clone.addClass('glossy');
      }
    }

    // Size
    if (!select.is('.compact'))
    {
      inheritParent = select.parent('.compact');
      if (inheritParent.length > 0)
      {
        clone.addClass('compact');
      }
    }

    // Re-enable DOM watching if required
    if (isWatching)
    {
      $.template.enableDOMWatch();
    }

    // Prepare and open
    clone.removeClass('reversed').addClass('open');
    if (replaced)
    {
      list.on('touchend click', function(event)
      {
        // Check if scrolling
        if (event.type === 'touchend' && list.data('touch-scrolling'))
        {
          return;
        }
        event.stopPropagation();
      });
    }

    /*
     * Search field
     */

    // If search field should be used
    if (!clone.hasClass('auto-open') && (settings.searchField === true || (settings.searchField === null && list.children('a, span').length >= settings.searchIfMoreThan)))
    {
      // Create elements
      searchWrapper = $('<div class="select-search-wrapper"></div>').appendTo(clone);
      search = $('<input type="text" class="select-search" value="" placeholder="'+settings.searchText+'" autocomplete="off">').appendTo(searchWrapper);

      // Behavior
      search.on('keydown', function(event)
      {
        event.stopPropagation();

      }).on('focus', function(event)
      {
        searchFocus = true;

      }).on('blur', function(event)
      {
        searchFocus = false;

      }).keyup(function(event)
      {
        var text = $.trim(search.val()),
          keys = $.template.keys,
          searchRegex,
          matches, matchSelected,
          focus, next,
          replacedOption;

        event.stopPropagation();

        // Key handling
        switch (event.keyCode)
        {
          case keys.up:
            // Focused element
            matches = list.children('a, span').not('.disabled').not(':hidden');
            focus = matches.filter('.selected:first');
            if (focus.length === 0)
            {
              next = matches.last();
            }
            else
            {
              next = focus.prevAll('a, span').not('.disabled').not(':hidden').first();
            }

            // Focus previous option
            if (next.length > 0)
            {
              focus.removeClass('selected');
              next.addClass('selected');
              if ($.fn.scrollToReveal)
              {
                next.scrollToReveal();
              }

              // Update replaced and trigger change
              if (replaced)
              {
                replacedOption = next.data('select-value');
                if (replacedOption)
                {
                  // If multiple selection, clear all before
                  if (replaced[0].multiple)
                  {
                    replaced.find('option:selected').prop('selected', false);
                  }

                  replacedOption.selected = true;
                  replaced.trigger('change');
                }
              }
            }
            break;

          case keys.down:
            // Focused element
            matches = list.children('a, span').not('.disabled').not(':hidden');
            focus = matches.filter('.selected:last');
            if (focus.length === 0)
            {
              next = matches.first();
            }
            else
            {
              next = focus.nextAll('a, span').not('.disabled').not(':hidden').first();
            }

            // Focus next option
            if (next.length > 0)
            {
              focus.removeClass('selected');
              next.addClass('selected');
              if ($.fn.scrollToReveal)
              {
                next.scrollToReveal();
              }

              // Update replaced and trigger change
              if (replaced)
              {
                replacedOption = next.data('select-value');
                if (replacedOption)
                {
                  // If multiple selection, clear all before
                  if (replaced[0].multiple)
                  {
                    replaced.find('option:selected').prop('selected', false);
                  }

                  replacedOption.selected = true;
                  replaced.trigger('change');
                }
              }
            }
            break;

          case keys.enter:
          case keys.space:
            // Just close the select if open
            select.trigger('close-select');
            break;

          default:
            // If search is empty
            if (text.length === 0)
            {
              list.children('a, span').show();
            }
            else
            {
              // Regular expression
              searchRegex = new RegExp(text.toLowerCase(), 'g');

              // Loop through values to find a match
              list.children('a, span').each(function(i)
              {
                var option = $(this);

                // If matches
                if ($.trim(option.text().toLowerCase()).match(searchRegex))
                {
                  option.show();
                }
                else
                {
                  option.hide();
                }
              });
            }
            break;
        }
      });
    }

    /*
     * Set select list position according to available screen space
     */

    // Add scroll
    if ($.fn.customScroll && !list.hasCustomScroll())
    {
      list.customScroll({
        padding: 4,
        showOnHover: false,
        usePadding: true,
        continuousWheelScroll: false,
        continuousTouchScroll: false
      });
    }

    // Get heights
    listOffset = list.removeClass('reversed').position().top;
    listHeight = list.outerHeight();
    listExtra = listHeight-list.height();

    // Function to refresh position on resize/scroll
    updateList = function()
    {
      var scrollPos;

      // Refresh size
      listHeight = list.css('max-height', '').outerHeight();

      // Select vertical position
      position = clone.offset().top-win.scrollTop();

      // Viewport height
      winHeight = win.height();

      // If too long to fit
      if (position+listOffset+listHeight > winHeight)
      {
        // Check if it fits on top
        if (position-listOffset-listHeight > 0)
        {
          // Display on top
          clone.addClass('reversed');
        }
        /*
         * Now we know that the list can't be displayed full size, so we truncate it.
         * If the select is above 60% of screen height, it will show under, otherwise on top
         */
        else
        {
          if (position > winHeight*0.6)
          {
            // Display on top
            clone.addClass('reversed');
            availableHeight = position;
          }
          else
          {
            // Display under
            clone.removeClass('reversed');
            availableHeight = winHeight-position-listOffset;
          }

          // Remove list padding/borders from available size
          availableHeight -= listExtra;

          // Set max-height to use available space
          list.css({
            maxHeight: (availableHeight-10)+'px'
          });

          // Try to restore scroll position
          scrollPos = select.data('scrollPosition');
          if (scrollPos)
          {
            list[0].scrollTop = scrollPos;
          }
        }
      }
      else
      {
        // Clear changes
        clone.removeClass('reversed');
      }

      // Clear data
      select.removeData('scrollPosition');

      // Update scroll
      if ($.fn.customScroll)
      {
        list.refreshCustomScroll();
      }
    };

    // Function to handle focus loss
    onBlur = function(event)
    {
      var target = $(event.target);

      // Validation for click/touchend event
      if ((event.type === 'click' || event.type === 'touchend') && (target.closest(list).length || (searchWrapper && target.closest(searchWrapper).length)))
      {
        return;
      }
      // Validation for scroll events when search field has focus
      else if (event.type === 'scroll' && searchFocus)
      {
        updateList();
        return;
      }

      // Remove events
      win.off('resize', updateList);
      doc.off('scroll', onBlur);
      scrollParents.off('scroll', onBlur);
      if (onHover && !$.template.touchOs)
      {
        clone.off('mouseleave', onBlur);
      }
      else
      {
        doc.off('touchend click', onBlur);
      }
      select.off('close-select', onBlur);
      clone.off('close-select', onBlur);

      // Stop listening for changes
      if (replaced)
      {
        replaced.off('change silent-change update-select-list', onChange);
      }

      // Remove search field
      if (search)
      {
        list.children('a, span').show();
      }

      // Store scroll position for later re-opening
      select.data('scrollPosition', list[0].scrollTop);

      // Stop DOM watching
      isWatching = $.template.disableDOMWatch();

      // Put element back in place
      clone.detach();    // Detach is used to preserve event listeners
      select.removeData('clone').removeClass('select-cloned');

      // Re-enable DOM watching if required
      if (isWatching)
      {
        $.template.enableDOMWatch();
      }
    };

    // First call and binding
    updateList();
    win.on('resize', updateList);
    doc.on('scroll', onBlur);
    scrollParents.on('scroll', onBlur);
    if (onHover && !$.template.touchOs)
    {
      clone.on('mouseleave', onBlur);
    }
    else
    {
      doc.on('touchend click', onBlur);
    }
    clone.on('close-select', onBlur);
    select.on('close-select', onBlur);
  }

  /**
   * Detect fixed or fluid size
   *
   * @param jQuery select the replacement select
   * @param DOM replaced the replaced select
   * @param jQuery list the replacement drop-down list
   * @return void
   */
  function _refreshSelectSize(select, replaced, list)
  {
    // Detect fixed width
    if (replaced.style.width !== '' && replaced.style.width != 'auto')
    {
      if (!select.hasClass('fixedWidth'))
      {
        select.addClass('fixedWidth');
        if (select.hasClass('selectMultiple'))
        {
          list.css('width', replaced.style.width);
        }
      }
    }
    else
    {
      if (select.hasClass('fixedWidth'))
      {
        select.removeClass('fixedWidth');
        if (select.hasClass('selectMultiple'))
        {
          list.css('width', '');
        }
      }
    }
  }

  /**
   * Refresh select values
   *
   * @param jQuery select the replacement select
   * @param jQuery replaced the replaced select
   * @return void
   */
  function _refreshSelectValues(select, replaced)
  {
    var list = select.children('.drop-down'),
      checkList = select.hasClass('check-list') ? '<span class="check"></span>' : '',
      existing, isWatching;

    // If valid
    if (list.length > 0 && replaced)
    {
      // Disable DOM watching for better performance
      isWatching = $.template.disableDOMWatch();

      // Refresh size mode
      _refreshSelectSize(select, replaced[0], list);

      // Existing options
      existing = list.children('span, strong');

      // Synchronise list
      replaced.find('option, optgroup').each(function(i)
      {
        var classes = [],
          $this = this,
          option = (this.nodeName.toLowerCase() === 'option'),
          node = option ? 'span' : 'strong',
          text = option ? $(this).text() : this.label,
          found = false,
          newItem;

        // Empty text
        if (text.length === 0)
        {
          if (!option)
          {
            return;
          }
          text = '&nbsp;';
        }

        // Check if the element already exists
        if (existing.length)
        {
          existing.each(function()
          {
            var element = $(this);
            if (element.data('select-value') === $this)
            {
              found = element;
              existing = existing.not(this);
              return false;
            }
          });
        }

        // If the item already exists
        if (found)
        {
          // Put at end to use right order
          found.detach().appendTo(list);

          // Reset text in case it changed
          found.html(checkList+text);

          // Check classes
          found[this.selected ? 'addClass' : 'removeClass']('selected');
          found[(this.parentNode.nodeName.toLowerCase() === 'optgroup') ? 'addClass' : 'removeClass']('in-group');
          found[this.disabled ? 'addClass' : 'removeClass']('disabled');

          // Done
          return;
        }

        // Mode
        if (option)
        {
          // State
          if (this.selected)
          {
            classes.push('selected');
          }

          // If in an optgroup
          if (this.parentNode.nodeName.toLowerCase() === 'optgroup')
          {
            classes.push('in-group');
          }

          // If disabled
          if (this.disabled)
          {
            classes.push('disabled');
          }
        }

        // Create
        newItem = $('<'+node+((classes.length > 0) ? ' class="'+classes.join(' ')+'"' : '')+'>'+checkList+text+'</'+node+'>')
              .appendTo(list)
              .data('select-value', this);

        // Set behavior if not disabled
        if (option && !this.disabled)
        {
          newItem.on('touchend click', _clickSelectValue);
        }
      });

      // Remove items not found
      if (existing.length)
      {
        existing.remove();
      }

      // Re-enable DOM watching if required
      if (isWatching)
      {
        $.template.enableDOMWatch();
      }
    }
  }

  /**
   * Select a list value
   *
   * @param object event
   * @return void
   */
  function _clickSelectValue(event)
  {
    var option = $(this),
      list = option.parent(),
      select = list.parent(),
      replaced = select.data('replaced'),
      replacedOption = option.data('select-value'),
      multiple = replaced[0].multiple,
      selected, value;

    // Detect touch scrolling
    if (list.data('touch-scrolling'))
    {
      return;
    }

    // Check if valid touch-click event
    if (!$.template.processTouchClick(this, event))
    {
      event.stopPropagation();
      return;
    }

    // If valid
    if (replaced && replacedOption)
    {
      // If multiple selection and holding ctrl/cmd
      if (multiple && ($.template.touchOs || event.ctrlKey || event.metaKey || select.hasClass('easy-multiple-selection')))
      {
        // Current option state
        selected = option.hasClass('selected');

        // Multiple selects require a last one selected option, except if marked
        if (!select.hasClass('allow-empty'))
        {
          // Only change if the option wasn't selected, or if there is at least one other selected option
          if (!selected || (selected && (value = replaced.val()) && value.length > 1))
          {
            // Update select
            replacedOption.selected = !selected;
            replaced.trigger('change');
          }
        }
        else
        {
          // Default behavior
          replacedOption.selected = !selected;
          replaced.trigger('change');
        }
      }
      // Standard selection mode
      else
      {
        // Get current value
        value = replaced.val();
        if (multiple && (value === null || value === undefined))
        {
          value = [];
        }

        // Compare depending on mode
        if ((multiple && (value.length !== 1 || value[0] !== replacedOption.value)) || (!multiple && value !== replacedOption.value))
        {
          // Update select
          replaced.val(replacedOption.value).trigger('change');
        }

        // Close select
        if (!select.hasClass('selectMultiple'))
        {
          select.trigger('close-select');
        }
      }
    }
  }

  /**
   * Updates the select text
   *
   * @param jQuery select the replacement select
   * @param jQuery replaced the replaced select
   * @param object settings the options (may be from another object)
   * @return void
   */
  function _updateSelectText(select, replaced, settings)
  {
    var selected = replaced.find(':selected'),
      selectValue = select.children('.select-value'),
      values = [],
      text;

    // Not for multiple selects
    if (select.hasClass('selectMultiple'))
    {
      return;
    }

    // Static text
    if (settings.staticValue)
    {
      selectValue.html(settings.staticValue);
      return;
    }

    // If nothing selected
    if (selected.length === 0)
    {
      // Get empty placeholder
      text = replaced.data('no-value-text') || settings.noValueText;

      // Must not be empty to preserve vertical-align
      if (!text || text.length === 0)
      {
        text = '&nbsp;';
      }

      // Set text
      selectValue.addClass('alt').html(text);
    }
    else
    {
      // Gather selected values texts
      selected.each(function(i)
      {
        values.push($(this).text());
      });

      // Update displayed value
      if (replaced[0].multiple)
      {
        switch (values.length)
        {
          case 1:
            _updateSelectValueText(selectValue, values, replaced.data('single-value-text'), settings.singleValueText);
            break;

          case replaced[0].options.length:
            _updateSelectValueText(selectValue, values, replaced.data('all-values-text'), settings.allValuesText);
            break;

          default:
            _updateSelectValueText(selectValue, values, replaced.data('multiple-values-text'), settings.multipleValuesText);
            break;
        }
      }
      else
      {
        selectValue.text((values.length > 0) ? values.join(', ') : '&nbsp;');
      }
    }
  }

  /**
   * Set the select replacement text according to options
   *
   * @param jQuery selectValue the text block
   * @param array values the list of selected values text
   * @param string|boolean dataText template specified in the element's data, if any
   * @param string|boolean defaultText default value
   * @return void
   */
  function _updateSelectValueText(selectValue, values, dataText, defaultText)
  {
    // If no user value, use default
    if (!dataText)
    {
      dataText = defaultText;
    }

    // Must not be empty to preserve vertical-align
    if (typeof dataText === 'string' && dataText.length === 0)
    {
      dataText = '&nbsp;';
    }

    // Check format
    if (typeof dataText === 'boolean')
    {
      selectValue.removeClass('alt').html((values.length > 0) ? values.join(', ') : '&nbsp;');
    }
    else
    {
      selectValue.addClass('alt').html(dataText.replace('%d', values.length));
    }
  }

  /**
   * Get a select selected value index
   *
   * @param jQuery select the select selection
   * @return int|boolean, the selected index, or -1 if none, or false if several values are selected
   */
  function _getSelectedIndex(select)
  {
    // Mode
    if (select[0].multiple)
    {
      // Multiple select values
      val = select.val();

      // If several values
      if (val && val.length > 1)
      {
        selectedIndex = false;
      }
      else
      {
        selectedIndex = select[0].selectedIndex;
      }
    }
    else
    {
      selectedIndex = select[0].selectedIndex;
    }

    // Detect if undefined
    if (selectedIndex === null || selectedIndex === undefined)
    {
      selectedIndex = -1;
    }

    return selectedIndex;
  }

  /**
   * Clean delete of a radio/checkbox replacement
   *
   * @return void
   */
  function _removeCheckableReplacement()
  {
    var element = $(this),
      replacement = element.data('replacement'),
      blurFunc;

    // If not replaced
    if (!replacement)
    {
      return;
    }

    // If focused
    blurFunc = replacement.data('checkableBlurFunction');
    if (blurFunc)
    {
      blurFunc();
    }

    // Tabindex
    this.tabIndex = select[0].tabIndex;

    // Remove select from replacement and restore classes
    element.detach().insertBefore(replacement).css('display', '');
    this.className = element.data('initial-classes');
    element.removeData('initial-classes');

    // Remove references
    element.removeData('replacement');

    // Delete replacement
    replacement.remove();
  }

  /**
   * Clean delete of a select replacement
   *
   * @return void
   */
  function _removeSelectReplacement()
  {
    var element = $(this),
      select = element.data('replacement');

    // If not replaced
    if (!select)
    {
      return;
    }

    // If open
    if (select.hasClass('select-cloned'))
    {
      select.trigger('close-select');
    }

    // If focused
    if (select.hasClass('focus'))
    {
      select.blur();
    }

    // Tabindex
    if (select[0].tabIndex > 0)
    {
      this.tabIndex = select[0].tabIndex;
    }

    // Remove select from replacement and restore classes
    element.detach().insertBefore(select).css('display', '');
    this.className = element.data('initial-classes');
    element.removeData('initial-classes');

    // Remove references
    element.removeData('replacement');

    // Stop scrolling
    if ($.fn.customScroll)
    {
      select.children('.drop-down').removeCustomScroll();
    }

    // Delete select
    select.remove();
  }

  /**
   * Clean delete of a file input replacement
   *
   * @return void
   */
  function _removeInputStyling()
  {
    var element = $(this),
      parent = element.parent();

    // If not replaced
    if (!parent.hasClass('file'))
    {
      return;
    }

    // Remove input from styling
    element.detach().insertBefore(parent);

    // Delete styling
    parent.remove();
  }

  /********************************************************/
  /*        Event delegation for template elements        */
  /********************************************************/

  /*
   * Event delegation is used to handle most of the template setup, as it does also apply to dynamically added elements
   * @see http://api.jquery.com/on/
   */

  doc.on('click', 'label', function(event)
  {
    var label = $(this),
      element = $('#'+this.htmlFor),
      replacement;

    // If no input, exit
    if (element.length === 0)
    {
      return;
    }

    // Replacement
    replacement = element.data('replacement');

    // IE7/8 only triggers 'change' on blur and does not handle change on 'click' for hidden elements, so we need to use a workaround
    if ($.template.ie7 || $.template.ie8)
    {
      // If checkbox/radio
      if (element.is(':checkbox, :radio'))
      {
        // If replaced
        if (replacement)
        {
          // Trigger event
          replacement.trigger('click');
          return;
        }

        // If checkable is included in label
        if (label.hasClass('button') && element.closest('label').is(label))
        {
          // Do not handle if disabled
          if (element.closest('.disabled').length > 0 || element.is(':disabled'))
          {
            return;
          }

          // Check if state can be changed
          if (element.is(':checkbox') || !element.prop('checked'))
          {
            element.prop('checked', !element.prop('checked')).trigger('change');
          }
        }

        return;
      }
    }

    // If hidden select
    if (element.is('select'))
    {
      // Only process if hidden
      if (replacement && element.is(':hidden'))
      {
        replacement.focus();
      }
    }
  });

  // Change radio/checkboxes
  doc.on('click', 'span.switch, span.radio, span.checkbox', function(event)
  {
    var element = $(this),
      replaced = element.data('replaced');

    // If not valid, exit
    if (!replaced || replaced.length === 0)
    {
      return;
    }

    // Only process if not clicking in the inner checkable
    if (event.target === replaced[0])
    {
      return;
    }

    // Do not handle if disabled
    if (element.closest('.disabled').length > 0 || replaced.is(':disabled'))
    {
      return;
    }

    // If dragged too recently
    if (element.data('switch-dragged'))
    {
      return;
    }

    // Check if state can be changed
    if (replaced.is(':checkbox') || !replaced.prop('checked'))
    {
      replaced.prop('checked', !replaced.prop('checked')).trigger('change');
    }
  });

  // Drag switches
  doc.on('mousedown touchstart', 'span.switch', function(event)
  {
      // Parent switch
    var switchEl = $(this),
      replaced = switchEl.data('replaced'),
      reversed = (switchEl.closest('.reversed-switches').length > 0),

      // Button
      button = switchEl.children('.switch-button'),

      // Is it a mini/tiny switch
      mini = switchEl.hasClass('mini'),
      tiny = switchEl.hasClass('tiny'),

      // Size adjustments
      buttonOverflow = tiny ? 2 : 0,
      valuesOverflow = ((mini || tiny) ? 7 : 4)+(2*buttonOverflow),
      marginIE7 = ($.template.ie7 && !mini && !tiny) ? 4 : 0,

      // Original button position
      initialPosition = button.position().left,

      // Inner elements
      onEl = switchEl.children('.switch-on'),
      onSpan = onEl.children(),
      offEl = switchEl.children('.switch-off'),
      offSpan = offEl.children(),

      // Available space
      switchWidth = switchEl.width(),
      buttonWidth = button.outerWidth(true),
      availableSpace = switchWidth-buttonWidth+(2*buttonOverflow),

      // Type of event
      touchEvent = (event.type === 'touchstart'),

      // Event start position
      offsetHolder = touchEvent ? event.originalEvent.touches[0] : event,
      mouseX = offsetHolder.pageX,

      // Work vars
      ieSelectStart, dragged = false, value;

    // If not valid, exit
    if (!replaced || replaced.length === 0)
    {
      return;
    }

    // Do not handle if disabled
    if (switchEl.closest('.disabled').length || replaced.is(':disabled'))
    {
      return;
    }

    // Stop text selection
    event.preventDefault();
    ieSelectStart = document.onselectstart;
    document.onselectstart = function()
    {
      return false;
    };

    // Add class to prevent animation
    switchEl.addClass('dragging');

    // Watch mouse/finger move
    function watchMouse(event)
    {
      var offsetHolder = touchEvent ? event.originalEvent.touches[0] : event,
        position = Math.max(0, Math.min(availableSpace, initialPosition+(offsetHolder.pageX-mouseX)));

      // Actual value
      value = (position > availableSpace/2) ? !reversed : reversed;

      // Move inner elements
      if (reversed)
      {
        button.css('right', (availableSpace-position-buttonOverflow)+'px');
        offEl.css('right', (switchWidth-position-valuesOverflow)+'px');
        offSpan.css('margin-left', -(availableSpace-position+marginIE7)+'px');
        onEl.css('left', (buttonWidth+position-valuesOverflow)+'px');
      }
      else
      {
        button.css('left', (position-buttonOverflow)+'px');
        onEl.css('right', (switchWidth-position-valuesOverflow)+'px');
        onSpan.css('margin-left', -(availableSpace-position+marginIE7)+'px');
        offEl.css('left', (buttonWidth+position-valuesOverflow)+'px');
      }

      // Drag is effective
      dragged = true;
    }
    doc.on(touchEvent ? 'touchmove' : 'mousemove', watchMouse);

    // Watch for mouseup/touchend
    function endDrag()
    {
      doc.off(touchEvent ? 'touchmove' : 'mousemove', watchMouse);
      doc.off(touchEvent ? 'touchend' : 'mouseup', endDrag);

      // Remove class preventing animation
      switchEl.removeClass('dragging');

      // Reset positions
      if (reversed)
      {
        button.css('right', '');
        offEl.css('right', '');
        offSpan.css('margin-left', '');
        onEl.css('left', '');
      }
      else
      {
        button.css('left', '');
        onEl.css('right', '');
        onSpan.css('margin-left', '');
        offEl.css('left', '');
      }

      // Re-enable text selection
      document.onselectstart = ieSelectStart ? ieSelectStart : null;

      // If dragged, update value
      if (dragged)
      {
        // Set new value
        if (replaced.prop('checked') != value)
        {
          replaced.prop('checked', value).change();
        }

        // Prevent change on upcoming click event
        switchEl.data('switch-dragged', true);
        setTimeout(function()
        {
          switchEl.removeData('switch-dragged');

        }, 40);
      }
      else if (touchEvent)
      {
        // Click event is not trigerred for touch devices when touch events were handled
        switchEl.click();
      }
    }
    doc.on(touchEvent ? 'touchend' : 'mouseup', endDrag);
  });

  // Radios and checkboxes changes
  doc.on('change silent-change', ':radio, :checkbox', function(event)
  {
    var element = $(this),
      replacement = element.data('replacement'),
      checked = this.checked;

    // Update visual style
    if (replacement)
    {
      // Update style
      replacement[checked ? 'addClass' : 'removeClass']('checked');
    }
    // Button labels
    else if (element.parent().is('label.button'))
    {
      element.parent()[checked ? 'addClass' : 'removeClass']('active');
    }

    // If radio, refresh others without triggering 'change'
    if (this.type === 'radio')
    {
      $('input[name="'+this.name+'"]:radio').not(this).each(function(i)
      {
        var input = $(this),
          replacement = input.data('replacement');

        // Switch
        if (replacement)
        {
          replacement[checked ? 'removeClass' : 'addClass']('checked');
        }
        // Button labels
        else if (input.parent().is('label.button'))
        {
          input.parent()[checked ? 'removeClass' : 'addClass']('active');
        }
      });
    }
  });

  // Switches, radios and checkboxes focus
  doc.on('focus', 'span.switch, span.radio, span.checkbox', function(event)
  {
    var element = $(this),
      replaced = element.data('replaced'),
      handleKeysEvents = false;

    // If not valid, exit
    if (!replaced || replaced.length === 0)
    {
      return;
    }

    // Do not handle if disabled
    if (element.closest('.disabled').length > 0 || replaced.is(':disabled'))
    {
      event.preventDefault();
      return;
    }

    // IE7-8 focus handle is different from modern browsers
    if ($.template.ie7 || $.template.ie8)
    {
      //doc.find('.focus').not(element).blur();
    }

    // Show focus
    element.addClass('focus');

    /*
     * Keyboard events handling
     */
    handleKeysEvents = function(event)
    {
      if (event.keyCode == $.template.keys.space)
      {
        // If radio, do not allow uncheck as this may leave all radios unchecked
        if (!replaced.is(':radio') || !replaced[0].checked)
        {
          // Change replaced state, listener will update style
          replaced[0].checked = !replaced[0].checked;
          replaced.change();
        }
        event.preventDefault();
      }
    };

    // Blur function
    function onBlur()
    {
      // Remove styling
      element.removeClass('focus');

      // Clear data
      element.removeData('checkableBlurFunction');

      // Stop listening
      doc.off('keydown', handleKeysEvents);
      element.off('blur', onBlur);
    }

    // Store for external calls
    element.data('checkableBlurFunction', onBlur);

    // Start listening
    element.on('blur', onBlur);
    doc.on('keydown', handleKeysEvents);
  });

  // Textareas focus
  doc.on('focus', 'textarea', function(event)
  {
    var element = $(this);

    // Fixes the focus issues on some browsers
    //doc.find('.focus').not(element).blur();

    // Styling
    element.addClass('focus');

  }).on('blur', 'textarea', function()
  {
    $(this).removeClass('focus');
  });

  // Inputs focus
  doc.on('focus', 'input', function(event)
  {
    var input = $(this),
      replacement, wrapper,
      last;

    // Do not handle if disabled
    if (input.closest('.disabled').length > 0 || input.is(':disabled'))
    {
      event.preventDefault();
      return;
    }

    // For radios and focus, pass focus to replacement element
    if (this.type === 'radio' || this.type === 'checkbox')
    {
      replacement = input.data('replacement');

      // Update visual style
      if (replacement)
      {
        replacement.addClass('focus');
      }

      // Done, even if no replacement
      return;
    }

    // Fixes the focus issues on some browsers
    //doc.find('.focus').not(input).blur();

    // Placeholder polyfill
    if (!Modernizr.input.placeholder && input.attr('placeholder') && input.val() === input.attr('placeholder'))
    {
      input.removeClass('placeholder').val('');
    }

    // Look for wrapped inputs
    wrapper = input.closest('.input, .inputs');

    // If wrapped
    if (wrapper.length > 0)
    {
      // Styling
      wrapper.addClass('focus');

      // For number inputs
      if (wrapper.hasClass('number'))
      {
        // Watch keydown
        input.on('keydown.number', function(event)
        {
          // If up and down
          if (event.which === 38 || event.which === 40)
          {
            input.incrementNumber((event.which === 38), event.shiftKey);
          }
        });

        // Watch keyup
        input.on('keyup.number', function(event)
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
      }
    }
    else
    {
      // Styling
      input.addClass('focus');
    }

  }).on('blur', 'input', function()
  {
    var input = $(this),
      replacement,
      wrapper;

    // Not for radios and checkboxes
    if (this.type === 'radio' || this.type === 'checkbox')
    {
      replacement = input.data('replacement');

      // Update visual style
      if (replacement)
      {
        replacement.removeClass('focus');
      }

      // Done, even if no replacement
      return;
    }

    // Placeholder polyfill
    if (!Modernizr.input.placeholder && input.attr('placeholder') && input.val() === '' && input.attr('type') != 'password')
    {
      input.addClass('placeholder').val(input.attr('placeholder'));
    }

    // Remove styling
    wrapper = input.closest('.focus');
    wrapper.removeClass('focus');

    // For number inputs
    if (wrapper.hasClass('number'))
    {
      // Stop watching keyboard events
      input.off('keydown.number').off('keyup.number');

      // Validate value
      input.setNumber(input.val());
    }
  });

  // Placehoder support
  if (!Modernizr.input.placeholder)
  {
    // Empty placehoder on form submit
    doc.on('submit', 'form', function(event)
    {
      $(this).find('input.placeholder').each(function()
      {
        var input = $(this);

        if (input.attr('placeholder') && input.val() === input.attr('placeholder'))
        {
          input.val('');
        }
      });
    });
  }

  // File inputs
  doc.on('change silent-change', '.file > input[type="file"]', function(event)
  {
    var input = $(this),
      files = [], text, i;

    // Update styling text
    if (this.multiple && this.files)
    {
      for (i = 0; i < this.files.length; i++)
      {
        files.push(this.files[i].name.split(/(\/|\\)/).pop());
      }
      text = files.join(', ');
    }
    else
    {
      text = input.val().split(/(\/|\\)/).pop();
    }

    // Set text
    input.siblings('.file-text').text(text);
  });

  // Value inputs
  doc.on('click', '.number-up, .number-down', function(event)
  {
    var button = $(this),
      wrapper = button.parent(),
      input = wrapper.children('input:first'),
      value;

    // Check if valid
    if (input.length === 0 || input.is(':disabled'))
    {
      return;
    }

    // Increment
    input.incrementNumber(button.hasClass('number-up'), event.shiftKey).focus().trigger('change');
  });

  // Scroll on value inputs
  doc.on('mousewheel', '.number', function(event, delta, deltaX, deltaY)
  {
    var input = $(this).find('input');

    // Check if valid
    if (!input.length || input.is(':disabled'))
    {
      return;
    }

    // Change value
    input.incrementNumber(delta > 0, event.shiftKey).focus().trigger('change');

    // Prevent parents from scrolling
    event.preventDefault();
  });

  // Handle native select focus
  doc.on('focus', '.select > select', function()
  {
    var select = $(this),
      replacement = select.data('replacement');
    if (replacement)
    {
      if (replacement.hasClass('select-styled-list'))
      {
        replacement.focus();
      }
      else
      {
        replacement.hasClass('focus');
        select.one('blur', function()
        {
          replacement.removeClass('focus');
        });
      }
    }
  });
  doc.on('focus select-focus', 'span.select, span.selectMultiple', function(event)
  {
    // Only work if the element is the event's target
    if (event.target !== this)
    {
      return;
    }

    var select = $(this).closest('.select, .selectMultiple'),
      settings = select.data('select-settings') || {},
      replaced = select.data('replaced'),
      handleKeysEvents, search = '',
      blurTimeout, searchTimeout;

    // Do not handle clones
    if (select.hasClass('select-clone'))
    {
      return;
    }

    // Do not handle for non-styled lists
    if (replaced && !select.hasClass('select-styled-list'))
    {
      replaced.focus();
      return;
    }

    // Do not handle if disabled
    if (select.closest('.disabled').length > 0 || (replaced && replaced.is(':disabled')))
    {
      event.preventDefault();
      return;
    }

    // Handle really close blur/focus events
    blurTimeout = select.data('selectBlurTimeout');
    if (blurTimeout)
    {
      // The select is still focused but about to blur, prevent and remain focused
      clearTimeout(blurTimeout);
      select.removeData('selectBlurTimeout');
      return;
    }

    // Do not handle if already focused
    if (select.hasClass('focus'))
    {
      return;
    }

    // Fixes the focus issues on some browsers
    //doc.find('.focus').not(select).blur();

    // Visual style
    select.addClass('focus');

    /**
     * Keyboard events handling
     */

    // Affect original element, listeners will update the replacement
    handleKeysEvents = function(event)
    {
      var keys = $.template.keys,
        target = select.data('clone') || select,
        list = target.children('.drop-down'),
        selectedIndex, mode,
        focus, next, replacedOption,
        character, searchRegex;

      // If using easy multiple selection, use focus instead of selection
      mode = select.hasClass('easy-multiple-selection') ? 'focus' : 'selected';

      // Key handling
      switch (event.keyCode)
      {
        case keys.up:
          // If open or multiple, work on displayed options
          if (target.hasClass('open') || select.hasClass('selectMultiple'))
          {
            // Focused element
            focus = list.children('.'+mode+':first');
            if (focus.length === 0)
            {
              next = list.children('a, span').not('.disabled').last();
            }
            else
            {
              next = focus.prevAll('a, span').not('.disabled').first();
            }

            // Focus previous option
            if (next.length > 0)
            {
              focus.removeClass(mode);
              next.addClass(mode);
              if ($.fn.scrollToReveal)
              {
                next.scrollToReveal();
              }

              // If selection mode, update replaced and trigger change
              if (mode === 'selected' && replaced)
              {
                replacedOption = next.data('select-value');
                if (replacedOption)
                {
                  // If multiple selection, clear all before
                  if (replaced[0].multiple)
                  {
                    replaced.find('option:selected').prop('selected', false);
                  }

                  replacedOption.selected = true;
                  replaced.trigger('change');
                }
              }
            }

            event.preventDefault();
          }
          // If replacement
          else if (replaced)
          {
            // Update original, listeners will update the replacement
            selectedIndex = _getSelectedIndex(replaced);
            if (selectedIndex !== false)
            {
              while (selectedIndex > 0)
              {
                // Make sure it is not disabled
                if (!replaced[0].options[selectedIndex-1].disabled)
                {
                  replaced[0].selectedIndex = selectedIndex-1;
                  replaced.change();
                  break;
                }

                // Else, next option
                --selectedIndex;
              }
            }
            event.preventDefault();
          }
          break;

        case keys.down:
          // If not open yet, check if we have to
          if (select.hasClass('select') && !target.hasClass('open') && settings.openOnKeyDown)
          {
            _openSelect(select);
            event.preventDefault();
          }
          else
          {
            // If open or multiple, work on displayed options
            if (target.hasClass('open') || select.hasClass('selectMultiple'))
            {
              // Focused element
              focus = list.children('.'+mode+':last');
              if (focus.length === 0)
              {
                next = list.children('a, span').not('.disabled').first();
              }
              else
              {
                next = focus.nextAll('a, span').not('.disabled').first();
              }

              // Focus next option
              if (next.length > 0)
              {
                focus.removeClass(mode);
                next.addClass(mode);
                if ($.fn.scrollToReveal)
                {
                  next.scrollToReveal();
                }

                // If selection mode, update replaced and trigger change
                if (mode === 'selected' && replaced)
                {
                  replacedOption = next.data('select-value');
                  if (replacedOption)
                  {
                    // If multiple selection, clear all before
                    if (replaced[0].multiple)
                    {
                      replaced.find('option:selected').prop('selected', false);
                    }

                    replacedOption.selected = true;
                    replaced.trigger('change');
                  }
                }
              }

              event.preventDefault();
            }
            // If replacement
            else if (replaced)
            {
              // Update original, listeners will update the replacement
              selectedIndex = _getSelectedIndex(replaced);
              if (selectedIndex !== false)
              {
                while (selectedIndex < replaced[0].options.length-1)
                {
                  // Make sure it is not disabled
                  if (!replaced[0].options[selectedIndex+1].disabled)
                  {
                    replaced[0].selectedIndex = selectedIndex+1;
                    replaced.change();
                    break;
                  }

                  // Else, next option
                  ++selectedIndex;
                }
              }
              event.preventDefault();
            }
          }
          break;

        case keys.enter:
        case keys.space:
          // If focus mode on, simulate click
          if (mode === 'focus' && (select.hasClass('selectMultiple') || target.hasClass('open')))
          {
            // Focused element
            focus = list.children('.'+mode);
            if (focus.length === 1)
            {
              event.preventDefault();
              focus.click();
            }
          }
          // Else, just close the select if open
          else if (target.hasClass('open'))
          {
            target.trigger('close-select');
          }
          break;

        default:
          // Get pressed key character
          character = String.fromCharCode(event.keyCode);

          // If regular character
          if (character && character.length === 1)
          {
            // If a search timeout is in, stop it
            if (searchTimeout)
            {
              clearTimeout(searchTimeout);
            }

            // Add to search
            search += character.toLowerCase();
            searchRegex = new RegExp('^'+search, 'g');

            // Start timeout to clear search string when no more key are pressed
            searchTimeout = setTimeout(function()
            {
              search = '';

            }, 1500);

            // Mode
            if (target.hasClass('open') || select.hasClass('selectMultiple'))
            {
              // Loop through values to find a match
              list.children('a, span').each(function(i)
              {
                var option = $(this);

                // If matches
                if ($.trim(option.text().toLowerCase()).match(searchRegex))
                {
                  // Focused element
                  focus = list.children('.'+mode+':last');

                  // Focus option
                  focus.removeClass(mode);
                  option.addClass(mode);
                  if ($.fn.scrollToReveal)
                  {
                    option.scrollToReveal();
                  }

                  // If selection mode, update replaced and trigger change
                  if (mode === 'selected' && replaced)
                  {
                    replacedOption = option.data('select-value');
                    if (replacedOption)
                    {
                      // Set value
                      replaced.val(replacedOption.value).trigger('change');
                    }
                  }

                  // Prevent default key event
                  event.preventDefault();

                  // Stop search
                  return false;
                }
              });
            }
            // Closed mode only works for replacements
            else if (replaced)
            {
              // Loop through values to find a match
              replaced.find('option').each(function(i)
              {
                // If matches
                if ($.trim($(this).text().toLowerCase()).match(searchRegex))
                {
                  // Set value
                  replaced.val(this.value).trigger('change');

                  // Prevent default key event
                  event.preventDefault();

                  // Stop search
                  return false;
                }
              });
            }
          }
          break;
      }
    };

    // Blur function
    function onBlur(event, timed)
    {
      var target = $(event.target),
        clone = select.data('clone');

      // If this is an internal operation, do not process
      if (select.data('select-hiding'))
      {
        return;
      }

      // Validation for click/touchend event
      if ((event.type === 'click' || event.type === 'touchend') && (target.closest(select).length || (clone && target.closest(clone).length)))
      {
        return;
      }

      // Handle really close blur/focus events
      blurTimeout = select.data('selectBlurTimeout');
      if (!blurTimeout)
      {
        // Wait, are you sure you want me to blur? Let's just wait a little...
        select.data('selectBlurTimeout', setTimeout(function() { onBlur.call(this, event, true); }, 40));
        return;
      }
      else if (timed)
      {
        // The blur timeout has ended without getting back focus, so let's blur!
        select.removeData('selectBlurTimeout');
      }
      else
      {
        // Multiple blur events, do not handle
        return;
      }

      // Close if open
      select.trigger('close-select');

      // Remove styling
      select.removeClass('focus');

      // Stop listening
      doc.off('focusin', onFocusin);
      doc.off('keydown', handleKeysEvents);
      doc.off('click', onBlur);
      select.off('blur', onBlur);
    }

    // Watch focus on other elements
    function onFocusin(event)
    {
      var target = $(event.target),
        clone = select.data('clone');

      // Check if focus target is within the select
      if (target.closest(select).length || (clone && target.closest(clone).length))
      {
        // Pseudo-focus to preserve styling and key events
        select.trigger('select-focus');
      }
      else
      {
        // Handle focus on another input while waiting to refocus a select
        blurTimeout = select.data('selectBlurTimeout');
        if (blurTimeout)
        {
          clearTimeout(blurTimeout);
        }
        onBlur.call(this, event, true);
      }
    }

    // Start listening
    select.on('blur', onBlur);
    doc.on('touchend click', onBlur);
    doc.on('keydown', handleKeysEvents);
    doc.on('focusin', onFocusin);
  });

  // Handle select change
  doc.on('change silent-change', 'select', function()
  {
    var replaced = $(this),
      select = replaced.data('replacement');

    // If valid
    if (select)
    {
      _updateSelectText(select, replaced, select.data('select-settings'));
    }
  });

  // Opening when on touch device
  if ($.template.touchOs)
  {
    // Open on tap
    doc.on('touchend', '.select-arrow, span.select-value', function(event)
    {
      _openSelect($(this).parent(), false, event);
    });
  }
  else
  {
    // Selects opening arrow
    doc.on('click', '.select-arrow, span.select-value', function(event)
    {
      var select = $(this).parent();
      if (!select.hasClass('select-clone') && !select.hasClass('auto-open'))
      {
        _openSelect(select, false, event);
      }
    });

    // Auto-opening selects
    doc.on('mouseenter', '.select.auto-open', function(event)
    {
      var select = $(this);
      if (!select.hasClass('select-clone'))
      {
        _openSelect($(this), true, event);
      }
    });
  }

  /*
   * Form validation hooks:
   * The replaced selects need to be un-hidden to be validated, then hidden back
   */
  doc.on('jqv.form.validating', 'form', function(event)
  {
    var form = $(this),
      hidden = form.find('span.select > select, span.selectMultiple > select').filter(':hidden').show(),

      // Return to normal state
      validateEnd = function()
      {
        hidden.css('display', '');
        form.off('jqv.form.result', validateEnd);
      };

    // Listen for end of validation
    form.on('jqv.form.result', validateEnd);
  });

})(jQuery, window, document);