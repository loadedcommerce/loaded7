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
require($lC_Vqmod->modCheck('templates/bs_starter/classes/output.php'));
$code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'bs_starter';
if (!defined('DIR_WS_TEMPLATE')) define('DIR_WS_TEMPLATE', DIR_WS_CATALOG . 'templates/' . $code . '/');
if (!defined('DIR_FS_TEMPLATE')) define('DIR_FS_TEMPLATE', DIR_FS_CATALOG . 'templates/' . $code . '/');
if (!defined('DIR_WS_TEMPLATE_IMAGES')) define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_CATALOG . 'templates/' . $code . '/images/');  
?>
<!DOCTYPE html>
<html lang="<?php echo substr(strtolower($lC_Language->getCode()), 0, 2); ?>">
  <head>
    <meta charset="utf-8">
    <!-- meta tags -->
    <?php echo $lC_Template->getPageTags();?>
    <!-- meta tags -->
    <meta name="author" content="Loaded Commerce">
    <base href="<?php echo lc_href_link(null, null, 'AUTO', false); ?>" />
    <!-- include open graph rags -->
    <?php echo $lC_Template->getPageOGPTags(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="templates/bs_starter/css/bootstrap.css">
    <link rel="stylesheet" href="templates/bs_starter/css/bootstrap-datepicker.css">
    
    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="templates/bs_starter/css/template.css?v=1">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
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
    <div id="loaded7" class="loadedcommerce-main-wrapper">
      <!--[if lt IE 7]>
          <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
      <![endif]-->  
      <?php
      // page header
      if ($lC_Template->hasPageHeader()) {
        if (file_exists('templates/' . $lC_Template->getCode() . '/header.php')) {
          include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/header.php'));
        }
      }      

      // set the format; 1, 2, or 3 columns
      $left = $lC_Template->getBoxModules('left');
      $right = $lC_Template->getBoxModules('right');
      
      if (!empty($left) && !empty($right)) { // 3 cols
        $box_class = 'col-sm-3 col-lg-3';
        $content_class = 'col-sm-6 col-lg-6';
        $_SESSION['content_span'] = '6';        
      } else if (!empty($left) && empty($right)) { // 2 cols left
        $box_class = 'col-sm-3 col-lg-3';
        $content_class = 'col-sm-9 col-lg-9'; 
        $_SESSION['content_span'] = '9';       
      } else if (empty($left) && !empty($right)) { // 2 cols right
        $box_class = 'col-sm-3 col-lg-3';
        $content_class = 'col-sm-9 col-lg-9';
        $_SESSION['content_span'] = '9';
      } else {
        $box_class = '';
        $content_class = 'col-sm-12 col-lg-12'; // 1 col
        $_SESSION['content_span'] = '12';
      }
      ?>
      <div id="content-container" class="container">
        <div class="row"> 
          <!--left column -->
          <?php if (!empty($left)) echo '<div id="content-left-container" class="' . $box_class . '">' . $lC_Template->getInfoBoxHtml('left') . '</div>' . "\n"; ?>
             
          <!--content start-->  
          <div id="content-center-container" class="<?php echo $content_class; ?>">
            <?php
            if ($lC_MessageStack->size('header') > 0) {
              echo '<div class="alert alert-danger">' . $lC_MessageStack->get('header') . '</div>';
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
            ?>
            <div id="content-center-main-container">
              <?php
              // main content
              if ($lC_Template->getCode() == DEFAULT_TEMPLATE) {
                include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
              } else {
                if (file_exists('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename())) {
                  include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                } else {
                  include($lC_Vqmod->modCheck('templates/' . DEFAULT_TEMPLATE . '/content/' . $lC_Template->getGroup() . '/' . $lC_Template->getPageContentsFilename()));
                }
              }
              ?>              
            </div>
            <?php
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
            
          <!--right column-->
          <?php if (!empty($right)) echo '<div id="content-right-container" class="' . $box_class . '">' . $lC_Template->getInfoBoxHtml('right') . '</div>' . "\n"; ?>
                    
        </div> <!-- end row --> 
        
        <?php
        // page footer
        if ($lC_Template->hasPageFooter()) {
          if (file_exists('templates/' . $lC_Template->getCode() . '/footer.php')) {
            include($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '/footer.php'));
          }            

          if ($lC_Services->isStarted('banner') && $lC_Banner->exists('footer')) {
            echo $lC_Banner->display();
          }     
        }

        if ( $lC_Template->showDebugMessages() && ($lC_MessageStack->size('debug') > 0) ) {
          echo '<div id="debug-info-container" style="display:none;" class="alert alert-warning"><span></span></div>';
        }         
        ?>       
      </div>  
      <!-- Enable responsive features in IE8 with Respond.js (https://github.com/scottjehl/Respond) -->
      <script src="ext/jquery/respond.min.js"></script>    
      
      <!-- Core JS -->
      <script src="ext/bootstrap/bootstrap-datepicker.js"></script>
      <script src="ext/jquery/jquery.loadmask.js"></script>
      
      <?php 
      // core js
      if (file_exists('includes/javascript/general.js.php')) {
        $lC_Template->addJavascriptPhpFilename('includes/javascript/general.js.php'); 
      }
      // template specific js
      if (file_exists('templates/' . $lC_Template->getCode() . '/javascript/general.js.php')) {
        $lC_Template->addJavascriptPhpFilename('includes/javascript/general.js.php'); 
      }
      ?>      
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
    </div>
  </body>
</html>