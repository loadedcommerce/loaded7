<?php
/*
  $Id: actions.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The lC_Actions class loads action modules to execute specific tasks
 */

  class lC_Actions {

/**
 * Loads the action module to execute
 *
 * @param string $module The name of the module to execute
 * @access public
 */

    public static function parse($module) {
      $module = basename($module);

      if ( !empty($module) && file_exists('includes/modules/actions/' . $module . '.php') ) {
        include('includes/modules/actions/' . $module . '.php');

        call_user_func(array('lC_Actions_' . $module, 'execute'));
      }
    }
  }
?>