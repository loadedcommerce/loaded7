<?php
/*
  $Id: database.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

/**
 * The lC_Session_database class stores the session data in the database
 */

  class lC_Session_database extends lC_Session {

/**
 * Constructor, loads the database based session storage handler
 *
 * @param string $name The name of the session
 * @access public
 */

    public function __construct($name = null) {
      parent::__construct($name);

      session_set_save_handler(array(&$this, '_custom_open'),
                               array(&$this, '_custom_close'),
                               array(&$this, '_custom_read'),
                               array(&$this, '_custom_write'),
                               array(&$this, '_custom_destroy'),
                               array(&$this, '_custom_gc'));
    }

/**
 * Opens the database based session storage handler
 *
 * @access protected
 */

    protected function _custom_open() {
      return true;
    }

/**
 * Closes the database based session storage handler
 *
 * @access protected
 */

    protected function _custom_close() {
      return true;
    }

/**
 * Read session data from the database based session storage handler
 *
 * @param string $id The ID of the session
 * @access protected
 */

    protected function _custom_read($id) {
      global $lC_Database;

      $Qsession = $lC_Database->query('select value from :table_sessions where id = :id');

      if ( $this->_life_time > 0 ) {
        $Qsession->appendQuery('and expiry >= :expiry');
        $Qsession->bindInt(':expiry', time());
      }

      $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':id', $id);
      $Qsession->execute();

      if ( $Qsession->numberOfRows() === 1 ) {
        return $Qsession->value('value');
      }

      return false;
    }

/**
 * Writes session data to the database based session storage handler
 *
 * @param string $id The ID of the session
 * @param string $value The session data to store
 * @access protected
 */

    protected function _custom_write($id, $value) {
      global $lC_Database;

      $Qsession = $lC_Database->query('replace into :table_sessions values (:id, :expiry, :value)');
      $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':id', $id);
      $Qsession->bindInt(':expiry', time() + $this->_life_time);
      $Qsession->bindValue(':value', $value);
      $Qsession->execute();

      return ( $Qsession->affectedRows() === 1 );
    }

/**
 * Destroys the session data from the database based session storage handler
 *
 * @param string $id The ID of the session
 * @access protected
 */

    protected function _custom_destroy($id) {
      return $this->delete($id);
    }

/**
 * Garbage collector for the database based session storage handler
 *
 * @param string $max_life_time The maxmimum time a session should exist
 * @access protected
 */

    protected function _custom_gc($max_life_time) {
      global $lC_Database;

// $max_life_time is already added to the time in the _custom_write method

      $Qsession = $lC_Database->query('delete from :table_sessions where expiry < :expiry');
      $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindInt(':expiry', time());
      $Qsession->execute();

      return ( $Qsession->affectedRows() > 0 );
    }

/**
 * Deletes the session data from the database based session storage handler
 *
 * @param string $id The ID of the session
 * @access public
 */

    public function delete($id = null) {
      global $lC_Database;

      if ( empty($id) ) {
        $id = $this->_id;
      }

      $Qsession = $lC_Database->query('delete from :table_sessions where id = :id');
      $Qsession->bindTable(':table_sessions', TABLE_SESSIONS);
      $Qsession->bindValue(':id', $id);
      $Qsession->execute();

      return ( $Qsession->affectedRows() === 1 );
    }
  }
?>