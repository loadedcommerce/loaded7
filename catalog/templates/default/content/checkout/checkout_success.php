<?php
  /**  
  *  $Id: checkout_success.php v1.0 2013-01-01 datazen $
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
  $oID = lC_Success::getOrderID($lC_Customer->getID());
?>
<!--content/checkout/checkout_success.php start-->
<div id="checkout_success_details" class="full_page">
  <h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
  <div class="checkout_steps">
    <ol id="checkoutSteps">
    <li class="section allow active first-checkout-li">
    <div class="step-title">
      <h2><?php echo $lC_Language->get('box_ordering_steps_complete'); ?></h2>
    </div> 
    <div id="checkout-step-login">
      <div class="col2-set">
        <div class="short-code msg success"><span><h5><?php echo $lC_Language->get('success_heading'); ?></h5></span></div>
        <div style="clear:both;">&nbsp;</div>
        <div id="mobile-order-number-id"> 
          <h3><?php echo $lC_Language->get('checkout_order_number') . '<span class="mobile-order-number-id-span">' . $_SESSION['cartID']; ?></span></h3>
          <h3><?php echo $lC_Language->get('checkout_order_id') . '<span class="mobile-order-number-id-span">' . $oID; ?></span></h3>
        </div>
        <div id="checkout_shipping_col1" style="width:35%; float:left;">
          <div id="ship-to-address-block">
            <h3><?php echo $lC_Language->get('ship_to_address'); ?></h3>
            <span id="ship-to-span"><?php echo lC_Address::format(lC_Success::getShippingAddress($oID, $lC_Customer->getID()), '<br />'); ?></span>
          </div>
          <div id="shipping-method-block">  
            <h3><?php echo $lC_Language->get('shipping_method_heading'); ?></h3>
            <p>
              <?php 
                foreach (lC_Success::getOrderTotals($oID) as $module) {
                  if ($module['class'] == 'shipping') {
                    echo $module['title']; 
                  }                    
                } 
              ?>
            </p>
          </div>
          <div id="bill-to-address-block">
            <h3><?php echo $lC_Language->get('bill_to_address'); ?></h3>
            <span id="bill-to-span"><?php echo lC_Address::format(lC_Success::getBillingAddress($oID, $lC_Customer->getID()), '<br />'); ?></span>
          </div>
          <div id="payment-method-block">  
            <h3><?php echo $lC_Language->get('payment_method_heading'); ?></h3>
            <p><?php echo lC_Success::getPaymentMethod($oID); ?></p>
          </div>
          <div id="order-comment-block" style="width:91%;">
            <h3><?php echo $lC_Language->get('order_comment_title'); ?></h3>
            <div id="success-order-comment-inner">
              <?php echo (lC_Success::getOrderComments($oID) != null) ? lC_Success::getOrderComments($oID) : $lC_Language->get('order_comment_none'); ?>
            </div>
          </div> 
        </div>
        <div id="checkout_shipping_col2" style="width:60%; float:right;">
          <form name="order" id="order" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'success=update', 'SSL'); ?>" method="post">
            <div id="success-products-listing-heading">
              <h3 id="success-order-number"><?php echo $lC_Language->get('checkout_order_number') . '&nbsp;' . $_SESSION['cartID']; ?></h3>
              <h3 id="success-order-id"><?php echo $lC_Language->get('checkout_order_id') . '&nbsp;' . $oID; ?></h3>
            </div>
            <div style="clear:both;"></div>
            <div id="success-products-listing">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <?php
                  foreach (lC_Success::getOrderProducts($oID) as $products) {
                    echo '<tr class="success-products-listing-row">' . "\n" .
                         '<td width="30"><b>' . $products['quantity'] . '&nbsp;x&nbsp;</b></td>' . "\n" .
                         '<td><b>' . $products['name'] . '</b><br /><span class="confirmation-products-listing-model purple">' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</span>';

                    if ( is_array($products['options']) && empty($products['options']) === false ) {
                      foreach ( $products['options'] as $key => $val) {
                        echo '<br /><span class="confirmation-products-listing-model">- ' . $val['group_title'] . ': ' . $val['value_title'] . '</span>';
                      }
                    }                           

                    if ( lC_Success::isVariant($products['id']) === true ) {
                      foreach ( lC_Success::getVariants($products['id']) as $variant) {
                        echo '<br /><span class="confirmation-products-listing-model">- ' . $variant['group_title'] . ': ' . $variant['value_title'] . '</span>';
                      }
                    }
                    echo '</td>' . "\n";
                    echo '                <td style="float:right;">' . $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']) . '</td>' . "\n" .
                    '              </tr>' . "\n";
                  }
                ?>
              </table>
            </div>
            <div style="clear:both;"></div>
            <div class="col2-set" id="success-comment-order-total">
              <div id="success-order-totals">
                <table id="totals-table">
                  <tbody>                  
                  <?php 
                    foreach (lC_Success::getOrderTotals($oID) as $module) { 
                      ?>
                      <tr>
                        <td class="align_right<?php if ($module['class'] == 'sub_total') echo ' sc_sub_total'; if ($module['class'] == 'total') echo ' sc_total'; ?>" style="padding-right:10px;"><?php echo $module['title']; ?></td>
                        <td class="align_right<?php if ($module['class'] == 'sub_total') echo ' sc_sub_total'; if ($module['class'] == 'total') echo ' sc_total'; ?>"><?php echo $module['text']; ?></td>
                      </tr>                        
                      <?php 
                    } 
                  ?>
                  </tbody>
                </table>               
              </div>
            </div>
            <div style="clear:both;">&nbsp;</div>
            <div id="success-additional-info">
            <div id="checkoutSuccessNotification" class="borderPadMe">
              <?php
                $products_array = lC_Success::globalNotifications($lC_Customer->getID());
                if (isset($products_array) && !empty($products_array)) {
                  echo $lC_Language->get('add_selection_to_product_notifications') . '<br />';
                  $products_displayed = array();
                  for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
                    if (!in_array($products_array[$i]['id'], $products_displayed)) {
                      echo '<div class="productsNotifications">' . lc_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' <b>' . $products_array[$i]['text'] . '</b></div>';
                      $products_displayed[] = $products_array[$i]['id'];
                    }
                  }
                } else {
                  echo sprintf($lC_Language->get('view_order_history'), lc_href_link(FILENAME_ACCOUNT, null, 'SSL'), lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL')) . '<br /><br />' . sprintf($lC_Language->get('contact_store_owner'), lc_href_link(FILENAME_INFO, 'contact'));
                }
              ?>
            </div> 
          </form>
          <div id="checkoutSuccessActions" style="margin-top:20px;">
            <span class="buttonRight"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></span>
          </div>
          <div style="clear:both;">&nbsp;</div>
          <?php
            if (DOWNLOAD_ENABLED == '1') {
            ?>
            <div id="checkoutSuccessDownloads">
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <?php
                  if (file_exists(DIR_FS_TEMPLATE . 'modules/downloads.php')) {
                    require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/downloads.php'));
                  } else {
                    require($lC_Vqmod->modCheck('includes/modules/downloads.php')); 
                  }    
                ?>
              </table>
            </div>
            <?php
            }
          ?>
          <div style="clear:both;">&nbsp;</div>
        </div>
      </div>
    </div>
  </div>
  </li>
  </ol>
  </div>
</div>
<div style="clear:both;"></div>
<!--content/checkout/checkout_success.php end-->