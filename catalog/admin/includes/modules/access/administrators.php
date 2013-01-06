<?php
/*
  $Id: administrators.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Administrators extends lC_Access {
    var $_module = 'administrators',
        $_group = 'configuration',
        $_icon = 'people.png',
        $_title,
        $_sort_order = 100;

    function lC_Access_Administrators() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_administrators_title');

      $this->_subgroups = array(array('icon' => 'statistics.png',
                                      'title' => $lC_Language->get('access_administrators_groups_title'),
                                      'identifier' => 'set=groups'),
                                array('icon' => 'people.png',
                                      'title' => $lC_Language->get('access_administrators_members_title'),
                                      'identifier' => 'set=members'));
    }
  }
?>