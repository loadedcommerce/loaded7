<?php
/*
  $Id: products_listing.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--Listing Toolbar Starts-->
<div id="toolbarContainer">
<div class="toolbar">
  <!-- will add back later :: maestro
  <div class="sortby">
    <label>Sort by:</label>
    <select>
      <option>PRICE</option>
      <option>NAME</option>
    </select>
  </div>-->
  <div class="viewby">
    <label>View as:</label>
    <a class="list" id="listView" href="javascript:void(0);"></a><a class="grid" id="gridView" href="javascript:void(0);"></a>
  </div>
  <!-- will add back later :: maestro
  <div class="show_no">
    <label>Show:</label>
    <select>
      <option>12 ITEMS</option>
      <option>24 ITEMS</option>
    </select>
  </div>-->
</div>
</div>
<!--Listing Toolbar Ends-->
<?php
// create column list
$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                     'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                     'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                     'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                     'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                     'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                     'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                     'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
asort($define_list);
$column_list = array();
reset($define_list);
while (list($key, $value) = each($define_list)) {
  if ($value > 0) $column_list[] = $key;
}
if ( ($Qlisting->numberOfRows() > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
  ?>
  <div>
    <div class="listingPageLinks">
      <span style="float: left"><?php echo $Qlisting->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></span>
      <span style="float: right"><?php echo $Qlisting->getBatchPageLinks('page', lc_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></span>
    </div>
    <div style="clear:both; height:10px;"></div>
  </div>
  <?php
}
?>
<div style="clear:both;"></div>
<div>
  <?php
  if ($Qlisting->numberOfRows() > 0) {
    $rows = 0;
    $grid_string = '';        
    $list_string = '';

    while ($Qlisting->next()) {
      $lC_Product = new lC_Product($Qlisting->valueInt('products_id'));
      $rows++;
      $grid_string .= '<li style="height:320px;"><!--row' . $rows . ' -->' . "\n";        
      $list_string .= '<li style="background:' . $color . '; "><div class="product_info">' . "\n";
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_text = '&nbsp;' . $lC_Product->getModel() . '&nbsp;';
            $lr_text = '&nbsp;' . $lC_Product->getModel() . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            if (isset($_GET['manufacturers'])) {
              $lc_text = '<div class="product_info"><b>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&manufacturers=' . $_GET['manufacturers']), $lC_Product->getTitle()) . '</b><br />
                          <small>' . substr(lc_clean_html($lC_Product->getDescription()), 0, 58) . '</small></div>';
            } else {
              $lc_text = '<div class="product_info"><b>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . ($cPath ? '&cPath=' . $cPath : '')), $lC_Product->getTitle()) . '</b><br />
                          <small>' . substr(lc_clean_html($lC_Product->getDescription()), 0, 58) . '...</small></div>';
            }
            if (isset($_GET['manufacturers'])) {
              $lr_text = '<b>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&manufacturers=' . $_GET['manufacturers']), $lC_Product->getTitle()) . '</b><br />
                          <small>' . lc_clean_html($lC_Product->getDescription()) . '</small>';
            } else {
              $lr_text = '<b>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . ($cPath ? '&cPath=' . $cPath : '')), $lC_Product->getTitle()) . '</b><br />
                          <small>' . lc_clean_html($lC_Product->getDescription()) . '</small>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_text = '&nbsp;';
            if ( $lC_Product->hasManufacturer() ) {
              $lc_text = '&nbsp;' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $lC_Product->getManufacturerID()), $lC_Product->getManufacturer()) . '&nbsp;';
            }
            $lr_text = '&nbsp;';
            if ( $lC_Product->hasManufacturer() ) {
              $lr_text = '&nbsp;' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $lC_Product->getManufacturerID()), $lC_Product->getManufacturer()) . '&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_text = '';
            $lr_text = '';
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_text = '&nbsp;' . $lC_Product->getQuantity() . '&nbsp;';
            $lr_text = '&nbsp;' . $lC_Product->getQuantity() . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_text = '&nbsp;' . $lC_Product->getWeight() . '&nbsp;';
            $lr_text = '&nbsp;' . $lC_Product->getWeight() . '&nbsp;';
            break; 
          case 'PRODUCT_LIST_IMAGE':
            if (isset($_GET['manufacturers'])) {
              $lc_text = lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&manufacturers=' . $_GET['manufacturers']), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle()), ' class="product_image"');
            } else {
              $lc_text = lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . ($cPath ? '&cPath=' . $cPath : '')), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle()), ' class="product_image"');
            }
            if (isset($_GET['manufacturers'])) {
              $lr_text = lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . '&manufacturers=' . $_GET['manufacturers']), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle()), ' class="product_image" align="left"');
            } else {
              $lr_text = lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword() . ($cPath ? '&cPath=' . $cPath : '')), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle()), ' class="product_image" align="left"');
            } 
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_text = '<a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add') . '" class="noDecoration"><div class="price_info"><button class="price_add" type="button"><span class="pr_price" style="white-space:nowrap;">' . $lC_Product->getPriceFormated(true) . '</span><span class="pr_add">' . $lC_Language->get('button_buy_now') . '</span></button></div></a>'; 
            $lr_text = '<a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add') . '" class="noDecoration"><div class="price_info"><button class="price_add" type="button"><span class="pr_price" style="white-space:nowrap;">' . $lC_Product->getPriceFormated(true) . '</span><span class="pr_add">' . $lC_Language->get('button_buy_now') . '</span></button></div></a>'; 

            break;
        }
        $grid_string .= $lc_text . "\n";
        $list_string .= $lr_text . "\n";
      }

      $grid_string .= '</li>' . "\n";
      $list_string .= '</div></li>' . "\n";
      if ($rows > 2) {
        $grid_string .= '';
        $rows = 0;
      }
    }     
  } else {
    $grid_string = '<div>' . $lC_Language->get('no_products_in_category') . '</div>';
    $list_string = '<div>' . $lC_Language->get('no_products_in_category') . '</div>';
  }
  ?>
  <div id="viewGrid" class="products_list products_slider">
    <ul>
      <?php echo $grid_string; ?>
    </ul>
  </div>
  <div id="viewList" class="products_list_list products_slider hidden">
    <ul>
      <?php echo $list_string; ?>
    </ul>
  </div>       
</div>
<div style="clear:both;"></div>
<?php
if ( ($Qlisting->numberOfRows() > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
  ?>
<div>
  <div class="listingPageLinks">
    <span style="float: right;"><?php echo $Qlisting->getBatchPageLinks('page', lc_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></span>
    <span style="float: left; margin-top:6px;"><?php echo $Qlisting->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></span>
  </div>
  <div style="clear:both;">&nbsp;</div>
</div> 
  <?php
}
?>