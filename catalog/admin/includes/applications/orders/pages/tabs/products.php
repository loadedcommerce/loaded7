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
                  <div class="columns with-small-padding small-margin-left hide-below-768 bbottom-grey">
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom">SKU/Model</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobil no-margin-bottom">Name</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom">Fulfillment</div>
                    <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom">Tax Class</div>
                    <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom">Price</div>
                    <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom">Qty</div>
                    <div class="new-row-mobile one-column twelve-columns-mobile no-margin-bottom">Product Total</div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile no-margin-bottom align-right">Action</div>
                  </div>
                  <?php
                    $Qordersproducts = array(array('id' => '24', 'model' => 'Floral001RL', 'name' => 'Floral Dress - Red - Large', 'stock' => 'In Stock', 'tax_class' => '5%', 'price' => '125.00', 'qty' => '2', 'total' => '250.00'),
                                             array('id' => '25', 'model' => 'Watch001', 'name' => 'Mens Watch', 'stock' => 'Shipped', 'tax_class' => '7% (FL)', 'price' => '5%', 'qty' => '1', 'total' => '100.00'),
                                             array('id' => '26', 'model' => 'GC124GIFT', 'name' => 'Gift Certificate $125', 'stock' => '-', 'tax_class' => 'Non Taxable', 'price' => '125.00', 'qty' => '1', 'total' => '125.00'),
                                             array('id' => '27', 'model' => 'Floral001GXL', 'name' => 'Floral Dress - Green - Large', 'stock' => 'Out of Stock', 'tax_class' => '5%', 'price' => '75.00', 'qty' => '2', 'total' => '125.00')); 
                    foreach ($Qordersproducts as $products) {
                  ?>
                  <div class="columns with-small-padding small-margin-left bbottom-grey">
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">SKU/Model: </span>
                      <span id="products_model_<?php echo $products['id']; ?>"><?php echo $products['model']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Name: </span>
                      <span id="products_name_<?php echo $products['id']; ?>"><?php echo $products['name']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Fulfillment: </span>
                      <span id="products_stock_<?php echo $products['id']; ?>"><?php echo $products['stock']; ?></span>
                    </div>
                    <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Tax Class: </span>
                      <span id="products_tax_class_<?php echo $products['id']; ?>"><?php echo $products['tax_class']; ?></span>
                    </div>
                    <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Price: </span>
                      <span id="products_price_<?php echo $products['id']; ?>"><?php echo $products['price']; ?></span>
                    </div>
                    <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Qty: </span>
                      <span id="products_qty_<?php echo $products['id']; ?>"><?php echo $products['qty']; ?></span>
                    </div>
                    <div class="new-row-mobile one-column twelve-columns-mobile small-margin-bottom">
                      <span class="show-below-768 bold">Total: </span>
                      <span id="products_total_<?php echo $products['id']; ?>"><?php echo $products['total']; ?></span>
                    </div>
                    <div class="new-row-mobile two-columns twelve-columns-mobile small-margin-bottom align-right">
                      <span id="buttons_<?php echo $products['id']; ?>">
                        <span class="button-group">
                          <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct('<?php echo $products['id']; ?>');">Edit</a>
                          <a class="button compact icon-trash with-tooltip" title="Delete" href="javascript:void(0)"></a>
                        </span>
                      </span>
                    </div>
                  </div>
                  <?php
                    }
                  ?>
                </fieldset>
              </div> 
            </div>
          </div>
