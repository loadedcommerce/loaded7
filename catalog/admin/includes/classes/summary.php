<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: statistics.php v1.0 2013-08-08 datazen $
*/
class lC_Summary {

  /* Private methods */
  var $_title,
      $_title_link,
      $_sort_order,
      $_data;

  /* Public methods */
  public function getTitle() {
    return $this->_title;
  }

  public function getTitleLink() {
    return $this->_title_link;
  }

  public function hasTitleLink() {
    if (isset($this->_title_link) && !empty($this->_title_link)) {
      return true;
    }

    return false;
  }

  public function getData() {
    return $this->_data;
  }
  
  public function getSortOrder() {
    return $this->sort_order;
  }    

  public function hasData() {
    if (isset($this->_data) && !empty($this->_data)) {
      return true;
    }

    return false;
  }
}
?>