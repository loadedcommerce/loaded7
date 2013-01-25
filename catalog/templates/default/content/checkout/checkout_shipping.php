<?php
  /*
  $Id: checkout_shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
if ($lC_MessageStack->size('checkout_shipping') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('checkout_shipping', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--CHECKOUT SHIPPING SECTION STARTS-->
<div id="checkout_shipping_details" class="full_page">
  <!--CHECKOUT SHIPPING DETAILS STARTS-->
  <h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
  <form name="checkout_shipping" id="checkout_shipping" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping=process', 'SSL'); ?>" method="post">
    <div class="checkout_steps">
      <ol id="checkoutSteps">
        <li class="section allow active">
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h2>
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
              <!--APPLY COUPON TIP STARTS-->
              <div style="padding:10px; display:hidden;" class="show-on-mobile mobile-coupon-tip">You will be able to apply coupons on the confirmation page.</div>
              <!--APPLY COUPON TIP ENDS-->
              <div id="checkout_shipping_col1" style="width:35%; float:left;">
                <!--SHIP TO ADDRESS BLOCK STARTS-->
                <div id="ship-to-address-block">
                  <h3><?php echo $lC_Language->get('ship_to_address'); ?></h3>
                  <span style="float:right;"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                  <span id="ship-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?></span>
                  <br /><br />
                  <input type="checkbox" name="shipto_as_billable" id="shipto_as_billable" class="checkbox">
                  <label for="shipto_as_billable">&nbsp;<?php echo $lC_Language->get('billable_address_checkbox'); ?></label>
                </div>
                <!--SHIP TO ADDRESS BLOCK ENDS-->
                <!--ORDER TOTAL LISTING STARTS-->
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
                <!--ORDER TOTAL LISTING ENDS-->
                <!--APPLY COUPON TIP STARTS-->
                <div style="padding:10px;" class="hide-on-mobile">You will be able to apply coupons on the confirmation page.</div>
                <!--APPLY COUPON TIP ENDS-->
              </div>
              <div id="checkout_shipping_col2" style="width:60%; float:right;">
                <?php
                  if ($lC_Shipping->hasQuotes()) {
                ?>
                <!--CHECKOUT SHIPPING QUOTES STARTS-->
                <div id="shippingQuotes">
                  <h3><?php echo $lC_Language->get('shipping_method_title'); ?></h3>
                  <?php
                    if ($lC_Shipping->numberOfQuotes() > 1) {
                  ?>
                  <p style="margin-top: 0px;"><?php echo $lC_Language->get('choose_shipping_method'); ?></p>
                  <?php
                    } else {
                  ?>
                  <p style="margin-top: 0px;"><?php echo $lC_Language->get('only_one_shipping_method_available'); ?></p>
                  <?php
                    }
                  ?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                      $radio_buttons = 0;
                      foreach ($lC_Shipping->getQuotes() as $quotes) {
                      ?>
                      <tr>
                        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" id="shippingSelect">
                        <tr>
                          <td width="10">&nbsp;</td>
                          <td colspan="3" class="shippingQuotesTitle"><b><?php echo $quotes['module']; ?></b>&nbsp;<?php if (isset($quotes['icon']) && !empty($quotes['icon'])) { echo $quotes['icon']; } ?></td>
                          <td width="10">&nbsp;</td>
                        </tr>
                        <?php
                          if (isset($quotes['error'])) {
                          ?>
                          <tr>
                            <td width="10">&nbsp;</td>
                            <td colspan="3"><?php echo $quotes['error']; ?></td>
                            <td width="10">&nbsp;</td>
                          </tr>
                          <?php
                          } else {
                            $counter = 0;
                            foreach ($quotes['methods'] as $methods) {
                              if ($quotes['id'] . '_' . $methods['id'] == $lC_ShoppingCart->getShippingMethod('id')) {
                                echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                              } else {
                                echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                              }
                            ?>
                            <td width="10">&nbsp;</td>
                            <td width="75%"><?php echo $methods['title']; ?></td>
                            <?php
                              if ( ($lC_Shipping->numberOfQuotes() > 1) || (sizeof($quotes['methods']) > 1) ) {
                              ?>
                              <td><?php echo $lC_Currencies->displayPrice($methods['cost'], $quotes['tax_class_id']); ?></td>
                              <td style="text-align:right;"><?php echo lc_draw_radio_field('shipping_mod_sel', $quotes['id'] . '_' . $methods['id'], $lC_ShoppingCart->getShippingMethod('id'), 'id="' . $quotes['id'] . '_' . $counter . '"'); ?></td>
                              <?php
                              } else {
                              ?>
                              <td align="right" colspan="2"><?php echo $lC_Currencies->displayPrice($methods['cost'], $quotes['tax_class_id']) . lc_draw_hidden_field('shipping_mod_sel', $quotes['id'] . '_' . $methods['id']); ?></td>
                              <?php
                              }
                            ?>
                            <td width="10">&nbsp;</td>
                          </tr>
                          <?php
                            $counter++;
                            $radio_buttons++;
                          }
                        }
                        ?>
                        </table>
                      </td>
                    </tr>
                    <?php
                    }
                  ?>
                  </table>
                </div>
                <div style="clear:both;">&nbsp;</div>
                <!--CHECKOUT SHIPPING QUOTES ENDS-->
                <?php
                  }
                ?>
                <!--CHECKOUT SHIPPING ACTIONS STARTS-->
                <div id="shippingActions">
                  <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, '', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
                  <span class="buttonRight"><a onclick="$('#checkout_shipping').submit();" class="noDecoration"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('continue_checkout'); ?></button></a></span>
                </div>
                <!--CHECKOUT SHIPPING ACTIONS ENDS-->
              </div>
              <div style="clear:both;"></div>
            </div>
          </div>
        </li>
        <li class="next-li">
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h2>
          </div>
        </li>
        <li>
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h2>
          </div>
        </li>
        <li>
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_complete'); ?></h2>
          </div>
        </li>
      </ol>
    </div>
  </form>
  <div style="clear:both;"></div>
  <!--CHECKOUT SHIPPING DETAILS ENDS-->
</div>
<!--CHECKOUT SHIPPING SECTION ENDS-->