<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: statistics.php v1.0 2013-08-08 datazen $
*/
class lC_Statistics {

  // Private variables
  var $_icon, 
      $_title, 
      $_header, 
      $_data, 
      $_resultset;

  // Public methods
  public function getIcon() {
    return $this->_icon;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getHeader() {
    return $this->_header;
  }

  public function getData() {
    return $this->_data;
  }

  public function activate() {
    $this->_setHeader();
    $this->_setData();
  }

  public function getBatchTotalPages($text) {
    return $this->_resultset->getBatchTotalPages($text);
  }

  public function getBatchPageLinks($batch_keyword = 'page', $parameters = '', $with_pull_down_menu = true) {
    return $this->_resultset->getBatchPageLinks($batch_keyword, $parameters, $with_pull_down_menu);
  }

  public function getBatchPagesPullDownMenu($batch_keyword = 'page', $parameters = '') {
    return $this->_resultset->getBatchPagesPullDownMenu($batch_keyword, $parameters);
  }

  public function isBatchQuery() {
    return $this->_resultset->isBatchQuery();
  }
}
?>