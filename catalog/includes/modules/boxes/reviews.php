<?php
/*
  $Id: reviews.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_reviews extends lC_Modules {
    var $_title,
        $_code = 'reviews',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_reviews() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_reviews_heading');
    }

    function initialize() {
      global $lC_Database, $lC_Services, $lC_Cache, $lC_Language, $lC_Product, $lC_Image;

      $this->_title_link = lc_href_link(FILENAME_PRODUCTS, 'reviews');

      if ($lC_Services->isStarted('reviews')) {
        if ((BOX_REVIEWS_CACHE > 0) && $lC_Cache->read('box-reviews' . (isset($lC_Product) && is_a($lC_Product, 'lC_Product') && $lC_Product->isValid() ? '-' . $lC_Product->getID() : '') . '-' . $lC_Language->getCode(), BOX_REVIEWS_CACHE)) {
          $data = $lC_Cache->getCache();
        } else {
          $data = array();

          $Qreview = $lC_Database->query('select r.reviews_id, r.reviews_rating, p.products_id, pd.products_name, pd.products_keyword, i.image from :table_reviews r, :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where r.products_id = p.products_id and p.products_status = 1 and r.languages_id = :language_id and p.products_id = pd.products_id and pd.language_id = :language_id and r.reviews_status = 1');
          $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
          $Qreview->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
          $Qreview->bindTable(':table_products', TABLE_PRODUCTS);
          $Qreview->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qreview->bindInt(':default_flag', 1);
          $Qreview->bindInt(':language_id', $lC_Language->getID());
          $Qreview->bindInt(':language_id', $lC_Language->getID());

          if (isset($lC_Product) && is_a($lC_Product, 'lC_Product') && $lC_Product->isValid()) {
            $Qreview->appendQuery('and p.products_id = :products_id');
            $Qreview->bindInt(':products_id', $lC_Product->getID());
          }

          $Qreview->appendQuery('order by r.reviews_id desc limit :max_random_select_reviews');
          $Qreview->bindInt(':max_random_select_reviews', BOX_REVIEWS_RANDOM_SELECT);
          $Qreview->executeRandomMulti();

          if ($Qreview->numberOfRows()) {
            $Qtext = $lC_Database->query('select substring(reviews_text, 1, 60) as reviews_text from :table_reviews where reviews_id = :reviews_id and languages_id = :languages_id');
            $Qtext->bindTable(':table_reviews', TABLE_REVIEWS);
            $Qtext->bindInt(':reviews_id', $Qreview->valueInt('reviews_id'));
            $Qtext->bindInt(':languages_id', $lC_Language->getID());
            $Qtext->execute();

            $data = array_merge($Qreview->toArray(), $Qtext->toArray());

            $Qtext->freeResult();
            $Qreview->freeResult();
          }

          $lC_Cache->write($data);
        }

        $this->_content = '';

        if (empty($data)) {
          if (isset($lC_Product) && is_a($lC_Product, 'lC_Product') && $lC_Product->isValid()) {
            $this->_content = '<div style="float: left; width: 55px;">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()), lc_image(DIR_WS_TEMPLATE_IMAGES . 'box_write_review.png', $lC_Language->get('button_write_review'))) . '</div>' .
                              lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()), $lC_Language->get('box_reviews_write')) .
                              '<div style="clear: both;"></div>';
          }
        } else {
          if (!empty($data['image'])) {
            $this->_content = '<div align="center">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=' . $data['reviews_id'] . '&' . $data['products_keyword']), $lC_Image->show($data['image'], $data['products_name'])) . '</div>';
          }

          $this->_content .= lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=' . $data['reviews_id'] . '&' . $data['products_keyword']), wordwrap(lc_output_string_protected($data['reviews_text']), 15, "\n") . '...') . '<br /><div align="center">' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $data['reviews_rating'] . '.png' , sprintf($lC_Language->get('box_reviews_stars_rating'), $data['reviews_rating'])) . '</div>';
        }
      }
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Random Review Selection', 'BOX_REVIEWS_RANDOM_SELECT', '10', 'Select a random review from this amount of the newest reviews available', '6', '0', now())");
      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_REVIEWS_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_REVIEWS_RANDOM_SELECT', 'BOX_REVIEWS_CACHE');
      }

      return $this->_keys;
    }
  }
?>