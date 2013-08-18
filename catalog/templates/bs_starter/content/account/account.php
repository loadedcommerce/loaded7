<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/account.php start-->
<div class="row-fluid">
  <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
  <?php 
    if ( $lC_MessageStack->size('account') > 0 ) echo '<div class="message-stack-container alert alert-error small-margin-bottom margin-left-neg">' . $lC_MessageStack->get('account') . '</div>' . "\n"; 
  ?>
  <div class="span12">
    <h3><?php echo $lC_Language->get('my_account_title'); ?></h3>
    <div class="well clearfix large-margin-right">
      <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'my_account.png', $lC_Language->get('my_account_title'), null, null, 'class="pull-left large-margin-right img-responsive"'); ?>
      <div class="">
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'edit', 'SSL'), $lC_Language->get('my_account_information')); ?></div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'), $lC_Language->get('my_account_address_book')); ?></div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'password', 'SSL'), $lC_Language->get('my_account_password')); ?></div>
      </div>
    </div>
    <h3><?php echo $lC_Language->get('my_orders_title'); ?></h3>
    <div class="well clearfix large-margin-right">
      <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'my_orders.png', $lC_Language->get('my_orders_title'), null, null, 'class="pull-left large-margin-right img-responsive"'); ?>
      <div class="">
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'), $lC_Language->get('my_orders_view')); ?></div>
      </div>
    </div>
    <h3><?php echo $lC_Language->get('my_notifications_title'); ?></h3> 
    <div class="well clearfix large-margin-right">
      <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'my_notifications.png', $lC_Language->get('my_notifications_title'), null, null, 'class="pull-left large-margin-right img-responsive"'); ?>
      <div class="">
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'newsletters', 'SSL'), $lC_Language->get('my_notifications_newsletters')); ?></div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL'), $lC_Language->get('my_notifications_products')); ?></div>
      </div>
    </div>  
  </div>
</div>
<!--content/account/account.php end-->