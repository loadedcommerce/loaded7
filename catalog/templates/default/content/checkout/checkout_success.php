<?php
/*
  $Id: checkout_success.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/ 
include_once('templates/' . $lC_Template->getCode() . '/classes/success.php');
$oID = lC_Success::getOrderID($lC_Customer->getID());
?>
<!--CHECKOUT SUCCESS SECTION STARTS-->
<div id="checkout_success_details" class="full_page">
  <!--CHECKOUT SUCCESS DETAILS STARTS-->
  <h5><?php echo $lC_Language->get('text_checkout'); ?></h5>
  <div class="checkout_steps">
    <ol id="checkoutSteps">
      <li class="first-checkout-li">
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h2>
        </div>
      </li>
      <li>
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h2>
        </div>
      </li>
      <li>
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h2>
        </div>
      </li>  
      <li class="section allow active">
        <div class="step-title">
          <h2><?php echo $lC_Language->get('box_ordering_steps_complete'); ?></h2>
        </div> 
        <div id="checkout-step-login">
          <div class="col2-set">
            <div class="short-code msg success"><span><h5><?php echo $lC_Language->get('success_heading'); ?></h5></span></div>
            <div style="clear:both;">&nbsp;</div>
            <div id="checkout_shipping_col1" style="width:35%; float:left;">
              <!--SHIP TO ADDRESS BLOCK STARTS-->
              <div id="ship-to-address-block">
                <h3><?php echo $lC_Language->get('ship_to_address'); ?></h3>
                <span id="ship-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?></span>
              </div>
              <!--SHIP TO ADDRESS BLOCK ENDS-->
              <!--SHIPPING METHOD BLOCK STARTS-->
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
              <!--SHIPPING METHOD BLOCK ENDS-->
              <!--BILL TO ADDRESS BLOCK STARTS-->
              <div id="bill-to-address-block">
                <h3><?php echo $lC_Language->get('bill_to_address'); ?></h3>
                <span id="bill-to-span"><?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?></span>
              </div>
              <!--BILL TO ADDRESS BLOCK ENDS-->
              <!--PAYMENT METHOD BLOCK STARTS-->
              <div id="payment-method-block">  
                <h3><?php echo $lC_Language->get('payment_method_heading'); ?></h3>
                <p><?php echo lC_Success::getPaymentMethod($oID); ?></p>
              </div>
              <!--PAYMENT METHOD BLOCK ENDS-->
            </div>
            <div id="checkout_shipping_col2" style="width:60%; float:right;">
              <form name="order" id="order" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'success=update', 'SSL'); ?>" method="post">
                <div id="success-products-listing-heading">
                  <h3 id="success-order-number"><?php echo $lC_Language->get('checkout_order_number') . '&nbsp;' . $_SESSION['cartID']; ?></h3>
                  <h3 id="success-order-id"><?php echo $lC_Language->get('checkout_order_id') . '&nbsp;' . $_SESSION['cartID']; ?></h3>
                </div>
                <div style="clear:both;"></div>
                <div id="success-products-listing">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                      foreach (lC_Success::getOrderProducts($oID) as $products) {
                        echo '              <tr class="success-products-listing-row">' . "\n" .
                             '                <td width="30"><b>' . $products['quantity'] . '&nbsp;x&nbsp;</b></td>' . "\n" .
                             '                <td><b>' . $products['name'] . '</b><br /><span class="confirmation-products-listing-model">' . $lC_Language->get('listing_model_heading') . ': ' . $products['model'] . '</span>';
                        if ( lC_Success::isVariant($products['id']) === true ) {
                          foreach ( lC_Success::getVariants($products['id']) as $variant) {
                            echo '<br /><span class="confirmation-products-listing-model">- ' . $variant['group_title'] . ': ' . $variant['value_title'] . '</span>';
                          }
                        }
                        echo '</td>' . "\n";
                        echo '                <td style="float:right;"><b>' . $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']) . '</b></td>' . "\n" .
                             '              </tr>' . "\n";
                      }
                    ?>
                  </table>
                </div>
                <div style="clear:both;"></div>
                <div class="col2-set" id="success-comment-order-total">
                  <div id="success-order-comment">
                    <h3><?php echo $lC_Language->get('order_comment_title'); ?></h3>
                    <div id="success-order-comment-inner">
                      <?php echo lC_Success::getOrderComments($oID); ?>
                    </div>
                  </div>
                  <div id="success-order-totals">
                    <h3><?php echo $lC_Language->get('order_totals_title'); ?></h3>
                    <div id="ot-container">
                    <?php 
                      foreach (lC_Success::getOrderTotals($oID) as $module) {
                    ?>
                    <div class="ot-block" id="<?php echo $module['code']; ?>">
                      <label><?php echo $module['title']; ?></label>
                      <span><?php echo $module['text']; ?></span>
                    </div>
                    <div style="clear:both;"></div>
                    <?php
                      } 
                    ?>
                    </div>
                  </div>
                </div>
                <div style="clear:both;">&nbsp;</div>
                <!--CHECKOUT SUCCESS ACTIONS ENDS-->
                <div id="checkoutSuccessActions">
                  <span class="buttonRight"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_confirm_order'); ?></button></span>
                </div>
                <!--CHECKOUT SUCCESS ACTIONS ENDS-->
                <div style="clear:both;">&nbsp;</div>
              </form>
              <div id="success-additional-info">
                <!--CHECKOUT SUCCESS NOTIFICATION ENDS-->
                <div id="checkoutSuccessNotification" class="borderPadMe">
                <?php
                  $products_array = lC_Success::globalNotifications($lC_Customer->getID());
                  if (isset($products_array) && !empty($products_array)) {
                    echo $lC_Language->get('add_selection_to_product_notifications') . '<br /><p class="productsNotifications">';
                    $products_displayed = array();
                    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
                      if (!in_array($products_array[$i]['id'], $products_displayed)) {
                        echo lc_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br />';
                        $products_displayed[] = $products_array[$i]['id'];
                      }
                    }
                    echo '</p>';     
                  } else {
                    echo sprintf($lC_Language->get('view_order_history'), lc_href_link(FILENAME_ACCOUNT, null, 'SSL'), lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL')) . '<br /><br />' . sprintf($lC_Language->get('contact_store_owner'), lc_href_link(FILENAME_INFO, 'contact'));
                  }
                ?>
                </div> 
                <div style="clear:both;">&nbsp;</div>
                <!--CHECKOUT SUCCESS NOTIFICATION ENDS-->
                <?php
                  if (DOWNLOAD_ENABLED == '1') {
                ?>
                <!--CHECKOUT SUCCESS DOWNLOADS STARTS-->
                <div id="checkoutSuccessDownloads">
                  <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <?php
                    if (file_exists(DIR_FS_TEMPLATE . 'modules/downloads.php')) {
                      require(DIR_FS_TEMPLATE . 'modules/downloads.php');
                    } else {
                      require('includes/modules/downloads.php'); 
                    }    
                    ?>
                  </table>
                </div>
                <!--CHECKOUT SUCCESS DOWNLOADS ENDS-->
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
<!--CHECKOUT SUCCESS SECTION ENDS-->
