<?php
/*
  $Id: customer.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
          <div id="section_orders_customer">
            <h3 class="show-below-768 margin-left margin-top no-margin-bottom">Customer</h3>
            <div id="section_orders_customer_content" class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                <fieldset class="fieldset">
                  <legend class="legend">Customer Info</legend>
                  <div class="columns">
                    <div class="new-row-mobile nine-columns twelve-columns-mobile">
                      <div class="field-block button-height">
                        <p class="button-height field-line-height">
                          <label for="cust_name" class="label">Name</label>
                          <span id="cust_name" class="bold">Sal Iozzia</span>
                        </p>
                        <p class="button-height field-line-height">
                          <label for="cust_name2" class="label">Company Name</label>
                          <span id="cust_name2" class="bold"><small class="tag orange-bg">B2B</small></span>
                        </p>
                        <p class="button-height field-line-height">
                          <label for="cust_name" class="label">Email</label>
                          <span id="cust_name" class="bold">sal@loadedcommerce.com</span>
                        </p>
                        <p class="button-height field-line-height">
                          <label for="cust_name" class="label">Address</label>
                          <span id="cust_name" class="field-block-address-offset">
                            <span class="bold field-block-address">1234 Main St.<br />Atlanta, GA 35282<br />United States</span>
                          </span>
                        </p>
                        <p class="button-height field-line-height">
                          <label for="cust_name" class="label">Phone Number</label>
                          <span id="cust_name" class="bold">852-820-7896</span>
                        </p>
                        <p class="button-height field-line-height">
                          <label for="cust_name" class="label">Customer Group</label>
                          <span id="cust_name" class="bold">Retail</span>
                        </p>
                      </div>
                    </div>
                    <div class="new-row-mobile three-columns twelve-columns-mobile">
                      <span class="button-group">
                        <a href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=1'); ?>" class="button compact">View</a>
                        <a href="#" class="button compact">Edit</a>
                      </span>
                    </div>
                  </div> 
                </fieldset>
                <fieldset class="fieldset">
                  <legend class="legend">Alternate Customer Info <?php echo lc_go_pro(); ?></legend>
                  <div class="columns">
                    <div class="new-row-mobile nine-columns twelve-columns-mobile">
                      <div class="field-block button-height">
                        <p class="button-height field-line-height">
                          <label for="cust_name" class="label">Email</label>
                          <span id="cust_name" class="bold">&nbsp;</span>
                        </p>
                        <p class="button-height field-line-height">
                          <label for="cust_name2" class="label">Phone Number</label>
                          <span id="cust_name2" class="bold">&nbsp;</span>
                        </p>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="fieldset">
                  <legend class="legend">Order History</legend>
                  <div class="columns with-small-padding small-margin-left hide-below-768 bbottom-grey">
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom">Number</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobil no-margin-bottom">Amount</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom">Order Status</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom">Shipping Status</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom">Payment Status</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom align-right">Action</div>
                  </div>
                  <?php
                    $Qordershistory = array(array('id' => '3', 'number' => '52001', 'amount' => '89.66', 'status' => 'Complete', 'shipping' => 'Shipped', 'payment' => 'Paid'),
                                             array('id' => '8', 'number' => '598744', 'amount' => '127.35', 'status' => 'Pending', 'shipping' => 'Backordered', 'payment' => 'Paid'),
                                             array('id' => '12', 'number' => '99268', 'amount' => '206.54', 'status' => 'Complete', 'shipping' => 'Shipped', 'payment' => 'Paid'),
                                             array('id' => '23', 'number' => '13NKG7S', 'amount' => '9.87', 'status' => 'Complete', 'shipping' => 'Shipped', 'payment' => 'Paid')); 
                    foreach ($Qordershistory as $history) {
                  ?>
                  <div class="columns with-small-padding small-margin-left bbottom-grey">
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Number: </span>
                      <span><?php echo $history['number']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Amount: </span>
                      <span>$<?php echo $history['amount']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Order Status: </span>
                      <span><?php echo $history['status']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Shipping Status: </span>
                      <span><?php echo $history['shipping']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Payment Status: </span>
                      <span><?php echo $history['payment']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom align-right">
                      <a class="button compact icon-pencil" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders=' . $history['id'] . '&action=save'); ?>">View</a>
                    </div>
                  </div>
                  <?php
                    }
                  ?>
                </fieldset>
              </div>
            </div>
          </div>
