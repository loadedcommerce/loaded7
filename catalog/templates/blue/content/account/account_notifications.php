<?php
/**  
*  $Id: account_notifications.php v1.0 2013-01-01 datazen $
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
<!--content/account/account_notifications.php start-->
<div class="full_page">
  <div class="content">
    <form name="account_notifications" id="account_notifications" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'notifications=save', 'SSL'); ?>" method="post">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <h4><?php echo $lC_Language->get('product_notifications_global'); ?></h4>
        <div> 
          <div>
            <div class="borderPadMe">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="30"><?php echo lc_draw_checkbox_field('product_global', '1', $Qglobal->value('global_product_notifications')); ?></td>
                  <td><b><?php echo lc_draw_label($lC_Language->get('product_notifications_global'), 'product_global'); ?></b></td>
                </tr>
                <tr>
                  <td width="30">&nbsp;</td>
                  <td><?php echo $lC_Language->get('product_notifications_global_description'); ?></td>
                </tr>
              </table>
            </div>
            <?php
              if ($Qglobal->valueInt('global_product_notifications') != '1') {
              ?> 
              <div style="clear:both;"></div>
              <div style="background-color:#FFFFFF;">
                <h4><?php echo $lC_Language->get('product_notifications_products'); ?></h4>
                <div class="borderPadMe">
                  <?php
                    if ($lC_Template->hasCustomerProductNotifications($lC_Customer->getID())) {
                    ?>
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td colspan="2"><?php echo $lC_Language->get('product_notifications_products_description'); ?></td>
                      </tr>
                      <?php
                        $Qproducts = $lC_Template->getListing();
                        $counter = 0;
                        while ($Qproducts->next()) {
                          $counter++;
                        ?>
                        <tr>
                          <td width="30"><?php echo lc_draw_checkbox_field('products[' . $counter . ']', $Qproducts->valueInt('products_id'), true); ?></td>
                          <td><b><?php echo lc_draw_label($Qproducts->value('products_name'), 'products[' . $counter . ']'); ?></b></td>
                        </tr>
                        <?php
                        }
                      ?>
                    </table>
                    <?php
                    } else {
                      echo $lC_Language->get('product_notifications_products_none');
                    }
                  ?>
                </div>
              </div>
              <?php
              }
            ?>
          </div> 
          <div style="clear:both;">&nbsp;</div>    
          <div id="accountNotificationsActions" class="action_buttonbar">
            <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span> 
            <span class="buttonRight"><a onclick="$('#account_notifications').submit();"><button class="button purple_btn" type="button"><?php echo $lC_Language->get('button_save'); ?></button></a></span>
          </div> 
          <div style="clear:both;"></div> 
        </div>
      </div>
    </form>
  </div>
</div>
<!--content/account/account_notifications.php end-->