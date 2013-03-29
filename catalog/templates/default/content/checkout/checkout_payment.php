<?php
/**  
*  $Id: checkout_payment.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('checkout_payment') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('checkout_payment', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/checkout/checkout_payment.php start-->
<div id="checkout_payment_details" class="full_page">
  <h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
  <form name="checkout_payment" id="checkout_payment" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'confirmation', 'SSL'); ?>" method="post" onsubmit="return check_form();">
    <div class="checkout_steps">
      <ol id="checkoutSteps">
        <li class="first-checkout-li">
          <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>">
            <div class="step-title">
              <h2><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h2>
            </div>
          </a>
        </li>
        <li class="section allow active">
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h2>
          </div>
          <div id="checkout-step-login">
            <div class="col2-set">
              <div id="mobile-grand-total">
                <?php 
                  foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
                    if ($module['code'] == 'total') {
                    ?>
                    <div id="mobile-arrow-down"><span class="arrow-down"></span></div>
                    <div class="ot-mobile-block" id="mobile_<?php echo $module['code']; ?>">
                      <label><?php echo $module['title']; ?></label>
                      <span><?php echo $module['text']; ?></span>
                    </div>
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
                    <span><?php echo $module['text']; ?></span>
                  </div>
                  <div style="clear:both;"></div>
                  <?php
                  }
                ?>            
              </div>
              <div id="checkout_coupon_tip_mobile" style="display:none;"><?php echo $lC_Language->get('checkout_coupon_tip'); ?></div>
              <div id="checkout_shipping_col1" style="width:32%; float:left;">
                <div id="ship-to-address-block" class="hide-on-320 hide-on-480">
                  <h3><?php echo $lC_Language->get('ship_to_address'); ?></h3>
                  <span style="float:right;"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                  <span id="ship-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?></span>
                </div>
                <div id="shipping-method-block" class="hide-on-320 hide-on-480">  
                  <h3><?php echo $lC_Language->get('shipping_method_heading'); ?></h3>
                  <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                  <p><?php echo $lC_ShoppingCart->getShippingMethod('title'); ?></p>
                </div>
                <div id="ot-container">
                  <div class="ot-block" id="order-number">
                    <label><?php echo $lC_Language->get('checkout_order_number'); ?></label>
                    <span><?php echo $_SESSION['cartID']; ?></span>
                  </div>
                  <?php foreach ($lC_ShoppingCart->getOrderTotals() as $module) { ?>
                    <div class="ot-block" id="<?php echo $module['code']; ?>">
                      <label><?php echo $module['title']; ?></label>
                      <span><?php echo $module['text']; ?></span>
                    </div>
                    <div style="clear:both;"></div>
                    <?php } ?>
                </div>
                <div id="checkout_coupon_tip"><?php echo $lC_Language->get('checkout_coupon_tip'); ?></div>
              </div>
              <div id="checkout_shipping_col2" style="width:65%; float:right;">
                <div id="checkoutPaymentAddress">
                  <h3><?php echo $lC_Language->get('bill_to_address'); ?></h3>
                  <span id="bill-to-address">
                    <?php
                      if ($_SESSION['shipto_as_billable'] == 'on') {
                        echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); 
                      } else {
                        echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); 
                      }                      
                    ?>
                  </span>
                  <span id="bill-to-change">
                    <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_change_address'); ?></a><br />
                    <?php echo $lC_Language->get('choose_billing_destination'); ?>
                  </span>
                  <div style="clear:both;"></div>
                </div>
                <div id="checkoutPaymentMethods">
                  <h3><?php echo $lC_Language->get('payment_method_title'); ?></h3>
                  <div>
                    <?php
                      $selection = $lC_Payment->selection(); 
                      if (sizeof($selection) > 1) {
                      ?>
                      <p style="margin-top:0px;"><?php echo $lC_Language->get('choose_payment_method'); ?></p>
                      <?php
                      } else {
                      ?>
                      <p style="margin-top:0px;"><?php echo $lC_Language->get('only_one_payment_method_available'); ?></p>
                      <?php
                      }
                    ?>
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <?php
                        $radio_buttons = 0;
                        $counter = 0;
                        for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
                        ?>
                        <tr>
                          <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <?php
                            if ( ($n == 1) || ($lC_ShoppingCart->hasBillingMethod() && ($selection[$i]['id'] == $lC_ShoppingCart->getBillingMethod('id'))) ) {
                              echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                            } else {
                              echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                            }
                          ?>
                          <td width="10">&nbsp;</td>
                          <?php
                            if ($n > 1) {
                            ?>
                            <td colspan="3"><?php echo '<b>' . $selection[$i]['module'] . '</b>'; ?></td>
                            <td align="right" width="20"><?php echo lc_draw_radio_field('payment_method', $selection[$i]['id'], ($lC_ShoppingCart->hasBillingMethod() ? $lC_ShoppingCart->getBillingMethod('id') : null), 'id="pm_' . $counter . '"',''); ?></span></td>
                            <?php
                            } else {
                            ?>
                            <td colspan="4" style="padding:10px;"><?php echo '<b>' . $selection[$i]['module'] . '</b>' . lc_draw_hidden_field('payment_method', $selection[$i]['id']); ?></td>
                            <?php
                            }
                          ?>
                          <td width="10">&nbsp;</td>
                        </tr>
                        <?php
                          if (isset($selection[$i]['error'])) {
                          ?>
                          <tr>
                            <td width="10">&nbsp;</td>
                            <td colspan="4"><?php echo $selection[$i]['error']; ?></td>
                            <td width="10">&nbsp;</td>
                          </tr>
                          <?php
                          } else if(isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
                          ?>
                          <tr>
                            <td width="10">&nbsp;</td>
                            <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                                <?php
                                  for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
                                  ?>
                                  <tr>
                                    <td width="10">&nbsp;</td>
                                    <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                                    <td width="10">&nbsp;</td>
                                    <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                                    <td width="10">&nbsp;</td>
                                  </tr>
                                  <?php
                                  }
                                ?>
                              </table></td>
                            <td width="10">&nbsp;</td>
                          </tr>
                          <?php
                          }
                        ?>
                      </table></td>
                      </tr>   
                      <?php
                        $counter++;
                        $radio_buttons++;
                      }
                    ?>
                    </table>
                  </div>
                </div>
                <br />
                <div style="clear:both;"></div>
                <div id="checkoutPaymentActions">
                  <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
                  <span class="buttonRight"><a onclick="$('#checkout_payment').submit();" class="noDecoration"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('continue_checkout'); ?></button></a></span>
                </div>
                <div style="clear:both;"></div>
              </div>
            </div>
          </div>
        </li>
        <li class="next-li">
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h2>
          </div>
        </li>
      </ol>
    </div>
  </form>
</div>
<div style="clear:both;"></div>
<!--content/checkout/checkout_payment.php end-->