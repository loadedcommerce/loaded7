<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: actions.php v1.0 2013-08-08 datazen $
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
    global $lC_Vqmod;
    
    $module = basename($module);

    if ( !empty($module) && file_exists('includes/modules/actions/' . $module . '.php') ) {
      include($lC_Vqmod->modCheck('includes/modules/actions/' . $module . '.php'));

      call_user_func(array('lC_Actions_' . $module, 'execute'));
    }
  }
}
?>