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
  public function __construct() {
    $this->is_enabled = (defined('MODULE_SERVICES_INSTALLED') && in_array('coupons', explode(';', MODULE_SERVICES_INSTALLED))) ? true : false;
    
    if ($this->is_enabled) {
      if ( !isset($_SESSION['lC_Coupons_data']) ) {
        $_SESSION['lC_Coupons_data'] = array('contents' => array());
      }

      $this->_contents =& $_SESSION['lC_Coupons_data']['contents'];     
    }
  }
  
  // public methods
  public function addEntry($code) {
    global $lC_Coupons;
    
    $cInfo = $lC_Coupons->_getData($code);
         
echo "<pre>";
print_r($cInfo);
echo "</pre>";
die('11');
         
    if (is_array($cInfo) && empty($cInfo) === false) {    
      if ($lC_Coupons->_isValid($cInfo)) {

        $name = $cInfo['name'];
        $discount = $cInfo['reward'];

        $_SESSION['lC_Coupons_data']['contents'][$code] = array('title' => $name . ' (' . $code . ')',
                                                                'total' => $discount);  
        return 1;                                              
      } else {
        // coupon not valid
        return -3;
      }
    
    } else {
      // coupon not found
      return -2;
    }   
          
  }
  
  public function removeEntry($code) {
    if (array_key_exists($code, $_SESSION['lC_Coupons_data']['contents'])) {    
      unset($_SESSION['lC_Coupons_data']['contents'][$code]);
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
  
  private function _getData($code, $status = 1) {
    global $lC_Database, $lC_Language;

    $Qcoupons = $lC_Database->query('select * from :table_coupons c left join :table_coupons_description cd on (c.coupons_id = cd.coupons_id) where c.code = :code and c.status = :status and cd.language_id = :language_id limit 1');
    $Qcoupons->bindTable(':table_coupons', TABLE_COUPONS);
    $Qcoupons->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
    $Qcoupons->bindValue(':code', $code);
    $Qcoupons->bindInt(':status', $status);
    $Qcoupons->bindInt(':language_id', $lC_Language->getID());
    $Qcoupons->execute();   
    
    return (is_array($Qcoupons->toArray())) ? $Qcoupons->toArray() : false;     
  } 
  
  private function _isValid($cInfo) {
    return true;
  }
}
?>