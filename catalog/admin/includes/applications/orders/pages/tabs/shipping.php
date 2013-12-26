<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shipping.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_orders_shipping">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_shipping'); ?></h3>
  <div id="section_orders_customer_content" class="columns with-padding">
    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_create_shipment') . ' ' . lc_go_pro(); ?></legend>
        <a class="button orders-print-packing-slip-button" href="javascript:void(0)">
          <span class="button-icon green-gradient"><span class="icon-printer"></span></span>
          <span class="orders-print-packing-slip-button-text"><?php echo $lC_Language->get('text_print_packing_slip'); ?></span>
        </a>
        <div class="columns">
          <div class="new-row-mobile twelve-columns twelve-columns-mobile">
            <?php echo $lC_Language->get('text_go_pro_and_enjoy'); ?>
          </div>
        </div> 
      </fieldset>
    </div>
  </div>
</div>