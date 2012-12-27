<?php
  /*
  $Id: checkout_shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
?>
<!--CHECKOUT SHIPPING SECTION STARTS-->
  <div id="checkout_shipping_details" class="full_page">
    <!--CHECKOUT SHIPPING DETAILS STARTS-->
    <div class="content">
      <form name="checkout_shipping" id="checkout_shipping" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'shipping=process', 'SSL'); ?>" method="post">
        <div class="short-code-column">
          <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
          <h4><?php echo $lC_Language->get('shipping_address_title'); ?></h4>
          <div id="shippingHeading">
            <div style="float: right; padding: 0px 0px 10px 20px;">
              <?php echo lC_Address::format($lC_ShoppingCart->getShippingAddress(), '<br />'); ?>
            </div>
            <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
              <?php echo '<b>' . $lC_Language->get('current_shipping_address_title') . '</b><br />' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_south_east.png'); ?>
            </div>     
            <?php echo $lC_Language->get('choose_shipping_destination'). '<br /><br /><a href="' . lc_href_link(FILENAME_CHECKOUT, 'shipping_address', 'SSL')  . '" style="text-decoration:none;"><button class="button brown_btn" type="button">' . $lC_Language->get('button_change_address') . '</button></a>'; ?>
            <div style="clear: both;"></div>
          </div>
          <?php
            if ($lC_Shipping->hasQuotes()) {
          ?>
          <!--CHECKOUT SHIPPING QUOTES STARTS-->
          <div id="shippingQuotes">
            <h4><?php echo $lC_Language->get('shipping_method_title'); ?></h4>
            <?php
              if ($lC_Shipping->numberOfQuotes() > 1) {
            ?>
            <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
              <?php echo '<b>' . $lC_Language->get('please_select') . '</b><br />' . lc_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?>
            </div>
            <p style="margin-top: 0px;"><?php echo $lC_Language->get('choose_shipping_method'); ?></p>
            <?php
              } else {
            ?>
            <p style="margin-top: 0px;"><?php echo $lC_Language->get('only_one_shipping_method_available'); ?></p>
            <?php
              }
            ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                $radio_buttons = 0;
                foreach ($lC_Shipping->getQuotes() as $quotes) {
                ?>
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td colspan="3" class="shippingQuotesTitle"><b><?php echo $quotes['module']; ?></b>&nbsp;<?php if (isset($quotes['icon']) && !empty($quotes['icon'])) { echo $quotes['icon']; } ?></td>
                    <td width="10">&nbsp;</td>
                  </tr>
                  <?php
                    if (isset($quotes['error'])) {
                    ?>
                    <tr>
                      <td width="10">&nbsp;</td>
                      <td colspan="3"><?php echo $quotes['error']; ?></td>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <?php
                    } else {
                      foreach ($quotes['methods'] as $methods) {
                        if ($quotes['id'] . '_' . $methods['id'] == $lC_ShoppingCart->getShippingMethod('id')) {
                          echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                        } else {
                          echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                        }
                      ?>
                      <td width="10">&nbsp;</td>
                      <td width="75%"><?php echo $methods['title']; ?></td>
                      <?php
                        if ( ($lC_Shipping->numberOfQuotes() > 1) || (sizeof($quotes['methods']) > 1) ) {
                        ?>
                        <td><?php echo $lC_Currencies->displayPrice($methods['cost'], $quotes['tax_class_id']); ?></td>
                        <td style="text-align:right;"><?php echo lc_draw_radio_field('shipping_mod_sel', $quotes['id'] . '_' . $methods['id'], $lC_ShoppingCart->getShippingMethod('id')); ?></td>
                        <?php
                        } else {
                        ?>
                        <td align="right" colspan="2"><?php echo $lC_Currencies->displayPrice($methods['cost'], $quotes['tax_class_id']) . lc_draw_hidden_field('shipping_mod_sel', $quotes['id'] . '_' . $methods['id']); ?></td>
                        <?php
                        }
                      ?>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <?php
                      $radio_buttons++;
                    }
                  }
                ?>
              </table></td>
              </tr>
              <?php
                }
              ?>
              </table>
            </div>
            <!--CHECKOUT SHIPPING QUOTES ENDS-->
            <?php
            }
          ?>
          <!--CHECKOUT SHIPPING COMMENTS STARTS-->
          <div id="shippingComments">
            <h4><?php echo $lC_Language->get('add_comment_to_order_title'); ?></h4>
            <div>
              <?php 
                echo lc_draw_textarea_field('comments', (isset($_SESSION['comments']) ? $_SESSION['comments'] : null), null, null, 'style="width: 99%;"'); 
              ?>
            </div>
          </div>
          <!--CHECKOUT SHIPPING COMMENTS STARTS-->
        </div><br /> 
        <div id="shippingActions" class="action_buttonbar">
          <span class="buttonLeft continueCheckoutActionText"><?php echo '<b>' . $lC_Language->get('continue_checkout_procedure_title') . '</b> ' . $lC_Language->get('continue_checkout_procedure_to_payment'); ?></span>
          <span class="buttonRight"><a onclick="$('#checkout_shipping').submit();"><button class="checkout" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div>
      </form>
      <div style="clear:both;"></div>
    </div>
    <!--CHECKOUT SHIPPING DETAILS ENDS-->
  </div>
<!--CHECKOUT SHIPPING SECTION ENDS-->