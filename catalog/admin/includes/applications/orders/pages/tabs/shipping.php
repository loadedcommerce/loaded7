<?php
/*
  $Id: shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
          <div id="section_orders_shipping">
            <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_shipping'); ?></h3>
            <div id="section_orders_customer_content" class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                <fieldset class="fieldset">
                  <legend class="legend"><?php echo $lC_Language->get('text_create_shipment') . ' ' . lc_go_pro(); ?></legend>
                  <a class="button orders-print-packing-slip-button" href="javascript:void(0)">
                    <span class="button-icon green-gradient"><span class="icon-printer"></span></span>
                    <span class="orders-print-packing-slip-button-text"><?php echo $lC_Language->get('text_print_packing_slip'); ?></span>
                  </a>
                  <div class="columns">
                    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                      <?php echo $lC_Language->get('text_go_pro_and_enjoy'); ?>
                    </div>
                  </div> 
                </fieldset>
              </div>
            </div>
          </div>
