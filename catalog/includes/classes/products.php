<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
class lC_Products {
  var $_category,
      $_recursive = true,
      $_manufacturer,
      $_sql_query,
      $_sort_by,
      $_sort_by_direction;

  /* Class constructor */
  function __construct($id = null) {
    if (is_numeric($id)) {
      $this->_category = $id;
    }
  }

  /* Public methods */
  function hasCategory() {
    return isset($this->_category) && !empty($this->_category);
  }

  function isRecursive() {
    return $this->_recursive;
  }

  function hasManufacturer() {
    return isset($this->_manufacturer) && !empty($this->_manufacturer);
  }

  function setCategory($id, $recursive = true) {
    $this->_category = $id;

    if ($recursive === false) {
      $this->_recursive = false;
    }
  }

  function setManufacturer($id) {
    $this->_manufacturer = $id;
  }

  function setSortBy($field, $direction = '+') {
    switch ($field) {
      case 'model':
        $this->_sort_by = 'p.products_model';
        break;
      case 'manufacturer':
        $this->_sort_by = 'm.manufacturers_name';
        break;
      case 'quantity':
        $this->_sort_by = 'p.products_quantity';
        break;
      case 'weight':
        $this->_sort_by = 'p.products_weight';
        break;
      case 'price':
        $this->_sort_by = 'final_price';
        break;
      case 'date_added':
        $this->_sort_by = 'p.products_date_added';
        break;
    }

    $this->_sort_by_direction = ($direction == '-') ? '-' : '+';
  }

  function setSortByDirection($direction) {
    $this->_sort_by_direction = ($direction == '-') ? '-' : '+';
  }

  function &execute($max_entries = MAX_DISPLAY_SEARCH_RESULTS) {
    global $lC_Database, $lC_Language, $lC_CategoryTree, $lC_Image;

    $Qlisting = $lC_Database->query('select SQL_CALC_FOUND_ROWS distinct p.products_id 
                                       from :table_products p 
                                     left join :table_product_attributes pa 
                                       on (p.products_id = pa.products_id) 
                                     left join :table_templates_boxes tb 
                                       on (pa.id = tb.id and tb.code = "manufacturers"), 
                                     :table_products_description pd, 
                                     :table_categories c, 
                                     :table_products_to_categories p2c 
                                     where p.products_status = 1 
                                       and p.products_id = pd.products_id 
                                       and pd.language_id = :language_id 
                                       and p.products_id = p2c.products_id 
                                       and p2c.categories_id = c.categories_id');
                                       
    $Qlisting->bindTable(':table_products', TABLE_PRODUCTS);
    $Qlisting->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qlisting->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qlisting->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qlisting->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qlisting->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    $Qlisting->bindInt(':default_flag', 1);
    $Qlisting->bindInt(':language_id', $lC_Language->getID());

    if ($this->hasCategory()) {
      if ($this->isRecursive()) {
        $subcategories_array = array($this->_category);

        $Qlisting->appendQuery('and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and p2c.categories_id in (:categories_id)');
        $Qlisting->bindRaw(':categories_id', implode(',', $lC_CategoryTree->getChildren($this->_category, $subcategories_array)));
      } else {
        $Qlisting->appendQuery('and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = :language_id and p2c.categories_id = :categories_id');
        $Qlisting->bindInt(':language_id', $lC_Language->getID());
        $Qlisting->bindInt(':categories_id', $this->_category);
      }
    }

    if ($this->hasManufacturer()) {
      $Qlisting->appendQuery('and pa.value = :manufacturers_id');
      $Qlisting->bindInt(':manufacturers_id', $this->_manufacturer);
    }

    $Qlisting->appendQuery('order by');

    if (isset($this->_sort_by)) {
      $Qlisting->appendQuery(':order_by :order_by_direction, pd.products_name');
      $Qlisting->bindRaw(':order_by', $this->_sort_by);
      $Qlisting->bindRaw(':order_by_direction', (($this->_sort_by_direction == '-') ? 'desc' : ''));
    } else {
      $Qlisting->appendQuery('pd.products_name :order_by_direction');
      $Qlisting->bindRaw(':order_by_direction', (($this->_sort_by_direction == '-') ? 'desc' : ''));
    }

    $Qlisting->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), $max_entries);     
    $Qlisting->execute();

    return $Qlisting;
  }
}
?>