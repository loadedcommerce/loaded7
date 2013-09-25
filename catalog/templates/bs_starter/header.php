<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: header.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--header.php start-->
<div class="navbar navbar-inverse" role="navigation">   
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only"><?php echo $lC_Language->get('text_toggle_navigation'); ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
        <?php 
        if ($lC_Customer->isLoggedOn()) { 
          echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL') , '">' . $lC_Language->get('text_sign_out') . '</a></li>';
        } 
        ?>
        <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
        <li><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>"><?php echo $lC_Language->get('text_checkout'); ?></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php echo $lC_Template->getTopCategoriesSelection(); ?>
          </ul>
        </li>
      </ul>
      <div class="pull-right mid-margin-top large-margin-right">
        <ul class="language-menu">
          <?php echo $lC_Template->getLanguageSelection(true, false, ''); ?>
        </ul>
      </div>   
      <?php if ($lC_Template->getBranding('chat_code') != '') { ?>
        <div class="pull-right mid-margin-top large-margin-right">
          <?php echo $lC_Template->getBranding('chat_code'); ?>
        </div>  
      <?php } ?>    
    </div>
  </div>
</div>

<div class="page-header">
  <div class="container">
    <div class="row">
      <div class="col-sm-3 col-lg-3">
        <h1 class="logo">
          <a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>">
          <?php 
            if ($lC_Template->getBranding('site_image') != '') {
              echo '<img alt="' . STORE_NAME . '" src="' . DIR_WS_IMAGES . 'branding/' . $lC_Template->getBranding('site_image') . '" />';
            } else { 
              echo STORE_NAME; 
            }
          ?>
          </a>
        </h1>
        <?php if ($lC_Template->getBranding('slogan') != '') { ?>
          <p class="slogan clear-both"><?php echo $lC_Template->getBranding('slogan'); ?></p>
        <?php } ?>
      </div>
      <div class="col-sm-9 col-lg-9">
        <div class="row">
          <div class="text-center col-sm-8 col-lg-8 small-margin-top">
            <form role="form" class="form-inline" name="search" id="search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get">
              <div class="input-append">
                <input type="text" class="form-control search-query" name="keywords" id="keywords" style="width:50%; display:inline;"><?php echo lc_draw_hidden_session_id_field(); ?>
                <button type="submit" class="btn"><?php echo $lC_Language->get('button_search'); ?></button>
              </div>
            </form>
          </div>  
          <div class="col-sm-4 col-lg-4">
            <p class="text-right margin-top">
              <?php
                if ($lC_Template->getBranding('sales_email') != '' || $lC_Template->getBranding('sales_phone') != '') {
                  echo '<div>';
                  if ($lC_Template->getBranding('sales_email') != '') {
                    echo $lC_Template->getBranding('sales_email');
                  }
                  if ($lC_Template->getBranding('sales_phone') != '') {
                    echo ' | ' . $lC_Template->getBranding('sales_phone');
                  }
                  echo '</div>';
                }
              ?>
              <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'cart', 'NONSSL'); ?>">View Cart</a> | Total: <?php echo $lC_Currencies->format($lC_ShoppingCart->getSubTotal()); ?> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>)
            </p>
          </div>
        </div>
      </div>       
    </div>
    <div class="row small-margin-top">
      <?php
      if ($lC_Services->isStarted('breadcrumb')) {
        echo '<ol class="breadcrumb">' . $lC_Breadcrumb->getPathList() . '</ol>' . "\n";
      }
      ?>  
    </div>
    <script>
    $(document).ready(function() {
      $(".breadcrumb li").each(function(){
        if ($(this).is(':last-child')) {
          $(this).addClass('active');
        }
      });    
    });
    </script>
  </div>
</div>
<!--header.php end-->