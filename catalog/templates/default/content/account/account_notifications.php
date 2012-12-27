<?php
/*
  $Id: account_notifications.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--ACCOUNT NOTIFICATIONS SECTION STARTS-->
  <div class="full_page">
    <!--ACCOUNT NOTIFICATIONS CONTENT STARTS-->
    <div class="content">
      <!-- Need to get with Scott on class code to support newsletter data for customer -->
      <form name="account_newsletter" id="account_newsletter" action="#" method="post">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <h4><?php echo $lC_Language->get('newsletter_product_notifications_global'); ?></h4>
        <!--ACCOUNT NOTIFICATIONS SELECTIONS STARTS-->
        <div> 
          <div>
            <div class="borderPadMe">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="30"><?php echo lc_draw_checkbox_field('product_global', '1', $Qglobal->value('global_product_notifications')); ?></td>
                  <td><b><?php echo lc_draw_label($lC_Language->get('newsletter_product_notifications_global'), 'product_global'); ?></b></td>
                </tr>
                <tr>
                  <td width="30">&nbsp;</td>
                  <td><?php echo $lC_Language->get('newsletter_product_notifications_global_description'); ?></td>
                </tr>
              </table>
            </div>
            <?php
              if ($Qglobal->valueInt('global_product_notifications') != '1') {
            ?> 
            <div style="clear:both;"></div>
            <div style="background-color:#FFFFFF;">
              <h4><?php echo $lC_Language->get('newsletter_product_notifications_products'); ?></h4>
              <div class="borderPadMe">
                <?php
                  if ($lC_Template->hasCustomerProductNotifications($lC_Customer->getID())) {
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="2"><?php echo $lC_Language->get('newsletter_product_notifications_products_description'); ?></td>
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
                    echo $lC_Language->get('newsletter_product_notifications_products_none');
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
            <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span> 
            <span class="buttonRight"><a onclick="$('#account_notifications').submit();"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
          </div> 
          <div style="clear:both;"></div> 
        </div>
        <!--ACCOUNT NOTIFICATIONS SELECTIONS ENDS-->
      </div>
      </form>
    </div>
    <!--ACCOUNT NOTIFICATIONS CONTENT ENDS-->
  </div>
<!--ACCOUNT NOTIFICATIONS SECTION ENDS-->