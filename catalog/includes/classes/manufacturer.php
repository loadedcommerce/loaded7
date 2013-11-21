<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturer.php v1.0 2013-08-08 datazen $
*/
class lC_Manufacturer {
  var $_data = array();

  public function lC_Manufacturer($id) {
    global $lC_Database;

    $Qmanufacturer = $lC_Database->query('select manufacturers_id as id, manufacturers_name as name, manufacturers_image as image from :table_manufacturers where manufacturers_id = :manufacturers_id');
    $Qmanufacturer->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturer->bindInt(':manufacturers_id', $id);
    $Qmanufacturer->execute();

    if ($Qmanufacturer->numberOfRows() === 1) {
      $this->_data = $Qmanufacturer->toArray();
    }
  }

  public function getID() {
    if (isset($this->_data['id'])) {
      return $this->_data['id'];
    }

    return false;
  }

  public function getTitle() {
    if (isset($this->_data['name'])) {
      return $this->_data['name'];
    }

    return false;
  }

  public function getImage() {
    if (isset($this->_data['image'])) {
      return $this->_data['image'];
    }

    return false;
  }
}
?>