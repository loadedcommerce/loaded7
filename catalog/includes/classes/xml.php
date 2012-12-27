<?php
/*
  $Id: xml.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  include(dirname(__FILE__) . '/../../ext/phpxml/xml.php');

  class lC_XML {
    var $_xml,
        $_encoding;

    function lC_XML($xml, $encoding = '') {
      $this->_xml = $xml;

      if (!empty($encoding)) {
        $this->_encoding = $encoding;
      }
    }

    function toArray() {
      return XML_unserialize($this->_xml);
    }

    function toXML() {
      return XML_serialize($this->_xml, $this->_encoding);
    }
  }
?>