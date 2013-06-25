<?php
/**  
*  $Id: checkout_confirmation.php v1.0 2013-01-01 datazen $
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
<!--content/checkout/checkout_confirmation.php start-->
<div id="checkout_confirmation_details" class="full_page">
  <h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
  <div class="checkout_steps">
    <ol id="checkoutSteps">
      <li class="first-checkout-li">
        <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>">
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h2>
          </div>
        </a>
      </li>
      <li>
        <a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment&skip=no', 'SSL'); ?>">
          <div class="step-title">
            <h2><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h2>
          </div>
        </a>
      </li>
      <li class="section allow active">
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h2>
        </div> 
        <div id="checkout-step-login">
          <div class="col2-set">
            <div id="checkout_shipping_col1" style="width:35%; float:left;">
              <div id="ship-to-address-block">
                <h3><?php echo $lC_Language->get('ship_to_address'); ?></h3>
                <span style="float:right;"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                <span id="ship-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?></span>
              </div>
              <div id="shipping-method-block">  
                <h3><?php echo $lC_Language->get('shipping_method_heading'); ?></h3>
                <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                <p><?php echo $lC_ShoppingCart->getShippingMethod('title'); ?></p>
              </div>
              <div id="bill-to-address-block">
                <h3><?php echo $lC_Language->get('bill_to_address'); ?></h3>
                <span style="float:right;"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                <span id="bill-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?></span>
              </div>
              <div id="payment-method-block">  
                <h3><?php echo $lC_Language->get('payment_method_heading'); ?></h3>
                <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span>
                <p><?php echo $lC_ShoppingCart->getBillingMethod('title'); ?></p>
              </div>
            </div>
            <div id="checkout_shipping_col2" style="width:60%; float:right;">
              <!-- placement for order reference number later <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'); ?>" class="sc-button small grey colorWhite noDecoration"><?php echo $lC_Language->get('button_edit'); ?></a></span> -->
              <h3><?php echo $lC_Language->get('checkout_order_number') . '&nbsp;' . $_SESSION['cartID']; ?></h3>
              <div id="confirmation-products-listing">
                <div id="checkoutConfirmationDetails">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                      if ($lC_ShoppingCart->numberOfTaxGroups() > 1) {
                      ?>
                      <tr>
                        <td colspan="2"><?php echo '<b>' . $lC_Language->get('order_products_title') . '</b>'; ?></td>
                        <td align="right"><b><?php echo $lC_Language->get('order_tax_title'); ?></b></td>
                        <td align="right"><b><?php echo $lC_Language->get('order_total_title'); ?></b></td>
                      </tr>
                      <?php
                      } else {
                      ?>
                      <tr>
                        <td colspan="4"><?php echo '<b>' . $lC_Language->get('order_products_title') . '</b>'; ?></td>
                      </tr>
                      <?php
                      }
                      foreach ($lC_ShoppingCart->getProducts() as $products) {
                        echo '              <tr class="confirmation-products-listing-row">' . "\n" .
                        '                <td width="30"><b>' . $products['quantity'] . '&nbsp;x&nbsp;</b></td>' . "\n" .
                        '                <td><b>' . $products['name'] . '</b><br /><span class="confirmation-products-listing-model">' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</span>';
                        if ( (STOCK_CHECK == '1') && !$lC_ShoppingCart->isInStock($products['item_id']) ) {
                          echo '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
                        }
                        if ( $lC_ShoppingCart->isVariant($products['item_id']) ) {
                          foreach ( $lC_ShoppingCart->getVariant($products['item_id']) as $variant) {
                            echo '<br />- ' . $variant['group_title'] . ': ' . $variant['value_title'];
                          }
                        }
                        echo '</td>' . "\n";
                        if ($lC_ShoppingCart->numberOfTaxGroups() > 1) {
                          echo '                <td style="float:right;"><b>' . lC_Tax::displayTaxRateValue($products['tax']) . '</b></td>' . "\n";
                        }
                        echo '                <td style="float:right;"><b>' . $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']) . '</b></td>' . "\n" .
                        '              </tr>' . "\n";
                      }
                    ?>
                  </table>
                </div>
              </div>
              <div style="clear:both;"></div>
              <div class="col2-set" id="discount-code-order-total">
                <div id="confirmation-discount-code">
                  <h3><?php //echo $lC_Language->get('discount_code_title'); ?></h3>
                  <div id="confirmation-discount-code-inner">
                    <!-- Discount Code input field goes here -->
                  </div>
                  <div id="confirmation-discount-code-button">
                    <!-- Discount button goes here
                    <a href="#" class="sc-button small grey colorWhite noDecoration buttonRight">
                    <?php echo $lC_Language->get('apply_discount_code'); ?>
                    </a>
                    -->
                  </div>
                </div>
                <div id="confirmation-order-totals">
                  <h3><?php echo $lC_Language->get('order_totals_title'); ?></h3>
                  <!--ORDER TOTAL LISTING STARTS-->
                  <div id="ot-container">
                    <?php 
                      foreach ($lC_ShoppingCart->getOrderTotals() as $module) { 
                        if ($module['code'] != 'total') {
                        ?>
                        <div class="ot-block" id="<?php echo $module['code']; ?>">
                          <label><?php echo $module['title']; ?></label>
                          <span><?php echo $module['text']; ?></span>
                        </div>
                        <div style="clear:both;"></div>
                        <?php 
                        }
                      } 
                    ?>
                  </div>
                  <div style="clear:both;"></div>
                </div>
              </div>
              <?php
                if ($lC_Payment->hasActive()) {
                  if ($confirmation = $lC_Payment->confirmation()) {
                  ?>
                  <div id="checkoutConfirmationPaymentInformation">
                    <h6><?php echo $lC_Language->get('order_payment_information_title'); ?></h6>
                    <div>
                      <p><?php echo $confirmation['title']; ?></p>
                      <?php
                        if (isset($confirmation['fields'])) {
                        ?>
                        <table border="0" cellspacing="0" cellpadding="2">
                          <?php
                            for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
                            ?>
                            <tr>
                              <td width="10">&nbsp;</td>
                              <td><?php echo $confirmation['fields'][$i]['title']; ?></td>
                              <td width="10">&nbsp;</td>
                              <td><?php echo $confirmation['fields'][$i]['field']; ?></td>
                            </tr>
                            <?php
                            }
                          ?>
                        </table>
                        <?php
                        }
                        if (isset($confirmation['text'])) {
                        ?>
                        <p><?php echo $confirmation['text']; ?></p>
                        <?php
                        }
                      ?>
                    </div>
                  </div>
                  <?php
                  }
                }
              ?>
              <div style="clear:both;"></div>
              <?php
              
              if ($lC_Payment->hasActionURL()) {
                //$form_action_url = ($lC_Payment->hasIframeURL()) ?  $lC_Payment->getIframeURL() : $lC_Payment->getActionURL();
                $form_action_url = $lC_Payment->getActionURL();
              } else {
                $form_action_url = lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL');
              }
              
              // ppec inject
              if (isset($_SESSION['PPEC_PROCESS']['LINK']) && $_SESSION['PPEC_PROCESS']['LINK'] != NULL) $form_action_url = $_SESSION['PPEC_PROCESS']['LINK'];
              
              echo "<form name='checkout_confirmation' id='checkout_confirmation' action='" . $form_action_url . "' method='post'>";
              if ($lC_Payment->hasActive()) {
                echo $lC_Payment->process_button();
              }
              ?>
                <div class="col2-set" id="order-comment-grand-total">
                  <div id="confirmation-order-comment">
                    <h3><?php echo $lC_Language->get('order_comment_title'); ?></h3>
                    <div id="confirmation-order-comment-inner">
                      <?php echo lc_draw_textarea_field('comments', (isset($_SESSION['comments']) ? $_SESSION['comments'] : null), null, null, 'placeholder="' . $lC_Language->get('text_add_comment_to_order') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('text_add_comment_to_order') . '\'" style="height:47px; width:98%"'); ?>
                    </div>
                  </div>
                  <div id="confirmation-grand-total">
                    <h3><?php echo $lC_Language->get('order_total_title'); ?></h3>
                    <div id="confirmation-grand-total-inner">
                      <div id="ot-container">
                        <?php 
                          foreach ($lC_ShoppingCart->getOrderTotals() as $module) { 
                            if ($module['code'] == 'total') {
                            ?>
                            <div class="ot-block" id="<?php echo $module['code']; ?>">
                              <label><?php echo $module['title']; ?></label>
                              <span><?php echo $module['text']; ?></span>
                            </div>
                            <div style="clear:both;"></div>
                            <?php 
                            }
                          } 
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div style="clear:both;"></div>
                <?php
                  if (DISPLAY_CONDITIONS_ON_CHECKOUT == '1') {
                  ?>              
                  <div id="terms-conditions">
                    <?php echo lc_draw_checkbox_field('conditions', array(array('id' => 1, 'text' => $lC_Language->get('order_conditions_acknowledge'))), false); ?>
                  </div>
                  <script>
                    $("#checkout_confirmation").submit(function() {
                        if($('#conditions').is(':checked')){
                        } else {
                          alert('<?php echo $lC_Language->get('error_conditions_not_accepted'); ?>');
                          return false;
                        }
                    });
                  </script>
                  <div style="clear:both;"></div>
                  <?php
                  }
                ?>
                <div style="clear:both;"></div>
                <div id="checkoutConfirmationActions">
                  <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
                  <span class="buttonRight"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_confirm_order'); ?></button></span>
                </div>
                <div style="clear:both;"></div>
              </form>
            </div>
          </div>
        </div>
      </li>
    </ol>
  </div>
</div>
<div style="clear:both;"></div>
<!--content/checkout/checkout_confirmation.php end-->