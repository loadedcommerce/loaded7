<?php
/*
  $Id: coupons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Coupons {
  public $is_enabled = false;

  private $_contents = array();
  
  // class constructor
  public function lC_Coupons() {
    $this->is_enabled = (defined('SERVICE_COUPONS_ENABLE_COUPONS') && SERVICE_COUPONS_ENABLE_COUPONS == '1') ? true : false;
    
 /*   if ($this->is_enabled) {
      if ( !isset($_SESSION['lC_Coupons_data']) ) {
        $_SESSION['lC_Coupons_data'] = array('contents' => array());
      }

      $this->_contents =& $_SESSION['lC_Coupons_data']['contents'];     
    }*/
  }
  
  // public methods
  public function addEntry($code) {
    
    $cData = $this->_get($code);
    
echo "<pre>cData ";
print_r($cData);
echo "</pre>";
die();    

    if (is_array($cData) && empty($cData) === false) {    
      $this->_contents[$code] = array('title' => $cData['name'],
                                      'total' => $discount);     
    }
  }
  
  public function removeEntry($code) {
    if (array_key_exists($code, $this->_contents)) {    
      unset($this->_contents[$code]);
    }    
  }
  
  public function reset() {
    $this->_contents = array();
  }
  
  public function getAll() {
    return $this->_contents;
  }
  
  public function hasContents() {
    return !empty($this->_contents);
  }  
  
  private function _get($code) {
    global $lC_Database, $lC_Language;

    $Qcoupons = $lC_Database->query('select * from :table_coupons c left join :table_coupons_description cd on (c.coupons_id = cd.coupons_id) where c.code = :code and cd.language_id = :language_id limit 1');
    $Qcoupons->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupons->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupons->bindInt(':code', $code);
    $Qcoupons->bindInt(':language_id', $lC_Language->getCode());
    $Qcoupons->execute();   
    
    return (is_array($Qcoupons->toArray())) ? $Qcoupons->toArray() : false;     
  }  

}
?>