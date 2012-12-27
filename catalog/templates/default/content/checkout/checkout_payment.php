<?php
/*
  $Id: checkout_payment.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('checkout_payment') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('checkout_payment', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--CHECKOUT PAYMENT SECTION STARTS-->
  <div id="checkout_payment_details" class="full_page">
    <!--CHECKOUT PAYMENT DETAILS STARTS-->
    <div class="content">
      <form name="checkout_payment" id="checkout_payment" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'confirmation', 'SSL'); ?>" method="post" onsubmit="return check_form();">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <?php
          if (DISPLAY_CONDITIONS_ON_CHECKOUT == '1') {
        ?>
        <!--CHECKOUT PAYMENT CONDITIONS STARTS-->
        <div id="checkoutPaymentConditions">
          <h4><?php echo $lC_Language->get('order_conditions_title'); ?></h4>
          <div>
            <?php echo sprintf($lC_Language->get('order_conditions_description'), lc_href_link(FILENAME_INFO, 'conditions', 'AUTO')) . '<br /><br />' . lc_draw_checkbox_field('conditions', array(array('id' => 1, 'text' => $lC_Language->get('order_conditions_acknowledge'))), false); ?>
          </div>
        </div>
        <!--CHECKOUT PAYMENT CONDITIONS ENDS-->
        <?php
          }
        ?>
        <!--CHECKOUT PAYMENT ADDRESS STARTS-->
        <div id="checkoutPaymentAddress">
          <h4><?php echo $lC_Language->get('billing_address_title'); ?></h4>
          <div>
            <div style="float: right; padding: 0px 0px 10px 20px;">
              <?php echo lC_Address::format($lC_ShoppingCart->getBillingAddress(), '<br />'); ?>
            </div>
            <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
              <?php echo '<b>' . $lC_Language->get('billing_address_title') . '</b><br />' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_south_east.png'); ?>
            </div>
            <?php echo $lC_Language->get('choose_billing_destination'). '<br /><br /><a href="' . lc_href_link(FILENAME_CHECKOUT, 'payment_address', 'SSL')  . '" style="text-decoration:none;"><button class="button brown_btn" type="button">' . $lC_Language->get('button_change_address') . '</button></a>'; ?>  
            <div style="clear: both;"></div>
          </div>
        </div>
        <!--CHECKOUT PAYMENT ADDRESS ENDS-->
        <!--CHECKOUT PAYMENT METHODS STARTS-->
        <div id="checkoutPaymentMethods">
          <h4><?php echo $lC_Language->get('payment_method_title'); ?></h4>
          <div>
            <?php
            $selection = $lC_Payment->selection(); 
            if (sizeof($selection) > 1) {
              ?>
              <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
                <?php echo '<b>' . $lC_Language->get('please_select') . '</b><br />' . lc_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?>
              </div>
              <p style="margin-top: 0px;"><?php echo $lC_Language->get('choose_payment_method'); ?></p>
              <?php
            } else {
              ?>
              <p style="margin-top: 0px;"><?php echo $lC_Language->get('only_one_payment_method_available'); ?></p>
              <?php
            }
            ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              $radio_buttons = 0;
              for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
                ?>
                <tr>
                  <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                    if ( ($n == 1) || ($lC_ShoppingCart->hasBillingMethod() && ($selection[$i]['id'] == $lC_ShoppingCart->getBillingMethod('id'))) ) {
                      echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                    } else {
                      echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                    }
                    ?>
                    <td width="10">&nbsp;</td>
                    <?php
                    if ($n > 1) {
                      ?>
                      <td colspan="3"><?php echo '<b>' . $selection[$i]['module'] . '</b>'; ?></td>
                      <td align="right"><?php echo lc_draw_radio_field('payment_method', $selection[$i]['id'], ($lC_ShoppingCart->hasBillingMethod() ? $lC_ShoppingCart->getBillingMethod('id') : null)); ?></td>
                      <?php
                    } else {
                      ?>
                      <td colspan="4"><?php echo '<b>' . $selection[$i]['module'] . '</b>' . lc_draw_hidden_field('payment_method', $selection[$i]['id']); ?></td>
                      <?php
                    }
                    ?>
                    <td width="10">&nbsp;</td>
                    </tr>
                    <?php
                    if (isset($selection[$i]['error'])) {
                      ?>
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td colspan="4"><?php echo $selection[$i]['error']; ?></td>
                        <td width="10">&nbsp;</td>
                      </tr>
                      <?php
                    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
                      ?>
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                          <?php
                          for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
                            ?>
                            <tr>
                              <td width="10">&nbsp;</td>
                              <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                              <td width="10">&nbsp;</td>
                              <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                              <td width="10">&nbsp;</td>
                            </tr>
                            <?php
                          }
                          ?>
                        </table></td>
                        <td width="10">&nbsp;</td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table></td>
                </tr>   
                <?php
                $radio_buttons++;
              }
              ?>
            </table>
          </div>
        </div>
        <!--CHECKOUT PAYMENT METHODS ENDS-->
        <!--CHECKOUT PAYMENT COMMENTS STARTS-->
        <div class="checkoutPaymentComents">
          <h4><?php echo $lC_Language->get('add_comment_to_order_title'); ?></h4>
          <div>
            <?php echo lc_draw_textarea_field('comments', (isset($_SESSION['comments']) ? $_SESSION['comments'] : null), null, null, 'style="width: 99%;"'); ?>
          </div>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--CHECKOUT PAYMENT COMMENTS ENDS-->
        <!--CHECKOUT PAYMENT ACTIONS STARTS-->
        <div id="checkoutPaymentActions" class="action_buttonbar">
          <span class="buttonLeft"><?php echo '<b>' . $lC_Language->get('continue_checkout_procedure_title') . '</b> ' . $lC_Language->get('continue_checkout_procedure_to_confirmation'); ?></span>
          <span class="buttonRight"><a onclick="$('#checkout_payment').submit();"><button class="checkout" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
      <!--CHECKOUT PAYMENT ACTIONS ENDS-->
      </div>
      </form>
    </div>
  </div>
  <div style="clear:both;"></div>
  <!--CHECKOUT PAYMENT DETAILS ENDS-->
<!--CHECKOUT PAYMENT SECTION ENDS-->