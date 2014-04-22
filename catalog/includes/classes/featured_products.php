<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
class lC_Featured_products {

  /* Private variables */
  var $_featured_products = array();

  /* Class constructor */
  public function lC_Featured_products() {
  }

  /* Public methods */  
  public function getListingOutput() {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Image, $lC_Featured_products;
    
    $Qf = $lC_Database->query('select products_id 
                                 from :table_featured_products 
                                where (str_to_date(expires_date, "%Y-%m-%d") >= str_to_date(now(), "%Y-%m-%d") or expires_date = "0000-00-00 00:00:00") 
                                  and status = 1 
                             order by expires_date desc');
    $Qf->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qf->execute();
    
    $Qfresults = array();
    
    while ( $Qf->next() ) {
      $Qfresults[] = $Qf->valueInt('products_id');
    }
    
    $output = '';      
    foreach ($Qfresults as $featured) {
      $Qfeatured = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, p.products_quantity, pd.products_name, pd.products_keyword, pd.products_description, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where p.products_id = :products_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id');
      $Qfeatured->bindTable(':table_products', TABLE_PRODUCTS);
      $Qfeatured->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qfeatured->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qfeatured->bindInt(':products_id', $featured);
      $Qfeatured->bindInt(':default_flag', 1);
      $Qfeatured->bindInt(':language_id', $lC_Language->getID());
      $Qfeatured->execute();      
      
      while ( $Qfeatured->next() ) {
        $lC_Product = new lC_Product($Qfeatured->valueInt('products_id'));
        
        $Qcode = $lC_Database->query('select id from :table_templates_boxes where code = :code');
        $Qcode->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
        $Qcode->bindValue(':code', 'date_available');
        $Qcode->execute();
        
        $Qdate = $lC_Database->query('select value from :table_product_attributes where id = :id and products_id = :products_id');
        $Qdate->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
        $Qdate->bindValue(':id', $Qcode->value('id'));
        $Qdate->bindValue(':products_id', $Qfeatured->valueInt('products_id'));
        $Qdate->execute(); 
        
        if ( strtotime($lC_Product->getDateAvailable()) <= strtotime(lC_Datetime::getShort()) ) {
          $output .= '<div class="content-featured-products-listing-container">';
          $output .= '  <div class="content-featured-products-listing-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qfeatured->value('products_keyword')), $Qfeatured->value('products_name')) . '</div>' . "\n";
          $output .= '  <div class="content-featured-products-listing-description">' . lc_clean_html($Qfeatured->value('products_description')) . '</div>' . "\n";
          $output .= '  <div class="content-featured-products-listing-price">' . $lC_Currencies->displayPrice($Qfeatured->value('products_price'), $Qfeatured->valueInt('products_tax_class_id')) . '</div>' . "\n";
          $output .= '  <div class="content-featured-products-listing-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qfeatured->value('products_keyword')), $lC_Image->show($Qfeatured->value('image'), $Qfeatured->value('products_name'))) . '</div>' . "\n";
          if (DISABLE_ADD_TO_CART == 1 && $Qfeatured->valueInt('products_quantity') < 1) {
            $output .= '  <div class="content-featured-products-listing-buy-now"><button type="button" class="content-featured-products-listing-buy-now-button" disabled>' . $lC_Language->get('out_of_stock') . '</button></div>' . "\n"; 
          } else {
            $output .= '  <div class="content-featured-products-listing-buy-now"><button type="button" onclick="document.location.href=\'' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $Qfeatured->value('products_keyword') . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add') . '\'" class="content-featured-products-listing-buy-now-button">' . $lC_Language->get('button_buy_now') . '</button></div>' . "\n"; 
          }
          $output .= '</div>' . "\n";
        }
      }
    }
    
    return $output;
  }

  public function expireAll() {
    global $lC_Database;

    $Qfeatured = $lC_Database->query('select products_id from :table_featured_products where status = 1 and now() >= expires_date and expires_date > 0');
    $Qfeatured->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qfeatured->execute();

    while ($Qfeatured->next()) {
      $this->_setStatus($Qfeatured->valueInt('products_id'), false);
    }

    $Qfeatured->freeResult();
  }    

  /* Private methods */
  private function _setStatus($id, $status) {
    global $lC_Database;

    $Qstatus = $lC_Database->query('update :table_featured_products set status = :status where products_id = :products_id');
    $Qstatus->bindTable(':table_featured_products', TABLE_FEATURED_PRODUCTS);
    $Qstatus->bindInt(':status', ($status === true) ? '1' : '0');
    $Qstatus->bindInt(':products_id', $id);
    $Qstatus->execute();

    $Qstatus->freeResult();
  }
}
?>