<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
?>
<div id="section_orders_products">
  <h3 class="show-below-768 margin-left margin-top no-margin-bottom"><?php echo $lC_Language->get('text_products'); ?></h3>
  <div class="columns with-padding">
    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
      <fieldset class="mid-margin-bottom">
        <legend class="small-margin-bottom">
          <span class="icon-list icon-anthracite"><strong class="small-margin-left"><?php echo $lC_Language->get('text_products_ordered'); ?></strong></span>
        </legend>
        <table class="table">
          <thead>
            <tr>
              <th class="hide-on-mobile"><?php echo $lC_Language->get('text_sku_model'); ?></th>
              <th><?php echo $lC_Language->get('text_name'); ?></th>
              <th class="hide-on-mobile hide-on-tablet"><?php echo $lC_Language->get('text_fulfillment'); ?></th>
              <!--<th class="hide-on-mobile hide-on-tablet"><?php echo $lC_Language->get('text_tax'); ?></th>-->
              <th class="align-right hide-on-mobile"><?php echo $lC_Language->get('text_price'); ?></th>
              <th class="align-center hide-on-mobile"><?php echo $lC_Language->get('text_qty'); ?></th>
              <th class="align-right hide-on-mobile"><?php echo $lC_Language->get('text_total'); ?></th>
              <th class="align-right"><?php echo $lC_Language->get('text_action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach (lC_Orders_Admin::getOrdersProducts($_GET[$lC_Template->getModule()]) as $products) {
            ?>
            <tr id="orders_products_<?php echo $products['orders_products_id']; ?>">
              <td class="hide-on-mobile">
                <span id="products_model_<?php echo $products['orders_products_id']; ?>"><?php echo $products['model']; ?></span>
              </td>
              <td>
                <span id="products_name_<?php echo $products['orders_products_id']; ?>"><?php echo $products['name']; ?>
                <?php
                  if ( isset($product['attributes']) && is_array($product['attributes']) && ( sizeof($product['attributes']) > 0 ) ) {
                    foreach ( $product['attributes'] as $attributes ) {
                      echo '<br /><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <span class="large-margin-left"><i>' . $attributes['option'] . ': ' . $attributes['value'] . '</i></span></nobr>';
                    }
                  }              
                  if ( isset($product['options']) && is_array($product['options']) && ( sizeof($product['options']) > 0 ) ) {
                    foreach ( $product['options'] as $key => $val ) {
                      echo '<br /><nobr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <span class="small" class="large-margin-left"><i>' . $val['group_title'] . ': ' . $val['value_title'] . '</i></span></nobr>';
                    }
                  }
                ?>
              </td>
              <td class="hide-on-mobile hide-on-tablet">
                <span id="products_stock_<?php echo $products['orders_products_id']; ?>"><?php echo $products['stock']; ?></span>
              </td>
              <!--<td class="hide-on-mobile hide-on-tablet">
                <span id="products_tax_<?php echo $products['orders_products_id']; ?>"><?php echo ($products['tax'] != '') ? $lC_Currencies->format($products['tax']) : $lC_Currencies->format(0); ?></span>
              </td>-->
              <td class="align-right hide-on-mobile">
                <span id="products_price_<?php echo $products['orders_products_id']; ?>"><?php echo $lC_Currencies->format($products['price']); ?></span>
              </td>
              <td class="align-center hide-on-mobile">
                <span id="products_qty_<?php echo $products['orders_products_id']; ?>"><?php echo $products['quantity']; ?></span>
              </td>
              <td class="align-right hide-on-mobile">
                <span id="products_total_<?php echo $products['orders_products_id']; ?>"><?php echo $lC_Currencies->format($products['price']*$products['quantity']); ?></span>
              </td>
              <td class="align-right" style="min-width:90px;">
                <span id="buttons_<?php echo $products['orders_products_id']; ?>">
                  <span class="button-group">
                    <a class="button icon-pencil" href="javascript:void(0);" onclick="editOrderProduct('<?php echo $_GET[$lC_Template->getModule()]; ?>','<?php echo $products['orders_products_id']; ?>');"><?php echo $lC_Language->get('text_edit'); ?></a>
                  </span>
                  <span class="button-group">
                    <a class="button icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct('<?php echo $products['orders_products_id']; ?>', '<?php echo $products['products_id']; ?>', '<?php echo $products['name']; ?>');"></a>
                  </span>
                </span>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
        <div class="columns with-small-padding small-margin-left bbottom-grey small-margin-top small-margin-right small-margin-bottom align-right height bold">
          <?php echo $lC_Language->get('text_product_sub_total') . ': ' . $oInfo->get('orderSubTotal'); ?>
        </div>
        <div class="columns with-small-padding small-margin-left align-left productSearchInputWrapper">
          <div class="margin-right"><?php echo $lC_Template->showProductSearch('add_product'); ?></div>
          <input id="add_product" type="hidden" value="" name="add_product">
          <a href="javascript:void(0);" onclick="addOrderProduct('<?php echo $_GET[$lC_Template->getModule()];?>', 'add_product');">
            <button class="button glossy" type="button" disabled="disabled">
              <?php echo $lC_Language->get('button_add'); ?>
            </button>
          </a>
        </div>
      </fieldset>
    </div> 
  </div>
</div>