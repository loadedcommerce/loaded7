<?php
/*
  $Id: default.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Default class manages default template functions
*/
class lC_Default {
 /*
  * Returns the live search data
  *
  * @param string $search The search string 
  * @access public
  * @return array
  */
  public static function find($search) {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Image;

    $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS p.*, pd.products_name, pd.products_description, pd.products_keyword from :table_products p, :table_products_description pd where p.parent_id = 0 and p.products_id = pd.products_id and pd.language_id = :language_id');

    $Qproducts->appendQuery('and (pd.products_name like :products_name or pd.products_keyword like :products_keyword) order by pd.products_name');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->bindValue(':products_name', '%' . $search . '%');
    $Qproducts->bindValue(':products_keyword', '%' . $search . '%');

    $Qproducts->execute();

    $cnt = 0;
    $result = '<table id="liveSearchTable" border="0" width="100%" cellspacing="0" cellpadding="2" onMouseover="bgcolor:#cccccc;">';
    while ( $Qproducts->next() ) {
      $price = $lC_Currencies->format($Qproducts->value('products_price'));
      $products_status = ($Qproducts->valueInt('products_status') === 1);
      $products_quantity = $Qproducts->valueInt('products_quantity');
      $products_name = $Qproducts->value('products_name');
      $products_description = $Qproducts->value('products_description');
      $products_keyword = $Qproducts->value('products_keyword');

      if ( $Qproducts->valueInt('has_children') === 1 ) {
        $Qvariants = $lC_Database->query('select min(products_price) as min_price, max(products_price) as max_price, sum(products_quantity) as total_quantity, min(products_status) as products_status from :table_products where parent_id = :parent_id');
        $Qvariants->bindTable(':table_products', TABLE_PRODUCTS);
        $Qvariants->bindInt(':parent_id', $Qproducts->valueInt('products_id'));
        $Qvariants->execute();

        $products_status = ($Qvariants->valueInt('products_status') === 1);
        $products_quantity = '(' . $Qvariants->valueInt('total_quantity') . ')';

        $price = $lC_Currencies->format($Qvariants->value('min_price'));

        if ( $Qvariants->value('min_price') != $Qvariants->value('max_price') ) {
          $price .= '&nbsp;-&nbsp;' . $lC_Currencies->format($Qvariants->value('max_price'));
        }
      }

      $Qimage = $lC_Database->query("select image from :table_products_images where products_id = '" . $Qproducts->valueInt('products_id') . "'");
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->execute();

      $products_image = $Qimage->value('image');
      $products_link = lc_href_link(FILENAME_PRODUCTS, $products_keyword);

      $rowClass = ($cnt & 1) ? 'liveSearchRowOdd' : 'liveSearchRowEven';
      $result .= '<tr onclick="window.location=\'' . $products_link . '\';" class="' . $rowClass . '"><td valign="top">' .
                 '  <ol class="liveSearchListing">' .
                 '    <li>' .
                 '      <span class="liveSearchListingSpan" style="width: ' . $lC_Image->getWidth('mini') . 'px;">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products_keyword), $lC_Image->show($products_image, $products_name, null, 'mini')) . '</span>' .
                 '      <div class="liveSearchListingDiv">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $products_keyword), $products_name) . '</div>' .
                 '      <div class="liveSearchListingPrice">' . $price . '</div>' .
                 '      <div style="clear: both;"></div>' .
                 '    </li>' .
                 '  </ol>' .
                 '</td></tr></a>';
      $cnt++;
    }
    $result .= '</table>';

    $Qproducts->freeResult();

    return $result;
  }
}
?>