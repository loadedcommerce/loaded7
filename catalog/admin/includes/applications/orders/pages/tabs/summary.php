<?php
/*
  $Id: summary.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
          <div id="section_orders_summary">
            <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_summary'); ?></h3>
            <div class="columns with-padding">
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-user icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('text_customer'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <!--<td class="align-right pad-5 width-25 bold grey"><?php echo $lC_Language->get('text_name'); ?></td>-->
                        <td class="align-left mid-padding-left small-padding-top"><?php echo $oInfo->get('customerAddress'); ?></td>
                      </tr>
                      <!--<tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_company_name'); ?></td>
                        <td class="align-left pad-5 width-66 bold"><small class="tag orange-bg">B2B</small></td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_city_state'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Atlanta, GA USA</td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_customer_group'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Retail</td>
                      </tr>-->
                    </tbody>
                  </table>
                </fieldset>
              </div>
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-dropbox icon-anthracite"><strong class="small-margin-left"><?php echo /*$lC_Language->get('text_shipping')*/$lC_Language->get('subsection_shipping_address'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <!--<td class="align-right pad-5 width-25 bold grey"><?php echo $lC_Language->get('text_method'); ?></td>-->
                        <td class="align-left mid-padding-left small-padding-top"><?php echo $oInfo->get('deliveryAddress'); ?></td>
                      </tr>
                      <!--<tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_type'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Next Day Air</td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_shipping_state'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Backordered</td>
                      </tr>-->
                    </tbody>
                  </table>
                </fieldset>
              </div>
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-card icon-anthracite"><strong class="small-margin-left"><?php echo /*$lC_Language->get('text_information')*/$lC_Language->get('subsection_billing_address'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <!--<td class="align-right pad-5 width-25 bold grey"><?php echo $lC_Language->get('text_addresses'); ?></td>-->
                        <td class="align-left mid-padding-left small-padding-top"><?php echo $oInfo->get('billingAddress'); ?></td>
                      </tr>
                      <!--<tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_date_ordered'); ?></td>
                        <td class="align-left pad-5 width-66 bold">2013/08/04</td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_purchase_order'); ?></td>
                        <td class="align-left pad-5 width-66 bold"><small class="tag orange-bg">B2B</small></td>
                      </tr>-->
                    </tbody>
                  </table>
                </fieldset>
              </div>
            </div>
            <div class="columns with-padding large-pull-top">
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-credit-card icon-anthracite"><strong class="small-margin-left"><?php echo /*$lC_Language->get('text_payment')*/$lC_Language->get('subsection_payment_method'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <!--<td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_method'); ?></td>-->
                        <td class="align-left mid-padding-left small-padding-top"><?php echo $oInfo->get('paymentMethod'); ?></td>
                      </tr>
                      <!--<tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_details'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Sal Iozzia - Visa - 4111</td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_payment_state'); ?></td>
                        <td class="align-left pad-5 width-66 bold">PAID</td>
                      </tr>-->
                    </tbody>
                  </table>
                </fieldset>
              </div>
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-clock icon-anthracite"><strong class="small-margin-left"><?php echo /*$lC_Language->get('text_shipping')*/$lC_Language->get('subsection_status'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <!--<td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_method'); ?></td>-->
                        <td class="align-left mid-padding-left small-padding-top">
                          <p class="no-padding"><?php echo $oInfo->get('orderStatus'); ?></p>
                        </td>
                      </tr>
                      <!--<tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_type'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Next Day Air</td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_shipping_state'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Backordered</td>
                      </tr>-->
                    </tbody>
                  </table>
                </fieldset>
              </div>
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-bag icon-anthracite"><strong class="small-margin-left"><?php echo /*$lC_Language->get('text_shipping')*/$lC_Language->get('subsection_total'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <!--<td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_method'); ?></td>-->
                        <td class="align-left mid-padding-left small-padding-top">
                          <p class="no-padding"><?php echo $oInfo->get('orderTotal'); ?></p>
                        </td>
                      </tr>
                      <!--<tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_type'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Next Day Air</td>
                      </tr>
                      <tr>
                        <td class="align-right pad-5 width-33 bold grey"><?php echo $lC_Language->get('text_shipping_state'); ?></td>
                        <td class="align-left pad-5 width-66 bold">Backordered</td>
                      </tr>-->
                    </tbody>
                  </table>
                </fieldset>
              </div>
            </div>
            <div class="columns">
              <div class="twelve-columns">
                <div class="field-drop-tabs field-drop-tabs-no-left button-height black-inputs">
                  <div class="columns">
                    <div class="two-columns twelve-columns-mobile new-row-mobile no-wrap no-margin-bottom">
                      <p class="white">Balance <small class="tag small-margin-left green-bg">PAID</small></p>
                      <p class="white">Address <small class="tag small-margin-left orange-bg">MIXED</small></p>
                    </div>
                    <div class="six-columns twelve-columns-mobile new-row-mobile">
                      <div class="columns">
                        <div class="twelve-columns">
                          <font class="white font-eightteen mid-margin-right">Current Status</font>
                          <?php echo lC_Orders_Admin::drawOrderStatusDropdown($_GET[$lC_Template->getModule()]); ?>
                        </div>
                        <div class="twelve-columns">
                          <div>
                            <?php echo lc_draw_textarea_field('comment', null, null, null, 'class="input autoexpanding full-width mid-pull-top" placeholder="Type here to send a message..."'); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="four-columns twelve-columns-mobile new-row-mobile">
                      <div class="columns">
                        <div class="twelve-columns twelve-columns-mobile new-row-mobile margin-bottom hide-below-768">&nbsp;</div>
                        <div class="twelve-columns twelve-columns-mobile new-row-mobile">
                          <div class="columns">
                            <div class="six-columns twelve-columns-mobile new-row-mobile small-margin-top no-margin-bottom">
                              <p class="small-margin-bottom no-wrap">
                                <input type="radio" class="radio small-margin-right" name="append_comment" value="1" checked="">Customer Message
                              </p>
                              <p class="small-margin-bottom">
                                <input type="radio" class="radio small-margin-right" name="append_comment" value="0">Admin Note
                              </p>
                            </div>
                            <div class="six-columns twelve-columns-mobile new-row-mobile no-margin-bottom">
                              <p class="no-margin-bottom">&nbsp;</p>
                              <p class="no-margin-top">
                                <input class="button float-right margin-bottom" type="submit" value="Update">
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <input id="notify_customer" type="hidden" value="1" name="notify_customer">
                  </div>
                </div>
              </div>
            </div>
            <div class="columns with-padding">
              <div class="twelve-columns mid-margin-bottom mid-pull-top">
                <fieldset>
                  <legend class="mid-margin-bottom">
                    <span class="icon-list icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('text_products_ordered'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <thead class="bbottom-anthracite">
                      <tr>
                        <th align="left" class="orders-products-listing-th hide-below-480"><?php echo $lC_Language->get('text_sku_model'); ?></th>
                        <th align="left" class="orders-products-listing-th"><?php echo $lC_Language->get('text_product_name'); ?></th>
                        <!--<th align="center" class="orders-products-listing-th hide-below-480"><?php echo $lC_Language->get('text_fulfillment'); ?></th>-->
                        <th align="right" class="orders-products-listing-th hide-below-480 pad-right-10"><?php echo $lC_Language->get('text_qty'); ?></th>
                        <th align="right" class="orders-products-listing-th hide-below-480"><?php echo $lC_Language->get('text_total'); ?></th>
                        <th align="right" class="orders-products-listing-th show-below-480"><?php echo $lC_Language->get('text_details'); ?></th>
                      </tr>
                    </thead>
                    <tbody class="mid-margin-bottom">
                      <?php echo $oInfo->get('orderProducts'); ?>
                      <tr class="mid-margin-bottom">
                        <td class="hide-below-480"></td>
                        <td class="hide-below-480"></td>
                        <td class="no-wrap small-padding-top bolder" align="right"><?php echo $lC_Language->get('text_product_sub_total'); ?></td>
                        <td class="small-padding-top bolder" align="right"><?php echo $oInfo->get('orderTotal'); ?></td>
                        <td class="show-below-480"></td>
                      </tr>
                    </tbody>
                  </table>
                </fieldset>
              </div>
            </div>
            <div class="columns">
              <div class="twelve-columns large-pull-top">
                <div class="columns with-padding mid-margin-top">
                  <div class="six-columns twelve-columns-mobile hide-below-768">
                    <fieldset>
                      <legend class="margin-bottom">
                        <span class="icon-chat icon-size2 icon-anthracite mid-margin-left"><strong class="small-margin-left"><?php echo $lC_Language->get('text_recent_messages'); ?></strong></span>
                      </legend>
                      <table width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                          <?php echo lC_Orders_Admin::getOrderComments($_GET[$lC_Template->getModule()]); ?>
                        </tbody>
                      </table>
                    </fieldset>
                  </div>
                  <div class="six-columns twelve-columns-mobile">
                    <div class="columns">
                      <div class="twelve-columns">
                        <fieldset>
                          <legend class="small-margin-bottom">
                            <strong class="small-margin-left"><?php echo $lC_Language->get('text_order_totals'); ?></strong>
                          </legend>
                          <table width="100%" cellpadding="0" cellspacing="0">
                            <tbody>
                              <?php echo $oInfo->get('orderTotals'); ?>
                            </tbody>
                          </table>
                        </fieldset>
                      </div>
                      <!--<div class="twelve-columns">
                        <fieldset>
                          <legend class="small-margin-bottom">
                            <strong class="small-margin-left"><?php echo $lC_Language->get('text_last_payment'); ?></strong>
                          </legend>
                          <table width="100%" cellpadding="0" cellspacing="0">
                            <tbody>
                              <tr>
                                <td class="align-right pad-5 width-75">2013/08/07 Credit Card 2345</td>
                                <td class="align-right pad-5 width-25">$572.50</td>
                              </tr>
                              <tr>
                                <td class="align-right pad-5 width-75 bold btop-grey"><?php echo $lC_Language->get('text_balance'); ?></td>
                                <td class="align-right pad-5 width-25 bold btop-grey">$0.00</td>
                              </tr>
                            </tbody>
                          </table>
                        </fieldset>
                      </div>-->
                    </div>
                  </div>
                  <div class="six-columns twelve-columns-mobile show-below-768">
                    <fieldset>
                      <legend class="margin-bottom">
                        <span class="icon-chat icon-size2 icon-anthracite mid-margin-left"><strong class="small-margin-left"><?php echo $lC_Language->get('text_recent_messages'); ?></strong></span>
                      </legend>
                      <table width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                          <?php echo lC_Orders_Admin::getOrderComments($_GET[$lC_Template->getModule()]); ?>
                        </tbody>
                      </table>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>