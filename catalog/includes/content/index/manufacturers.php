<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufactuers.php v1.0 2013-08-08 datazen $
*/
class lC_Index_Manufacturers extends lC_Template {

  /* Private variables */
  var $_module = 'manufacturers',
      $_group = 'index',
      $_page_title,
      $_page_contents = 'product_listing.php',
      $_page_image = 'table_background_list.gif';

  /* Class constructor */
  public function lC_Index_Manufacturers() {
    global $lC_Services, $lC_Language, $lC_Breadcrumb, $lC_Manufacturer;

    $this->_page_title = sprintf($lC_Language->get('index_heading'), STORE_NAME);
    $template_code = (isset($_SESSION['template']['code']) && $_SESSION['template']['code'] != NULL) ? $_SESSION['template']['code'] : 'default';

    if (is_numeric($_GET[$this->_module])) {
      include('includes/classes/manufacturer.php');
      $lC_Manufacturer = new lC_Manufacturer($_GET[$this->_module]);

      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Manufacturer->getTitle(), lc_href_link(FILENAME_DEFAULT, $this->_module . '=' . $_GET[$this->_module]));
      }

      $this->_page_title = $lC_Manufacturer->getTitle();
      $this->_page_image = 'manufacturers/' . $lC_Manufacturer->getImage();
      
      $this->addOGPTags('site_name', STORE_NAME);
      $this->addOGPTags('type', 'website');
      $this->addOGPTags('title', $this->_page_title);
      $this->addOGPTags('description', $this->_page_title);
      $this->addOGPTags('url', lc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $lC_Manufacturer->getID(), 'NONSSL',false,true,true));
      $this->addOGPTags('image', HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . $template_code . '/images/logo.png');

      $this->_process();
    } else {
      $this->_page_contents = 'index.php';
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_Manufacturer, $lC_Products;

    include('includes/classes/products.php');
    $lC_Products = new lC_Products();
    $lC_Products->setManufacturer($lC_Manufacturer->getID());

    if (isset($_GET['filter']) && is_numeric($_GET['filter']) && ($_GET['filter'] > 0)) {
      $lC_Products->setCategory($_GET['filter']);
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