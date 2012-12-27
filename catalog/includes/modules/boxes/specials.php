<?php
/*
  $Id: specials.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_specials extends lC_Modules {
    var $_title,
        $_code = 'specials',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_specials() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_specials_heading');
    }

    function initialize() {
      global $lC_Database, $lC_Services, $lC_Currencies, $lC_Cache, $lC_Language, $lC_Image;

      $this->_title_link = lc_href_link(FILENAME_PRODUCTS, 'specials');

      if ($lC_Services->isStarted('specials')) {
        if ((BOX_SPECIALS_CACHE > 0) && $lC_Cache->read('box-specials-' . $lC_Language->getCode() . '-' . $lC_Currencies->getCode(), BOX_SPECIALS_CACHE)) {
          $data = $lC_Cache->getCache();
        } else {
          $Qspecials = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, s.specials_new_products_price, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_specials s where s.status = 1 and s.products_id = p.products_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id order by s.specials_date_added desc limit :max_random_select_specials');
          $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
          $Qspecials->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
          $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qspecials->bindInt(':default_flag', 1);
          $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
          $Qspecials->bindInt(':language_id', $lC_Language->getID());
          $Qspecials->bindInt(':max_random_select_specials', BOX_SPECIALS_RANDOM_SELECT);
          $Qspecials->executeRandomMulti();

          $data = array();

          if ($Qspecials->numberOfRows()) {
            $data = $Qspecials->toArray();

            $data['products_price'] = '<s>' . $lC_Currencies->displayPrice($Qspecials->valueDecimal('products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</s>&nbsp;<span class="productSpecialPrice">' . $lC_Currencies->displayPrice($Qspecials->valueDecimal('specials_new_products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</span>';

            $lC_Cache->write($data);
          }
        }

        if (empty($data) === false) {
          $this->_content = '';

          if (empty($data['image']) === false) {
            $this->_content = '<span id="sBoxImg">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $data['products_keyword']), $lC_Image->show($data['image'], $data['products_name'])) . '</span><br />';
          }

          $this->_content .= '<span id="sBoxName">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, $data['products_keyword']), $data['products_name']) . '<br /></span><span id="sBoxPrice">' . $data['products_price'] . '</span>';
        }
      }
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Random Product Specials Selection', 'BOX_SPECIALS_RANDOM_SELECT', '10', 'Select a random product on special from this amount of the newest products on specials available', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_SPECIALS_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_SPECIALS_RANDOM_SELECT', 'BOX_SPECIALS_CACHE');
      }

      return $this->_keys;
    }
  }
?>