<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: message_stack.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('../includes/classes/message_stack.php'));

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
  public function output($group, $imgLoc = '', $ext = '') {
    $result = false;

    if ( $this->exists($group) ) {
      $result = '<p class="big-message white-gradient">';

      foreach ( $this->_data[$group] as $message ) {
        switch ( $message['type'] ) {
          case 'error':
            $bullet = '<span class="icon-size3 icon-cross icon-red small-margin-right"></span>';
            break;

          case 'warning':
            $bullet = '<span class="icon-size3 icon-warning icon-orange small-margin-right"></span>';
            break;

          case 'success':
            $bullet = '<span class="icon-size3 icon-tick icon-green small-margin-right"></span>';
            break;

          default:
            $bullet = '<span class="icon-size3 icon-thumbs small-margin-right"></span>';
        }

        $result .= $bullet . ' ' . lc_output_string($message['text']) . '<br />';
      }

      $result .= '</p>';

      unset($this->_data[$group]);
    }

    return $result;
  }
}
?>