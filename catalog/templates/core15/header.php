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
  <div class="container topnav-container">
    <div class="pull-right margin-right">   
      <?php if ($lC_Template->getBranding('chat_code') != '') { ?>
      <ul class="chat-menu nav-item pull-right no-margin-bottom">
        <li>
          <?php echo $lC_Template->getBranding('chat_code'); ?>
        </li>
      </ul>  
      <?php } ?>
      <ul class="cart-menu nav-item pull-right no-margin-bottom">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-shopping-cart white small-margin-right"></i> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>) <span class="hide-on-mobile-portrait"><?php echo $lC_Language->get('text_items'); ?></span> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu cart-dropdown">
            <!-- going to change -->
            <li style="white-space: nowrap;">
              <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'cart', 'NONSSL'); ?>"><?php echo $lC_Language->get('button_view_cart'); ?> | <?php echo $lC_Language->get('text_total'); ?>: <?php echo $lC_Currencies->format($lC_ShoppingCart->getSubTotal()); ?> (<?php echo $lC_ShoppingCart->numberOfItems(); ?>)</a>
            </li>
          </ul>
        </li>
      </ul>
      <ul class="account-menu nav-item pull-right no-margin-bottom">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user white small-margin-right"></i> <i class="fa fa-bars fa-bars-mobile"></i><span class="hide-on-mobile-portrait"><?php echo $lC_Language->get('my_account'); ?></span> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu account-dropdown">
            <?php 
            if ($lC_Customer->isLoggedOn()) { 
              echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, 'logoff', 'SSL') , '">' . $lC_Language->get('text_sign_out') . '</a></li>';
            } else {
              echo '<li><a href="' . lc_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . $lC_Language->get('text_login') . '</a></li>';
            }
            ?>
            <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo $lC_Language->get('my_account'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'); ?>"><?php echo $lC_Language->get('text_my_orders'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>"><?php echo $lC_Language->get('text_address_book'); ?></a></li>
            <li><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password', 'SSL'); ?>"><?php echo $lC_Language->get('text_change_password'); ?></a></li>
          </ul>
        </li>
      </ul>
      <ul class="locale-menu nav-item pull-right no-margin-bottom">
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
        <div class="text-right show-on-mobile">
          <form role="form" class="form-inline" name="mobile-search" id="mobile-search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get">
            <span class="text-right">
              <button type="button" class="btn btn-sm cursor-pointer small-margin-right<?php echo (($lC_ShoppingCart->numberOfItems() > 0) ? ' btn-success' : ' btn-default disabled'); ?>" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'">Checkout</button>  
              <i class="fa fa-search navbar-search-icon cursor-pointer" onclick="window.location.href='<?php echo lc_href_link(FILENAME_SEARCH, '', 'NONSSL'); ?>'"></i>
              <input type="text" class="navbar-search" name="keywords" id="keywords" placeholder="<?php echo $lC_Language->get('button_search'); ?>"><?php echo lc_draw_hidden_session_id_field(); ?>
            </span>
          </form>
        </div>
      </div>    
      <div class="no-margin-bottom">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav col-lg-7">
            <li><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo $lC_Language->get('text_home'); ?></a></li>
            <?php echo lC_Template_output::getCategoryNav(); ?>
          </ul>
          <div class="text-right small-margin-top small-margin-bottom col-lg-5">
            <form role="form" class="form-inline hide-on-mobile" name="search" id="search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get">
              <span class="text-right">
                <?php
                  if ($lC_Template->getBranding('social_facebook_page') != '') {
                    echo lc_link_object(lc_href_link($lC_Template->getBranding('social_facebook_page'), '', 'NONSSL'), lc_image(DIR_WS_IMAGES . 'icons/fb-ico.png', 'title', null, null, 'class="small-margin-right"'), 'target="_blank"');
                  }
                  if ($lC_Template->getBranding('social_twitter') != '') {
                    echo lc_link_object(lc_href_link($lC_Template->getBranding('social_twitter'), '', 'NONSSL'), lc_image(DIR_WS_IMAGES . 'icons/tw-ico.png', 'title', null, null, 'class="small-margin-right"'), 'target="_blank"');
                  } 
                  if ($lC_Template->getBranding('social_pinterest') != '') {
                    echo lc_link_object(lc_href_link($lC_Template->getBranding('social_pinterest'), '', 'NONSSL'), lc_image(DIR_WS_IMAGES . 'icons/pn-ico.png', 'title', null, null, 'class="small-margin-right"'), 'target="_blank"');
                  } 
                  if ($lC_Template->getBranding('social_google_plus') != '') {
                    echo lc_link_object(lc_href_link($lC_Template->getBranding('social_google_plus'), '', 'NONSSL'), lc_image(DIR_WS_IMAGES . 'icons/gp-ico.png', 'title', null, null, 'class="small-margin-right"'), 'target="_blank"');
                  } 
                  if ($lC_Template->getBranding('social_youtube') != '') {
                    echo lc_link_object(lc_href_link($lC_Template->getBranding('social_youtube'), '', 'NONSSL'), lc_image(DIR_WS_IMAGES . 'icons/yt-ico.png', 'title', null, null, 'class="small-margin-right"'), 'target="_blank"');
                  } 
                  if ($lC_Template->getBranding('social_linkedin') != '') {
                    echo lc_link_object(lc_href_link($lC_Template->getBranding('social_linkedin'), '', 'NONSSL'), lc_image(DIR_WS_IMAGES . 'icons/in-ico.png', 'title', null, null, 'class="small-margin-right"'), 'target="_blank"');
                  }                  
                ?>
                <button type="button" class="btn btn-sm cursor-pointer small-margin-right<?php echo (($lC_ShoppingCart->numberOfItems() > 0) ? ' btn-success' : ' btn-default disabled'); ?>" onclick="window.location.href='<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>'">Checkout</button>  
                <i class="fa fa-search navbar-search-icon"></i>
                <input type="text" class="navbar-search" name="keywords" id="keywords" placeholder="<?php echo $lC_Language->get('button_search'); ?>"><?php echo lc_draw_hidden_session_id_field(); ?>
              </span>
            </form>
          </div>  
        </div>
      </div>
    </div>
    <div class="small-margin-top hide-on-mobile">
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