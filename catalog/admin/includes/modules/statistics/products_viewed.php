<?php
/*
  $Id: products_viewed.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  if ( !class_exists('lC_Statistics') ) {
    include('includes/classes/statistics.php');
  }

  class lC_Statistics_Products_Viewed extends lC_Statistics {

// Class constructor

    function lC_Statistics_Products_Viewed() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/statistics/products_viewed.php');

      $this->_setIcon();
      $this->_setTitle();
    }

// Private methods

    function _setIcon() {
      $this->_icon = lc_icon_admin('products.png');
    }

    function _setTitle() {
      global $lC_Language;

      $this->_title = $lC_Language->get('statistics_products_viewed_title');
    }

    function _setHeader() {
      global $lC_Language;

      $this->_header = array($lC_Language->get('statistics_products_viewed_table_heading_products'),
                             $lC_Language->get('statistics_products_viewed_table_heading_language'),
                             $lC_Language->get('statistics_products_viewed_table_heading_total'));
    }

    function _setData() {
      global $lC_Database, $lC_Language;

      $this->_data = array();

      $this->_resultset = $lC_Database->query('select p.products_id, pd.products_name, pd.products_viewed, l.name, l.code from :table_products p, :table_products_description pd, :table_languages l where p.products_id = pd.products_id and l.languages_id = pd.language_id order by pd.products_viewed desc');
      $this->_resultset->bindTable(':table_products', TABLE_PRODUCTS);
      $this->_resultset->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $this->_resultset->bindTable(':table_languages', TABLE_LANGUAGES);
      $this->_resultset->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
      $this->_resultset->execute();

      while ( $this->_resultset->next() ) {
        $this->_data[] = array(lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, 'products&pID=' . $this->_resultset->valueInt('products_id') . '&action=preview'), $this->_icon . '&nbsp;' . $this->_resultset->value('products_name')),
                               $lC_Language->showImage($this->_resultset->value('code')),
                               $this->_resultset->valueInt('products_viewed'));
      }
    }
  }
?>