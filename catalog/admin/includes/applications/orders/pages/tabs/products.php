<?php
/*
  $Id: products.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
          <div id="section_orders_products">
            <h3 class="show-below-768 margin-left margin-top no-margin-bottom">Products</h3>
            <div class="columns with-padding">
              <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                <fieldset>
                  <legend class="small-margin-bottom">
                    <span class="icon-list icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('orders_summary_products_ordered'); ?></strong></span>
                  </legend>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <thead class="bbottom-anthracite">
                      <tr>
                        <th align="left" class="orders-products-listing-th hide-below-768">SKU/Model</th>
                        <th align="left" class="orders-products-listing-th">Product Name</th>
                        <th align="center" class="orders-products-listing-th hide-below-768">Fullfillment</th>
                        <th align="center" class="orders-products-listing-th">Tax Class</th>
                        <th align="center" class="orders-products-listing-th">Product Price</th>
                        <th align="right" class="orders-products-listing-th">Qty</th>
                        <th align="right" class="orders-products-listing-th">Product Total</th>
                        <th align="right" width="15%" class="orders-products-listing-th">Action</th>
                      </tr>
                    </thead>
                    <tbody class="mid-margin-bottom">
                    <?php
                      $Qordersproducts = array(array('id' => '24', 'model' => 'Floral001RL', 'name' => 'Floral Dress - Red - Large', 'stock' => 'In Stock', 'qty' => '2', 'total' => '250.00'),
                                               array('id' => '25', 'model' => 'Watch001', 'name' => 'Mens Watch', 'stock' => 'Shipped', 'qty' => '1', 'total' => '100.00'),
                                               array('id' => '26', 'model' => 'GC124GIFT', 'name' => 'Gift Certificate $125', 'stock' => '-', 'qty' => '1', 'total' => '125.00'),
                                               array('id' => '27', 'model' => 'Floral001GXL', 'name' => 'Floral Dress - Green - Large', 'stock' => 'Out of Stock', 'qty' => '1', 'total' => '125.00')); 
                      foreach ($Qordersproducts as $products) {
                      ?>
                      <tr id="orderProductsRow_<?php echo $products['id']; ?>" class="bbottom-grey">
                        <td align="left" class="orders-products-listing-td pad-right-10 hide-below-768"><?php echo $products['model']; ?></td>
                        <td align="left" class="orders-products-listing-td"><?php echo $products['name']; ?></td>
                        <td align="center" class="orders-products-listing-td hide-below-768"><small class="tag red-bg no-wrap"><?php echo $products['stock']; ?></small></td>
                        <td align="right" class="orders-products-listing-td pad-right-10"><?php echo $products['tax_class']; ?></td>
                        <td align="right" class="orders-products-listing-td pad-right-10"><?php echo $products['price']; ?></td>
                        <td align="right" class="orders-products-listing-td pad-right-10"><?php echo $products['qty']; ?></td>
                        <td align="right" class="orders-products-listing-td">$<?php echo $products['total']; ?></td>
                        <td align="right" width="15%" class="orders-products-listing-td">
                          <span class="button-group">
                            <a class="button compact icon-pencil" href="javascript:void(0)">Edit</a>
                            <a class="button compact icon-trash with-tooltip" title="Delete" href="javascript:void(0)"></a>
                          </span>
                        </td>
                      </tr>
                      <?php
                        }
                      ?>
                      <tr>
                        <td class="hide-below-768"></td>
                        <td class=""></td>
                        <td class="hide-below-768"></td>
                        <td class=""></td>
                        <td class="no-wrap pad-right-10 pad-top-10 bolder" align="right" colspan="2">Product Sub Total</td>
                        <td class="pad-top-10 bolder" align="right">$600.00</td>
                        <td width="15%" class="">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </fieldset>
              </div> 
            </div>
          </div>
