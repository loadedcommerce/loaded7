<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: receipt.php v1.0 2013-08-08 datazen $
*/
require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
$order = new lC_Order($_GET['receipt']); 
?>
<!--content/info/receipt.php start-->
<div class="large-margin-top">

  <div class="row">
    <div class="col-sm-3 col-lg-3">
      <h1 class="logo"><a href="<?php echo lc_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img alt="<?php echo STORE_NAME; ?>" src="<?php echo DIR_WS_TEMPLATE_IMAGES; ?>logo.png" /></a></h1>
    </div>
    <div class="col-sm-9 col-lg-9 text-right"> 
      <address>
        <strong><?php echo STORE_NAME; ?></strong><br>
        <?php echo nl2br(STORE_NAME_ADDRESS); ?>
      </address> 
    </div>
  </div>
  
  <div class="row clearfix">
    <div class="col-sm-3 col-lg-3">
      <address>
        <strong><?php echo $lC_Language->get('receipt_billing_address_title'); ?></strong><br/>
        <?php echo lC_Address::format($order->billing, '<br />'); ?>
      </address>
    </div>
    <div class="col-sm-3 col-lg-3">
      <address>
       <strong><?php echo $lC_Language->get('receipt_delivery_address_title'); ?></strong><br/>
        <?php echo lC_Address::format($order->delivery, '<br />'); ?>
       </address>
    </div>
    <div class="col-sm-6 col-lg-6">
      <div class="well text-right">
        <h3 class="no-margin-top"><?php echo $lC_Language->get('receipt_order_number_title'); ?> <?php echo $_GET['receipt']; ?></h3>
        <div><strong><?php echo $lC_Language->get('receipt_order_date_title'); ?></strong> <?php echo lC_DateTime::getShort($order->info['date_purchased']); ?></div>
        <div><strong><?php echo $lC_Language->get('receipt_order_status_title'); ?></strong> <?php echo  $order->info['orders_status']; ?></div>
        <div><strong><?php echo $lC_Language->get('receipt_payment_method_title'); ?></strong> <?php echo $order->info['payment_method']; ?></div>        
      </div>
    </div>
  </div>   
  
  <div class="row">
    <div class="col-sm-12 col-lg-12">
      <table id="content-receipt-products-table" class="table table-striped table-hover no-margin-bottom">
        <?php
          if (sizeof($order->info['tax_groups']) > 1) {
            ?>
            <tr>
              <th colspan="2" class="content-receipt-title"><?php echo $lC_Language->get('order_products_title'); ?></th>
              <th><?php echo $lC_Language->get('order_tax_title'); ?></th>
              <th class="text-right"><?php echo $lC_Language->get('order_total_title'); ?></th>
            </tr>
            <?php
          } else {
            ?>
            <tr>
              <th colspan="2" class="content-receipt-title"><?php echo $lC_Language->get('receipt_products_title'); ?></th>
              <th class="text-right"><?php echo $lC_Language->get('receipt_products_totals'); ?></th>
            </tr>
            <?php
          }
          foreach ($order->products as $product) {
            ?>
            <tr>
              <td class="products-listing-separator"><?php echo $product['qty']; ?>&nbsp;x</td>
              <td><?php echo $product['name']; 
                if (isset($product['attributes']) && (sizeof($product['attributes']) > 0)) {
                  foreach ($product['attributes'] as $attribute) {
                    echo '<br /><nobr><small>&nbsp;<i> - ' . $attribute['option'] . ': ' . $attribute['value'] . '</i></small></nobr>';
                  }
                }  
                ?>
              </td>
              <?php
              if (sizeof($order->info['tax_groups']) > 1) {
                echo '<td class="text-right">' . lC_Tax::displayTaxRateValue($product['tax']) . '</td>' . "\n";
              }
              ?>
              <td class="text-right"><?php echo $lC_Currencies->displayPriceWithTaxRate($product['price'], $product['tax'], $product['qty'], false, $order->info['currency'], $order->info['currency_value']); ?></td>
            </tr>
            <?php
          }
        ?>
      </table>
      <div id="content-receipt-totals-container" class="small-margin-right small-padding-right">
        <?php
          foreach ($order->totals as $total) {              
            echo '<div class="text-right margin-bottom"><span class="margin-right">' . $total['title'] . '</span><span class="">' . $total['text'] . '</span></div>' . "\n";
          }
        ?>
      </div>      

    </div>
  </div>       
  <div class="button-set clearfix large-margin-top">
    <button onclick="javascript:window.print();" class="pull-right btn btn-lg btn-success" type="button"><?php echo $lC_Language->get('text_print'); ?></button>
    <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
  </div>   
</div>    
<!--content/account/receipt.php end-->