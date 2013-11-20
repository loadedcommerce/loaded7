<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/account.php start-->
<div class="row">
  <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
  <?php 
    if (isset($_GET['success']) && $_GET['success'] != NULL) echo '<div class="message-success-container alert alert-success"><img class="margin-right" src="images/icons/success.gif">' . preg_replace('/[^a-zA-Z0-9]\'\.\,/', '', $_GET['success']) . '</div>' . "\n";
  ?>
  <div class="col-sm-12 col-lg-12">
    <h3><?php echo $lC_Language->get('my_details_title'); ?></h3>
    <div class="well clearfix large-margin-right">
      <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'icons/64/account.png', $lC_Language->get('my_account_title'), null, null, 'class="img-responsive pull-left large-margin-right img-responsive"'); ?>
      <div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'edit', 'SSL'), $lC_Language->get('my_account_information')); ?></div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'), $lC_Language->get('my_account_address_book')); ?></div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'password', 'SSL'), $lC_Language->get('my_account_password')); ?></div>
      </div>
    </div>
    <h3><?php echo $lC_Language->get('my_orders_title'); ?></h3>
    <div class="well clearfix large-margin-right">
      <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'icons/64/orders.png', $lC_Language->get('my_orders_title'), null, null, 'class="img-responsive pull-left large-margin-right img-responsive"'); ?>
      <div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'), $lC_Language->get('my_orders_view')); ?></div>
      </div>
    </div>
    <h3><?php echo $lC_Language->get('my_notifications_title'); ?></h3> 
    <div class="well clearfix large-margin-right">
      <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'icons/64/notifications.png', $lC_Language->get('my_notifications_title'), null, null, 'class="img-responsive pull-left large-margin-right img-responsive"'); ?>
      <div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'newsletters', 'SSL'), $lC_Language->get('my_notifications_newsletters')); ?></div>
        <div><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL'), $lC_Language->get('my_notifications_products')); ?></div>
      </div>
    </div>  
  </div>
</div>
<!--content/account/account.php end-->