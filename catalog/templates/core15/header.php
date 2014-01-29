<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: header.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--header.php start-->     
<div class="topnav mid-margin-bottom">
  <div class="container">
    <div class="pull-right">
      <ul class="locale-menu nav navbar-nav">
        <li class="dropdown">
          <?php 
            echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">' . 
                    $lC_Language->showImage($value['code'], '18', '12', 'class="locale-header-icon"') . 
                 '  <span class="locale-header-currency">' . 
                      $lC_Currencies->getCode() . 
                 '    (' . $lC_Currencies->getSessionSymbolLeft() . ')' . 
                 '  </span>' .  
                 '  <b class="caret"></b>' . 
                 '</a>'; 
          ?>
          <ul class="dropdown-menu locale-header-dropdown">
            <?php echo lC_Template_output::getTemplateLanguageSelection(true, true, ''); ?>
            <li role="presentation" class="divider"></li>
            <?php echo lC_Template_output::getTemplateCurrenciesSelection(true, true, ''); ?>
          </ul>
        </li>
      </ul>
      <ul class="account-menu nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">My Account <b class="caret"></b></a>
          <ul class="dropdown-menu account-dropdown">
            <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('text_sign_in'); ?></a></li>
            <?php 
            if ($lC_Customer->isLoggedOn()) { 
              echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL') , '">' . $lC_Language->get('text_sign_out') . '</a></li>';
            } 
            ?>
            <li><a href="<?php echo lc_href_link(FILENAME_INFO, 'contact', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_contact'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>"><?php echo $lC_Language->get('text_checkout'); ?></a></li>
          </ul>
        </li>
      </ul>
      <ul class="cart-menu nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-shopping-cart white small-margin-right"></i> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>) Items <b class="caret"></b>
          </a>
          <ul class="dropdown-menu cart-dropdown">
            <li style="white-space: nowrap;">
              <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'cart', 'NONSSL'); ?>">View Cart | Total: <?php echo $lC_Currencies->format($lC_ShoppingCart->getSubTotal()); ?> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>)</a>
            </li>
          </ul>
        </li>
      </ul>   
      <?php if ($lC_Template->getBranding('chat_code') != '') { ?>
      <ul class="nav navbar-nav">
        <li>
          <?php echo $lC_Template->getBranding('chat_code'); ?>
        </li>
      </ul>  
      <?php } ?>
    </div>
  </div>
</div>
<div class="page-header">
  <div class="container">
    <div class="row mid-margin-bottom">
      <div class="col-sm-12 col-lg-6">
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
      <div class="col-sm-12 col-lg-6">
        <div class="col-sm-12 col-lg-12 text-right">
          <?php 
            if ($lC_Template->getBranding('sales_phone') != '') { 
              echo '<i class="fa fa-phone"></i> ' . $lC_Template->getBranding('sales_phone'); 
            } 
          ?>&nbsp;
          <?php 
            if ($lC_Template->getBranding('sales_email') != '') { 
              echo '<i class="fa fa-envelope"></i> ' . $lC_Template->getBranding('sales_email'); 
            } 
          ?>
        </div>  
      </div>       
    </div>
    <div class="navbar navbar-inverse no-margin-bottom" role="navigation">   
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only"><?php echo $lC_Language->get('text_toggle_navigation'); ?></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>    
      <div class="no-margin-bottom">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav col-lg-10">
            <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
            <?php echo lC_Template_output::getCategoryNav(); ?>
          </ul>
          <div class="text-right small-margin-top small-margin-bottom small-margin-right-neg">
            <button type="button" class="btn btn-success btn-sm">Checkout</button>  
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Search</button>
          </div>  
        </div>
      </div>
    </div>
    <div class="small-margin-top">
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