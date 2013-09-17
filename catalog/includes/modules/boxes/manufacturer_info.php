<?php
/**
  @package    catalog::search::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturer_info.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_manufacturer_info extends lC_Modules {
  var $_title,
      $_code = 'manufacturer_info',
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_manufacturer_info() {
    global $lC_Language;

    if (function_exists($lC_Language->injectDefinitions))$lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
    
    $this->_title = $lC_Language->get('box_manufacturer_info_heading');
  }

  public function initialize() {
    global $lC_Database, $lC_Language, $lC_Product;

    if (isset($lC_Product) && is_a($lC_Product, 'lC_Product')) {
      $Qmanufacturer = $lC_Database->query('select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from :table_manufacturers m left join :table_manufacturers_info mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = :languages_id), :table_products p  where p.products_id = :products_id and p.manufacturers_id = m.manufacturers_id');
      $Qmanufacturer->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qmanufacturer->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qmanufacturer->bindTable(':table_products', TABLE_PRODUCTS);
      $Qmanufacturer->bindInt(':languages_id', $lC_Language->getID());
      $Qmanufacturer->bindInt(':products_id', $lC_Product->getID());
      $Qmanufacturer->execute();

      if ($Qmanufacturer->numberOfRows()) {
        $this->_content = '';

        if (!lc_empty($Qmanufacturer->value('manufacturers_image'))) {
          $this->_content .= '<li class="box-manufacturers-info-image">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $Qmanufacturer->valueInt('manufacturers_id')), lc_image(DIR_WS_IMAGES . 'manufacturers/' . $Qmanufacturer->value('manufacturers_image'), $Qmanufacturer->value('manufacturers_name'))) . '</li>' . "\n";
        }

        if (!lc_empty($Qmanufacturer->value('manufacturers_url'))) {
          $this->_content .= '<li class="box-manufacturers-info-url">' . lc_link_object(lc_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $Qmanufacturer->valueInt('manufacturers_id')), sprintf($lC_Language->get('box_manufacturer_info_website'), $Qmanufacturer->value('manufacturers_name')), 'target="_blank"') . '</li>' . "\n";
        }
        $this->_content .= '<li class="box-manufacturers-info-link">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $Qmanufacturer->valueInt('manufacturers_id')), $lC_Language->get('box_manufacturer_info_products')) . '</li>' . "\n";
      }
    }
  }
}
?>