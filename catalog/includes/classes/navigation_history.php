<?php
/*
  $Id: navigation_history.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_NavigationHistory {

    /* Private variables */
    var $_data = array(),
        $_snapshot = array();

    /* Class constructor */
    function lC_NavigationHistory($add_current_page = false) {
      if (isset($_SESSION['lC_NavigationHistory_data']) && is_array($_SESSION['lC_NavigationHistory_data']) && (empty($_SESSION['lC_NavigationHistory_data']) === false)) {
        $this->_data =& $_SESSION['lC_NavigationHistory_data'];
      }

      if (isset($_SESSION['lC_NavigationHistory_snapshot']) && is_array($_SESSION['lC_NavigationHistory_snapshot']) && (empty($_SESSION['lC_NavigationHistory_snapshot']) === false)) {
        $this->_snapshot =& $_SESSION['lC_NavigationHistory_snapshot'];
      }

      if ($add_current_page === true) {
        $this->addCurrentPage();
      }
    }

    /* Public methods */
    function addCurrentPage() {
      global $request_type, $cPath;

      $set = 'true';

      for ($i=0, $n=sizeof($this->_data); $i<$n; $i++) {
        if ($this->_data[$i]['page'] == basename($_SERVER['SCRIPT_FILENAME'])) {
          if (isset($cPath)) {
            if (!isset($this->_data[$i]['get']['cPath'])) {
              continue;
            } else {
              if ($this->_data[$i]['get']['cPath'] == $cPath) {
                array_splice($this->_data, ($i+1));
                $set = 'false';
                break;
              } else {
                $old_cPath = explode('_', $this->_data[$i]['get']['cPath']);
                $new_cPath = explode('_', $cPath);

                for ($j=0, $n2=sizeof($old_cPath); $j<$n2; $j++) {
                  if ($old_cPath[$j] != $new_cPath[$j]) {
                    array_splice($this->_data, ($i));
                    $set = 'true';
                    break 2;
                  }
                }
              }
            }
          } else {
            array_splice($this->_data, $i);
            $set = 'true';
            break;
          }
        }
      }

      if ($set == 'true') {
        $this->_data[] = array('page' => basename($_SERVER['SCRIPT_FILENAME']),
                               'mode' => $request_type,
                               'get' => $_GET,
                               'post' => $_POST);

        if (isset($_SESSION['lC_NavigationHistory_data']) === false) {
          $_SESSION['lC_NavigationHistory_data'] = $this->_data;
        }
      }
    }

    function removeCurrentPage() {
      $last_entry_position = sizeof($this->_data) - 1;

      if ($this->_data[$last_entry_position]['page'] == basename($_SERVER['SCRIPT_FILENAME'])) {
        unset($this->_data[$last_entry_position]);

        if (sizeof($this->_data) > 0) {
          if (isset($_SESSION['lC_NavigationHistory_data']) === false) {
            $_SESSION['lC_NavigationHistory_data'] = $this->_data;
          }
        } else {
          $this->resetPath();
        }
      }
    }

    function hasPath($back = 1) {
      if ( (is_numeric($back) === false) || (is_numeric($back) && ($back < 1)) ) {
        $back = 1;
      }

      return isset($this->_data[sizeof($this->_data) - $back]);
    }

    function getPathURL($back = 1, $exclude = array()) {
      if ( (is_numeric($back) === false) || (is_numeric($back) && ($back < 1)) ) {
        $back = 1;
      }

      $back = sizeof($this->_data) - $back;

      return lc_href_link($this->_data[$back]['page'], $this->_parseParameters($this->_data[$back]['get'], $exclude), $this->_data[$back]['mode']);
    }

    function setSnapshot($page = '') {
      global $request_type;

      if (is_array($page)) {
        $this->_snapshot = array('page' => $page['page'],
                                 'mode' => $page['mode'],
                                 'get' => $page['get'],
                                 'post' => $page['post']);
      } else {
        $this->_snapshot = array('page' => basename($_SERVER['SCRIPT_FILENAME']),
                                 'mode' => $request_type,
                                 'get' => $_GET,
                                 'post' => $_POST);
      }

      if (isset($_SESSION['lC_NavigationHistory_snapshot']) === false) {
        $_SESSION['lC_NavigationHistory_snapshot'] = $this->_snapshot;
      }
    }

    function hasSnapshot() {
      return !empty($this->_snapshot);
    }

    function getSnapshot($key) {
      if (isset($this->_snapshot[$key])) {
        return $this->_snapshot[$key];
      }
    }

    function getSnapshotURL($auto_mode = false) {
      if ($this->hasSnapshot()) {
        $target = lc_href_link($this->_snapshot['page'], $this->_parseParameters($this->_snapshot['get']), ($auto_mode === true) ? 'AUTO' : $this->_snapshot['mode']);
      } else {
        $target = lc_href_link(FILENAME_DEFAULT, null, ($auto_mode === true) ? 'AUTO' : $this->_snapshot['mode']);
      }

      return $target;
    }

    function redirectToSnapshot() {
      $target = $this->getSnapshotURL(true);

      $this->resetSnapshot();

      lc_redirect($target);
    }

    function resetPath() {
      $this->_data = array();

      if (isset($_SESSION['lC_NavigationHistory_data'])) {
        unset($_SESSION['lC_NavigationHistory_data']);
      }
    }

    function resetSnapshot() {
      $this->_snapshot = array();

      if (isset($_SESSION['lC_NavigationHistory_snapshot'])) {
        unset($_SESSION['lC_NavigationHistory_snapshot']);
      }
    }

    function reset() {
      $this->resetPath();
      $this->resetSnapshot();
    }

    function _parseParameters($array, $additional_exclude = array()) {
      global $lC_Session;

      $exclude = array('x', 'y', $lC_Session->getName());

      if (is_array($additional_exclude) && (empty($additional_exclude) === false)) {
        $exclude = array_merge($exclude, $additional_exclude);
      }

      $string = '';

      if (is_array($array) && (empty($array) === false)) {
        foreach ($array as $key => $value) {
          if (in_array($key, $exclude) === false) {
            $string .= $key . '=' . $value . '&';
          }
        }

        $string = substr($string, 0, -1);
      }

      return $string;
    }
  }
?>
