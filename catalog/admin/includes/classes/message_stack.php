<?php
/**
*  $Id: message_stack.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     LoadedCommerce Team
*  @copyright  (c) 2013 LoadedCommerce Team
*  @license    http://loadedcommerce.com/license.html
*/

require('includes/classes/message_stack.php');

/**
* The lC_MessageStack class manages information messages to be displayed.
* Messages that are shown are automatically removed from the stack.
*/

class lC_MessageStack_Admin extends lC_MessageStack {

  /**
  * Get the messages belonging to a group. The messages are placed into an
  * unsorted list wrapped in a DIV element with the "messageStack" style sheet
  * class.
  *
  * @param string $group The name of the group to get the messages from
  * @access public
  */
  public function get($group, $imgLoc = '', $ext = '') {
    $result = false;

  }
}
?>