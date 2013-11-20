<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: recently_visited.php v1.0 2013-08-08 datazen $
*/
class lC_RecentlyVisited {
  var $visits = array();

  /* Class constructor */
  public function __construct() {
    if (isset($_SESSION['lC_RecentlyVisited_data']) === false) {
      $_SESSION['lC_RecentlyVisited_data'] = array();
    }

    $this->visits =& $_SESSION['lC_RecentlyVisited_data'];
  }

  public function initialize() {
    global $lC_Product, $lC_Category, $lC_Search;

    if (SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS == '1') {
      if (isset($lC_Product) && is_a($lC_Product, 'lC_Product')) {
        $this->setProduct($lC_Product->getMasterID());
      }
    }

    if (SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES == '1') {
      if (isset($lC_Category) && is_a($lC_Category, 'lC_Category')) {
        $this->setCategory($lC_Category->getID());
      }
    }

    if (SERVICE_RECENTLY_VISITED_SHOW_SEARCHES == '1') {
      if (isset($lC_Search) && is_a($lC_Search, 'lC_Search')) {
        if ($lC_Search->hasKeywords()) {
          $this->setSearchQuery($lC_Search->getKeywords());
        }
      }
    }
  }

  public function setProduct($id) {
    if (isset($this->visits['products'])) {
      foreach ($this->visits['products'] as $key => $value) {
        if ($value['id'] == $id) {
          unset($this->visits['products'][$key]);
          break;
        }
      }

      if (sizeof($this->visits['products']) > (SERVICE_RECENTLY_VISITED_MAX_PRODUCTS * 2)) {
        array_pop($this->visits['products']);
      }
    } else {
      $this->visits['products'] = array();
    }

    array_unshift($this->visits['products'], array('id' => $id));
  }

  public function setCategory($id) {
    if (isset($this->visits['categories'])) {
      foreach ($this->visits['categories'] as $key => $value) {
        if ($value['id'] == $id) {
          unset($this->visits['categories'][$key]);
          break;
        }
      }

      if (sizeof($this->visits['categories']) > (SERVICE_RECENTLY_VISITED_MAX_CATEGORIES * 2)) {
        array_pop($this->visits['categories']);
      }
    } else {
      $this->visits['categories'] = array();
    }

    array_unshift($this->visits['categories'], array('id' => $id));
  }

  public function setSearchQuery($keywords) {
    global $lC_Search;

    if (isset($this->visits['searches'])) {
      foreach ($this->visits['searches'] as $key => $value) {
        if ($value['keywords'] == $keywords) {
          unset($this->visits['searches'][$key]);
          break;
        }
      }

      if (sizeof($this->visits['searches']) > (SERVICE_RECENTLY_VISITED_MAX_SEARCHES * 2)) {
        array_pop($this->visits['searches']);
      }
    } else {
      $this->visits['searches'] = array();
    }

    array_unshift($this->visits['searches'], array('keywords' => $keywords,
                                                   'results' => $lC_Search->getNumberOfResults()
                                                  ));
  }

  public function hasHistory() {
    if ($this->hasProducts() || $this->hasCategories() || $this->hasSearches()) {
      return true;
    }

    return false;
  }

  public function hasProducts() {
    if ( SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS == '1' ) {
      if ( isset($this->visits['products']) && !empty($this->visits['products']) ) {
        foreach ($this->visits['products'] as $k => $v) {
          if ( !lC_Product::checkEntry($v['id']) ) {
            unset($this->visits['products'][$k]);
          }
        }

        return (sizeof($this->visits['products']) > 0);
      }
    }

    return false;
  }

  public function getProducts() {
    $history = array();

    if (isset($this->visits['products']) && (empty($this->visits['products']) === false)) {
      $counter = 0;

      foreach ($this->visits['products'] as $k => $v) {
        $counter++;

        $lC_Product = new lC_Product($v['id']);
        $lC_Category = new lC_Category($lC_Product->getCategoryID());

        $history[] = array('name' => $lC_Product->getTitle(),
                           'id' => $lC_Product->getID(),
                           'keyword' => $lC_Product->getKeyword(),
                           'price' => (SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES == '1') ? $lC_Product->getPriceFormated(true) : '',
                           'image' => $lC_Product->getImage(),
                           'category_name' =>  $lC_Category->getTitle(),
                           'category_path' => $lC_Category->getPath()
                          );

        if ($counter == SERVICE_RECENTLY_VISITED_MAX_PRODUCTS) {
          break;
        }
      }
    }

    return $history;
  }

  public function hasCategories() {
    return ( (SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES == '1') && isset($this->visits['categories']) && !empty($this->visits['categories']) );
  }

  public function getCategories() {
    $history = array();

    if (isset($this->visits['categories']) && (empty($this->visits['categories']) === false)) {
      $counter = 0;

      foreach ($this->visits['categories'] as $k => $v) {
        $counter++;

        $lC_Category = new lC_Category($v['id']);

        if ($lC_Category->hasParent()) {
          $lC_CategoryParent = new lC_Category($lC_Category->getParent());
        }

        $history[]  = array('id' => $lC_Category->getID(),
                            'name' => $lC_Category->getTitle(),
                            'path' => $lC_Category->getPath(),
                            'image' => $lC_Category->getImage(),
                            'parent_name' => ($lC_Category->hasParent()) ? $lC_CategoryParent->getTitle() : '',
                            'parent_id' => ($lC_Category->hasParent()) ? $lC_CategoryParent->getID() : ''
                           );

        if ($counter == SERVICE_RECENTLY_VISITED_MAX_CATEGORIES) {
          break;
        }
      }
    }

    return $history;
  }

  public function hasSearches() {
    return ( (SERVICE_RECENTLY_VISITED_SHOW_SEARCHES == '1') && isset($this->visits['searches']) && !empty($this->visits['searches']) );
  }

  public function getSearches() {
    $history = array();

    if (isset($this->visits['searches']) && (empty($this->visits['searches']) === false)) {
      $counter = 0;

      foreach ($this->visits['searches'] as $k => $v) {
        $counter++;

        $history[]  = array('keywords' => $this->visits['searches'][$k]['keywords'],
                            'results' => $this->visits['searches'][$k]['results']
                           );

        if ($counter == SERVICE_RECENTLY_VISITED_MAX_SEARCHES) {
          break;
        }
      }
    }

    return $history;
  }
}
?>