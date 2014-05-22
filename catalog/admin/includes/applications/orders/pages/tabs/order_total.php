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
<div id="section_orders_totals">
  <h3 class="show-below-768 margin-left margin-top"><?php echo $lC_Language->get('text_order_totals'); ?></h3>
  <div class="columns with-padding no-margin-bottom">
    <?php 
      echo lc_draw_hidden_field('action_order_total', '', 'id="action_order_total"');
    ?>
    <div class="new-row-mobile twelve-columns twelve-columns-mobile no-margin-bottom">
      <span class="icon-list icon-anthracite">
        <strong class="small-margin-left">
          <?php echo $lC_Language->get('text_order_totals'); ?>
        </strong>
      </span>
    </div>
    <div class="new-row-mobile twelve-columns twelve-columns-mobile mid-margin-bottom align-right">
      <span class="mid-margin-bottom">
        <a href="javascript:void(0);" onclick="addOrderTotal(<?php echo $_GET[$lC_Template->getModule()]; ?>, '<?php echo $lC_Currencies->getSymbolLeft(); ?>');">
          <button type="button" class="button icon-plus-round green-gradient compact">
            <?php echo $lC_Language->get('text_add_item'); ?>            
          </button>
        </a>
        <a href="javascript:void(0);" onclick="saveOrderTotal('<?php echo $_GET[$lC_Template->getModule()]; ?>');">
          <button type="button" class="button icon-tick green-gradient compact">
            <?php echo $lC_Language->get('text_save'); ?>
          </button>
        </a>&nbsp;&nbsp;&nbsp;
      </span>
    </div>
    <?php
      echo lC_Orders_Admin::getOrderTotalsList($_GET[$lC_Template->getModule()]);
    ?>
  </div>
</div>