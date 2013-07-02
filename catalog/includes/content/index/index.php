<?php
  /*
  $Id: index.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */

  class lC_Index_Index extends lC_Template {

    /* Private variables */
    var $_module = 'index',
    $_group = 'index',
    $_page_title,
    $_page_contents = 'index.php',
    $_page_image = 'table_background_default.gif';

    /* Class constructor */
    function lC_Index_Index() {
      global $lC_Database, $lC_Services, $lC_Language, $lC_Breadcrumb, $cPath, $cPath_array, $current_category_id, $lC_CategoryTree, $lC_Category;

      $this->_page_title = sprintf($lC_Language->get('index_heading'), STORE_NAME);
      $template_code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'default';
      
      if (isset($cPath) && (empty($cPath) === false)) {
        if ($lC_Services->isStarted('breadcrumb')) {
          $Qcategories = $lC_Database->query('select categories_id, categories_name from :table_categories_description where categories_id in (:categories_id) and language_id = :language_id');
          $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcategories->bindTable(':categories_id', implode(',', $cPath_array));
          $Qcategories->bindInt(':language_id', $lC_Language->getID());
          $Qcategories->execute();

          $categories = array();
          while ($Qcategories->next()) {
            $categories[$Qcategories->value('categories_id')] = $Qcategories->valueProtected('categories_name');
          }

          $Qcategories->freeResult();

          if ($lC_Services->isStarted('breadcrumb')) {
            $lC_Breadcrumb->add(null, null, $_GET['cPath']);
          }          

        }

        $lC_Category = new lC_Category($current_category_id);
        
        // added to check for category status and show not found page
        if ( $lC_CategoryTree->getStatus($current_category_id) == 1 ) {
          // categry is enabled move on
          $this->_page_title = $lC_Category->getTitle();

          if ( $lC_Category->hasImage() ) {
            $this->_page_image = 'categories/' . $lC_Category->getImage();
          }

          $Qproducts = $lC_Database->query('select products_id from :table_products_to_categories where categories_id = :categories_id limit 1');
          $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
          $Qproducts->bindInt(':categories_id', $current_category_id);
          $Qproducts->execute();

          if ($Qproducts->numberOfRows() > 0) {
            $this->_page_contents = 'product_listing.php';

            $this->_process();
          } else {
            $Qparent = $lC_Database->query('select categories_id from :table_categories where parent_id = :parent_id limit 1');
            $Qparent->bindTable(':table_categories', TABLE_CATEGORIES);
            $Qparent->bindInt(':parent_id', $current_category_id);
            $Qparent->execute();

            $this->_page_contents = 'category_listing.php';
            
            $this->_process();
          }
          $this->addOGPTags('site_name', STORE_NAME);
          $this->addOGPTags('type', 'website');
          $this->addOGPTags('title', $this->_page_title);
          $this->addOGPTags('description', $this->_page_title);
          $this->addOGPTags('url', lc_href_link(FILENAME_DEFAULT, 'cPath=' . $_GET['cPath'], 'NONSSL',false,true,true));
          $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . $template_code . '/images/logo.png');
          if ( $lC_Category->hasImage() ) {
            $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $this->_page_image);
          }          
        } else {
          // category is disabled, show not found content
          $this->_page_contents = 'category_not_found.php';
        }
      } else {
        $this->addOGPTags('site_name', STORE_NAME);
        $this->addOGPTags('type', 'website');
        $this->addOGPTags('title', $this->_page_title);
        $this->addOGPTags('description', $this->_page_title);
        $this->addOGPTags('url', lc_href_link(FILENAME_DEFAULT, '', 'NONSSL',false,true,true));
        $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . $template_code . '/images/logo.png');
      }
    }

    /* Private methods */
    function _process() {
      global $current_category_id, $lC_Products;

      include('includes/classes/products.php');
      $lC_Products = new lC_Products($current_category_id);

      if (isset($_GET['filter']) && is_numeric($_GET['filter']) && ($_GET['filter'] > 0)) {
        $lC_Products->setManufacturer($_GET['filter']);
      }

      if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        if (strpos($_GET['sort'], '|d') !== false) {
          $lC_Products->setSortBy(substr($_GET['sort'], 0, -2), '-');
        } else {
          $lC_Products->setSortBy($_GET['sort']);
        }
      }
    }
  }
?>