<?php
/*
  $Id: session.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The lC_Session class manages the session data and custom storage handlers
 */

  class lC_Session {

/**
 * Holds the session cookie parameters (lifetime, path, domain, secure, httponly)
 *
 * @var array
 * @access protected
 */

    protected $_cookie_parameters = array();

/**
 * Defines if the session has been started or not
 *
 * @var boolean
 * @access protected
 */

    protected $_is_started = false;

/**
 * Holds the name of the session
 *
 * @var string
 * @access protected
 */

    protected $_name = 'lCsid';

/**
 * Holds the session id
 *
 * @var string
 * @access protected
 */

    protected $_id = null;

/**
 * Holds the file system save path for file based session storage
 *
 * @var string
 * @access protected
 */

    protected $_save_path = DIR_FS_WORK;

/**
 * Holds the life time in seconds of the session
 *
 * @var string
 * @access protected
 */

    protected $_life_time = SERVICE_SESSION_EXPIRATION_TIME;

/**
 * Constructor, loads custom session handle module if defined
 *
 * @param string $name The name of the session
 * @access public
 */

    public function __construct($name = null) {
      global $request_type;

      $this->setName($name);

      if ( $this->_life_time > 0 ) {
        $this->_life_time = $this->_life_time * 60;

        ini_set('session.gc_maxlifetime', $this->_life_time);
      } else {
     //   $this->_life_time = ini_get('session.gc_maxlifetime');
        $this->_life_time = '9600';
      }

      session_set_cookie_params($this->_life_time, (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH), (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN));

      register_shutdown_function(array($this, 'close'));
    }

/**
 * Loads the session storage handler
 *
 * @param string $name The name of the session
 * @access public
 */

    public static function load($name = null) {
      $class_name = 'lC_Session';

      if ( !lc_empty(basename(STORE_SESSIONS)) && file_exists(dirname(__FILE__) . '/session/' . basename(STORE_SESSIONS) . '.php') ) {
        include(dirname(__FILE__) . '/session/' . basename(STORE_SESSIONS) . '.php');

        $class_name = 'lC_Session_' . basename(STORE_SESSIONS);
      }

      return new $class_name($name);
    }

/**
 * Verify an existing session ID and create or resume the session if the existing session ID is valid
 *
 * @access public
 * @return boolean
 */

    public function start() {
      $sane_session_id = true;

      if ( isset($_GET[$this->_name]) && (empty($_GET[$this->_name]) || (ctype_alnum($_GET[$this->_name]) === false)) ) {
        $sane_session_id = false;
      } elseif ( isset($_POST[$this->_name]) && (empty($_POST[$this->_name]) || (ctype_alnum($_POST[$this->_name]) === false)) ) {
        $sane_session_id = false;
      } elseif ( isset($_COOKIE[$this->_name]) && (empty($_COOKIE[$this->_name]) || (ctype_alnum($_COOKIE[$this->_name]) === false)) ) {
        $sane_session_id = false;
      }
      
      if ( $sane_session_id === false ) {
        if ( isset($_COOKIE[$this->_name]) ) {
          setcookie($this->_name, '', time()-42000, $this->getCookieParameters('path'), $this->getCookieParameters('domain'));
        }

        lc_redirect(lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false));
      } else if (isset($_GET['lCsid']) && $_GET['lCsid'] != NULL && basename($_SERVER['PHP_SELF']) == 'checkout.php' && stristr($_SERVER['REQUEST_URI'], 'payment_template')) {

        $this->_is_started = true;
        $this->_id = $_GET['lCsid'];
        session_id($_GET['lCsid']);
        session_start();
        
        return true;
      } else if (isset($_GET['lCAdminID']) && $_GET['lCAdminID'] != NULL ) {

        $this->_is_started = true;
        $this->_id = $_GET['lCAdminID'];
        session_id($_GET['lCAdminID']);
        session_start();
        
        return true;
      } else if ( session_start() ) {
        $this->_is_started = true;
        $this->_id = session_id();

        return true;
      }

      return false;
    }

/**
 * Checks if the session has been started or not
 *
 * @access public
 * @return boolean
 */

    public function hasStarted() {
      return $this->_is_started;
    }

/**
 * Closes the session and writes the session data to the storage handler
 *
 * @access public
 */

    public function close() {
      if ( $this->_is_started === true ) {
        $this->_is_started = false;

        return session_write_close();
      }
    }

/**
 * Deletes an existing session
 *
 * @access public
 */

    public function destroy() {
      if ( $this->_is_started === true ) {
        if ( isset($_COOKIE[$this->_name]) ) {
          setcookie($this->_name, '', time()-42000, $this->getCookieParameters('path'), $this->getCookieParameters('domain'));
        }

        $this->delete();

        return session_destroy();
      }
    }

/**
 * Deletes an existing session from the storage handler
 *
 * @param string $id The ID of the session
 * @access public
 */

    public function delete($id = null) {
      if ( empty($id) ) {
        $id = $this->_id;
      }

      if ( file_exists($this->_save_path . '/' . $id) ) {
        @unlink($this->_save_path . '/' . $id);
      }
    }

/**
 * Delete an existing session and move the session data to a new session with a new session ID
 *
 * @access public
 */

    public function recreate() {
      if ( $this->_is_started === true ) {
        return session_regenerate_id(true);
      }
    }

/**
 * Return the session file based storage location
 *
 * @access public
 * @return string
 */

    public function getSavePath() {
      return $this->_save_path;
    }

/**
 * Return the session ID
 *
 * @access public
 * @return string
 */

    public function getID() {
      return $this->_id;
    }

/**
 * Return the name of the session
 *
 * @access public
 * @return string
 */

    public function getName() {
      return $this->_name;
    }

/**
 * Sets the name of the session
 *
 * @param string $name The name of the session
 * @access public
 */

    public function setName($name) {
      if ( empty($name) ) {
        $name = 'lCsid';
      }

      session_name($name);

      $this->_name = session_name();
    }

/**
 * Sets the storage location for the file based storage handler
 *
 * @param string $path The file path to store the session data in
 * @access public
 */

    public function setSavePath($path) {
      if ( substr($path, -1) == '/' ) {
        $path = substr($path, 0, -1);
      }

      session_save_path($path);

      $this->_save_path = session_save_path();
    }

/**
 * Returns the cookie parameters for the session (lifetime, path, domain, secure, httponly)
 *
 * @param string $key If specified, return only the value of this cookie parameter setting
 * @access public
 */

    public function getCookieParameters($key = null) {
      if ( empty($this->_cookie_parameters) ) {
        $this->_cookie_parameters = session_get_cookie_params();
      }

      if ( !empty($key) ) {
        return $this->_cookie_parameters[$key];
      }

      return $this->_cookie_parameters;
    }
  }
?>