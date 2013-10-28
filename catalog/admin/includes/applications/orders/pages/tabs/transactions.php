<?php
/*
  $Id: transactions.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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
                  <?php echo lC_Orders_Admin::getOrderTransactions($_GET[$lC_Template->getModule()]); ?>
                </tbody>
              </table>
            </div>
          </div>
