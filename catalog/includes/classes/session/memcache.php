<?php
/*
  $Id: memcache.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The lC_Session_memcache class stores the session data in the memcached caching system and in the database.
 * The session data is read from memcached to increase performance, and also written to the database when the
 * session data changes.
 */

  require('includes/classes/session/database.php');

  class lC_Session_memcache extends lC_Session_database {

/**
 * The resource handler of the memcache connection
 *
 * @var resource
 * @access private
 */

    private $_conn = false;

/**
 * The original session module name used to fallback onto when a connection to the memcached server cannot be established
 *
 * @var string
 * @access private
 */

    private $_orig_module_name;

/**
 * Constructor, loads the memcache based session storage handler
 *
 * @param string $name The name of the session
 * @access public
 */

    public function __construct($name = null) {
      parent::__construct($name);

      if ( class_exists('Memcache') ) {
        $this->_orig_module_name = session_module_name();

        session_set_save_handler(array(&$this, '_open'),
                                 array(&$this, '_close'),
                                 array(&$this, '_read'),
                                 array(&$this, '_write'),
                                 array(&$this, '_destroy'),
                                 array(&$this, '_gc'));
      }
    }

/**
 * Opens the memcache based session storage handler
 *
 * @access protected
 */

    protected function _open() {
      $this->_conn = new Memcache();

      if ( $this->_conn->connect('localhost', 11211) !== true ) {
// fallback to the default PHP session storage mechanism
        session_module_name($this->_orig_module_name);

        return $this->start();
      }

      return true;
    }

/**
 * Closes the memcache based session storage handler
 *
 * @access protected
 */

    protected function _close() {
      return $this->_conn->close();
    }

/**
 * Read session data from the memcache based session storage handler
 *
 * @param string $id The ID of the session
 * @access protected
 */

    protected function _read($id) {
      $id = 'sess_' . $id;

      return $this->_conn->get($id);
    }

/**
 * Writes session data to the memcache based session storage handler
 *
 * @param string $id The ID of the session
 * @param string $value The session data to store
 * @access protected
 */

    protected function _write($id, $value) {
      $id = 'sess_' . $id;

      if ( $this->_conn->get($id) ) {
        return $this->_conn->replace($id, $value, 0, $this->_life_time);
      } else {
        return $this->_conn->set($id, $value, 0, $this->_life_time);
      }
    }

/**
 * Destroys the session data from the memcache based session storage handler
 *
 * @param string $id The ID of the session
 * @access protected
 */

    protected function _destroy($id) {
      $id = 'sess_' . $id;

      return $this->_conn->delete($id);
    }

/**
 * Garbage collector for the memcache based session storage handler
 *
 * @param string $max_life_time The maxmimum time a session should exist
 * @access protected
 */

    protected function _gc($max_life_time) {
      return true;
    }
  }
?>