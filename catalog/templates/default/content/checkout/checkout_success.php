<?php
/*
  $Id: checkout_success.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$Qglobal = $lC_Database->query('select global_product_notifications from :table_customers where customers_id =:customers_id');
$Qglobal->bindTable(':table_customers', TABLE_CUSTOMERS);
$Qglobal->bindInt(':customers_id', $lC_Customer->getID());
$Qglobal->execute();
if ($Qglobal->valueInt('global_product_notifications') !== 1) {
  $Qorder = $lC_Database->query('select orders_id from :table_orders where customers_id = :customers_id order by date_purchased desc limit 1');
  $Qorder->bindTable(':table_orders', TABLE_ORDERS);
  $Qorder->bindInt(':customers_id', $lC_Customer->getID());
  $Qorder->execute();
  $Qproducts = $lC_Database->query('select products_id, products_name from :table_orders_products where orders_id = :orders_id order by products_name');
  $Qproducts->bindTable(':table_orders_products', TABLE_ORDERS_PRODUCTS);
  $Qproducts->bindInt(':orders_id', $Qorder->valueInt('orders_id'));
  $Qproducts->execute();
  $products_array = array();
  while ($Qproducts->next()) {
    $products_array[] = array('id' => $Qproducts->valueInt('products_id'),
                              'text' => $Qproducts->value('products_name'));
  }
}
?>
<!--CHECKOUT SUCCESS SECTION STARTS-->
  <div id="checkout_success_details" class="full_page">
    <!--CHECKOUT SUCCESS DETAILS STARTS-->
    <div class="content">
      <form name="order" id="order" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'success=update', 'SSL'); ?>" method="post">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <!--CHECKOUT SUCCESS MESSAGE ENDS-->
        <div id="checkoutSuccessDetails">
          <div class="short-code msg success"><span><?php echo $lC_Language->get('order_processed_successfully'); ?></span></div>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--CHECKOUT SUCCESS MESSAGE ENDS-->
        <!--CHECKOUT SUCCESS NOTIFICATION ENDS-->
        <div id="checkoutSuccessNotification" class="borderPadMe">
          <?php
          if ($Qglobal->valueInt('global_product_notifications') != 1) {
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
        <h2 style="text-align: center;"><?php echo $lC_Language->get('thanks_for_shopping_with_us'); ?></h2>
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
        <!--CHECKOUT SUCCESS ACTIONS ENDS-->
        <div id="checkoutSuccessActions" class="action_buttonbar">
          <span class="buttonRight"><a a onclick="$('#order').submit();"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_confirm_order'); ?></button></a></span>
        </div>
        <!--CHECKOUT SUCCESS ACTIONS ENDS-->
        <div style="clear:both;"></div>
      </div>
      </form>
    </div>
  </div>
  <div style="clear:both;"></div>
  <!--CHECKOUT SUCCESS DETAILS ENDS-->
<!--CHECKOUT SUCCESS SECTION ENDS-->