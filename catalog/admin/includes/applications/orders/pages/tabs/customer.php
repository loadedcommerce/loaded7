<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customer.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_orders_customer">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_customer'); ?></h3>
  <div id="section_orders_customer_content" class="columns with-padding">
    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_customer_info'); ?></legend>
        <div class="columns">
          <div class="new-row-mobile nine-columns twelve-columns-mobile">
            <div class="field-block button-height">
              <p class="button-height field-line-height">
                <label for="cust_name" class="label"><?php echo $lC_Language->get('text_name'); ?></label>
                <span id="cust_name" class="bold">Sal Iozzia</span>
              </p>
              <p class="button-height field-line-height">
                <label for="cust_name2" class="label"><?php echo $lC_Language->get('text_company_name'); ?></label>
                <span id="cust_name2" class="bold"><small class="tag orange-bg">B2B</small></span>
              </p>
              <p class="button-height field-line-height">
                <label for="cust_name" class="label"><?php echo $lC_Language->get('text_email'); ?></label>
                <span id="cust_name" class="bold">sal@loadedcommerce.com</span>
              </p>
              <p class="button-height field-line-height">
                <label for="cust_name" class="label"><?php echo $lC_Language->get('text_address'); ?></label>
                <span id="cust_name" class="field-block-address-offset">
                  <span class="bold field-block-address">1234 Main St.<br />Atlanta, GA 35282<br />United States</span>
                </span>
              </p>
              <p class="button-height field-line-height">
                <label for="cust_name" class="label"><?php echo $lC_Language->get('text_phone_number'); ?></label>
                <span id="cust_name" class="bold">852-820-7896</span>
              </p>
              <p class="button-height field-line-height">
                <label for="cust_name" class="label"><?php echo $lC_Language->get('text_customer_group'); ?></label>
                <span id="cust_name" class="bold">Retail</span>
              </p>
            </div>
          </div>
          <div class="new-row-mobile three-columns twelve-columns-mobile">
            <span class="button-group">
              <a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=1'); ?>" class="button compact"><?php echo $lC_Language->get('text_view'); ?></a>
              <a href="#" class="button compact"><?php echo $lC_Language->get('text_edit'); ?></a>
            </span>
          </div>
        </div> 
      </fieldset>
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_alternate') . ' ' . $lC_Language->get('text_customer_info'); ?> <?php echo lc_go_pro(); ?></legend>
        <div class="columns">
          <div class="new-row-mobile nine-columns twelve-columns-mobile">
            <div class="field-block button-height">
              <p class="button-height field-line-height">
                <label for="cust_name" class="label"><?php echo $lC_Language->get('text_email'); ?></label>
                <span id="cust_name" class="bold">&nbsp;</span>
              </p>
              <p class="button-height field-line-height">
                <label for="cust_name2" class="label"><?php echo $lC_Language->get('text_phone_number'); ?></label>
                <span id="cust_name2" class="bold">&nbsp;</span>
              </p>
            </div>
          </div>
        </div>
      </fieldset>
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('text_order_history'); ?></legend>
        <div class="columns with-small-padding small-margin-left hide-below-768 bbottom-grey">
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_number'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobil no-margin-bottom"><?php echo $lC_Language->get('text_amount'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_order_status'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_shipping_status'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom"><?php echo $lC_Language->get('text_payment_status'); ?></div>
          <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom align-right"><?php echo $lC_Language->get('text_action'); ?></div>
        </div>
        <?php
          $Qordershistory = array(array('id' => '3', 'number' => '52001', 'amount' => '89.66', 'status' => 'Complete', 'shipping' => 'Shipped', 'payment' => 'Paid'),
                                   array('id' => '8', 'number' => '598744', 'amount' => '127.35', 'status' => 'Pending', 'shipping' => 'Backordered', 'payment' => 'Paid'),
                                   array('id' => '12', 'number' => '99268', 'amount' => '206.54', 'status' => 'Complete', 'shipping' => 'Shipped', 'payment' => 'Paid'),
                                   array('id' => '23', 'number' => '13NKG7S', 'amount' => '9.87', 'status' => 'Complete', 'shipping' => 'Shipped', 'payment' => 'Paid')); 
          foreach ($Qordershistory as $history) {
        ?>
        <div class="columns with-small-padding small-margin-left bbottom-grey">
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_number'); ?> </span>
            <span><?php echo $history['number']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_amount'); ?> </span>
            <span>$<?php echo $history['amount']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_order_status'); ?> </span>
            <span><?php echo $history['status']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_shipping_status'); ?> </span>
            <span><?php echo $history['shipping']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
            <span class="show-below-768 bold"><?php echo $lC_Language->get('text_payment_sttus'); ?> </span>
            <span><?php echo $history['payment']; ?></span>
          </div>
          <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom align-right">
            <a class="button compact icon-pencil" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders=' . $history['id'] . '&action=save'); ?>"><?php echo $lC_Language->get('text_view'); ?></a>
          </div>
        </div>
        <?php
          }
        ?>
      </fieldset>
    </div>
  </div>
</div>