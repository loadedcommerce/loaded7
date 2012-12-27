<?php
/*
  $Id: zone_groups.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
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