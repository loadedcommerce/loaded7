<?php
/*
  $Id: search.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Search_Search extends lC_Template {

  /* Private variables */
  var $_module = 'search',
      $_group = 'search',
      $_page_title,
      $_page_image = 'table_background_browse.gif',
      $_page_contents = 'search.php';

  /* Class constructor */
  function lC_Search_Search() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_Search, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/search.php'));

    $this->_page_title = $lC_Language->get('search_heading');

    $lC_Search = new lC_Search();

    if (isset($_GET['keywords'])) {
      $this->_page_title = $lC_Language->get('search_results_heading');
      $this->_page_contents = 'results.php';

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_search_results'), lc_href_link(FILENAME_SEARCH, lc_get_all_get_params()));
      }

      $this->_process();
    } else {
      // nothing to do 
    }
  }

  /* Private methods */
  function _process() {
    global $lC_Language, $lC_MessageStack, $lC_Search, $Qlisting, $lC_Vqmod;
    
    require_once($lC_Vqmod->modCheck('includes/classes/search.php'));
    
    if (isset($_GET['datefrom']) && $_GET['datefrom'] != ''){
      $lC_Search->setDateFrom($_GET['datefrom']);
    }

    if (isset($_GET['dateto']) && $_GET['dateto'] != ''){
      $lC_Search->setDateTo($_GET['dateto']);
    }

    if ($lC_Search->hasDateSet()) {
      if ($lC_Search->getDateFrom() > $lC_Search->getDateTo()) {
        $lC_MessageStack->add('search', $lC_Language->get('error_search_to_date_less_than_from_date'));
      }
    }

    if (isset($_GET['pfrom']) && !empty($_GET['pfrom'])) {
      if (settype($_GET['pfrom'], 'double')) {
        $lC_Search->setPriceFrom($_GET['pfrom']);
      } else {
        $lC_MessageStack->add('search', $lC_Language->get('error_search_price_from_not_numeric'));
      }
    }

    if (isset($_GET['pto']) && !empty($_GET['pto'])) {
      if (settype($_GET['pto'], 'double')) {
        $lC_Search->setPriceTo($_GET['pto']);
      } else {
        $lC_MessageStack->add('search', $lC_Language->get('error_search_price_to_not_numeric'));
      }
    }

    if ($lC_Search->hasPriceSet('from') && $lC_Search->hasPriceSet('to') && ($lC_Search->getPriceFrom() >= $lC_Search->getPriceTo())) {
      $lC_MessageStack->add('search', $lC_Language->get('error_search_price_to_less_than_price_from'));
    }

    if (isset($_GET['keywords']) && is_string($_GET['keywords']) && !empty($_GET['keywords'])) {
      $lC_Search->setKeywords(urldecode($_GET['keywords']));

      if ($lC_Search->hasKeywords() === false) {
        $lC_MessageStack->add('search', $lC_Language->get('error_search_invalid_keywords'));
      }
    }

    if (!$lC_Search->hasKeywords() && !$lC_Search->hasPriceSet('from') && !$lC_Search->hasPriceSet('to') && !$lC_Search->hasDateSet('from') && !$lC_Search->hasDateSet('to')) {
      $lC_MessageStack->add('search', $lC_Language->get('error_search_at_least_one_input'));
    }

    if (isset($_GET['category']) && is_numeric($_GET['category']) && ($_GET['category'] > 0)) {
      $lC_Search->setCategory($_GET['category'], (isset($_GET['recursive']) && ($_GET['recursive'] == '1') ? true : false));
    }

    if (isset($_GET['manufacturer']) && is_numeric($_GET['manufacturer']) && ($_GET['manufacturer'] > 0)) {
      $lC_Search->setManufacturer($_GET['manufacturer']);
    }

    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
      if (strpos($_GET['sort'], '|d') !== false) {
        $lC_Search->setSortBy(substr($_GET['sort'], 0, -2), '-');
      } else {
        $lC_Search->setSortBy($_GET['sort']);
      }
    }

    if ($lC_MessageStack->size('search') > 0) {
      $this->_page_contents = 'search.php';
    } else { 
      $Qlisting = $lC_Search->execute();
    }
  }
}
?>