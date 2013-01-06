<?php global $lC_Language, $lC_Template; ?>
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
 //     formSwitch = $('<div id="form-switch"><span class="button-group"></span></div>').appendTo(formBlock).children(),
      formSwitch = '',

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

      /*
      * Login
      * These functions will handle the login process through AJAX
      */
      $('#form-login').submit(function(event) {
          // Values
          var login = $.trim($('#user_name').val()),
          pass = $.trim($('#user_password').val());

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
      $('#form-password').submit(function(event) {
          // Values
          var mail = $.trim($('#mail').val());

          // Check inputs
          if (mail.length === 0)
            {
            // Display message
            displayError('Please fill in your email');
          }
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

            // Stop normal behavior
            event.preventDefault();

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
      $('#form-register').submit(function(event) {
          // Values
          var name = $.trim($('#name-register').val()),
          mail = $.trim($('#mail-register').val()),
          login = $.trim($('#login-register').val()),
          pass = $.trim($('#pass-register').val());

          // Remove previous messages
          formWrapper.clearMessages();

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

            // Stop normal behavior
            event.preventDefault();

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
      forms.each(function(i) {
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
/*          
          form.data('button', $('<a href="#'+this.id+'" class="button anthracite-gradient'+color+(active ? ' active' : '')+'">'+this.title+'</a>')
            .appendTo(formSwitch)
            .data('form', form));
*/
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
/*
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
          } else {
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
/*
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
*/
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

      /**
      * Function to prevent default action
      * @param object the event 
      */      
      function preventDefault(e) {
          e.preventDefault();
      }      
  });
</script>