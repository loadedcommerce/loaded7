<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: locale.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Locale extends lC_Access {
  var $_module = 'locale',
      $_group = 'configuration',
      $_icon = 'world.png',
      $_title,
      $_sort_order = 600;

  public function lC_Access_Locale() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_locale_title');
    
    $this->_subgroups = array(array('icon' => 'world.png',
                                    'title' => $lC_Language->get('access_countries_title'),
                                    'identifier' => '?countries'),
                              array('icon' => 'zones.png',
                                    'title' => $lC_Language->get('access_zone_groups_title'),
                                    'identifier' => '?zone_groups'));      
  }
}
?>