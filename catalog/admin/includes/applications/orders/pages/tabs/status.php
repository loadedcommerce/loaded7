<?php
/*
  $Id: status.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
          <div id="section_orders_status">
            <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('section_status_history'); ?></h3>
            <div class="with-padding">
              <div class="btop-anthracite"><?php echo lC_Orders_Admin::getOrderStatusHistory($_GET[$lC_Template->getModule()]); ?></div>
            </div>
          </div>
