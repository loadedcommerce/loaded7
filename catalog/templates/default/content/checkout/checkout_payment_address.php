<?php
/**  
*  $Id: checkout_payment_address.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('checkout_address') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('checkout_address', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/checkout/checkout_payment_address.php start-->
<div id="checkoutPaymentAddress" class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
  <div class="short-code-column">
    <form name="checkout_address" id="checkout_address" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'payment_address=process', 'SSL'); ?>" method="post" onsubmit="return check_form_optional(checkout_address);">
      <?php
        if (isset($_GET['payment_address']) && ($_GET['payment_address'] != 'process')) {
          if ($lC_Customer->hasDefaultAddress()) {
          ?>
          <div id="checkoutPaymentAddressHeading">
            <h4><?php echo $lC_Language->get('billing_address_title'); ?></h4>
            <div>
              <div style="float: right; padding: 0px 0px 10px 20px;">
                <?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?>
              </div>
              <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
                <?php echo '<b>' . $lC_Language->get('current_billing_address_title') . '</b><br />' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_south_east.png'); ?>
              </div>
              <?php echo $lC_Language->get('selected_billing_destination'); ?>
              <div style="clear: both;"></div>
            </div>
          </div>
          <?php
          }
          if (lC_AddressBook::numberOfEntries() > 1) {
          ?>
          <div id="checkoutPaymentAddressEntries">
            <h4><?php echo $lC_Language->get('address_book_entries_title'); ?></h4>
            <div>
              <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
                <?php echo '<b>' . $lC_Language->get('please_select') . '</b><br />' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_east_south.png'); ?>
              </div>
              <p style="margin-top: 0px;"><?php echo $lC_Language->get('select_another_billing_destination'); ?></p>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <?php
                  $radio_buttons = 0;
                  $Qaddresses = $lC_Template->getListing();
                  while ($Qaddresses->next()) {
                  ?>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                      if ($Qaddresses->valueInt('address_book_id') == $lC_ShoppingCart->getBillingAddress('id')) {
                        echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                      } else {
                        echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                      }
                    ?>
                    <td width="10">&nbsp;</td>
                    <td colspan="2"><b><?php echo $Qaddresses->valueProtected('firstname') . ' ' . $Qaddresses->valueProtected('lastname'); ?></b></td>
                    <td align="right"><div align="right" style="padding-right:12px;"><?php echo lc_draw_radio_field('address', $Qaddresses->valueInt('address_book_id'), $lC_ShoppingCart->getBillingAddress('id')); ?></div></td>
                    <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td colspan="3"><table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                          <td width="10">&nbsp;</td>
                          <td><?php echo lC_Address::format($Qaddresses->toArray(), ', '); ?></td>
                          <td width="10">&nbsp;</td>
                        </tr>
                      </table></td>
                    <td width="10">&nbsp;</td>
                  </tr>
                </table></td>
                <td width="10">&nbsp;</td>
                </tr>
                <?php
                  $radio_buttons++;
                }
              ?>
              </table>
            </div>
          </div>
          <?php
          }
        }
        if (lC_AddressBook::numberOfEntries() < MAX_ADDRESS_BOOK_ENTRIES) {
        ?>
        <div id="checkoutPaymentAddressDetails">
          <h4><?php echo $lC_Language->get('new_billing_address_title'); ?></h4>
          <p><?php echo $lC_Language->get('new_billing_address'); ?></p>
          <div id="addressBookDetails">
            <?php 
              if (file_exists(DIR_FS_TEMPLATE . 'modules/address_book_details.php')) {
                require(DIR_FS_TEMPLATE . 'modules/address_book_details.php');
              } else {
                require('includes/modules/address_book_details.php'); 
              }    
            ?>
          </div>
        </div>
        <?php
        }
      ?>
      <div id="checkoutPaymentAddressActions" class="action_buttonbar">
        <span class="buttonLeft"><?php echo '<b>' . $lC_Language->get('continue_checkout_procedure_title') . '</b> ' . $lC_Language->get('continue_checkout_procedure_to_shipping'); ?></span>
        <span class="buttonRight"><a onclick="$('#checkout_address').submit();"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
    </form>
  </div>
</div>
<div style="clear:both;"></div> 
<!--content/checkout/checkout_payment_address.php end-->