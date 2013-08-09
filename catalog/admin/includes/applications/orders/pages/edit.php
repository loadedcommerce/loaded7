<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $oInfo = new lC_ObjectInfo(lC_Orders_Admin::getInfo($_GET[$lC_Template->getModule()]));
}
?>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Language->get('text_edit_order') . ': ' . $_GET[$lC_Template->getModule()]; ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="order" id="order" class="dataForm" action="<?php //echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('orders_id') : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="order_quick_info">
        <div class="columns with-small-padding">
          <div class="four-columns twelve-columns-mobile"><h4>Order Number 6574389</h4></div>
          <div class="four-columns twelve-columns-mobile"><h4>Amount $507.50</h4></div>
          <div class="four-columns twelve-columns-mobile"><h4>Due $0.00</h4></div>
        </div>
      </div>
      <div id="order_tabs" class="side-tabs">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_orders_summary', $lC_Language->get('section_orders_summary')); ?></li>
          <li><?php echo lc_link_object('#section_orders_products', $lC_Language->get('section_orders_products')); ?></li>
          <li><?php echo lc_link_object('#section_orders_customer', $lC_Language->get('section_orders_customer')); ?></li>
          <li><?php echo lc_link_object('#section_orders_shipping', $lC_Language->get('section_orders_shipping')); ?></li>
          <li><?php echo lc_link_object('#section_orders_messages', $lC_Language->get('section_orders_messages')); ?></li>
          <li><?php echo lc_link_object('#section_orders_fraud', $lC_Language->get('section_orders_fraud')); ?></li>
          <li><?php echo lc_link_object('#section_orders_payments', $lC_Language->get('section_orders_payments')); ?></li>
        </ul>
        <div class="clearfix tabs-content" id="orders_sections">
          <div id="section_orders_summary">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-user icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_customer'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-credit-card icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_payment'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-info-round icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_info'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-dropbox icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_shipping'); ?></strong></span>
                  </legend>
                </fieldset>
              </div>
            </div>
            <div class="columns">
              <div class="twelve-columns">
                <div class="field-drop-tabs field-drop-tabs-no-left button-height black-inputs">
                  <div class="columns">
                    <div class="six-columns twelve-columns-mobile new-row-mobile">Left</div>
                    <div class="six-columns twelve-columns-mobile new-row-mobile ">Right</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns with-padding">
              <div class="twelve-columns mid-margin-bottom large-pull-top">
                <fieldset>
                  <legend class="mid-margin-bottom">
                    <span class="icon-list icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_products_ordered'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <thead class="bbottom-anthracite">
                      <tr>
                        <th align="left" class="orders-products-listing-th hide-below-480">SKU/Model</th>
                        <th align="left" class="orders-products-listing-th">Product Name</th>
                        <th align="center" class="orders-products-listing-th hide-below-480">Fullfillment</th>
                        <th align="right" class="orders-products-listing-th hide-below-480 pad-right-10">Qty</th>
                        <th align="right" class="orders-products-listing-th hide-below-480">Total</th>
                        <th align="right" class="orders-products-listing-th show-below-480">Details</th>
                      </tr>
                    </thead>
                    <tbody class="mid-margin-bottom">
                    <?php
                      $Qordersproducts = array(array('id' => '24',
                                                     'model' => 'Floral001RL', 
                                                     'name' => 'Floral Dress - Red - Large', 
                                                     'stock' => 'In Stock', 
                                                     'qty' => '2', 
                                                     'total' => '250.00'),
                                               array('id' => '25',
                                                     'model' => 'Watch001', 
                                                     'name' => 'Mens Watch', 
                                                     'stock' => 'Shipped', 
                                                     'qty' => '1', 
                                                     'total' => '100.00'),
                                               array('id' => '26',
                                                     'model' => 'GC124GIFT', 
                                                     'name' => 'Gift Certificate $125', 
                                                     'stock' => '-', 
                                                     'qty' => '1', 
                                                     'total' => '125.00'),
                                               array('id' => '27',
                                                     'model' => 'Floral001GXL', 
                                                     'name' => 'Floral Dress - Green - Large', 
                                                     'stock' => 'Out of Stock', 
                                                     'qty' => '1', 
                                                     'total' => '125.00')); 
                      foreach ($Qordersproducts as $products) {
                      ?>
                      <tr id="orderProductsRow_<?php echo $products['id']; ?>" class="bbottom-grey">
                        <td align="left" class="orders-products-listing-td hide-below-480"><?php echo $products['model']; ?></td>
                        <td align="left" class="orders-products-listing-td"><?php echo $products['name']; ?></td>
                        <td align="center" class="orders-products-listing-td hide-below-480"><small class="tag red-bg"><?php echo $products['stock']; ?></small></td>
                        <td align="right" class="orders-products-listing-td hide-below-480 pad-right-10"><?php echo $products['qty']; ?></td>
                        <td align="right" class="orders-products-listing-td hide-below-480">$<?php echo $products['total']; ?></td>
                        <td align="right" class="orders-products-listing-td show-below-480"><i title="Product Details" class="icon-info-round icon-blue mid-margin-right cursor-pointer" onclick="orderProductDetails(<?php echo $products['id']; ?>);"></li></td>
                      </tr>
                      <?php
                        }
                      ?>
                      <tr>
                        <td class="hide-below-480"></td>
                        <td class="hide-below-480"></td>
                        <td class="hide-below-480"></td>
                        <td class="no-wrap pad-right-10 pad-top-10 bold" align="right">Product Sub Total</td>
                        <td class="pad-top-10 bold" align="right">$600.00</td>
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
                  <div class="six-columns twelve-columns-mobile">
                    <fieldset>
                      <legend class="margin-bottom">
                        <span class="icon-chat icon-size2 icon-anthracite mid-margin-left"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_recent_messages'); ?></strong></span>
                      </legend>
                      <div class="with-small-padding silver-bg">
                        <div class="small-margin-top">
                          <span class="float-right green-bg with-min-padding">Comment</span>
                          <span class="icon-user icon-size2 icon-anthracite small-margin-left">
                            <span>
                              Sal Iozzia <small class="anthracite small-margin-left">2013/08/07</small>
                            </span>
                          </span>
                        </div>
                        <p class="edit-order-summary-block with-small-padding">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vehicula est ut interdum fringilla. Curabitur lobortis libero ut sagittis fermentum. Donec eu tortor ut elit condimentum iaculis. Nulla semper diam ut mi volutpat pharetra.</p>
                      </div>
                      <div class="with-small-padding">
                        <div class="small-margin-top">
                          <span class="float-right orange-bg with-min-padding">Message</span>
                          <span>
                            <img src="images/prod-mini.png" alt="Comment Image" />
                            <span class="small-margin-left">
                              John Nickelback <small class="anthracite small-margin-left">2013/08/05</small>
                            </span>
                          </span>
                        </div>
                        <p class="edit-order-summary-block with-small-padding">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vehicula est ut interdum fringilla. Curabitur lobortis libero ut sagittis fermentum. Donec eu tortor ut elit condimentum iaculis. Nulla semper diam ut mi volutpat pharetra.</p>
                      </div>
                      <div class="with-small-padding grey-bg">
                        <div class="small-margin-top">
                          <span class="float-right anthracite-bg with-min-padding">Note</span>
                          <span>
                            <img src="images/prod-mini.png" alt="Comment Image" />
                            <span class="small-margin-left">
                              Jane Quarterback <small class="anthracite small-margin-left">sent 2013/08/02</small>
                            </span>
                          </span>
                        </div>
                        <p class="edit-order-summary-block with-small-padding">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vehicula est ut interdum fringilla. Curabitur lobortis libero ut sagittis fermentum. Donec eu tortor ut elit condimentum iaculis. Nulla semper diam ut mi volutpat pharetra.</p>
                      </div>
                      <div class="with-small-padding">
                        <div class="small-margin-top">
                          <i class="icon-mail"></i> <span class="small-margin-left">2 Additional Messages</span>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="six-columns twelve-columns-mobile">
                    <div class="columns">
                      <div class="twelve-columns">
                        <fieldset>
                          <legend class="small-margin-bottom">
                            <span class="icon-credit-card icon-size2 icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_order_totals'); ?></strong></span>
                          </legend>
                        </fieldset>
                      </div>
                      <div class="twelve-columns">
                        <fieldset>
                          <legend class="small-margin-bottom">
                            <span class="icon-credit-card icon-size2 icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_last_payment'); ?></strong></span>
                          </legend>
                        </fieldset>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="section_orders_products">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_customer">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_shipping">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_messages">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_fraud">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
          <div id="section_orders_payments">
            <div class="columns with-padding">
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Left</div>
              <div class="new-row-mobile six-columns twelve-columns-mobile">Tab Content Right</div>
            </div>
          </div>
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_cancel'); ?></span>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#order\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span>
                <span class="button-text"><?php echo $lC_Language->get('button_save'); ?></span> 
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo $lC_Language->get('text_edit_order') . ': '/* . $lC_ObjectInfo->get('orders_id')*/; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Main content -->