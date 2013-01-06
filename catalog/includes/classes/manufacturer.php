<?php
/*
  $Id: manufacturer.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Manufacturer {
    var $_data = array();

    function lC_Manufacturer($id) {
      global $lC_Database;

      $Qmanufacturer = $lC_Database->query('select manufacturers_id as id, manufacturers_name as name, manufacturers_image as image from :table_manufacturers where manufacturers_id = :manufacturers_id');
      $Qmanufacturer->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qmanufacturer->bindInt(':manufacturers_id', $id);
      $Qmanufacturer->execute();

      if ($Qmanufacturer->numberOfRows() === 1) {
        $this->_data = $Qmanufacturer->toArray();
      }
    }

    function getID() {
      if (isset($this->_data['id'])) {
        return $this->_data['id'];
      }

      return false;
    }

    function getTitle() {
      if (isset($this->_data['name'])) {
        return $this->_data['name'];
      }

      return false;
    }

    function getImage() {
      if (isset($this->_data['image'])) {
        return $this->_data['image'];
      }

      return false;
    }
  }
?>