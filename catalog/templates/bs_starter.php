<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: bs_starter.php v1.0 2013-08-08 datazen $
*/
require($lC_Vqmod->modCheck('templates/bs_starter/classes/bs_starter.php'));
$code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'bs_starter';
if (!defined('DIR_WS_TEMPLATE')) define('DIR_WS_TEMPLATE', DIR_WS_CATALOG . 'templates/' . $code . '/');
if (!defined('DIR_FS_TEMPLATE')) define('DIR_FS_TEMPLATE', DIR_FS_CATALOG . 'templates/' . $code . '/');
if (!defined('DIR_WS_TEMPLATE_IMAGES')) define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_CATALOG . 'templates/' . $code . '/images/');  
?>
<!DOCTYPE html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo STORE_NAME . ($lC_Template->hasPageTitle() ? ': ' . $lC_Template->getPageTitle() : ''); ?></title>
    <base href="<?php echo lc_href_link(null, null, 'AUTO', false); ?>" />
    <meta name="description" content="Loaded Commerce Shopping Cart">
    <meta name="author" content="Loaded Commerce">
    <!-- include open graph rags -->
    <?php echo $lC_Template->getPageOGPTags(); ?>
    <meta name="viewport" content="content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="ext/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="ext/bootstrap/css/bootstrap-responsive.css">
    
    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="templates/bs_default/css/font-awesome.min.css">
    <link rel="stylesheet" href="templates/bs_default/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="ext/jquery/thickbox/thickbox.css" />
    <link rel="stylesheet" href="templates/bs_default/css/loadmask.css?v=1">     

    <!--[if lt IE 7]><link href="assets/css/font-awesome-ie7.min.css" rel="stylesheet"><![endif]-->
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script><![endif]-->
    
    <!-- Load Page/Group Specific Tags -->
    <?php
      echo $lC_Template->loadCSS($lC_Template->getCode(), $lC_Template->getGroup());
      if ($lC_Template->hasPageTags()) {
        echo $lC_Template->getPageTags();
      }
    ?>
    
    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="templates/bs_starter/icons/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="templates/bs_starter/icons/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="templates/bs_starter/icons/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="templates/bs_starter/icons/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="templates/bs_starter/icons/apple-touch-icon-57-precomposed.png">
    
    <!-- jQuery is always at top -->
    <script src="ext/jquery/jquery-1.9.1.min.js"></script>
    <script src="ext/bootstrap/bootstrap.min.js"></script>
  </head>

  <body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->  
  
    <div class="wrapper">
      <?php
      //moved here to support mobile browse catalog button
      $content_left = '';
      if ($lC_Template->hasPageBoxModules()) {
        ob_start();
        foreach ($lC_Template->getBoxModules('left') as $box) {
          $lC_Box = new $box();
          $lC_Box->initialize();
          if ($lC_Box->hasContent()) {
            if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
              include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
            } else {
              if (file_exists('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php')) {
                include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
              } else {
                include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
              }
            }
          }
          unset($lC_Box);
        }
        $content_left = ob_get_contents();
        ob_end_clean();
      }
      // page header
      if ($lC_Template->hasPageHeader()) {
        if (file_exists('templates/' . $lC_Template->getCode() . '/header.php')) {
          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/header.php'));
        }
      }                
      ?>
      <div class="section_container">
        <section>
          <?php
          if ($lC_Services->isStarted('breadcrumb')) {
            ?>
            <!--Breadcrumb starts-->  
            <div id="breadCrumbContainer"> 
              <ul class="breadcrumb"><?php echo $lC_Breadcrumb->getPathList(); ?></ul>
            </div>
            <!--Breadcrumb Ends-->
            <?php
          }
          ?>
          <div class="main_content"> 
            <!--Left Side Nav Starts-->
            <?php
            if (!empty($content_left)) {
              echo '<div id="left_side_nav" class="sideNavBox colLeft">';
              echo $content_left;     
              echo '</div>'; 
            }             
            ?>
            <!--Left Side Nav Ends-->  
            <!--Main Content Starts-->  
            <div class="colMid">
              <?php
                if ($lC_MessageStack->size('header') > 0) {
                  echo '<!--Message Stack Header Starts-->';
                  echo $lC_MessageStack->get('header');
                  echo '<!--Message Stack Header Ends-->';
                }
                if ($lC_Template->hasPageContentModules()) {
                  foreach ($lC_Services->getCallBeforePageContent() as $service) {
                    $$service[0]->$service[1]();
                  }
                  foreach ($lC_Template->getContentModules('before') as $box) {
                    $lC_Box = new $box();
                    $lC_Box->initialize();
                    if ($lC_Box->hasContent()) {
                      if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                        include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                      } else {
                        if (file_exists('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php')) {
                          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        } else {
                          include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        }
                      }
                    }
                    unset($lC_Box);
                  }
                }
                if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                  include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                } else {
                  if (file_exists('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename())) {
                    include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                  } else {
                    include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                  }
                }
                if ($lC_Template->hasPageContentModules()) {
                  foreach ($lC_Services->getCallAfterPageContent() as $service) {
                    $$service[0]->$service[1]();
                  }
                  foreach ($lC_Template->getContentModules('after') as $box) {
                    $lC_Box = new $box();
                    $lC_Box->initialize();
                    if ($lC_Box->hasContent()) {
                      if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                        include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                      } else {
                        if (file_exists('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php')) {
                          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        } else {
                          include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/content/' . $lC_Box->getCode() . '.php'));
                        }
                      }
                    }
                    unset($lC_Box);
                  }
                } 
                ?>
            </div>
            <!--Main Content Ends-->  
            <!--Right Side Nav Starts-->
            <?php
            $content_right = '';
            if ($lC_Template->hasPageBoxModules()) {
              ob_start();
              foreach ($lC_Template->getBoxModules('right') as $box) {
                $lC_Box = new $box();
                $lC_Box->initialize();
                if ($lC_Box->hasContent()) {
                  if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                    include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
                  } else {
                    if (file_exists('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php')) {
                      include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
                    } else {
                      include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/modules/boxes/' . $lC_Box->getCode() . '.php'));
                    }
                  }
                }
                unset($lC_Box);
              }
              $content_right = ob_get_contents();
              ob_end_clean();
            }
            if (!empty($content_right)) {
              echo '<div id="right_side_nav" class="sideNavBox colRight">';
              echo $content_right;
              echo '</div>';
            }             
            ?>
            <!--Right Side Nav Ends-->
            <div style="clear: both;"></div>
          </div>
          <?php
            // the default css is set for both columns visible until now
            // if only one of the two columns is visible...
            if ( (!empty($content_left) && empty($content_right)) || (empty($content_left) && !empty($content_right)) ) {
          ?>
          <!-- added to assist in the control of the three column width dynamically based on left and right column content :: maestro -->
          <script>
            $(document).ready(function() {
              $(".main_content").find(".colMid").addClass("mid75");
            });
          </script>
          <?php
            // if both columns are empty...
            } else if (empty($content_left) && empty($content_right)) {
          ?>
          <script>
            $(document).ready(function() {
              $(".main_content").find(".colMid").addClass("mid100");
            });
          </script>
          <?php } ?> 
          <div style="clear: both;"></div>          
        </section>
      </div>
      
      <?php
      // page footer
      if ($lC_Template->hasPageFooter()) {
        if (file_exists('templates/' . $lC_Template->getCode() . '/footer.php')) {
          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/footer.php'));
        }            
        echo '<br />';
        if ($lC_Services->isStarted('banner') && $lC_Banner->exists('468x60')) {
          echo '<p align="center">' . $lC_Banner->display() . '</p>';
        }     
      }  
      if ( $lC_Template->showDebugMessages() && ($lC_MessageStack->size('debug') > 0) ) {
        echo '<div id="debugInfoContainer" style="display:none;" class="short-code msg info"><span></span></div>';
      }         
      ?>       
    </div>  
    <!-- Enable responsive features in IE8 with Respond.js (https://github.com/scottjehl/Respond) -->
    <script src="ext/jquery/respond.js"></script>    
    <!-- Core JS -->
    <script src="ext/jquery/jquery.validate.min.js"></script>
    <script src="ext/jquery/jquery.easing.1.3.js"></script>
    <script src="ext/jquery/jquery.hoverIntent.min.js"></script>
    <script src="ext/jquery/jquery.liveSearch.js"></script>
    <script src="ext/jquery/jquery.jBreadCrumb.1.1.js"></script>
    <script src="ext/jquery/thickbox/thickbox-compressed.js"></script>
    <script src="ext/datepicker/datepicker.js"></script>
    <script src="ext/jquery/jquery.loadmask.js"></script>
    <script src="ext/jquery/jquery.activity-indicator-1.0.0.min.js"></script>
    <!-- Template JS -->
    <script src="templates/bs_default/javascript/placeholder.js" ></script>
    
    <!-- main js.php for all site pages -->
    <?php $lC_Template->addJavascriptPhpFilename('templates/' . $lC_Template->getCode() . '/javascript/general.js.php'); ?>
    
    <!-- js loc: <?php echo 'templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '.js'; ?> -->
    <!-- js.php loc: <?php echo 'templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '/' . $lC_Template->getModule() . '.js.php'; ?> -->
    <?php
      // add group specific (.js) filenames to the array for hasJavascript function (acount, products, info, search, index etc)
      if (file_exists('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '.js')) {
        $lC_Template->addJavascriptFilename('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '.js');
      }
      // add module specific (.js.php) filenames to the array for hasJavascript function
      if (file_exists('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '/' . $lC_Template->getModule() . '.js.php')) {
        $lC_Template->addJavascriptPhpFilename('templates/' . $lC_Template->getCode() . '/javascript/' . $lC_Template->getGroup() . '/' . $lC_Template->getModule() . '.js.php');
      }
      if ($lC_Template->hasJavascript()) {
        $lC_Template->getJavascript();
      }
    ?>
    <script>
      $(document).ready(function(e) {
        var searchUrl = '<?php echo lc_href_link('rpc.php', 'action=search', 'AUTO'); ?>'  
        $('#liveSearchContainer input[name="q"]').liveSearch({url: searchUrl + '&q='});
      }); 
    </script>
  </body>
</html>