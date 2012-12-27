<?php
  /*
  $Id: packaging_slip.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */  
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
        <td><table border="1" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
            <thead>
              <tr>
                <th align="left" colspan="2"><?php echo $lC_Language->get('table_heading_products'); ?></th>
                <th align="left"><?php echo $lC_Language->get('table_heading_product_model'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($lC_Order->getProducts() as $product) {
                  echo '        <tr>' . "\n" .
                  '          <td colspan="2" align="left" valign="top">' . $product['quantity'] . '&nbsp;x' . "\n" .
                  '          ' . $product['name'];
                  if (isset($product['attributes']) && (sizeof($product['attributes']) > 0)) {
                    foreach ($product['attributes'] as $attribute) {
                      echo '<br /><nobr>&nbsp;&nbsp;&nbsp;' . $attribute['option'] . ': ' . $attribute['value'] . '</nobr>';
                    }
                  }
                  echo '          </td>' . "\n" .
                  '          <td valign="top">' . $product['model'] . '</td>' . "\n";
                  '        </tr>' . "\n";
                }
              ?>
            </tbody>
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