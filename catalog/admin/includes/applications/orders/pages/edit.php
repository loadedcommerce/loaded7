<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
*/
if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $oInfo = new lC_ObjectInfo(lC_Orders_Admin::getInfo($_GET[$lC_Template->getModule()]));
}
?>
<style scoped="scoped">
  .replacement > .select-value { height: 19px; }
  span.select { height: 33px; }
  .select-value { line-height: 19px; }
</style>
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
          <li class="active" id="id_tab_orders_summary"><?php echo lc_link_object('#section_orders_summary', $lC_Language->get('section_orders_summary')); ?></li>
          <li id="id_tab_orders_products"><?php echo lc_link_object('#section_orders_products', $lC_Language->get('section_orders_products')); ?></li>
          <!--<li id="id_tab_orders_customer"><?php echo lc_link_object('#section_orders_customer', $lC_Language->get('section_orders_customer')); ?></li>
          <li id="id_tab_orders_shipping"><?php echo lc_link_object('#section_orders_shipping', $lC_Language->get('section_orders_shipping')); ?></li>-->
          <li id="id_tab_orders_status"><?php echo lc_link_object('#section_orders_status', $lC_Language->get('section_orders_status')); ?></li>
          <!--<li id="id_tab_orders_fraud"><?php echo lc_link_object('#section_orders_fraud', $lC_Language->get('section_orders_fraud')); ?></li>
          <li id="id_tab_orders_payments"><?php echo lc_link_object('#section_orders_payments', $lC_Language->get('section_orders_payments')); ?></li>-->
          <li id="id_tab_orders_transactions"><?php echo lc_link_object('#section_orders_transactions', $lC_Language->get('section_transaction_history')); ?></li>
          <li id="id_tab_orders_totals"><?php echo lc_link_object('#section_orders_totals', $lC_Language->get('section_order_totals')); ?></li>
        </ul>
        <div class="tabs-content" id="orders_sections">
          <?php 
            include('includes/applications/orders/pages/tabs/summary.php'); 
            include('includes/applications/orders/pages/tabs/products.php');  
            //include('includes/applications/orders/pages/tabs/customer.php'); 
            //include('includes/applications/orders/pages/tabs/shipping.php'); 
            include('includes/applications/orders/pages/tabs/status.php');
            //include('includes/applications/orders/pages/tabs/fraud.php'); 
            //include('includes/applications/orders/pages/tabs/payments.php'); 
            include('includes/applications/orders/pages/tabs/transactions.php'); 
            include('includes/applications/orders/pages/tabs/order_total.php'); 
          ?> 
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <select id="orders_edit_select" class="green-gradient select expandable-list" onchange="ordersEditSelect('<?php echo $oInfo->get('customerId'); ?>', '<?php echo $_GET[$lC_Template->getModule()]; ?>', this.value);">
                <option value=""><?php echo $lC_Language->get('text_actions'); ?></option>
                <option value="invoice"><?php echo $lC_Language->get('text_print_invoice'); ?></option>
                <option value="packing"><?php echo $lC_Language->get('text_print_packing_slip'); ?></option>
                <!--<option value="spin"><?php echo $lC_Language->get('text_spin_off_order'); ?></option>-->
                <option value="customer"><?php echo $lC_Language->get('text_go_to_customer'); ?></option>
              </select>&nbsp;
              <?php
                $close = lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule());
                button_save_close(false, false, $close);
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Main content -->
