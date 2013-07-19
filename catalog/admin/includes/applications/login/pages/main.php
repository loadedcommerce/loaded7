<?php
  /*
  $Id: main.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<div id="container">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=process'); ?>" id="form-login" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('heading_title'); ?>" accept-charset="utf-8">
          <ul class="inputs black-input large">
            <!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
            <li><span class="icon-user mid-margin-right"></span><input type="text" onfocus="$('#form-wrapper').clearMessages();" name="user_name" id="user_name" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('field_username'); ?>" autocomplete="on"></li>
            <li><span class="icon-lock mid-margin-right"></span><input type="password" onfocus="$('#form-wrapper').clearMessages();" name="user_password" id="user_password" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('field_password'); ?>" autocomplete="on"></li>
          </ul>
          <p class="button-height align-center">
            <button type="submit" class="button glossy" id="login"><?php echo $lC_Language->get('button_login'); ?></button><br />
          </p>
        </form> 
        
        <form method="post" action="" id="form-password" class="input-wrapper orange-gradient glossy" title="Lost Password?">
          <p class="message">
            If you can't remember your password, fill the input below with your e-mail and we'll send you a new one:
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="mail" id="mail" value="" class="input-unstyled" placeholder="Your e-mail" autocomplete="off"></li>
          </ul>
          <button type="submit" class="button glossy full-width" id="send-password">Send new password</button>
        </form>
        
        <?php
        /* 
        <form method="post" action="" id="form-register" class="input-wrapper green-gradient glossy" title="Register">
          <p class="message">
            New user? Yay! Let us know a little bit about you before you start:
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-card mid-margin-right"></span><input type="text" name="name" id="name-register" value="" class="input-unstyled" placeholder="Your name" autocomplete="off"></li>
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="mail" id="mail-register" value="" class="input-unstyled" placeholder="Your e-mail" autocomplete="off"></li>
          </ul>
          <ul class="inputs black-input large">
            <li><span class="icon-user mid-margin-right"></span><input type="text" name="login" id="login-register" value="" class="input-unstyled" placeholder="Login" autocomplete="off"></li>
            <li><span class="icon-lock mid-margin-right"></span><input type="password" name="pass" id="pass-register" value="" class="input-unstyled" placeholder="Password" autocomplete="off"></li>
          </ul>
          <button type="submit" class="button glossy full-width" id="send-register">Register</button>
        </form>
        */ 
        ?>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
  /*
   * How do I hook my login script to these?
   * --------------------------------------
   *
   * These scripts are meant to be non-obtrusive: if the user has disabled javascript or if an error occurs, the forms
   * works fine without ajax.
   *
   * The only part you need to edit are the scripts between the EDIT THIS SECTION tags, which do inputs validation and
   * send data to server. For instance, you may keep the validation and add an AJAX call to the server with the user
   * input, then redirect to the dashboard or display an error depending on server return.
   *
   * Or if you don't trust AJAX calls, just remove the event.preventDefault() part and let the form be submitted.
   */

  $(document).ready(function()
  {
    /*
     * JS login effect
     * This script will enable effects for the login page
     */
      // Elements
    var doc = $('html').addClass('js-login'),
      container = $('#container'),
      formWrapper = $('#form-wrapper'),
      formBlock = $('#form-block'),
      formViewport = $('#form-viewport'),
      forms = formViewport.children('form'),

      // Doors
      topDoor = $('<div id="top-door" class="form-door"><div></div></div>').appendTo(formViewport),
      botDoor = $('<div id="bot-door" class="form-door"><div></div></div>').appendTo(formViewport),
      doors = topDoor.add(botDoor),

      // Switch
      formSwitch = $('<div id="form-switch"><span class="button-group"></span></div>').appendTo(formBlock).children(),

      // Current form
      hash = (document.location.hash.length > 1) ? document.location.hash.substring(1) : false,

      // If layout is centered
      centered,

      // Store current form
      currentForm,

      // Animation interval
      animInt,

      // Work vars
      maxHeight = false,
      blocHeight;

    /******* EDIT THIS SECTION *******/

    /*
     * Login
     * These functions will handle the login process through AJAX
     */
    $('#form-login').submit(function(event)
    {
      // Values
      var login = $.trim($('#login').val()),
        pass = $.trim($('#pass').val());

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (login.length === 0)
      {
        // Display message
        displayError('Please fill in your login');
        return false;
      }
      else if (pass.length === 0)
      {
        // Remove empty login message if displayed
        formWrapper.clearMessages('Please fill in your login');

        // Display message
        displayError('Please fill in your password');
        return false;
      }
      else
      {
        // Remove previous messages
        formWrapper.clearMessages();

        // Show progress
        displayLoading('Checking credentials...');

        /*
         * This is where you may do your AJAX call, for instance:
         * $.ajax(url, {
         *     data: {
         *       login:  login,
         *       pass:  pass
         *     },
         *     success: function(data)
         *     {
         *       if (data.logged)
         *       {
         *         document.location.href = 'index.html';
         *       }
         *       else
         *       {
         *         formWrapper.clearMessages();
         *         displayError('Invalid user/password, please try again');
         *       }
         *     },
         *     error: function()
         *     {
         *       formWrapper.clearMessages();
         *       displayError('Error while contacting server, please try again');
         *     }
         * });
         */

        // Simulate server-side check
        setTimeout(function() {
          document.location.href = './'
        }, 2000);
      }
    });

    /*
     * Password recovery
     */
    $('#form-password').submit(function(event)
    {
      // Values
      var mail = $.trim($('#mail').val());

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (mail.length === 0)
      {
        // Display message
        displayError('Please fill in your email');
      }
      // Want more robust mail validation? see http://stackoverflow.com/a/2855946
      else if (!/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/.test(mail))
      {
        // Remove empty email message if displayed
        formWrapper.clearMessages('Please fill in your email');

        // Display message
        displayError('Email is not valid');
        return false;
      }
      else
      {
        // Remove previous messages
        formWrapper.clearMessages();

        // Show progress
        displayLoading('Sending credentials...');

        /*
         * This is where you may do your AJAX call
         */

        // Simulate server-side check
        setTimeout(function() {
          document.location.href = './'
        }, 2000);
      }
    });

    /*
     * Register
     */
    $('#form-register').submit(function(event)
    {
      // Values
      var name = $.trim($('#name-register').val()),
        mail = $.trim($('#mail-register').val()),
        login = $.trim($('#login-register').val()),
        pass = $.trim($('#pass-register').val());

      // Remove previous messages
      formWrapper.clearMessages();

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (name.length === 0)
      {
        // Display message
        displayError('Please fill in your name');
        return false;
      }
      else if (mail.length === 0)
      {
        // Display message
        displayError('Please fill in your email');
        return false;
      }
      else if (!/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/.test(mail))
      {
        // Display message
        displayError('Email is not valid');
        return false;
      }
      else if (login.length === 0)
      {
        // Display message
        displayError('Please fill in your login');
        return false;
      }
      else if (pass.length === 0)
      {
        // Display message
        displayError('Please fill in your password');
        return false;
      }
      else
      {
        // Show progress
        displayLoading('Registering...');

        /*
         * This is where you may do your AJAX call
         */

        // Simulate server-side check
        setTimeout(function() {
          document.location.href = './'
        }, 2000);
      }
    });

    /******* END OF EDIT SECTION *******/

    /*
     * Animated login
     */

    // Prepare forms
    forms.each(function(i)
    {
      var form = $(this),
        height = form.outerHeight(),
        active = (hash === false && i === 0) || (hash === this.id),
        color = this.className.match(/[a-z]+-gradient/) ? ' '+(/([a-z]+)-gradient/.exec(this.className)[1])+'-active' : '';

      // Store size
      form.data('height', height);

      // Min-height for mobile layout
      if (maxHeight === false || height > maxHeight)
      {
        maxHeight = height;
      }

      // Button in the switch
      form.data('button', $('<a href="#'+this.id+'" class="button anthracite-gradient'+color+(active ? ' active' : '')+'">'+this.title+'</a>')
                .appendTo(formSwitch)
                .data('form', form));

      // Remove title to prevent tooltip from showing - thanks efreed :)
      this.title = '';

      // If active
      if (active)
      {
        // Store
        currentForm = form;

        // Height of viewport
        formViewport.height(height);
      }
      else
      {
        // Hide for now
        form.hide();
      }
    });

    // Main bloc height (without form height)
    blocHeight = formBlock.height()-currentForm.data('height');

    // Handle resizing (mostly for debugging)
    function handleLoginResize()
    {
      // Detect mode
      centered = (container.css('position') === 'absolute');

      // Set min-height for mobile layout
      if (!centered)
      {
        formWrapper.css('min-height', (blocHeight+maxHeight+20)+'px');
        container.css('margin-top', '');
      }
      else
      {
        formWrapper.css('min-height', '');
        if (parseInt(container.css('margin-top'), 10) === 0)
        {
          centerForm(currentForm, false);
        }
      }
    };

    // Register and first call
    $(window).bind('normalized-resize', handleLoginResize);
    handleLoginResize();

    // Switch behavior
    formSwitch.on('click', 'a', function(event)
    {
      var link = $(this),
        form = link.data('form'),
        previousForm = currentForm;

      event.preventDefault();
      if (link.hasClass('active'))
      {
        return;
      }

      // Refresh forms sizes
      forms.each(function(i)
      {
        var form = $(this),
          hidden = form.is(':hidden'),
          height = form.show().outerHeight();

        // Store size
        form.data('height', height);

        // If not active
        if (hidden)
        {
          // Hide for now
          form.hide();
        }
      });

      // Clear messages
      formWrapper.clearMessages();

      // If an animation is already running
      if (animInt)
      {
        clearTimeout(animInt);
      }
      formViewport.stop(true);

      // Update active button
      currentForm.data('button').removeClass('active');
      link.addClass('active');

      // Set as current
      currentForm = form;

      // if CSS transitions are available
      if (doc.hasClass('csstransitions'))
      {
        // Close doors - step 1
        doors.removeClass('door-closed').addClass('door-down');
        animInt = setTimeout(function()
        {
          // Close doors, step 2
          doors.addClass('door-closed');
          animInt = setTimeout(function()
          {
            // Hide previous form
            previousForm.hide();

            // Show target form
            form.show();

            // Center layout
            centerForm(form, true);

            // Height of viewport
            formViewport.animate({
              height: form.data('height')+'px'
            }, function()
            {
              // Open doors, step 1
              doors.removeClass('door-closed');
              animInt = setTimeout(function()
              {
                // Open doors - step 2
                doors.removeClass('door-down');
              }, 300);
            });
          }, 300);
        }, 300);
      }
      else
      {
        // Close doors
        topDoor.animate({ top: '0%' }, 300);
        botDoor.animate({ top: '50%' }, 300, function()
        {
          // Hide previous form
          previousForm.hide();

          // Show target form
          form.show();

          // Center layout
          centerForm(form, true);

          // Height of viewport
          formViewport.animate({
            height: form.data('height')+'px'
          }, {

            /* IE7 is a bit buggy, we must force redraw */
            step: function(now, fx)
            {
              topDoor.hide().show();
              botDoor.hide().show();
              formSwitch.hide().show();
            },

            complete: function()
            {
              // Open doors
              topDoor.animate({ top: '-50%' }, 300);
              botDoor.animate({ top: '105%' }, 300);
              formSwitch.hide().show();
            }
          });
        });
      }
    });

    // Initial vertical adjust
    centerForm(currentForm, false);

    /*
     * Center function
     * @param jQuery form the form element whose height will be used
     * @param boolean animate whether or not to animate the position change
     * @param string|element|array any jQuery selector, DOM element or set of DOM elements which should be ignored
     * @return void
     */
    function centerForm(form, animate, ignore)
    {
      // If layout is centered
      if (centered)
      {
        var siblings = formWrapper.siblings().not('.closing'),
          finalSize = blocHeight+form.data('height');

        // Ignored elements
        if (ignore)
        {
          siblings = siblings.not(ignore);
        }

        // Get other elements height
        siblings.each(function(i)
        {
          finalSize += $(this).outerHeight(true);
        });

        // Setup
        container[animate ? 'animate' : 'css']({ marginTop: -Math.round(finalSize/2)+'px' });
      }
    };

    /**
     * Function to display error messages
     * @param string message the error to display
     */
    function displayError(message)
    {
      // Show message
      var message = formWrapper.message(message, {
        append: false,
        arrow: 'bottom',
        classes: ['red-gradient'],
        animate: false          // We'll do animation later, we need to know the message height first
      });

      // Vertical centering (where we need the message height)
      centerForm(currentForm, true, 'fast');

      // Watch for closing and show with effect
      message.bind('endfade', function(event)
      {
        // This will be called once the message has faded away and is removed
        centerForm(currentForm, true, message.get(0));

      }).hide().slideDown('fast');
    };

    /**
     * Function to display loading messages
     * @param string message the message to display
     */
    function displayLoading(message)
    {
      // Show message
      var message = formWrapper.message('<strong>'+message+'</strong>', {
        append: false,
        arrow: 'bottom',
        classes: ['blue-gradient', 'align-center'],
        stripes: true,
        darkStripes: false,
        closable: false,
        animate: false          // We'll do animation later, we need to know the message height first
      });

      // Vertical centering (where we need the message height)
      centerForm(currentForm, true, 'fast');

      // Watch for closing and show with effect
      message.bind('endfade', function(event)
      {
        // This will be called once the message has faded away and is removed
        centerForm(currentForm, true, message.get(0));

      }).hide().slideDown('fast');
    };
  });

  // What about a notification?
  notify('Alternate login', 'Want to see another login page style? Try the <a href="login.html"><b>default version</b></a> or the <a href="login-box.html"><b>box version</b></a>.', {
    autoClose: false,
    delay: 2500,
    icon: 'img/demo/icon.png'
  });
</script>