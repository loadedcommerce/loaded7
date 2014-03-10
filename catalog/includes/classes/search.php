<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: search.php v1.0 2013-08-08 datazen $
*/
require('includes/classes/products.php');

class lC_Search extends lC_Products {
  var $_period_min_year,
      $_period_max_year,
      $_date_from,
      $_date_to,
      $_price_from,
      $_price_to,
      $_keywords,
      $_number_of_results,
      $_category,
      $_recursive,
      $_manufacturer;

  /* Class constructor */
  public function lC_Search() {
    global $lC_Database;

    $Qproducts = $lC_Database->query('select min(year(products_date_added)) as min_year, max(year(products_date_added)) as max_year from :table_products limit 1');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->execute();

    $this->_period_min_year = $Qproducts->valueInt('min_year');
    $this->_period_max_year = $Qproducts->valueInt('max_year');
  }

  /* Public methods */
  public function getMinYear() {
    return $this->_period_min_year;
  }

  public function getMaxYear() {
    return $this->_period_max_year;
  }

  public function getDateFrom() {
    return $this->_date_from;
  }

  public function getDateTo() {
    return $this->_date_to;
  }

  public function getPriceFrom() {
    return $this->_price_from;
  }

  public function getPriceTo() {
    return $this->_price_to;
  }

  public function getKeywords() {
    return $this->_keywords;
  }

  public function getNumberOfResults() {
    return $this->_number_of_results;
  }
  
  public function hasDateSet($flag = null) {
    if ($flag == 'from') {
      return isset($this->_date_from);
    } else if ($flag == 'to') {
      return isset($this->_date_to);
    }

    return isset($this->_date_from) && isset($this->_date_to);
  }

  public function hasPriceSet($flag = null) {
    if ($flag == 'from') {
      return isset($this->_price_from);
    } elseif ($flag == 'to') {
      return isset($this->_price_to);
    }

    return isset($this->_price_from) && isset($this->_price_to);
  }

  public function hasKeywords() {
    return isset($this->_keywords) && !empty($this->_keywords);
  }

  public function hasManufacturer() {
    return isset($this->_manufacturer) && !empty($this->_manufacturer);
  }

  public function setCategory($category, $recursive) {
    $this->_category = $category;
    $this->_recursive = $recursive;
  }

  public function setDateFrom($timestamp) {
    $this->_date_from = $timestamp;
  }

  public function setDateTo($timestamp) {
    $this->_date_to = $timestamp;
  }

  public function setPriceFrom($price) {
    $this->_price_from = $price;
  }

  public function setPriceTo($price) {
    $this->_price_to = $price;
  }
  
  public function setRecursive($recursive) {
    $this->_recursive = $recursive;
  }
  
  public function setManufacturer($manufacturer) {
    $this->_manufacturer = $manufacturer;
  }

  public function setKeywords($keywords) {
    $terms = explode(' ', trim($keywords));

    $terms_array = array();

    $counter = 0;

    foreach ($terms as $word) {
      $counter++;

      if ($counter > 5) {
        break;
      } elseif (!empty($word)) {
        if (!in_array($word, $terms_array)) {
          $terms_array[] = $word;
        }
      }
    }

    $this->_keywords = implode(' ', $terms_array);
  }

  public function isRecursive() {
    return (isset($this->_recursive) && !empty($this->_recursive)) ? true : false;
  }

  public function &execute() {
    global $lC_Database, $lC_Customer, $lC_Currencies, $lC_Language, $lC_Image, $lC_CategoryTree;

    $Qlisting = $lC_Database->query('select SQL_CALC_FOUND_ROWS distinct p.*, pd.*, m.*, i.image, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price');

    if (($this->hasPriceSet('from') || $this->hasPriceSet('to')) && (DISPLAY_PRICE_WITH_TAX == '1')) {
      $Qlisting->appendQuery(', sum(tr.tax_rate) as tax_rate');
    }

    $Qlisting->appendQuery('from :table_products p left join :table_manufacturers m using(manufacturers_id) left join :table_specials s on (p.products_id = s.products_id) left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag)');
    $Qlisting->bindTable(':table_products', TABLE_PRODUCTS);
    $Qlisting->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qlisting->bindTable(':table_specials', TABLE_SPECIALS);
    $Qlisting->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qlisting->bindInt(':default_flag', 1);

    if (($this->hasPriceSet('from') || $this->hasPriceSet('to')) && (DISPLAY_PRICE_WITH_TAX == '1')) {
      if ($lC_Customer->isLoggedOn()) {
        $customer_country_id = $lC_Customer->getCountryID();
        $customer_zone_id = $lC_Customer->getZoneID();
      } else {
        $customer_country_id = STORE_COUNTRY;
        $customer_zone_id = STORE_ZONE;
      }

      $Qlisting->appendQuery('left join :table_tax_rates tr on p.products_tax_class_id = tr.tax_class_id left join :table_zones_to_geo_zones gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = 0 or gz.zone_country_id = :zone_country_id) and (gz.zone_id is null or gz.zone_id = 0 or gz.zone_id = :zone_id)');
      $Qlisting->bindTable(':table_tax_rates', TABLE_TAX_RATES);
      $Qlisting->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
      $Qlisting->bindInt(':zone_country_id', $customer_country_id);
      $Qlisting->bindInt(':zone_id', $customer_zone_id);
    }

    $Qlisting->appendQuery(', :table_products_description pd');
    if ($this->hasCategory()) $Qlisting->appendQuery(', :table_categories c, :table_products_to_categories p2c');
    $Qlisting->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    if ($this->hasCategory()) $Qlisting->bindTable(':table_categories', TABLE_CATEGORIES);
    if ($this->hasCategory()) $Qlisting->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    
    if ($this->hasManufacturer()) {
      $Qlisting->appendQuery(', :table_product_attributes pa');
      $Qlisting->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    }

    $Qlisting->appendQuery('where p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id');
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
      $Qlisting->appendQuery('and p.products_id = pa.products_id and pa.value = :manufacturers_id');
      $Qlisting->bindInt(':manufacturers_id', $this->_manufacturer);
    }

    if ($this->hasKeywords()) {
      $Qlisting->prepareSearch($this->_keywords, array('pd.products_name', 'pd.products_description'), true);
    }

    if ($this->hasDateSet('from')) {
      $dateParts = explode("/", $this->_date_from);
      $Qlisting->appendQuery('and p.products_date_added >= :products_date_added');
      $Qlisting->bindValue(':products_date_added', @date('Y-m-d H:i:s', @mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2])));
    }

    if ($this->hasDateSet('to')) {
      $dateParts = explode("/", $this->_date_to);
      $Qlisting->appendQuery('and p.products_date_added <= :products_date_added');
      $Qlisting->bindValue(':products_date_added', @date('Y-m-d H:i:s', @mktime(23, 59, 59, $dateParts[0], $dateParts[1], $dateParts[2])));
    }

    if ($this->hasPriceSet('from')) {
      if ($lC_Currencies->exists($_SESSION['currency'])) {
        $this->_price_from = $this->_price_from / $lC_Currencies->value($_SESSION['currency']);
      }
    }

    if ($this->hasPriceSet('to')) {
      if ($lC_Currencies->exists($_SESSION['currency'])) {
        $this->_price_to = $this->_price_to / $lC_Currencies->value($_SESSION['currency']);
      }
    }

    if (DISPLAY_PRICE_WITH_TAX == '1') {
      if ($this->_price_from > 0) {
        $Qlisting->appendQuery('and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= :price_from)');
        $Qlisting->bindFloat(':price_from', $this->_price_from);
      }

      if ($this->_price_to > 0) {
        $Qlisting->appendQuery('and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= :price_to)');
        $Qlisting->bindFloat(':price_to', $this->_price_to);
      }
    } else {
      if ($this->_price_from > 0) {
        $Qlisting->appendQuery('and (if(s.status, s.specials_new_products_price, p.products_price) >= :price_from)');
        $Qlisting->bindFloat(':price_from', $this->_price_from);
      }

      if ($this->_price_to > 0) {
        $Qlisting->appendQuery('and (if(s.status, s.specials_new_products_price, p.products_price) <= :price_to)');
        $Qlisting->bindFloat(':price_to', $this->_price_to);
      }
    }

    if (($this->hasPriceSet('from') || $this->hasPriceSet('to')) && (DISPLAY_PRICE_WITH_TAX == '1')) {
      $Qlisting->appendQuery('group by p.products_id, tr.tax_priority');
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
      
    $Qlisting->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_SEARCH_RESULTS);
    $Qlisting->execute();

    $this->_number_of_results = $Qlisting->getBatchSize();

    return $Qlisting;
  }
}
?>