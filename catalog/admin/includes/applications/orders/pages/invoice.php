<?php
  /*
  $Id: invoice.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
  require_once('../includes/classes/currencies.php');
  $lC_Currencies = new lC_Currencies();
  require_once('includes/classes/tax.php');
  $lC_Tax = new lC_Tax_Admin();
  $lC_Order = new lC_Order($_GET['oid']);
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding-no-top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2" class="table responsive-table">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
              <td class="pageHeading" align="right"><?php echo lc_image('../images/store_logo.jpg', STORE_NAME); ?></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><b><?php echo $lC_Language->get('subsection_billing_address'); ?></b></td>
                  </tr>
                  <tr>
                    <td><?php echo lC_Address::format($lC_Order->getBilling(), '<br />'); ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><?php echo $lC_Order->getCustomer('telephone'); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo '<a href="mailto:' . $lC_Order->getCustomer('email_address') . '"><u>' . $lC_Order->getCustomer('email_address') . '</u></a>'; ?></td>
                  </tr>
                </table></td>
              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><b><?php echo $lC_Language->get('subsection_shipping_address'); ?></b></td>
                  </tr>
                  <tr>
                    <td><?php echo lC_Address::format($lC_Order->getDelivery(), '<br />'); ?></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td><b><?php echo $lC_Language->get('subsection_payment_method'); ?></b></td>
              <td><?php echo $lC_Order->getPaymentMethod(); ?></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
            <thead>
              <tr>
                <th colspan="2"><?php echo $lC_Language->get('table_heading_products'); ?></th>
                <th><?php echo $lC_Language->get('table_heading_product_model'); ?></th>
                <th><?php echo $lC_Language->get('table_heading_tax'); ?></th>
                <th><?php echo $lC_Language->get('table_heading_price_net'); ?></th>
                <th><?php echo $lC_Language->get('table_heading_price_gross'); ?></th>
                <th><?php echo $lC_Language->get('table_heading_total_net'); ?></th>
                <th><?php echo $lC_Language->get('table_heading_total_gross'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($lC_Order->getProducts() as $product) {
                  echo '        <tr>' . "\n" .
                  '          <td valign="top" align="right">' . $product['quantity'] . '&nbsp;x</td>' . "\n" .
                  '          <td valign="top">' . $product['name'];
                  if (isset($product['attributes']) && (sizeof($product['attributes']) > 0)) {
                    foreach ($product['attributes'] as $attribute) {
                      echo '<br /><nobr>&nbsp;&nbsp;&nbsp;' . $attribute['option'] . ': ' . $attribute['value'] . '</nobr>';
                    }
                  }
                  echo '          </td>' . "\n" .
                  '          <td valign="top">' . $product['model'] . '</td>' . "\n";
                  echo '          <td align="right" valign="top">' . $lC_Tax->displayTaxRateValue($product['tax']) . '</td>' . "\n" .
                  '          <td align="right" valign="top"><b>' . $lC_Currencies->format($product['price'], true, $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</b></td>' . "\n" .
                  '          <td align="right" valign="top"><b>' . $lC_Currencies->displayPriceWithTaxRate($product['price'], $product['tax'], 1, true, $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</b></td>' . "\n" .
                  '          <td align="right" valign="top"><b>' . $lC_Currencies->format($product['price'] * $product['quantity'], true, $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</b></td>' . "\n" .
                  '          <td align="right" valign="top"><b>' . $lC_Currencies->displayPriceWithTaxRate($product['price'], $product['tax'], $product['quantity'], true, $lC_Order->getCurrency(), $lC_Order->getCurrencyValue()) . '</b></td>' . "\n";
                  echo '        </tr>' . "\n";
                }
              ?>
            </tbody>
          </table>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
              foreach ($lC_Order->getTotals() as $total) {
                echo '      <tr>' . "\n" .
                '        <td align="right">' . $total['title'] . '</td>' . "\n" .
                '        <td align="right">' . $total['text'] . '</td>' . "\n" .
                '      </tr>' . "\n";
              }
            ?>
          </table></td>
      </tr>
    </table>
    <div class="clear-both"></div>
  </div>
</section>
<?php 
  if (isset($_SESSION['error'])) unset($_SESSION['error']);
  if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);
  $lC_Template->loadModal($lC_Template->getModule());
?>
<!-- End main content -->