<?php
/**  
*  $Id: account_history_info.php v1.0 2013-01-01 datazen $
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
$order = new lC_Order($_GET['orders']);
?>
<!--content/account/account_history_info.php start-->
<div class="moduleBox">
  <div class="top">
    <div class="icon"><?php echo lc_icon('icon_orders.png', $lC_Template->getPageTitle()); ?></div>
    <div class="title"><?php echo $lC_Template->getPageTitle(); ?></div>
  </div>
  <div class="content">
    <div class="contentBorder">
      <span style="float: right;"><h6 style="text-decoration:none;"><b><?php echo $lC_Language->get('order_total_heading') . '</b> ' . $order->info['total']; ?></h6></span>
      <h6 style="text-decoration:none;"><b><?php echo  $lC_Language->get('order_date_heading') . '</b> ' . lC_DateTime::getLong($order->info['date_purchased']) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?></h6>
      <div>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="30%" valign="top">
              <?php
              if ($order->delivery != false) {
                ?>
                <h6><?php echo $lC_Language->get('order_delivery_address_title'); ?></h6>
                <p><?php echo lC_Address::format($order->delivery, '<br />'); ?></p>
                <?php
                if (!empty($order->info['shipping_method'])) {
                  ?>
                  <h6><?php echo $lC_Language->get('order_shipping_method_title'); ?></h6>
                  <p><?php echo $order->info['shipping_method']; ?></p>
                  <?php
                }
              }
              ?>
              <h6><?php echo $lC_Language->get('order_billing_address_title'); ?></h6>
              <p><?php echo lC_Address::format($order->billing, '<br />'); ?></p>
              <h6><?php echo $lC_Language->get('order_payment_method_title'); ?></h6>
              <p><?php echo $order->info['payment_method']; ?></p>
            </td>
            <td width="70%" valign="top">
              <div style="border: 1px; border-style: solid; border-color: #CCCCCC; background-color: #FBFBFB; padding: 5px;">
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
                  if (sizeof($order->info['tax_groups']) > 1) {
                    ?>
                    <tr>
                      <td colspan="2"><h6><?php echo $lC_Language->get('order_products_title'); ?></h6></td>
                      <td align="right"><h6><?php echo $lC_Language->get('order_tax_title'); ?></h6></td>
                      <td align="right"><h6><?php echo $lC_Language->get('order_total_title'); ?></h6></td>
                    </tr>
                    <?php
                  } else {
                    ?>
                    <tr>
                      <td colspan="3"><h6><?php echo $lC_Language->get('order_products_title'); ?></h6></td>
                    </tr>
                    <?php
                  }
                  foreach ($order->products as $product) {
                    echo '              <tr>' . "\n" .
                         '                <td align="right" valign="top" width="30">' . $product['qty'] . '&nbsp;x</td>' . "\n" .
                         '                <td valign="top">' . $product['name'];
                    if (isset($product['attributes']) && (sizeof($product['attributes']) > 0)) {
                      foreach ($product['attributes'] as $attribute) {
                        echo '<br /><nobr><small>&nbsp;<i> - ' . $attribute['option'] . ': ' . $attribute['value'] . '</i></small></nobr>';
                      }
                    }
                    echo '</td>' . "\n";
                    if (sizeof($order->info['tax_groups']) > 1) {
                      echo '                <td valign="top" align="right">' . lC_Tax::displayTaxRateValue($product['tax']) . '</td>' . "\n";
                    }
                    echo '                <td align="right" valign="top">' . $lC_Currencies->displayPriceWithTaxRate($product['price'], $product['tax'], $product['qty'], false, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
                         '              </tr>' . "\n";
                  }
                  ?>
                </table>
                <p>&nbsp;</p>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
                  foreach ($order->totals as $total) {
                    echo '              <tr>' . "\n" .
                         '                <td align="right">' . $total['title'] . '</td>' . "\n" .
                         '                <td align="right">' . $total['text'] . '</td>' . "\n" .
                         '              </tr>' . "\n";
                  }
                  ?>
                </table>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <?php
    $Qstatus = $order->getStatusListing();
    if ($Qstatus->numberOfRows() > 0) {
      ?>
      <div class="contentBorder">
        <h6><?php echo $lC_Language->get('order_history_heading'); ?></h6>
        <div class="content">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
            while ($Qstatus->next()) {
              echo '    <tr>' . "\n" .
                   '      <td width="16">' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'bullet_3.png') . '</td>' . "\n" .
                   '      <td valign="top" width="70">' . lC_DateTime::getShort($Qstatus->value('date_added')) . '</td>' . "\n" .
                   '      <td valign="top" width="70">' . $Qstatus->value('orders_status_name') . '</td>' . "\n" .
                   '      <td valign="top">' . (!lc_empty($Qstatus->valueProtected('comments')) ? nl2br($Qstatus->valueProtected('comments')) : '&nbsp;') . '</td>' . "\n" .
                   '    </tr>' . "\n";
            }
            ?>
          </table>
        </div>
      </div>
      <?php
    }
    if (DOWNLOAD_ENABLED == '1') {
      if (file_exists(DIR_FS_TEMPLATE . 'includes/modules/downloads.php')) {
          require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'includes/modules/downloads.php'));
        } else {
          require($lC_Vqmod->modCheck('includes/modules/downloads.php')); 
      }    
    }
    ?>
    <div class="submitFormButtons"> 
      <div class="buttons">
        <div style="float:left;"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'orders' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : ''), 'SSL'); ?>" class="button"><span><?php echo $lC_Language->get('button_back'); ?></span></a></div> 
        <div style="float:right;"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>" class="button"><span><?php echo $lC_Language->get('button_go_shopping'); ?></span></a></div>
      </div>  
    </div>
  </div>
  <div class="bottom"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'content_bottom.png'); ?></div>
</div>
<!--content/account/account_history_info.php end-->