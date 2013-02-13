<?php
/**  
*  $Id: account_history.php v1.0 2013-01-01 datazen $
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
?>
<!--content/account/account_history.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1> 
      <div class="borderPadMe">
        <table border="0" class="cart" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <th style="border-bottom:1px solid #cecece; width:5px;"></th>
            <th style="border-bottom:1px solid #cecece; width:35px; padding-right:5px;"><?php echo $lC_Language->get('text_view'); ?></th>
            <th style="border-bottom:1px solid #cecece;"><?php echo $lC_Language->get('text_number'); ?></th>
            <th style="border-bottom:1px solid #cecece;"><?php echo $lC_Language->get('order_shipped_to'); ?></th> 
            <th style="border-bottom:1px solid #cecece;"><?php echo $lC_Language->get('text_date'); ?></th>
            <th style="border-bottom:1px solid #cecece;"><?php echo $lC_Language->get('text_status'); ?></th>  
            <th style="border-bottom:1px solid #cecece;"><?php echo $lC_Language->get('text_items'); ?></th>
            <th style="border-bottom:1px solid #cecece; text-align:right;"><?php echo $lC_Language->get('text_total'); ?></th>
            <th style="border-bottom:1px solid #cecece; width:5px;"></th>
          </tr>
          <?php
            if (lC_Order::numberOfEntries() > 0) {
              $class = 'accountHistory-odd';              
              $Qhistory = lC_Order::getListing(MAX_DISPLAY_ORDER_HISTORY);
              while ($Qhistory->next()) {
                if (!lc_empty($Qhistory->value('delivery_name'))) {
                  $order_type = $lC_Language->get('order_shipped_to');
                  $order_name = $Qhistory->value('delivery_name');
                } else {
                  $order_type = $lC_Language->get('order_billed_to');
                  $order_name = $Qhistory->value('billing_name');
                }          
                $class = ($class == 'accountHistory-even' ? 'accountHistory-odd' : 'accountHistory-even'); 
              ?>
              <tr>
                <td style="height:5px;" class="<?php echo $class; ?>" colspan="9"></td>
              </tr>
              <tr id="orderListingRow" class="<?php echo $class; ?>" href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'receipt=' . $Qhistory->valueInt('orders_id'), 'SSL'); ?>">
                <td style="width:5px;"></td>
                <td style="width:35px; padding-right:5px; vertical-align:bottom; text-align:center;">

                  <?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'search_btn.png', $lC_Language->get('text_view')); ?>

                </td>
                <td><?php echo $Qhistory->valueInt('orders_id'); ?></td>                
                <td><?php echo lc_output_string_protected($order_name); ?></td> 
                <td><?php echo lC_DateTime::getShort($Qhistory->value('date_purchased')); ?></td>
                <td><?php echo $Qhistory->value('orders_status_name'); ?></td> 
                <td><?php echo lC_Order::numberOfProducts($Qhistory->valueInt('orders_id')); ?></td>
                <td style="text-align:right;"><?php echo strip_tags($Qhistory->value('order_total')); ?></td>
                <td style="width:5px;"></td>
              </tr>
              <?php 
              }
            } else {
              echo $lC_Language->get('no_orders_made_yet');
            }
          ?>
        </table>
        <script>
          $(document).ready(function(){
              $('table tr').click(function(){
                  window.location = $(this).attr('href');
                  return false;
              });
          });
        </script>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="accountHistoryActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_go_shopping'); ?></button></a></span>
      </div>        
      <div style="clear:both;"></div>
    </div>
  </div>
</div>
<!--content/account/account_history.php end-->