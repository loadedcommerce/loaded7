<?php
/**
  @package    catalog::install
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main_page.php v1.0 2013-08-08 datazen $
*/
$template = 'main_page';
?>
<!DOCTYPE html>
<!--[if IEMobile 7]><html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
  <?php ini_set('default_charset', 'UTF-8'); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <title>Loaded Commerce Open Source E-Commerce Solutions</title>
  <meta name="description" content="Loaded Commerce Shopping Cart">
  <meta name="author" content="Loaded Commerce">

  <!-- http://davidbcalhoun.com/2010/viewport-metatag -->
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <!-- For all browsers -->
  <link rel="stylesheet" href="templates/css/reset.css?v=1">
  <link rel="stylesheet" href="templates/css/style.css?v=1">
  <link rel="stylesheet" href="templates/css/colors.css?v=1">
  <link rel="stylesheet" media="print" href="templates/css/print.css?v=1">
  <!-- For progressively larger displays -->
  <link rel="stylesheet" media="only all and (min-width: 480px)" href="templates/css/480.css?v=1">
  <link rel="stylesheet" media="only all and (min-width: 768px)" href="templates/css/768.css?v=1">
  <link rel="stylesheet" media="only all and (min-width: 992px)" href="templates/css/992.css?v=1">
  <link rel="stylesheet" media="only all and (min-width: 1200px)" href="templates/css/1200.css?v=1">
  <!-- For Retina displays -->
  <link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="templates/css/2x.css?v=1">

  <!-- Additional styles -->
  <link rel="stylesheet" href="templates/css/styles/form.css?v=1">
  <link rel="stylesheet" href="templates/css/styles/switches.css?v=1">

  <!-- Modernizr is always at top and first -->
  <script src="templates/js/modernizr.custom.js"></script>  
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  

  <!-- For Modern Browsers -->
  <link rel="shortcut icon" href="img/favicons/favicon.png">
  <!-- For everything else -->
  <link rel="shortcut icon" href="img/favicons/favicon.ico">
  <!-- For retina screens -->
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/favicons/apple-touch-icon-retina.png">
  <!-- For iPad 1-->
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/favicons/apple-touch-icon-ipad.png">
  <!-- For iPhone 3G, iPod Touch and Android -->
  <link rel="apple-touch-icon-precomposed" href="img/favicons/apple-touch-icon.png">

  <!-- iOS web-app metas -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <!-- Startup image for web apps -->
  <link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
  <link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
  <link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">

  <!-- Microsoft clear type rendering -->
  <meta http-equiv="cleartype" content="on">

  <!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
  <meta name="application-name" content="Loaded Commerce Admin">
  <meta name="msapplication-tooltip" content="Loaded Commerce Cross-Platform Admin Template">
  <meta name="msapplication-starturl" content="<?php echo $_SERVER['PHP_SELF']; ?>">
  <!-- These custom tasks are examples, you need to edit them to show actual pages -->
  <meta name="msapplication-task" content="name=Agenda;action-uri=http://www.loadedcommerce.com;icon-uri=http://www.loadedcommerce.com/favicon.ico">
  <meta name="msapplication-task" content="name=My profile;action-uri=http://www.loadedcommerce.com;icon-uri=http://www.loadedcommerce.com/favicon.ico">
</head>

<body class="full-page-wizard">

  <?php require('templates/pages/' . $page_contents); ?>
  
  <div class="margin-top" id="pageFooter">Copyright &copy; <?php echo @date("Y"); ?> <a href="http://www.loaded7.com" target="_blank">Loaded Commerce</a></div>

  <!-- JavaScript at the bottom for fast page loading -->
  <script src="templates/js/setup.js"></script>

  <!-- Template functions -->
  <script src="templates/js/input.js"></script>
  <script src="templates/js/message.js"></script>
  <script src="templates/js/notify.js"></script>
  <script src="templates/js/scroll.js"></script>
  <script src="templates/js/tooltip.js"></script>
  <script src="templates/js/wizard.js"></script>
  <script src="templates/js/jquery.validate.min.js"></script>

  <script>
    $(document).ready(function() {
        // Elements
      var form = $('.wizard'),

        // If layout is centered
        centered;

      // Handle resizing (mostly for debugging)
      function handleWizardResize() {
        centerWizard(false);
      };

      // Register and first call
      $(window).bind('normalized-resize', handleWizardResize);

      /*
       * Center function
       * @param boolean animate whether or not to animate the position change
       * @return void
       */
      function centerWizard(animate) {
        form[animate ? 'animate' : 'css']({ marginTop: Math.max(0, Math.round(($.template.viewportHeight-30-form.outerHeight())/2))+'px' });
      };

      // Initial vertical adjust
      centerWizard(false);

      // Refresh position on change step
      form.on('wizardchange', function() { centerWizard(true); });
      
      hideElements();

    });

    function hideElements() {  
      if ($.template.mediaQuery.name === 'mobile-portrait') { 
        $('.hide-on-mobile-portrait').hide();
        $('.hide-on-mobile').hide();
      } else if ($.template.mediaQuery.name === 'mobile-landscape') {  
        $('.hide-on-mobile-portrait').hide();
        $('.hide-on-mobile-landscape').hide();
        $('.hide-on-mobile').hide();
      } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
        $('.hide-on-tablet-portrait').hide();    
        $('.hide-on-tablet').hide();              
      } else if ($.template.mediaQuery.name === 'tablet-landscape') {  
        $('.hide-on-tablet-portrait').hide();
        $('.hide-on-tablet-landscape').hide();      
        $('.hide-on-tablet').hide();      
      }   
    }  
  </script>
</body>
</html>