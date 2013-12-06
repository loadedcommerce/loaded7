<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: xml.php v1.0 2013-08-08 datazen $
*/
include(dirname(__FILE__) . '/../../ext/phpxml/xml.php');

class lC_XML {
  var $_xml,
      $_encoding;

  public function lC_XML($xml, $encoding = '') {
    $this->_xml = $xml;

    if (!empty($encoding)) {
      $this->_encoding = $encoding;
    }
  }

  public function toArray() {
    return XML_unserialize($this->_xml);
  }

  public function toXML() {
    return XML_serialize($this->_xml, $this->_encoding);
  }
}
?>