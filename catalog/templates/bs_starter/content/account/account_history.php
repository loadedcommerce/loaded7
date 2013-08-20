<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account_history.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/account_history.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1> 
    <div class="">
      <table border="0" id="content-account-history-table" class="table table-striped table-hover" width="100%" cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th><?php echo $lC_Language->get('text_view'); ?></th>
            <th><?php echo $lC_Language->get('text_number'); ?></th>
            <th><?php echo $lC_Language->get('order_shipped_to'); ?></th> 
            <th><?php echo $lC_Language->get('text_date'); ?></th>
            <th><?php echo $lC_Language->get('text_status'); ?></th>  
            <th><?php echo $lC_Language->get('text_items'); ?></th>
            <th><?php echo $lC_Language->get('text_total'); ?></th>
          </tr>
        </thead>
        <tbody>
        <?php
        if (lC_Order::numberOfEntries() > 0) {

          $Qhistory = lC_Order::getListing(MAX_DISPLAY_ORDER_HISTORY);

          while ($Qhistory->next()) {
            if (!lc_empty($Qhistory->value('delivery_name'))) {
              $order_type = $lC_Language->get('order_shipped_to');
              $order_name = $Qhistory->value('delivery_name');
            } else {
              $order_type = $lC_Language->get('order_billed_to');
              $order_name = $Qhistory->value('billing_name');
            }          
          ?>
          <tr>
            <td><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'receipt=' . $Qhistory->valueInt('orders_id'), 'SSL'); ?>"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'icons/16/search.png', $lC_Language->get('text_view')); ?></a></td>
            <td><?php echo $Qhistory->valueInt('orders_id'); ?></td>                
            <td><?php echo lc_output_string_protected($order_name); ?></td> 
            <td><?php echo lC_DateTime::getShort($Qhistory->value('date_purchased')); ?></td>
            <td><?php echo $Qhistory->value('orders_status_name'); ?></td> 
            <td><?php echo lC_Order::numberOfProducts($Qhistory->valueInt('orders_id')); ?></td>
            <td><?php echo strip_tags($Qhistory->value('order_total')); ?></td>
          </tr>
          <?php 
          }
        } else {
          echo $lC_Language->get('no_orders_made_yet');
        }
        ?>
        </tbody>
      </table>
    </div>
    <div class="btn-set small-margin-top clearfix">
      <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>"><button class="pull-right btn btn-lg btn-primary" type="button"><?php echo $lC_Language->get('button_go_shopping'); ?></button></a>
      <a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><button class="pull-left btn btn-lg btn-primary" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
    </div>  
  </div>
</div>
<!--content/account/account_history.php end-->