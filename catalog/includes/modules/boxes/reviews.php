<?php
/**
  @package    catalog::search::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_reviews extends lC_Modules {
  var $_title,
      $_code = 'reviews',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_reviews() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_reviews_heading');
  }

  public function initialize() {
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
           $this->_content = '<li class="box-reviews-write">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()), $lC_Language->get('box_reviews_write')) . '</li>' . "\n";
        }
      } else {
        if (!empty($data['image'])) {
          $this->_content = '<li class="box-reviews-image">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=' . $data['reviews_id'] . '&' . $data['products_keyword']), $lC_Image->show($data['image'], $data['products_name'])) . '</li>';
        }

        $this->_content .= '<li class="box-reviews-text">' . lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=' . $data['reviews_id'] . '&' . $data['products_keyword']), wordwrap(lc_output_string_protected($data['reviews_text']), 15, "\n") . '...') . "\n" .
                           '<li class="box-reviews-rating">' . $lC_Language->get('box_reviews_average_rating') . ' ' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $data['reviews_rating'] . '.png' , sprintf($lC_Language->get('box_reviews_stars_rating'), $data['reviews_rating'])) . '</li>';
      }
    }
  }

  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Random Review Selection', 'BOX_REVIEWS_RANDOM_SELECT', '10', 'Select a random review from this amount of the newest reviews available', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_REVIEWS_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
  }

  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_REVIEWS_RANDOM_SELECT', 'BOX_REVIEWS_CACHE');
    }

    return $this->_keys;
  }
}
?>