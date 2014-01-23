<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: status.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_orders_status" style="display:none;">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('section_status_history'); ?></h3>
  <div class="with-padding">
              <div class="btop-anthracite"><?php echo lC_Orders_Admin::getOrderStatusHistory($oInfo->get('oID')); ?></div>
  </div>
</div>