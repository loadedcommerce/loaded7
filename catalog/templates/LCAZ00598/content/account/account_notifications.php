<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account_notifications.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/account_notifications.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <form role="form" class="form-inline" name="account_notifications" id="account_notifications" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'notifications=save', 'SSL'); ?>" method="post">
      <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
      <h3 class="large-margin-top"><?php echo $lC_Language->get('product_notifications_global'); ?></h3>
      <div class="well">
        <div class="checkbox">
          <label class="margin-left"><?php echo lc_draw_checkbox_field('product_global', '1', $Qglobal->value('global_product_notifications'), null, null); ?>&nbsp;<?php echo $lC_Language->get('product_notifications_global'); ?></label>
        </div>
        <p class="large-margin-left no-margin-bottom normal">&nbsp;<?php echo $lC_Language->get('product_notifications_global_description'); ?></p>
      </div>
    
      <?php         
      if ($Qglobal->valueInt('global_product_notifications') != '1') {
        echo '<h3 class="">' . $lC_Language->get('product_notifications_products') . '</h3>' . "\n";
        if ($lC_Template->hasCustomerProductNotifications($lC_Customer->getID())) {
          echo '<p class="normal">' . $lC_Language->get('product_notifications_products_description') . '</p>' . "\n";
          $Qproducts = $lC_Template->getListing();
          $counter = 0;
          while ($Qproducts->next()) {
            $counter++;
            echo '<div class="well">' . "\n";
            echo '  <div class="checkbox">' . "\n";
            echo '    <label class="margin-left">' . lc_draw_checkbox_field('products[' . $counter . ']', $Qproducts->valueInt('products_id'), true, null, null) . '&nbsp;' . $Qproducts->value('products_name') . '</label>' . "\n";
            echo '  </div>' . "\n";
            echo '</div>' . "\n";
          }
        } else {
          echo '<div class="well">' . "\n";
          echo '  <p class="no-margin-bottom normal">&nbsp;' . $lC_Language->get('product_notifications_products_none') . '</p>' . "\n";
          echo '</div>' . "\n";        
        }
      }
      ?>    
    </form>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" onclick="$('#account_notifications').submit();" type="button"><?php echo $lC_Language->get('button_update'); ?></button>
      <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div>     
  </div>
</div> 
<!--content/account/account_notifications.php end-->