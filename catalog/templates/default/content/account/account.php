<?php
/*
  $Id: account.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('account') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('account', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--ACCOUNT SECTION STARTS-->
  <div class="full_page">
    <!--ACCOUNT CONTENT STARTS-->
    <div class="content">
      <form name="account_password" id="account_password" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password=save', 'SSL'); ?>" method="post" onsubmit="return check_form(account_password);">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <!--ACCOUNT LINK LISTING STARTS--> 
        <p><b><?php echo $lC_Language->get('my_account_title'); ?></b></p>
        <div class="borderPadMe">
          <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'my_account.png', $lC_Language->get('my_account_title'), null, null, 'style="float:left;"'); ?>
          <ul class="accountList">
            <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'edit', 'SSL'), $lC_Language->get('my_account_information')); ?></li>
            <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'), $lC_Language->get('my_account_address_book')); ?></li>
            <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'password', 'SSL'), $lC_Language->get('my_account_password')); ?></li>
          </ul>
        </div>
        <div style="clear: both;">&nbsp;</div>
        <p><b><?php echo $lC_Language->get('my_orders_title'); ?></b></p>
        <div class="borderPadMe">
          <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'my_orders.png', $lC_Language->get('my_orders_title'), null, null, 'style="float:left;"'); ?>
          <ul class="accountList">
            <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'), $lC_Language->get('my_orders_view')); ?></li>
          </ul>
        </div>
        <div style="clear: both;">&nbsp;</div>
        <p><b><?php echo $lC_Language->get('my_notifications_title'); ?></b></p> 
        <div class="borderPadMe">
          <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'my_notifications.png', $lC_Language->get('my_notifications_title'), null, null, 'style="float:left;"'); ?>
          <ul class="accountList">
            <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'newsletters', 'SSL'), $lC_Language->get('my_notifications_newsletters')); ?></li>
            <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL'), $lC_Language->get('my_notifications_products')); ?></li>
          </ul>
        </div>
        <div style="clear:both;">&nbsp;</div>     
        <!--ACCOUNT LINK LISTING ENDS-->    
        <!--ACCOUNT ACTIONS STARTS-->    
        <div id="accountPasswordActions" class="action_buttonbar">
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_go_shopping'); ?></button></a></span> 
        </div> 
        <div style="clear:both;"></div>
        <!--ACCOUNT ACTIONS ENDS-->    
      </div>
      </form>
    </div>
    <!--ACCOUNT CONTENT ENDS-->
  </div>
<!--ACCOUNT SECTION ENDS-->
