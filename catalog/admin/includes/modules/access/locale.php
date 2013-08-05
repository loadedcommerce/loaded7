<?php
/*
  $Id: locale.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Locale extends lC_Access {
    var $_module = 'locale',
        $_group = 'configuration',
        $_icon = 'world.png',
        $_title,
        $_sort_order = 600;

    function lC_Access_Locale() {
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