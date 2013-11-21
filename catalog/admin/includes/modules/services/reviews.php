<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
class lC_Services_reviews_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes;

  public function lC_Services_reviews_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/reviews.php');

    $this->title = $lC_Language->get('services_reviews_title');
    $this->description = $lC_Language->get('services_reviews_description');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'Maximum number of new reviews to display', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_public function, date_added) values ('Review Level', 'SERVICE_REVIEW_ENABLE_REVIEWS', '1', 'Customer level required to write a review.', '6', '0', 'lc_cfg_set_boolean_value(array(\'0\', \'1\', \'2\'))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_public function, date_added) values ('Moderate Reviews', 'SERVICE_REVIEW_ENABLE_MODERATION', '-1', 'Should reviews be approved by store admin.', '6', '0', 'lc_cfg_set_boolean_value(array(\'-1\', \'0\', \'1\'))', now())");
  }

  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MAX_DISPLAY_NEW_REVIEWS',
                 'SERVICE_REVIEW_ENABLE_REVIEWS',
                 'SERVICE_REVIEW_ENABLE_MODERATION');
  }
}
?>