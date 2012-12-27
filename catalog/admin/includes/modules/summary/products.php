<?php
/*
  $Id: products.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require('includes/applications/products/classes/products.php');

if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_products extends lC_Summary {

  var $enabled = FALSE,
      $sort_order = 50;
  
  /* Class constructor */
  function lC_Summary_products() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/products.php');
    $lC_Language->loadIniFile('products.php');

    $this->_title = $lC_Language->get('summary_products_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'products');

    if ( lC_Access::hasAccess('products') ) {
      $this->_setData();
    }
  }

  /* Private methods */
  function _setData() {
    global $lC_Database, $lC_Language, $lC_Currencies;

    if (!$this->enabled) {
      $this->_data = '';
    } else {
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile">' .
                     '  <h2 class="relative thin">' . $this->_title . '</h2>' .
                     '  <ul class="list spaced">';    
    
      if ( !isset($lC_Currencies) ) {
        if ( !class_exists('lC_Currencies') ) {
          include('../includes/classes/currencies.php');
        }

        $lC_Currencies = new lC_Currencies();
      }

      $Qproducts = $lC_Database->query('select p.products_id, greatest(p.products_date_added, p.products_last_modified) as date_last_modified, pd.products_name from :table_products p, :table_products_description pd where parent_id = 0 and p.products_id = pd.products_id and pd.language_id = :language_id order by date_last_modified desc limit 6');
      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qproducts->bindInt(':language_id', $lC_Language->getID());
      $Qproducts->execute();

      while ( $Qproducts->next() ) {
        $data = lC_Products_Admin::get($Qproducts->valueInt('products_id'));

        $products_icon = lc_icon_admin('products.png');
        $products_price = $data['products_price'];

        if ( !empty($data['variants']) ) {
          $products_icon = lc_icon_admin('attach.png');
          $products_price = null;

          foreach ( $data['variants'] as $variant ) {
            if ( ($products_price === null) || ($variant['data']['price'] < $products_price) ) {
              $products_price = $variant['data']['price'];
            }
          }

          if ( $products_price === null ) {
            $products_price = 0;
          }
        }
        
        $this->_data .= '    <li>' .
                        '      <span class="list-link icon-bag icon-blue" title="' . $lC_Language->get('edit') . '">' .  
                        '        <strong>' . (!empty($data['variants']) ? 'from ' : '') . $lC_Currencies->format($products_price) . '</strong> ' . lc_output_string_protected($data['products_name']) .
                        '      </span>' .
                        '      <div class="absolute-right compact show-on-parent-hover">' .
                        '        <a href="' . ((int)($_SESSION['admin']['access']['products'] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'products=' . $Qproducts->valueInt('products_id') . '&cID=' . $category_id . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['products'] < 3) ? ' disabled' : NULL) . '">' .  $lC_Language->get('icon_edit') . '</a>' .
                        '        <a href="' . ((int)($_SESSION['admin']['access']['products'] < 3) ? '#' : 'javascript://" onclick="copyProduct(\'' . $Qproducts->valueInt('products_id') . '\', \'' . urlencode($Qproducts->value('products_name')) . '\')') . '" class="button icon-pages with-tooltip' . ((int)($_SESSION['admin']['access']['products'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_copy') . '"></a>' .
                        '        <a href="' . ((int)($_SESSION['admin']['access']['products'] < 4) ? '#' : 'javascript://" onclick="deleteProduct(\'' . $Qproducts->valueInt('products_id') . '\', \'' . urlencode($Qproducts->value('products_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['products'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>' .
                        '      </div>' .
                        '    </li>';                                
      }

      $this->_data .= '  </ul>' .
                      '</div>';

      $Qproducts->freeResult();
      
      $this->_data .= $this->loadModal();      
    }
  }
  
  function loadModal() {
    global $lC_Database, $lC_Language, $lC_Template;
    
    if ( is_dir('includes/applications/products/modal') ) {
      if ( file_exists('includes/applications/products/modal/copy.php') ) include_once('includes/applications/products/modal/copy.php');
      if ( file_exists('includes/applications/products/modal/delete.php') ) include_once('includes/applications/products/modal/delete.php');
    }
  }  
}
?>