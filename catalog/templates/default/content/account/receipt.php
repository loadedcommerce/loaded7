<?php
/**  
*  $Id: receipt.php v1.0 2013-01-01 datazen $
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
require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
$order = new lC_Order($_GET['receipt']); 
?>
<!--content/account/receipt.php start-->
<div class="invoiceScreen">
  <div id="invoice">
    <div id="invoice-header"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'logo.png'); ?>
      <div class="vcard" id="company-address">
        <div class="fn org"><strong><?php echo STORE_NAME;?></strong></div>
        <div class="adr">
          <div class="street-address"><?php echo nl2br(STORE_NAME_ADDRESS); ?></div>
        </div>
      </div>
    </div>
    <div id="invoice-info">
      <h2><?php echo $lC_Language->get('receipt_order_number_title'); ?> <?php echo $order->_id; ?></h2>
      <p id="payment-terms"><?php echo $lC_Language->get('receipt_order_date_title'); ?> <?php echo lC_DateTime::getShort($order->info['date_purchased']); ?></p>
      <p id="payment-terms"><?php echo $lC_Language->get('receipt_order_status_title'); ?> <?php echo  $order->info['orders_status']; ?></p>
      <p id="payment-terms"><?php echo $lC_Language->get('receipt_payment_method_title'); ?> <?php echo $order->info['payment_method']; ?></p>
    </div>
    <div class="vcard" id="client-details"> <b><?php echo $lC_Language->get('receipt_billing_address_title'); ?></b><br/>
      <?php echo lC_Address::format($order->billing, '<br />'); ?>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div id="invoice-amount">
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <?php
          if (sizeof($order->info['tax_groups']) > 1) {
          ?>
          <tr id="header_row">
            <th colspan="2" class="receiptTitle"><?php echo $lC_Language->get('order_products_title'); ?></th>
            <th align="right"><?php echo $lC_Language->get('order_tax_title'); ?></th>
            <th class="subtotal_th"><?php echo $lC_Language->get('order_total_title'); ?> &nbsp;</th>
          </tr>
          <?php
          } else {
          ?>
          <tr id="header_row">
            <th colspan="2" class="receiptTitle"><?php echo $lC_Language->get('receipt_products_title'); ?></th>
            <th class="subtotal_th"><?php echo $lC_Language->get('receipt_products_totals'); ?></th>
          </tr>
          <?php
          }
          $bg = 'odd';
          foreach ($order->products as $product) {
            if($bg==''){
              $bg='odd';
            } else if($bg=='odd'){
              $bg='';
            }
            echo '<tr class="item ' . $bg . '">' . "\n" .
            '  <td align="right" class="item_l" width="30">' . $product['qty'] . '&nbsp;x</td>' . "\n" .
            '  <td valign="top">' . $product['name'];
            if (isset($product['attributes']) && (sizeof($product['attributes']) > 0)) {
              foreach ($product['attributes'] as $attribute) {
                echo '<br /><nobr><small>&nbsp;<i> - ' . $attribute['option'] . ': ' . $attribute['value'] . '</i></small></nobr>';
              }
            }
            echo '  </td>' . "\n";
            if (sizeof($order->info['tax_groups']) > 1) {
              echo '  <td class="item_l" align="right">' . lC_Tax::displayTaxRateValue($product['tax']) . '</td>' . "\n";
            }
            echo '  <td class="item_r">' . $lC_Currencies->displayPriceWithTaxRate($product['price'], $product['tax'], $product['qty'], false, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
            '</tr>' . "\n";
          }
        ?>
      </table>
      <table id="orderTotals" border="0" cellspacing="2" cellpadding="4" align="right" style="border-top:1px solid #e1e1e1; border-bottom:1px solid #e1e1e1; padding-top:5px; margin-top:20px;">
        <?php
          foreach ($order->totals as $total) {
            echo '<tr style="vertical-align: top;">' . "\n" .
            '  <td class="';
            if ($total['title'] == 'Sub-Total:') echo ' sc_sub_total'; 
            if ($total['title'] == 'Total:') echo ' sc_total'; 
            echo '" style="text-align: left; padding:3px;">';
            if ($total['title'] == 'Total:') echo '<b>';
            echo $total['title'];
            if ($total['title'] == 'Total:') echo '</b>';
            echo ' </td>' . "\n" .
            '  <td class="';
            if ($total['title'] == 'Sub-Total:') echo ' sc_sub_total'; 
            if ($total['title'] == 'Total:') echo ' sc_total'; 
            echo '" style="text-align: right; padding:3px;">' . $total['text'] . '</td>' . "\n" .
            '</tr>' . "\n";
          }
        ?>
      </table>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div id="invoice-other">
      <!--<h2>Other Information</h2> 
      Use it for some additional information-->
    </div>
    <div id="payment-details">
      <h2><?php echo $lC_Language->get('receipt_delivery_address_title'); ?></h2>
      <div id="bank_name"><?php echo lC_Address::format($order->delivery, '<br />'); ?></div>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div class="noPrint">
      <div id="accountHistoryActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="javascript:history.go(-1);" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a href="javascript:window.print();" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('text_print'); ?></button></a></span>
      </div>
    </div>
  </div>
</div>
<!--content/account/receipt.php end-->