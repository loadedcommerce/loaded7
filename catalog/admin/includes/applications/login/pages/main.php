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
<style>
.message { margin:-10px 0 22px 0; }
</style>
<div id="container" style="position:absolute; top:35%;">
  <div id="login-container" style="visibility:hidden;">
    <hgroup id="login-title" class="margin-bottom">
      <h1 class="login-title-image"><?php echo STORE_NAME; ?></h1>
    </hgroup>
    <div id="form-wrapper">
      <div id="form-block" class="scratch-metal">
        <div id="form-viewport">
          <form id="form-login" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=process'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('heading_title'); ?>" accept-charset="utf-8">
            <ul class="inputs black-input large">
              <!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
              <li><span class="icon-user mid-margin-right"></span><input type="text" onfocus="$('#form-wrapper').clearMessages();" name="user_name" id="user_name" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_username'); ?>" autocomplete="on"></li>
              <li><span class="icon-lock mid-margin-right"></span><input type="password" onfocus="$('#form-wrapper').clearMessages();" name="user_password" id="user_password" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_password'); ?>" autocomplete="on"></li>
            </ul>
            <p align="center" class="small-margin-bottom">
              <button type="submit" class="button glossy silver-gradient" style="padding:0 20px;" id="login"><?php echo $lC_Language->get('button_login'); ?></button>
            </p>
          </form>
          <form id="form-lost-password" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=lost_password'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('title_lost_password'); ?>?">
            <p class="small-margin-left small-margin-right mid-margin-bottom">
              <?php echo $lC_Language->get('text_send_new_password_instructions'); ?>
              <span class="block-arrow"><span></span></span>
            </p>
            <ul class="inputs black-input large">
              <li><span class="icon-mail mid-margin-right"></span><input type="text" name="password_email" id="password_email" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_password_email'); ?>" autocomplete="off"></li>
            </ul>
            <p align="center" class="small-margin-bottom">
              <button type="submit" class="button glossy silver-gradient" style="padding:0 20px;" id="lost-password"><?php echo $lC_Language->get('button_submit'); ?></button>
            </p>            
          </form>
          <?php
          if (isset($_GET['action']) && $_GET['action'] == 'register') {
            ?>
            <form id="form-activate-pro" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=pro_success'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('title_register'); ?>">
              <h3 class="align-center margin-bottom"><?php echo $lC_Language->get('heading_product_registration'); ?></h3>
              <a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=activate_free'); ?>"><button type="button" class="button glossy silver-gradient full-width" id="activate-free"><?php echo $lC_Language->get('button_activate_free'); ?></button></a>
              <p class="align-center mid-margin-top mid-margin-bottom"><?php echo $lC_Language->get('text_or'); ?></p>
              <ul class="inputs black-input large">
                <li><span class="icon-unlock mid-margin-right"></span><input type="text" name="serial" id="serial" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_pro_serial'); ?>" autocomplete="off"></li>
              </ul>
              <p class="full-width"><button type="submit" class="button glossy red-gradient full-width" id="activate-pro"><?php echo $lC_Language->get('button_activate_pro'); ?></button></p>
            </form>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
    <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
  </div>
</div>
<script>
  $(document).ready(function() {
    /*
    * JS login effect
    * This script will enable effects for the login page
    */
    // Elements
    $('body').removeClass('clearfix with-menu with-shortcuts');
    $('html').addClass('linen');

    
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
    $('#form-login').submit(function(event) {
      // Values
      var login = $.trim($('#user_name').val()),
      pass = $.trim($('#user_password').val());

      // Remove previous messages
      formWrapper.clearMessages();

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (login.length === 0) {
        // Display message
        displayError('<?php echo $lC_Language->get('text_enter_email'); ?>');
        return false;
      } else if (pass.length === 0) {
        // Remove empty login message if displayed
        formWrapper.clearMessages('<?php echo $lC_Language->get('text_enter_email'); ?>');

        // Display message
        displayError('<?php echo $lC_Language->get('text_enter_password'); ?>');
        return false;
      } else {
        // Remove previous messages
        formWrapper.clearMessages();

        // Show progress 
        displayLoading('<?php echo $lC_Language->get('ms_authenticating'); ?>');    

        // Stop normal behavior
        $("#form-login").bind("submit", preventDefault(event));

        var nvp = $("#form-login").serialize();
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=validateLogin&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) { 
              $("#form-login").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            displayError('<?php echo $lC_Language->get('ms_error_login_invalid'); ?>');   
            return false;
          }              
        );
      }
    });

    /*
    * Password recovery
    */
    $('#form-lost-password').submit(function(event) {
      // Values
      var email = $.trim($('#password_email').val());
      
      // Remove previous messages
      formWrapper.clearMessages();

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (email.length === 0) {
        // Display message
        displayError('<?php echo $lC_Language->get('text_enter_email'); ?>');
      } else {
        // Remove previous messages
        formWrapper.clearMessages();

        // Show progress
        displayLoading('<?php echo $lC_Language->get('ms_authenticating'); ?>');

        // Stop normal behavior
        $("#form-lost-password").bind("submit", preventDefault(event));

        var nvp = $("#form-lost-password").serialize();
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=lostPasswordConfirmEmail&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) {
              $("#form-lost-password").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            displayError('<?php echo $lC_Language->get('ms_error_user_invalid'); ?>');   
            return false;
          }              
        );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      }
    });

    /*
    * Register
    */
    $('#form-activate-pro').submit(function(event) {
      // Values
      var serial = $.trim($('#serial').val());

      // Remove previous messages
      formWrapper.clearMessages();

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (serial.length === 0) {
        // Display message
        displayError('<?php echo $lC_Language->get('text_enter_pro_serial'); ?>');
        return false;
      } else {
        // Remove previous messages
        formWrapper.clearMessages();
        
        // Show progress
        displayLoading('<?php echo $lC_Language->get('ms_authenticating'); ?>');

        // Stop normal behavior
        $("#form-activate-pro").bind("submit", preventDefault(event));

        var nvp = $("#form-activate-pro").serialize();
        alert('activate pro serial');
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // here we call the API via rpc and validate the serial (I think?)
        /*var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=activatePro&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) { 
              $("#form-activate-pro").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            //displayError('<?php echo $lC_Language->get('ms_error_login_invalid'); ?>');   
            //return false;
          }              
        );*/
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // temporary testing - go to pro success page
        $("#form-activate-pro").unbind("submit", preventDefault(event)).submit();
      }
    }); 
    /******* END OF EDIT SECTION *******//*
    * Animated login
    */

    // Prepare forms
    forms.each(function(i) {
      var form = $(this),
      height = form.outerHeight(),
      active = (hash === false && i === 0) || (hash === this.id),
      color = this.className.match(/[a-z]+-gradient/) ? ' '+(/([a-z]+)-gradient/.exec(this.className)[1])+'-active' : '';

      // Store size
      form.data('height', height);

      // Min-height for mobile layout
      if (maxHeight === false || height > maxHeight) {
        maxHeight = height;
      }

      // Button in the switch
      form.data('button', $('<a href="#'+this.id+'" id="'+this.id+'-button" class="button anthracite-gradient'+color+(active ? ' active' : '')+'">'+this.title+'</a>')
        .appendTo(formSwitch)
        .data('form', form));
      // If active
      if (active) {
        // Store
        currentForm = form;

        // Height of viewport
        formViewport.height(height);
      } else {
        // Hide for now
        form.hide();
      }
    });

    // Main bloc height (without form height)
    blocHeight = formBlock.height()-currentForm.data('height');

    // Handle resizing (mostly for debugging)
    function handleLoginResize() {
      // Detect mode
      centered = (container.css('position') === 'absolute');

      // Set min-height for mobile layout
      if (!centered) {
        formWrapper.css('min-height', (blocHeight+maxHeight+20)+'px');
        container.css('margin-top', '');
      } else {
        formWrapper.css('min-height', '');
        if (parseInt(container.css('margin-top'), 10) === 0) {
          centerForm(currentForm, false);
        }
      }
    };

    // Register and first call
    $(window).bind('normalized-resize', handleLoginResize);
    handleLoginResize();
    // Switch behavior
    formSwitch.on('click', 'a', function(event) {
      var link = $(this),
      form = link.data('form'),
      previousForm = currentForm;

      event.preventDefault();
      if (link.hasClass('active')) {
        return;
      }

      // Refresh forms sizes
      forms.each(function(i) {
        var form = $(this),
        hidden = form.is(':hidden'),
        height = form.show().outerHeight();

        // Store size
        form.data('height', height);

        // If not active
        if (hidden) {
          // Hide for now
          form.hide();
        }
      });

      // Clear messages
      formWrapper.clearMessages();

      // If an animation is already running
      if (animInt) {
        clearTimeout(animInt);
      }
      formViewport.stop(true);

      // Update active button
      currentForm.data('button').removeClass('active');
      link.addClass('active');

      // Set as current
      currentForm = form;

      // if CSS transitions are available
      if (doc.hasClass('csstransitions')) {
        // Close doors - step 1
        doors.removeClass('door-closed').addClass('door-down');
        animInt = setTimeout(function() {
          // Close doors, step 2
          doors.addClass('door-closed');
          animInt = setTimeout(function() {
            // Hide previous form
            previousForm.hide();

            // Show target form
            form.show();

            // Center layout
            centerForm(form, true);

            // Height of viewport
            formViewport.animate({
              height: form.data('height')+'px'
            }, function() {
              // Open doors, step 1
              doors.removeClass('door-closed');
              animInt = setTimeout(function() {
                // Open doors - step 2
                doors.removeClass('door-down');
              }, 300);
            });
          }, 300);
        }, 300);
      } else {
        // Close doors
        topDoor.animate({ top: '0%' }, 300);
        botDoor.animate({ top: '50%' }, 300, function() {
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
            step: function(now, fx) {
              topDoor.hide().show();
              botDoor.hide().show();
              formSwitch.hide().show();
            },             
            complete: function() {
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
    function centerForm(form, animate, ignore) {
      // If layout is centered
      if (centered) {
        var siblings = formWrapper.siblings().not('.closing'),
        finalSize = blocHeight+form.data('height');

        // Ignored elements
        if (ignore) {
          siblings = siblings.not(ignore);
        }

        // Get other elements height
        siblings.each(function(i) {
          finalSize += $(this).outerHeight(true);
        });

        // Setup
        container[animate ? 'animate' : 'css']({ marginTop: -Math.round((finalSize/2))+'px' });
      }
    };

    /**
    * Function to display error messages
    * @param string message the error to display
    */
    function displayError(message) {
      // clear any other messages
      formWrapper.clearMessages();
      // Show message
      var message = formWrapper.message(message, {
        append: false,
        arrow: 'bottom',
        classes: ['red-gradient', 'align-center'],
        closable: false,            
        animate: false          // We'll do animation later, we need to know the message height first
      });

      // Vertical centering (where we need the message height)
      centerForm(currentForm, true, 'fast');

      // Watch for closing and show with effect
      message.bind('endfade', function(event) {
        // This will be called once the message has faded away and is removed
        centerForm(currentForm, true, message.get(0));

      }).hide().slideDown('fast');
    };

    /**
    * Function to display loading messages
    * @param string message the message to display
    */
    function displayLoading(message) {
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
      message.bind('endfade', function(event) {
        // This will be called once the message has faded away and is removed
        centerForm(currentForm, true, message.get(0));

      }).hide().slideDown('fast');
    };
    
    $('#form-activate-pro-button').hide();
    
    // show the login page after it finishes loading
    $('#login-container').attr('style', 'visibility:normal');
    
    // if action=register, show register form on load
    var action = '<?php echo (isset($_GET['action']) && $_GET['action'] != NULL) ? $_GET['action'] : NULL; ?>';
    if (action == 'register') { 
      $('#form-activate-pro-button').click();
      $('#form-switch').hide();
    }
    
    /**
    * Function to prevent default action
    * @param object the event 
    */      
    function preventDefault(e) {
      e.preventDefault();
    }
        
  });    
</script>