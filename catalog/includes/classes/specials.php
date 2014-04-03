<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
class lC_Specials {

  /* Private variables */
  var $_specials = array();

  /* Class constructor */
  public function lC_Specials() {
  }

  /* Public methods */
  public function activateAll() {
    global $lC_Database;

    $Qspecials = $lC_Database->query('select specials_id from :table_specials where status = 0 and now() >= start_date and start_date > 0 and now() < expires_date');
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->execute();

    while ($Qspecials->next()) {
      $this->_setStatus($Qspecials->valueInt('specials_id'), true);
    }

    $Qspecials->freeResult();
  }

  public function expireAll() {
    global $lC_Database;

    $Qspecials = $lC_Database->query('select specials_id from :table_specials where status = 1 and now() >= expires_date and expires_date > 0');
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->execute();

    while ($Qspecials->next()) {
      $this->_setStatus($Qspecials->valueInt('specials_id'), false);
    }

    $Qspecials->freeResult();
  }

  public function isActive($id) {
    global $lC_Database;

    if (!isset($this->_specials[$id])) {
      $this->_specials[$id] = $this->getPrice($id);
    }

    return is_numeric($this->_specials[$id]);
  }

  public function getPrice($id) {
    global $lC_Database;

    if (!isset($this->_specials[$id])) {
      $Qspecial = $lC_Database->query('select specials_new_products_price from :table_specials where products_id = :products_id and status = 1');
      $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecial->bindInt(':products_id', $id);
      $Qspecial->execute();

      if ($Qspecial->numberOfRows() > 0) {
        $this->_specials[$id] = $Qspecial->valueDecimal('specials_new_products_price');
      } else {
        $this->_specials[$id] = null;
      }

      $Qspecial->freeResult();
    }

    return $this->_specials[$id];
  }

  public function getListing() {
    global $lC_Database, $lC_Language, $lC_Image;

    $Qspecials = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, pd.products_description, s.specials_new_products_price, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_specials s where p.products_status = 1 and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id and s.status = 1 order by s.specials_date_added desc');
    $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecials->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindInt(':default_flag', 1);
    $Qspecials->bindInt(':language_id', $lC_Language->getID());
    $Qspecials->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_SPECIAL_PRODUCTS);
    $Qspecials->execute();

    return $Qspecials;
  }
  
  public function getListingOutput() {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Image;
    
    $Qspecials = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, p.products_quantity, pd.products_name, pd.products_keyword, pd.products_description, s.specials_new_products_price, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_specials s where p.products_status = 1 and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id and s.status = 1 order by s.specials_date_added desc');
    $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
    $Qspecials->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
    $Qspecials->bindInt(':default_flag', 1);
    $Qspecials->bindInt(':language_id', $lC_Language->getID());
    $Qspecials->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_SPECIAL_PRODUCTS);
    $Qspecials->execute();
    
    $output = '';
    
    while ( $Qspecials->next() ) {
      $lC_Product = new lC_Product($Qspecials->valueInt('products_id'));
      
      $Qcode = $lC_Database->query('select id from :table_templates_boxes where code = :code');
      $Qcode->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
      $Qcode->bindValue(':code', 'date_available');
      $Qcode->execute();
      
      $Qdate = $lC_Database->query('select value from :table_product_attributes where id = :id and products_id = :products_id');
      $Qdate->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
      $Qdate->bindValue(':id', $Qcode->value('id'));
      $Qdate->bindValue(':products_id', $Qspecials->valueInt('products_id'));
      $Qdate->execute(); 
      
      if ( strtotime($lC_Product->getDateAvailable()) <= strtotime(lC_Datetime::getShort()) ) {
        $output .= '<div class="content-specials-listing-container">';
        $output .= '  <div class="content-specials-listing-name">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_keyword')), $Qspecials->value('products_name')) . '</div>' . "\n";
        $output .= '  <div class="content-specials-listing-description">' . lc_clean_html($Qspecials->value('products_description')) . '</div>' . "\n";
        $output .= '  <div class="content-specials-listing-price"><s>' . $lC_Currencies->displayPrice($Qspecials->value('products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</s> <span class="product-special-price">' . $lC_Currencies->displayPrice($Qspecials->value('specials_new_products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</span></div>' . "\n";
        $output .= '  <div class="content-specials-listing-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_keyword')), $lC_Image->show($Qspecials->value('image'), $Qspecials->value('products_name'))) . '</div>' . "\n";
        if (DISABLE_ADD_TO_CART == 1 && $Qspecials->valueInt('products_quantity') < 1) {
          $output .= '  <div class="content-specials-listing-buy-now"><button type="button" class="content-specials-listing-buy-now-button" disabled>' . $lC_Language->get('out_of_stock') . '</button></div>' . "\n"; 
        } else {
          $output .= '  <div class="content-specials-listing-buy-now"><button type="button" onclick="document.location.href=\'' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $Qspecials->value('products_keyword') . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add') . '\'" class="content-specials-listing-buy-now-button">' . $lC_Language->get('button_buy_now') . '</button></div>' . "\n"; 
        }
        $output .= '</div>' . "\n";
      }
    }
    
    return $output;
  }    

  /* Private methods */
  private function _setStatus($id, $status) {
    global $lC_Database;

    $Qstatus = $lC_Database->query('update :table_specials set status = :status, date_status_change = now() where specials_id = :specials_id');
    $Qstatus->bindTable(':table_specials', TABLE_SPECIALS);
    $Qstatus->bindInt(':status', ($status === true) ? '1' : '0');
    $Qstatus->bindInt(':specials_id', $id);
    $Qstatus->execute();

    $Qstatus->freeResult();
  }
}
?>