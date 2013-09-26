<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $oInfo = new lC_ObjectInfo(lC_Orders_Admin::getInfo($_GET[$lC_Template->getModule()]));
}
?>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Language->get('text_edit_order') . ': ' . $_GET[$lC_Template->getModule()]; ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="order" id="order" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <input id="order_id" type="hidden" value="<?php echo $_GET[$lC_Template->getModule()]; ?>" name="oid">
      <!--<div id="order_quick_info">
        <div class="columns with-small-padding">
          <div class="four-columns twelve-columns-mobile"><h4><?php echo $lC_Language->get('text_order_number'); ?> 6574389</h4></div>
          <div class="four-columns twelve-columns-mobile"><h4><?php echo $lC_Language->get('text_amount'); ?> $507.50</h4></div>
          <div class="four-columns twelve-columns-mobile"><h4><?php echo $lC_Language->get('text_due'); ?> $0.00</h4></div>
        </div>
      </div>-->
      <div id="order_tabs" class="side-tabs tab-opened">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_orders_summary', $lC_Language->get('section_orders_summary')); ?></li>
          <!--<li><?php echo lc_link_object('#section_orders_products', $lC_Language->get('section_orders_products')); ?></li>
          <li><?php echo lc_link_object('#section_orders_customer', $lC_Language->get('section_orders_customer')); ?></li>
          <li><?php echo lc_link_object('#section_orders_shipping', $lC_Language->get('section_orders_shipping')); ?></li>-->
          <li><?php echo lc_link_object('#section_orders_status', $lC_Language->get('section_orders_status')); ?></li>
          <!--<li><?php echo lc_link_object('#section_orders_fraud', $lC_Language->get('section_orders_fraud')); ?></li>
          <li><?php echo lc_link_object('#section_orders_payments', $lC_Language->get('section_orders_payments')); ?></li>-->
          <li><?php echo lc_link_object('#section_orders_transactions', $lC_Language->get('section_transaction_history')); ?></li>
        </ul>
        <div class="tabs-content" id="orders_sections">
          <?php 
            include('includes/applications/orders/pages/tabs/summary.php'); 
            //include('includes/applications/orders/pages/tabs/products.php');  
            //include('includes/applications/orders/pages/tabs/customer.php'); 
            //include('includes/applications/orders/pages/tabs/shipping.php'); 
            include('includes/applications/orders/pages/tabs/status.php');
            //include('includes/applications/orders/pages/tabs/fraud.php'); 
            //include('includes/applications/orders/pages/tabs/payments.php'); 
            include('includes/applications/orders/pages/tabs/transactions.php'); 
          ?> 
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon blue-gradient glossy">
                  <span class="icon-list"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_back_to_list'); ?></span>
              </a>&nbsp;
              <select id="orders_edit_select" class="green-gradient select expandable-list" onchange="ordersEditSelect('<?php echo $oInfo->get('customerId'); ?>', '<?php echo $_GET[$lC_Template->getModule()]; ?>', this.value);">
                <option value=""><?php echo $lC_Language->get('text_actions'); ?></option>
                <option value="invoice"><?php echo $lC_Language->get('text_print_invoice'); ?></option>
                <option value="packing"><?php echo $lC_Language->get('text_print_packing_slip'); ?></option>
                <!--<option value="spin"><?php echo $lC_Language->get('text_spin_off_order'); ?></option>-->
                <option value="customer"><?php echo $lC_Language->get('text_go_to_customer'); ?></option>
              </select>&nbsp;
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Main content -->