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

require('../includes/classes/message_stack.php');

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

    if ( $this->exists($group) ) {
      $result = '<div class="messageStack"><ul>';

      foreach ( $this->_data[$group] as $message ) {
        switch ( $message['type'] ) {
          case 'error':
            if ($imgLoc != null) {
              $bullet_image = $imgLoc . 'error' . $ext;
            } else {
              $bullet_image = DIR_WS_IMAGES . 'icons/error.gif';
            }
            break;

          case 'warning':
            if ($imgLoc != null) {
              $bullet_image = $imgLoc . 'warning' . $ext;
            } else {
              $bullet_image = DIR_WS_IMAGES . 'icons/warning.gif';
            }
            break;

          case 'success':
            if ($imgLoc != null) {
              $bullet_image = $imgLoc . 'success' . $ext;
            } else {
              $bullet_image = DIR_WS_IMAGES . 'icons/success.gif';
            }
            break;

          default:
            $bullet_image = DIR_WS_IMAGES . 'icons/bullet_default.gif';
        }

        $result .= '<li style="list-style-image: url(\'' . $bullet_image . '\')">' . lc_output_string($message['text']) . '</li>';
      }

      $result .= '</ul></div>';

      unset($this->_data[$group]);
    }

    return $result;
  }
}
?>