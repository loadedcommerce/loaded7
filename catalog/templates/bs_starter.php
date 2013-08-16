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
    <link rel="stylesheet" href="templates/bs_starter/css/bootstrap.css">
    <link rel="stylesheet" href="templates/bs_starter/css/bootstrap-responsive.css">
    
    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="templates/bs_starter/css/template.css?v=1">
    <link rel="stylesheet" href="templates/bs_starter/css/font-awesome.css?v=1">
    <link rel="stylesheet" href="templates/bs_starter/css/jquery.mCustomScrollbar.css?v=1">
    <link rel="stylesheet" type="text/css" href="ext/jquery/thickbox/thickbox.css?v=1" />
    <link rel="stylesheet" href="templates/bs_starter/css/loadmask.css?v=1">     

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
      // set the format; 1, 2, or 3 columns
      $left = $lC_Template->getBoxModules('left');
      $right = $lC_Template->getBoxModules('right');
      
      if (!empty($left) && !empty($right)) { // 3 cols
        $box_class = 'span3';
        $content_class = 'span6';        
      } else if (!empty($left) && empty($right)) { // 2 cols left
        $box_class = 'span3';
        $content_class = 'span9';        
      } else if (empty($left) && !empty($right)) { // 2 cols right
        $box_class = 'span3';
        $content_class = 'span9';
      } else {
        $box_class = '';
        $content_class = 'span12';
      }
      
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
      <div class="container">
        <div class="row-fluid"> 

          <!--left column start-->
          <?php
          if (!empty($content_left)) {
            echo '<div id="left-column" class="' . $box_class . '">' . $content_left . '</div>'; 
          }             
          ?>
          <!--left column end--> 
             
          <!--content start-->  
          <div id="main-content-container" class="<?php echo $content_class; ?>">
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
          <!--content end-->  
            
          <!--right column start-->
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
            echo '<div id="right-column" class="' . $box_class . '">' . $content_right . '</div>';
          }             
          ?>
          <!--right column end-->
          
        </div> <!-- end row-fluid --> 
      </div> <!-- end container -->
      
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
    <script src="ext/jquery/general.js.php"></script>
    
    <!-- gMap PLUGIN -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script src="ext/jquery/jquery.gmap.js"></script>

    <script src="ext/jquery/jquery.validate.min.js"></script>
    <script src="ext/jquery/jquery.easing.1.3.js"></script>
    <script src="ext/jquery/jquery.hoverIntent.min.js"></script>
    <script src="ext/jquery/jquery.liveSearch.js"></script>
    <script src="ext/jquery/thickbox/thickbox-compressed.js"></script>
    <script src="ext/datepicker/datepicker.js"></script>
    <script src="ext/jquery/jquery.loadmask.js"></script>
    <script src="ext/jquery/jquery.activity-indicator-1.0.0.min.js"></script>
    <!-- Template JS -->
    <script src="templates/bs_starter/javascript/placeholder.js" ></script>

    <!-- js for core logic -->
    <?php $lC_Template->addJavascriptPhpFilename('includes/javascript/general.js.php'); ?>
        
    <!-- js for template spcific logic -->
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
  </body>
</html>