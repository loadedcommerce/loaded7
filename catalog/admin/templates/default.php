<?php
/*
  $Id: default.php v1.0 2012-08-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  Based on the Developr theme
*/
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

  <title><?php echo STORE_NAME . ': ' . $lC_Language->get('administration_title') . ($lC_Template->hasPageTitle() ? ': ' . $lC_Template->getPageTitle() : ''); ?></title>
  <meta name="description" content="Loaded Commerce Shopping Cart">
  <meta name="author" content="Loaded Commerce">

  <!-- http://davidbcalhoun.com/2010/viewport-metatag -->
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <!-- For all browsers -->
  <link rel="stylesheet" href="templates/default/css/reset.css?v=1">
  <link rel="stylesheet" href="templates/default/css/style.css?v=1">
  <link rel="stylesheet" href="templates/default/css/colors.css?v=1">
  <link rel="stylesheet" media="print" href="templates/default/css/print.css?v=1">
  <!-- For progressively larger displays -->
  <link rel="stylesheet" media="only all and (min-width: 480px)" href="templates/default/css/480.css?v=1">
  <link rel="stylesheet" media="only all and (min-width: 768px)" href="templates/default/css/768.css?v=1">
  <link rel="stylesheet" media="only all and (min-width: 992px)" href="templates/default/css/992.css?v=1">
  <link rel="stylesheet" media="only all and (min-width: 1200px)" href="templates/default/css/1200.css?v=1">
  <!-- For Retina displays -->
  <link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="templates/default/css/2x.css?v=1">

  <!-- Webfonts -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>

  <!-- Additional styles -->
  <link rel="stylesheet" href="templates/default/css/styles/agenda.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/dashboard.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/form.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/modal.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/progress-slider.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/switches.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/table.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/datepicker.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/breadcrumb.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/fileuploader.css?v=1">
  <!-- DataTables -->
  <link rel="stylesheet" href="templates/default/css/styles/jquery.dataTables.css?v=1">
  <link rel="stylesheet" href="templates/default/css/styles/jquery.dataTables-tableTools.css?v=1">
  <!-- loading mask -->
  <link rel="stylesheet" href="templates/default/css/styles/jquery.loadmask.css?v=1">
  <!-- Google code prettifier -->
  <link rel="stylesheet" href="../ext/jquery/google-code-prettify/sunburst.css?v=1">

  <!-- Load Page Specific CSS -->
  <?php echo $lC_Template->loadPageCss($lC_Template->getModule()); ?>

  <!-- Modernizr is always at top and first -->
  <script src="../ext/jquery/modernizr.custom.js"></script>
  <script src="../ext/jquery/jquery-1.8.3.min.js"></script>

  <!-- For Modern Browsers -->
  <link rel="shortcut icon" href="templates/default/img/favicons/favicon.png?v=2">
  <!-- For everything else -->
  <link rel="shortcut icon" href="templates/default/img/favicons/favicon.ico?v=2">
  <!-- For retina screens -->
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="templates/default/img/favicons/apple-touch-icon-retina.png">
  <!-- For iPad 1-->
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="templates/default/img/favicons/apple-touch-icon-ipad.png">
  <!-- For iPhone 3G, iPod Touch and Android -->
  <link rel="apple-touch-icon-precomposed" href="templates/default/img/favicons/apple-touch-icon.png">

  <!-- iOS web-app metas -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <!-- Startup image for web apps -->
  <link rel="apple-touch-startup-image" href="templates/default/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
  <link rel="apple-touch-startup-image" href="templates/default/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
  <link rel="apple-touch-startup-image" href="templates/default/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

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

<body class="clearfix with-menu with-shortcuts">
  
  <!-- Prompt IE 6 users to install Chrome Frame -->
  <!--[if lt IE 7]><p class="message red-gradient simpler">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

  <?php
  require('templates/default/classes/output.php');
  $output = new output();
  
  if ($lC_Template->hasPageHeader()) {
    include('templates/default/header.php');
  }

  if ($lC_Template->hasPageWrapper()) {
    ?>
    <!-- Button to open/hide menu -->
    <a href="#" id="open-menu"><span>Menu</span></a>

    <!-- Button to open/hide shortcuts -->
    <a href="#" id="open-shortcuts"><span class="icon-thumbs"></span></a>
    <?php
  }

  // load the main content
  if ($lC_Template->isAuthorized($lC_Template->getModule())) {
    require('includes/applications/' . $lC_Template->getModule() . '/pages/' . $lC_Template->getPageContentsFilename());
  } else {
    // not authorized to view
    require('includes/applications/error_pages/pages/main.php');
  }

  if ($lC_Template->hasPageWrapper()) {
    ?>
    <!-- Side tabs shortcuts -->
    <ul id="shortcuts" role="complementary" class="children-tooltip tooltip-right" style="z-index:2;">
      <li id="sc-dashboard" class="current"><a href="./" class="shortcut-dashboard" title="<?php echo $lC_Language->get('icon_dashboard'); ?>"><?php echo $lC_Language->get('icon_dashboard'); ?></a></li>
      <li id="sc-orders"><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders'); ?>" class="shortcut-orders" title="<?php echo $lC_Language->get('icon_orders'); ?>"><?php echo $lC_Language->get('icon_orders'); ?></a></li>
      <li id="sc-customers"><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'customers'); ?>" class="shortcut-customers" title="<?php echo $lC_Language->get('icon_customers'); ?>"><?php echo $lC_Language->get('icon_customers'); ?></a></li>
      <!-- li id="sc-content"><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'categories'); ?>" class="shortcut-content" title="<?php echo $lC_Language->get('icon_content'); ?>"><?php echo $lC_Language->get('icon_content'); ?></a></li -->
      <li id="sc-products"><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'products'); ?>" class="shortcut-products" title="<?php echo $lC_Language->get('icon_products'); ?>"><?php echo $lC_Language->get('icon_products'); ?></a></li>
      <li id="sc-marketing"><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'banner_manager'); ?>" class="shortcut-marketing" title="<?php echo $lC_Language->get('icon_marketing'); ?>"><?php echo $lC_Language->get('icon_marketing'); ?></a></li>
      <!-- li id="sc-store" <?php echo (($_SESSION['admin']['access']['configuration'] > 0) ? NULL : 'class="hidden"'); ?>><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'store'); ?>" class="shortcut-store" title="<?php echo $lC_Language->get('icon_app_store'); ?>"><?php echo $lC_Language->get('icon_app_store'); ?></a></li -->
      <li id="sc-reports"><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'statistics'); ?>" class="shortcut-reports" title="<?php echo $lC_Language->get('icon_reports'); ?>"><?php echo $lC_Language->get('icon_reports'); ?></a></li>
    </ul>

    <!-- Sidebar/drop-down menu -->
    <section id="menu" role="complementary">

      <!-- This wrapper is used by several responsive layouts -->
      <div id="menu-content">
        
        <header>
          <div class="headerInner">
            <a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>" title="View The Store" class="with-tooltip tooltip-bottom grey" target="_blank"><?php echo substr(HTTP_SERVER, 7, 29); ?></a>
          </div>  
        </header>
        
        <div id="profile">
          <div id="profileInner">                
            <div class="profile50">
              <div id="profileLeft">
                <img src="templates/default/img/user.png" width="64" height="64" alt="User name" class="user-icon">
                <?php echo $lC_Language->get('text_hello'); ?>
                <span class="name"><?php echo $_SESSION['admin']['firstname']; ?><br />
                <?php echo $_SESSION['admin']['lastname']; ?></span>
                <small class="margin-left"><?php echo $lC_Language->get('profile_slate_edit_logout'); ?></small>
              </div>
            </div>
            <div class="profile50">
              <div id="profileRight">
                <div class="profile-right-fourth">
                  <a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'administrators&aID=' . $_SESSION['admin']['id']); ?>">
                    <img src="templates/default/img/user.png" width="32" height="32"><br />
                    <small><?php echo $lC_Language->get('profile_slate_edit_profile'); ?></small>
                  </a>
                </div>
                <div class="profile-right-fourth">
                  <a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'administrators&aID=' . $_SESSION['admin']['id']); ?>">
                    <div style="height:8px;"></div>
                    <span class="icon icon-lock icon-size3"></span>
                    <div style="height:12px;"></div>
                    <small><?php echo $lC_Language->get('profile_slate_change_password'); ?></small>
                  </a>
                </div>
                <div class="profile-right-fourth">
                  <a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login&action=logoff'); ?>" class="confirm">
                    <div style="height:8px;"></div>
                    <span class="icon icon-cross-round icon-size3"></span>
                    <div style="height:12px;"></div>
                    <small><?php echo $lC_Language->get('profile_slate_logout'); ?></small>
                  </a>
                </div>
                <div class="profile-right-fourth prf-bottom">
                  <a href="#" id="profileBack">
                    <span class="icon icon-reply icon-size3"></span>
                    <div style="height:5px;"></div>
                    <small><?php echo $lC_Language->get('button_back'); ?></small>
                  </a>
                </div>                
              </div>
            </div>
          </div>
        </div>

        <!-- Big menu small navigation -->
        <ul id="access" class="children-tooltip">
          <li id="li-search"><a onclick="toggleSubMenu('search');" href="#" title="Search"><span class="icon-search"></span></a></li>
          <li id="li-add"><a onclick="toggleSubMenu('add');" href="#" title="Quick Add"><span class="icon-plus"></span></a></li>
          <li id="li-messages"><a onclick="toggleSubMenu('messages');" href="#" title="Messages"><span class="icon-mail"></span></a></li>
          <li id="li-settings" <?php echo (($_SESSION['admin']['access']['configuration'] > 0) ? NULL : 'class="disabled"'); ?>><a href="#" onclick="toggleChildMenu('settings');" title="Settings"><span id="sp-settings" class="icon-gear"><div></div></span></a></li>
        </ul>

        <div id="messagesContainer" style="display:none;">
          <section>
          Messages
          </section>
        </div>      
        
        <div id="addContainer" style="display:none;">
          <section>
          Quick Add
          </section>
        </div>  
        
        <div id="searchContainer" style="display:none;">
          <section>
          Search
          </section>
        </div> 
                
        <div id="settingsContainer" style="display:none;">
          <section class="navigable">
            <ul id="big-menu-settings-ul" class="big-menu blue-gradient">
              <?php echo $output->drawBigMenu('configuration', 'blue-gradient'); ?>
              <!-- li><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'store'); ?>"><?php echo $lC_Language->get('menu_title_addons'); ?></a></li>
              <li><a href="http://www.loadedcommerce.com/additional-serials-p-360.html?CDpath=216_295" target="_blank"><?php echo $lC_Language->get('menu_title_license_management'); ?></a></li>
              <li><a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'updater'); ?>"><?php echo $lC_Language->get('menu_title_core_update'); ?></a></li -->
              <li><a href="http://support.loadedcommerce.com" target="_blank"><?php echo $lC_Language->get('menu_title_report_issues'); ?></a></li>
              <li><a href="http://www.loadedcommerce.com/support-memberships-pc-175_198.html" target="_blank"><?php echo $lC_Language->get('menu_title_get_help'); ?></a></li>
            </ul>
          </section>
          <section>
            <div id="versionInfo" class="anthracite-gradient glossy align-center white"><?php echo $lC_Language->get('text_version_info'); ?></div>
            <p id="versionBg" class="white"><span id="versionText"><?php echo utility::getVersion(); ?></span></p>
          </section>
        </div>
        
        <!-- main big menu -->
        <div id="mainMenuContainer">
          <section class="navigable">
            <ul id="big-menu-ul" class="big-menu">
              <?php echo $output->drawBigMenu(); ?>
            </ul>
          </section>
        </div>
      </div>
      
      <footer id="menu-footer">
        <p class="w-mark"></p>
      </footer>      
    </section>
    <!-- End content wrapper -->
    <?php
  }

  if ($lC_Template->hasPageFooter()) {
    include('templates/default/footer.php');
  }
  ?>

  <!-- Load page specific responsive javascript -->
  <?php $lC_Template->loadPageResponsiveScript($lC_Template->getModule()); ?>
                             
  <!-- Include template general.js.php -->
  <?php if (file_exists('templates/default/general.js.php')) include('templates/default/general.js.php'); ?>

  <!-- JavaScript at the bottom for fast page loading -->
  <script src="../ext/jquery/tinycon.min.js"></script>
  <script src="../ext/jquery/jquery.validate.pack.js"></script>
  <script src="../ext/jquery/jquery.tinysort.min.js"></script>
  <script src="../ext/jquery/jquery.easing.1.3.js"></script>
  <script src="../ext/jquery/jquery.jBreadCrumb.1.1.js"></script>
  <script src="../ext/jquery/fileuploader.js"></script> 
  <script src="../ext/jquery/jquery-ui-sortable.min.js"></script> 
  <script src="../ext/jquery/glDatePicker/glDatePicker.min.js"></script>
  <script src="../ext/jquery/DataTables/media/js/jquery.dataTables.min.js"></script>
  <script src="../ext/jquery/DataTables/media/js/jquery.dataTables.reloadAjax.js"></script>
  <script src="../ext/jquery/jquery.details.min.js"></script>
  <script src="../ext/jquery/jquery.loadmask.js"></script>
  <!-- Template functions -->
  <script src="templates/default/js/setup.js"></script>
  <script src="templates/default/js/float.js"></script>
  <!-- script src="templates/default/js/accordions.js"></script -->
  <script src="templates/default/js/auto-resizing.js"></script>
  <script src="templates/default/js/input.js"></script>
  <script src="templates/default/js/message.js"></script>
  <script src="templates/default/js/collapsible.js"></script>
  <script src="templates/default/js/content-panel.js"></script>
  <script src="templates/default/js/table.js"></script>
  <script src="templates/default/js/navigable.js"></script>
  <script src="templates/default/js/modal.js"></script>
  <script src="templates/default/js/notify.js"></script>
  <script src="templates/default/js/scroll.js"></script>
  <script src="templates/default/js/progress-slider.js"></script>
  <script src="templates/default/js/tooltip.js"></script>
  <script src="templates/default/js/confirm.js"></script>
  <!-- script src="templates/default/js/agenda.js"></script -->
  <script src="templates/default/js/tabs.js"></script>    <!-- Must be loaded last -->
  <!-- Load page specific javascript -->
  <?php $lC_Template->loadPageScript($lC_Template->getModule()); ?>
</body>
</html>