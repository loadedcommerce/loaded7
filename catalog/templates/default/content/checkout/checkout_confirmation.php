<?php
/*
  $Id: checkout_confirmation.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--CHECKOUT PAYMENT SECTION STARTS-->
  <div id="checkout_confirmation_details" class="full_page">
    <!--CHECKOUT PAYMENT DETAILS STARTS-->
    <div class="content">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <div id="checkoutConfirmationDetails">
          <div>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="25%" valign="top" class="action_buttonbar">
                  <?php
                    if ($lC_ShoppingCart->hasShippingAddress()) {
                  ?>
                  <p><?php echo '<b>' . $lC_Language->get('order_delivery_address_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></p>
                  <p class="bottom10"><?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?></p>
                  <?php
                      if ($lC_ShoppingCart->hasShippingMethod()) {
                  ?>
                  <p><?php echo '<b>' . $lC_Language->get('order_shipping_method_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></p>
                  <p class="bottom10"><?php echo $lC_ShoppingCart->getShippingMethod('title'); ?></p>
                  <?php
                      }
                    }
                  ?>
                  <p><?php echo '<b>' . $lC_Language->get('order_billing_address_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></p>
                  <p class="bottom10"><?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?></p>
                  <p><?php echo '<b>' . $lC_Language->get('order_payment_method_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></p>
                  <p><?php echo $lC_ShoppingCart->getBillingMethod('title'); ?></p>
                </td>
                <td width="5%" valign="top"></td>
                <td width="70%" valign="top">
                  <div style="border: 1px; border-style: solid; border-color: #CCCCCC; background-color: #FBFBFB; padding: 10px;">
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <?php
                        if ($lC_ShoppingCart->numberOfTaxGroups() > 1) {
                      ?>
                      <tr>
                        <td colspan="2"><?php echo '<b>' . $lC_Language->get('order_products_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></td>
                        <td align="right"><b><?php echo $lC_Language->get('order_tax_title'); ?></b></td>
                        <td align="right"><b><?php echo $lC_Language->get('order_total_title'); ?></b></td>
                      </tr>
                      <?php
                        } else {
                      ?>
                      <tr>
                        <td colspan="4"><?php echo '<b>' . $lC_Language->get('order_products_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></td>
                      </tr>
                      <?php
                        }
                        foreach ($lC_ShoppingCart->getProducts() as $products) {
                          echo '              <tr>' . "\n" .
                               '                <td width="30">' . $products['quantity'] . '&nbsp;x&nbsp;</td>' . "\n" .
                               '                <td>' . $products['name'];
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
                            echo '                <td style="float:right;">' . lC_Tax::displayTaxRateValue($products['tax']) . '</td>' . "\n";
                          }
                          echo '                <td style="float:right;">' . $lC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity']) . '</td>' . "\n" .
                               '              </tr>' . "\n";
                        }
                      ?>
                    </table>
                    <p>&nbsp;</p>
                    <table border="0" width="100%" cellspacing="0" cellpadding="4">
                    <?php
                      // if ($lC_OrderTotal->hasActive()) {
                      //   foreach ($lC_OrderTotal->getResult() as $module) {
                      foreach ($lC_ShoppingCart->getOrderTotals() as $module) {
                        echo '              <tr>' . "\n" .
                             '                <td style="float:right; width:20%; text-align:right;">' . $module['text'] . '</td>' . "\n" .
                             '                <td style="float:right;">' . $module['title'] . '</td>' . "\n" .
                             '              </tr>';
                      }
                      //  }
                      //  }
                    ?>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
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
          if (isset($_SESSION['comments']) && !empty($_SESSION['comments'])) {
        ?>
        <div id="checkoutConfirmationComments">
          <h6><?php echo '<b>' . $lC_Language->get('order_comments_title') . '</b> ' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'), '<span class="orderEdit">' . $lC_Language->get('order_text_edit_title') . '</span>'); ?></h6>
          <div>
            <?php echo nl2br(lc_output_string_protected($_SESSION['comments'])) . lc_draw_hidden_field('comments', $_SESSION['comments']); ?>
          </div>
        </div>
        <?php
          }
          if ($lC_Payment->hasActionURL()) {
            $form_action_url = $lC_Payment->getActionURL();
          } else {
            $form_action_url = lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL');
          }
          echo '<form name="checkout_confirmation" id="checkout_confirmation" on" action="' . $form_action_url . '" method="post">';
          if ($lC_Payment->hasActive()) {
            echo $lC_Payment->process_button();
          }
        ?>
        <div style="clear:both;">&nbsp;</div>
        <div id="checkoutConfirmationActions" class="action_buttonbar">
          <span class="buttonRight"><a onclick="$('#checkout_confirmation').submit();"><button class="checkout" type="submit"><?php echo $lC_Language->get('button_confirm_order'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
      <!--CHECKOUT PAYMENT ACTIONS ENDS-->
      </div>
      </form>
    </div>
  </div>
  <div style="clear:both;"></div>
  <!--CHECKOUT PAYMENT DETAILS ENDS-->
<!--CHECKOUT PAYMENT SECTION ENDS-->