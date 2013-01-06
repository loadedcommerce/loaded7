<?php
/*
  $Id: zone_groups.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Zone_groups extends lC_Access {
    var $_module = 'zone_groups',
        $_group = 'configuration',
        $_icon = 'relationships.png',
        $_title,
        $_sort_order = 1400;

    function lC_Access_Zone_groups() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_zone_groups_title');
    }
  }
?>