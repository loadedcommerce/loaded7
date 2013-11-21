<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_shipping_address.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--content/checkout/checkout_shipping_address.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">  
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_checkout'); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('checkout_shipping_address') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('checkout_shipping_address') . '</div>' . "\n"; 
    ?>
    <?php 
    if ( $lC_MessageStack->size('checkout_shipping_account') > 0 ) echo '<div class="message-stack-container alert alert-success small-margin-bottom">' . $lC_MessageStack->get('checkout_shipping_account') . '</div>' . "\n"; 
    ?>
    <div id="content-checkout-shipping-address-container">
      <div class="panel panel-default no-margin-bottom">
        <div class="panel-heading">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_delivery'); ?></h3>
        </div>
        <div class="panel-body no-padding-bottom no-padding-top">
          <div class="row large-margin-top">
            <div class="col-sm-4 col-lg-4">
              <div class="well relative no-padding-bottom">
                <h4 class="no-margin-top"><?php echo $lC_Language->get('ship_to_address'); ?></h4>
                <address>
                  <?php echo $lC_Language->get('add_first_address'); ?>
                </address>
              </div>
              <div class="well">
                <?php 
                foreach ($lC_ShoppingCart->getOrderTotals() as $module) {   
                  ?>
                  <div class="clearfix">
                    <span class="pull-left ot-<?php echo strtolower(str_replace('_', '-', $module['code'])); ?>"><?php echo strip_tags($module['title']); ?></span>
                    <span class="pull-right ot-<?php echo strtolower(str_replace('_', '-', $module['code'])); ?>"><?php echo strip_tags($module['text']); ?></span>                
                  </div>                    
                  <?php
                }
                ?>                
              </div>        
            </div>
            <div class="col-sm-8 col-lg-8">
              <form role="form" name="checkout_address" id="checkout_address" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping_address=process', 'SSL'); ?>" method="post">
                <div class="clearfix">
                  <?php
                  if (isset($_GET['shipping_address']) && ($_GET['shipping_address'] != 'process')) {
                    if ($lC_Customer->hasDefaultAddress()) {
                      ?>
                      <h3 class="no-margin-top"><?php echo $lC_Language->get('shipping_address_title'); ?></h3>
                      <address class="pull-left half-width">
                        <?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?>
                      </address>
                      <div class="pull-right half-width">
                        <div class="pull-right"><?php echo $lC_Language->get('selected_shipping_destination'); ?></div>
                      </div>
                    <?php
                    }
                    if (lC_AddressBook::numberOfEntries() > 1) {
                      ?>
                      <div class="clear-both clearfix">
                        <h3 class="no-margin-top"><?php echo $lC_Language->get('address_book_entries_title'); ?></h3>
                        <div class="alert alert-warning"><?php echo $lC_Language->get('select_another_shipping_destination'); ?></div>
                        <?php
                        $radio_buttons = 0;
                        $Qaddresses = $lC_Template->getListing();
                        while ($Qaddresses->next()) {
                          echo '<table class="table no-margin-bottom content-checkout-address-selection-table">';
                          if ($Qaddresses->valueInt('address_book_id') == $lC_ShoppingCart->getShippingAddress('id') || lC_AddressBook::numberOfEntries() == 1) {
                            echo '<tr class="module-row-selected cursor-pointer" id="default-selected" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                          } else {
                            echo '<tr class="module-row cursor-pointer" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                          }
                          ?>
                          <td class=""><span class="strong"><?php echo $Qaddresses->valueProtected('firstname') . ' ' . $Qaddresses->valueProtected('lastname'); ?></span><br /><small><?php echo str_replace($Qaddresses->valueProtected('firstname') . ' ' . $Qaddresses->valueProtected('lastname') . ', ', '', lC_Address::format($Qaddresses->toArray(), ', ')); ?></small></td>
                          <td class="text-right"><?php echo lc_draw_radio_field('address', $Qaddresses->valueInt('address_book_id'), $lC_ShoppingCart->getShippingAddress('id'), 'id="address_' . $radio_buttons . '"', ''); ?></td>
                          </tr>
                          </table>
                          <?php
                          $radio_buttons++;
                        }
                        ?>
                      </div>
                      <?php
                    }
                  } 
                  ?>
                </div>
                <?php
                  if ( (lC_AddressBook::numberOfEntries() < MAX_ADDRESS_BOOK_ENTRIES) || (lC_AddressBook::numberOfEntries() < 1) ) {
                  ?>
                  <div class="" id="checkoutShippingAddressDetails"<?php echo (lC_AddressBook::numberOfEntries() < 1) ? '' : 'style="display:none;"'; ?>>
                    <h3 class="no-margin-top"><?php echo $lC_Language->get('new_shipping_address_title'); ?></h3>
                    <p><?php echo $lC_Language->get('new_shipping_address'); ?></p>
                    <div id="addressBookDetails">
                    <?php
                      if (file_exists(DIR_FS_TEMPLATE . 'modules/address_book_details.php')) {
                        require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/address_book_details.php'));
                      } else {
                        require($lC_Vqmod->modCheck('includes/modules/address_book_details.php')); 
                      }             
                    ?>
                    </div>
                  </div>
                  <?php
                  }  
                ?>                
              </form>
              <div class="btn-set clearfix">
                <button class="btn btn-lg btn-success pull-right" onclick="$('#checkout_address').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
                <?php 
                  if (lC_AddressBook::numberOfEntries() < 1) {
                ?>
                <button class="btn btn-lg" type="button" id="shipping-address-form"><?php echo $lC_Language->get('text_cancel'); ?></button>
                <?php } else { ?>
                <button class="btn btn-lg btn-primary" type="button" id="shipping-address-form" style="display:none;"><?php echo $lC_Language->get('show_address_form'); ?></button>
                <?php } ?>
              </div> 
            </div>
          </div> <!-- /row -->        
        </div>
      </div> 
      <div class="clearfix panel panel-default no-margin-bottom">
        <div class="panel-heading">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_payment'); ?></h3>
        </div>
      </div>     
      <div class="panel panel-default no-margin-bottom">
        <div class="panel-heading">
          <h3 class="no-margin-top no-margin-bottom"><?php echo $lC_Language->get('box_ordering_steps_confirmation'); ?></h3>
        </div>
      </div>         
    </div> 
  </div>
</div> 
<!--content/checkout/checkout_shipping_address.php end-->