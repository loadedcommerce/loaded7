<?php
/*
  $Id: summary.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Summary {

    /* Private methods */
    var $_title,
        $_title_link,
        $_sort_order,
        $_data;

    /* Public methods */
    function getTitle() {
      return $this->_title;
    }

    function getTitleLink() {
      return $this->_title_link;
    }

    function hasTitleLink() {
      if (isset($this->_title_link) && !empty($this->_title_link)) {
        return true;
      }

      return false;
    }

    function getData() {
      return $this->_data;
    }
    
    function getSortOrder() {
      return $this->sort_order;
    }    

    function hasData() {
      if (isset($this->_data) && !empty($this->_data)) {
        return true;
      }

      return false;
    }
  }
?>