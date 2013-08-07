<?php
  /*
  $Id: lost_password.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
//$_SESSION['user_not_exists'] = null;
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="mid-margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <?php if (isset($_SESSION['user_not_exists']) && $_SESSION['user_not_exists'] === true) { ?>
        <form id="form-no-user" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=lost_password'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('title_lost_password'); ?>?">
          <p class="small-margin-left small-margin-right">
            <?php echo $lC_Language->get('text_lost_password_no_user'); ?>
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="password_email" id="password_email" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_password_email'); ?>" autocomplete="off"></li>
          </ul>
          <p class="full-width"><button type="submit" class="button glossy green-gradient full-width" id="no-user"><?php echo $lC_Language->get('button_submit'); ?></button></p>
        </form>
        <?php } else { ?>
        <form id="form-lost-password" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=password_change'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center"><?php echo $lC_Language->get('heading_lost_password'); ?></h3>
          <ul class="inputs black-input large">
            <li>
              <span class="icon-key mid-margin-right"></span>
              <input type="text" name="key" id="key" value="<?php echo (isset($_GET['key']) && $_GET['key'] != '') ? $_GET['key'] : ''; ?>" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_manual_key_entry'); ?>" autocomplete="off">
              <input type="hidden" name="email" id="email" value="<?php echo (isset($_SESSION['user_confirmed_email'])) ? $_SESSION['user_confirmed_email'] : $_GET['email']; ?>">
            </li>
          </ul>
          <p class="small-margin-left no-margin-top">
            <?php echo $lC_Language->get('text_lost_password_key_instructions_1'); ?>
            <b><?php echo $_SESSION['user_confirmed_email']; ?></b>
          </p>
          <p class="small-margin-left"><?php echo $lC_Language->get('text_lost_password_key_instructions_2'); ?></p>
          <p class=" align-center large-margin-bottom margin-top">
            <button type="button" class="button glossy grey-gradient float-left" onclick="javascript:location.href='<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>';"><?php echo $lC_Language->get('button_cancel'); ?></button>
            <button type="submit" class="button glossy green-gradient float-right"><?php echo $lC_Language->get('button_submit'); ?></button>
          </p>
          <p>&nbsp;</p>
        </form>
        <?php } ?>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
  $(document).ready(function() {
    // Elements
    $('body').removeClass('clearfix with-menu with-shortcuts');
    $('html').addClass('linen');

    var doc = $('html').addClass('js-login'),
    container = $('#container'),
    formWrapper = $('#form-wrapper'),
    formBlock = $('#form-block'),
    formViewport = $('#form-viewport'),
    forms = formViewport.children('form'),

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
    $('#form-lost-password').submit(function(event) {
      // Values
      var key = $.trim($('#key').val()),
          email = $.trim($('#email').val());

      // Remove previous messages
      formWrapper.clearMessages();

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (key.length === 0) {
        // Display message
        displayError('<?php echo $lC_Language->get('text_enter_key'); ?>');
        return false;
      } /*else if (email.length === 0) {
        // Display message
        displayError('<?php echo $lC_Language->get('text_email_missing'); ?>');
        return false;
      } */else {
        // Remove previous messages
        formWrapper.clearMessages();

        // Show progress 
        displayLoading('<?php echo $lC_Language->get('ms_authenticating'); ?>');    

        // Stop normal behavior
        $("#form-lost-password").bind("submit", preventDefault(event));

        var nvp = $("#form-lost-password").serialize();
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=lostPasswordConfirmKey&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) { 
              $("#form-lost-password").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            displayError('<?php echo $lC_Language->get('ms_error_key_invalid'); ?>');   
            return false;
          }              
        );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      }
    });
    
    $('#form-no-user').submit(function(event) {
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
        return false;
      } else {
        // Remove previous messages
        formWrapper.clearMessages();

        // Show progress 
        displayLoading('<?php echo $lC_Language->get('ms_authenticating'); ?>');    

        // Stop normal behavior
        $("#form-no-user").bind("submit", preventDefault(event));

        var nvp = $("#form-no-user").serialize();
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=lostPasswordConfirmEmail&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) {
              $("#form-no-user").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            displayError('<?php echo $lC_Language->get('ms_error_user_invalid'); ?>');   
            return false;
          }              
        );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      }
    });
    
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
        container[animate ? 'animate' : 'css']({ marginTop: -Math.round(finalSize/2)+'px' });
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

    /**
    * Function to prevent default action
    * @param object the event 
    */      
    function preventDefault(e) {
      e.preventDefault();
    }
  });
</script>