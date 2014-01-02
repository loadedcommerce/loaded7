<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_order_totals">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_orders_total'); ?></h3>
  <div class="columns with-padding">
    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
      <fieldset>
        <legend class="small-margin-bottom">
          <span class="icon-list icon-anthracite large-margin-left"><strong class="small-margin-left"><?php echo $lC_Language->get('text_orders_total'); ?></strong></span>
          <span class="button-group padding3 ">
            <a class="button compact icon-plus" href="javascript:void(0);" onclick="addOrderTotal(<?php echo $orders_ID;?>);"><?php echo 'Add'; ?></a> 
          </span>
        </legend>
        <?php 
        echo lc_draw_hidden_field("action_order_total", '', 'id = action_order_total');
        echo lC_Orders_Admin::getOrderTotalsList($_GET[$lC_Template->getModule()]);
        
        ?>
      </fieldset>
    </div> 
  </div>
</div>