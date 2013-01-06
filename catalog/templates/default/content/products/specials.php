<?php
/*
  $Id: specials.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$Qspecials = lC_Specials::getListing();
?>
<style>
.products_list div.price_info button { margin-top:10px; }
.full_page { margin-top:10px; }
</style>
<!--PRODUCT SPECIALS SECTION STARTS-->
  <div class="full_page">
    <!--PRODUCT SPECIALS CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <?php
          if ( ($Qspecials->numberOfRows() > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
        ?>
        <!--Listing Toolbar Starts-->
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
        <!--Listing Toolbar Ends-->
        <!--PRODUCT SPECIALS LISTING STARTS-->
        <div class="listingPageLinks">
          <span style="float: right;"><?php echo $Qspecials->getBatchPageLinks(); ?></span>
          <span style="float: left; margin-top:6px;"><?php echo $Qspecials->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></span>
        </div>
        <div style="clear:both; height:10px;"></div> 
        <?php
          }
        ?>
        <?php
          if ($Qspecials->numberOfRows() > 0) {
            $gridView = '';
            $listView = '';
            while ($Qspecials->next()) {
              $gridView .= '<li><div class="product_info">';
              $listView .= '<li><div class="product_info">';
              if (!lc_empty($Qspecials->value('image'))) {
                $gridView .= lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_keyword')), $lC_Image->show($Qspecials->value('image'), $Qspecials->value('products_name')), ' class="product_image"');
                $listView .= lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_keyword')), $lC_Image->show($Qspecials->value('image'), $Qspecials->value('products_name')), ' class="product_image"');
              }
              $gridView .= '<b>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_keyword')), $Qspecials->value('products_name')) . '</b><br />
                            <small>' . lc_clean_html(substr($Qspecials->value('products_description'), 0, 65)) . '</small>
                            </div> 
                            <a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $Qspecials->value('products_keyword') . '&' . lc_get_all_get_params(array('action')) . '&action=cart_add') . '" class="noDecoration">
                            <div class="price_info">
                            <button class="price_add" type="button">
                            <span class="pr_price" style="white-space:nowrap;"><s>' . $lC_Currencies->displayPrice($Qspecials->value('products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</s> ' . $lC_Currencies->displayPrice($Qspecials->value('specials_new_products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</span>
                            <span class="pr_add">' . $lC_Language->get('button_buy_now') . '</span>
                            </button>
                            </div>
                            </a>';
              $listView .= '<b>' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_keyword')), $Qspecials->value('products_name')) . '</b><br />
                            <small>' . lc_clean_html($Qspecials->value('products_description')) . '</small>
                            </div> 
                            <a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $Qspecials->value('products_keyword') . '&' . lc_get_all_get_params(array('action')) . '&action=cart_add') . '" class="noDecoration">
                            <div class="price_info" style="margin-top:-70px;">
                            <button class="price_add" type="button">
                            <span class="pr_price" style="white-space:nowrap;"><s>' . $lC_Currencies->displayPrice($Qspecials->value('products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</s> ' . $lC_Currencies->displayPrice($Qspecials->value('specials_new_products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</span>
                            <span class="pr_add">' . $lC_Language->get('button_buy_now') . '</span>
                            </button>
                            </div>
                            </a>';
            }
            $gridView .= '</li>' . "\n";
            $listView .= '</li>' . "\n";
          } else {
            echo '<p>' . $lC_Language->get('no_new_specials') . '</p>';
          }
        ?>
        <div id="viewGrid" class="products_list products_slider">
          <ul>
            <?php echo $gridView; ?>
          </ul>
        </div>
        <div id="viewList" class="products_list_list products_slider hidden">
          <ul>
            <?php echo $listView; ?>
          </ul>  
        </div>
        <div style="clear:both; height:10px;"></div>
        <?php
          if ( ($Qspecials->numberOfRows() > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
        ?>
        <div class="listingPageLinks">
          <span style="float: right;"><?php echo $Qspecials->getBatchPageLinks(); ?></span>
          <span style="float: left; margin-top:6px;"><?php echo $Qspecials->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></span>
        </div>
        <div style="clear:both;">&nbsp;</div> 
        <?php
          }
        ?>
        <!--PRODUCT SPECIALS LISTING ENDS-->
        <!--PRODUCT SPECIALS CONTENT STARTS-->
        <div id="productsSpecialsActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="javascript: history.go(-1);" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
      <!--PRODUCT SPECIALS CONTENT ENDS-->
      </div>
    </div>
    <!--PRODUCT SPECIALS CONTENT ENDS-->
  </div>
<!--PRODUCT SPECIALS CONTENT ENDS-->
