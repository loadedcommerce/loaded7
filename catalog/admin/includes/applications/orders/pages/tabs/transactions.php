<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: transactions.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_orders_transactions" style="display:none;">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_transactions'); ?></h3>
  <div class="with-padding">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table">
      <thead>
        <tr>
          <td><?php echo $lC_Language->get('table_heading_date_added'); ?></td>
          <td><?php echo $lC_Language->get('table_heading_status'); ?></td>
          <td width="10%"><?php echo $lC_Language->get('table_heading_comments'); ?></td>
        </tr>
      </thead>
      <tbody>
                  <?php echo lC_Orders_Admin::getOrderTransactions($oInfo->get('oID')); ?>
      </tbody>
    </table>
  </div>
</div>