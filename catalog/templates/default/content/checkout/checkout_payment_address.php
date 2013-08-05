<?php
/**  
*  $Id: checkout_payment_address.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('checkout_address') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('checkout_address', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/checkout/checkout_payment_address.php start-->
<div id="checkout_shipping_details" class="full_page">
<h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
<!--   <form name="checkout_address" id="checkout_address" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address=process', 'SSL'); ?>" method="post"> -->
<div class="checkout_steps">
<ol id="checkoutSteps">
<li class="section allow active">
  <div class="step-title">
    <h2><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h2>
  </div>
  <div style="margin-bottom:18px;">
  <div class="col2-set">
    <div id="mobile-grand-total">
      <?php 
       foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
         if ($module['code'] == 'total') {
      ?>
      <div id="mobile-arrow-down"><span class="arrow-down"></span></div>
      <div class="ot-mobile-block" id="mobile_<?php echo $module['code']; ?>">
        <label><?php echo $module['title']; ?></label>
        <span><?php echo $module['text']; ?></span> </div>
      <div style="clear:both;"></div>
      <?php
       }
     }
    ?>
    </div>
    <div id="mobile-order-totals">
      <div id="mobile-arrow-up"><span class="arrow-up"></span></div>
      <?php 
       foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
     ?>
      <div class="ot-mobile-block" id="mobile_<?php echo $module['code']; ?>">
        <label><?php echo $module['title']; ?></label>
        <span><?php echo $module['text']; ?></span> </div>
      <div style="clear:both;"></div>
      <?php
       }
      ?>
    </div>
    <div id="checkout_coupon_tip_mobile" style="display:none;"><?php echo $lC_Language->get('checkout_coupon_tip'); ?></div>
    <div id="checkout_shipping_col1" style="width:30%; float:left;">
      <div id="ship-to-address-block" class="hide-on-320 hide-on-480">
        <h3><?php echo $lC_Language->get('ship_to_address'); ?></h3>
        <p><?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?></p>
      </div>
      <div id="shipping-method-block" class="hide-on-320 hide-on-480">
        <h3><?php echo $lC_Language->get('shipping_method_heading'); ?></h3>
        <p><?php echo $lC_ShoppingCart->getShippingMethod('title'); ?></p>
      </div>
      <div id="ot-container">
        <div class="ot-block" id="order-number">
          <label><?php echo $lC_Language->get('checkout_order_number'); ?></label>
          <span><?php echo $_SESSION['cartID']; ?></span> </div>
        <?php foreach ($lC_ShoppingCart->getOrderTotals() as $module) { ?>
        <div class="ot-block" id="<?php echo $module['code']; ?>">
          <label><?php echo $module['title']; ?></label>
          <span><?php echo $module['text']; ?></span> </div>
        <div style="clear:both;"></div>
        <?php } ?>
      </div>
    </div>
    <div id="checkout_shipping_col2" style="width:64%; float:right;">
      <form name="checkout_address" id="checkout_address" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address=process', 'SSL'); ?>" method="post">
        <?php
         if (isset($_GET['payment_address']) && ($_GET['payment_address'] != 'process')) {
         if ($lC_Customer->hasDefaultAddress()) {
       ?>
        <div id="checkoutShippingAddressHeading" style="margin-top:15px;">
          <h3><?php echo $lC_Language->get('billing_address_title'); ?></h3>
                      <span id="bill-to-address"><?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?></span>
                      <span id="bill-to-change"><?php echo $lC_Language->get('selected_billing_destination'); ?></span>
                      <div style="clear: both;"></div>



        </div>
        <?php
        }
       if (lC_AddressBook::numberOfEntries() > 1) {
       ?>
        <div id="checkoutShippingAddressEntries" style="margin-top:15px;">
        <h3><?php echo $lC_Language->get('address_book_entries_title'); ?></h3>
        <div>
          <div class="hide-on-320 hide-on-480" style="float: right; padding: 0px 0px 10px 20px; text-align: center;"> <?php echo '<b>' . $lC_Language->get('please_select') . '</b><br />'; ?> </div>
          <div>
            <p style="margin-top: 0px;"><?php echo $lC_Language->get('select_another_billing_destination'); ?></p>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                  $radio_buttons = 0;
                  $Qaddresses = $lC_Template->getListing();
                  while ($Qaddresses->next()) {
                  ?>
              <tr>
                <td width="10">&nbsp;</td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                      if ($Qaddresses->valueInt('address_book_id') == $lC_ShoppingCart->getBillingAddress('id')) {
                        echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                      } else {
                        echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                      }
                    ?>
                    <td width="10">&nbsp;</td>
                      <td colspan="2" style="padding:2px 0 2px 0;"><b><?php echo $Qaddresses->valueProtected('firstname') . ' ' . $Qaddresses->valueProtected('lastname'); ?></b></td>
                      <td align="right"><div align="right" style="padding-right:12px;"><?php echo lc_draw_radio_field('address', $Qaddresses->valueInt('address_book_id'), $lC_ShoppingCart->getBillingAddress('id'),null,''); ?></span></div></td>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="10">&nbsp;</td>
                      <td colspan="3" style="padding-bottom:10px;"><table border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td width="10">&nbsp;</td>
                            <td><?php echo lC_Address::format($Qaddresses->toArray(), ', '); ?></td>
                            <td width="10">&nbsp;</td>
                          </tr>
                        </table></td>
                      <td width="10">&nbsp;</td>
                    </tr>
                  </table></td>
                <td width="10">&nbsp;</td>
              </tr>
              <?php
                  $radio_buttons++;
                }
              ?>
            </table>
          </div>
        </div>
        <?php
          }
        }
        if (lC_AddressBook::numberOfEntries() < MAX_ADDRESS_BOOK_ENTRIES) {
        ?>
        <div id="checkoutPaymentAddressDetails" style="display:none;">
          <h4><?php echo $lC_Language->get('new_billing_address_title'); ?></h4>
          <p><?php echo $lC_Language->get('new_billing_address'); ?></p>
          <div id="addressBookDetails">
            <?php 
              if (file_exists(DIR_FS_TEMPLATE . 'modules/address_book_details.php')) {
                require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/address_book_details.php'));
              } else {
                require($lC_Vqmod->modCheck('includes/modules/address_book_details.php')); 
              }    
            ?>
          </div>
        </div>
        <?php
        }
      ?>
        <div id="checkoutPaymentAddressActions" class="action_buttonbar"> 
          <!-- <span class="buttonLeft"><?php echo '<b>' . $lC_Language->get('continue_checkout_procedure_title') . '</b> ' . $lC_Language->get('continue_checkout_procedure_to_shipping'); ?></span> -->
          <span class="buttonRight"><button class="button purple_btn" type="submit" onclick="validateForm();"><?php echo $lC_Language->get('button_continue'); ?></button></span> </div>
          <span style="float: left"><button class="button purple_btn" name="payment_address_form" type="button" id="payment_address_form"><?php echo $lC_Language->get('show_address_form'); ?></button></span>
        <div style="clear:both;"></div>
      </form>
    </div>
  </div>
  <div style="clear:both;"></div>
  <!--content/checkout/checkout_payment_address.php end-->