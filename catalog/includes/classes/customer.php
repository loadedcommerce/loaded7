<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customer.php v1.0 2013-08-08 datazen $
*/
class lC_Customer {

  /* Private variables */
  var $_is_logged_on = false,
      $_data = array();

  /* Class constructor */
  public function lC_Customer() {
    if (isset($_SESSION['lC_Customer_data']) && is_array($_SESSION['lC_Customer_data']) && isset($_SESSION['lC_Customer_data']['id']) && is_numeric($_SESSION['lC_Customer_data']['id'])) {
      $this->setIsLoggedOn(true);
      $this->_data =& $_SESSION['lC_Customer_data'];
    }
  }

  /* Public methods */
  public function getID() {
    if (isset($this->_data['id']) && is_numeric($this->_data['id'])) {
      return $this->_data['id'];
    }

    return false;
  }
  
  public function getCustomerGroup() {
    if (isset($this->_data['customers_group']) && is_numeric($this->_data['customers_group'])) {
      return $this->_data['customers_group'];
    } else {
      return DEFAULT_CUSTOMERS_GROUP_ID;
    }
  }    
  
  public function getBaselineDiscount($id = null) {
    if (isset($this->_data['baseline_discount']) && is_numeric($this->_data['baseline_discount'])) {
      return $this->_data['baseline_discount'];
    } else if (is_numeric($id)) {
      $Qcg = $lC_Database->query('select baseline_discount from :table_customers_groups_data where customers_group_id = :customers_group_id limit 1');
      $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
      $Qcg->bindInt(':customers_group_id', $id);
      $Qcg->execute(); 
      
      if ($lC_Database->isError()) die($lC_Database->getError());
           
      if ($Qcg->numberOfRows() > 0) {
        $discount = $Qcg->valueDecimal('baseline_discount');
        $Qcg->freeResult();
        
        return $discount;
      }
      $Qcg->freeResult();
    }

    return false;
  }  

  public function getFirstName() {
    static $first_name = null;

    if (is_null($first_name)) {
      if (isset($this->_data['first_name'])) {
        $first_name = $this->_data['first_name'];
      }
    }

    return $first_name;
  }

  public function getLastName() {
    static $last_name = null;

    if (is_null($last_name)) {
      if (isset($this->_data['last_name'])) {
        $last_name = $this->_data['last_name'];
      }
    }

    return $last_name;
  }

  public function getName() {
    static $name = '';

    if (empty($name)) {
      if (isset($this->_data['first_name'])) {
        $name .= $this->_data['first_name'];
      }

      if (isset($this->_data['last_name'])) {
        if (empty($name) === false) {
          $name .= ' ';
        }

        $name .= $this->_data['last_name'];
      }
    }

    return $name;
  }

  public function getGender() {
    static $gender = null;

    if (is_null($gender)) {
      if (isset($this->_data['gender'])) {
        $gender = $this->_data['gender'];
      }
    }

    return $gender;
  }

  public function getEmailAddress() {
    static $email_address = null;

    if (is_null($email_address)) {
      if (isset($this->_data['email_address'])) {
        $email_address = $this->_data['email_address'];
      }
    }

    return $email_address;
  }
  
  public function getTelephone() {
    static $telephone = null;

    if (is_null($telephone)) {
      if (isset($this->_data['telephone'])) {
        $telephone = $this->_data['telephone'];
      }
    }

    return $telephone;
  }    

  public function getCountryID() {
    static $country_id = null;

    if (is_null($country_id)) {
      if (isset($this->_data['country_id'])) {
        $country_id = $this->_data['country_id'];
      }
    }

    return $country_id;
  }

  public function getZoneID() {
    static $zone_id = null;

    if (is_null($zone_id)) {
      if (isset($this->_data['zone_id'])) {
        $zone_id = $this->_data['zone_id'];
      }
    }

    return $zone_id;
  }

  public function getDefaultAddressID() {
    static $id = null;

    if (is_null($id)) {
      if (isset($this->_data['default_address_id'])) {
        $id = $this->_data['default_address_id'];
      }
    }

    return $id;
  }

  public function setCustomerData($customer_id = -1) {
    global $lC_Database, $lC_Language;

    $this->_data = array();

    if (is_numeric($customer_id) && ($customer_id > 0)) {
      $Qcustomer = $lC_Database->query('select customers_group_id, customers_gender, customers_firstname, customers_lastname, customers_email_address, customers_telephone, customers_default_address_id from :table_customers where customers_id = :customers_id');
      $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomer->bindInt(':customers_id', $customer_id);
      $Qcustomer->execute();

      if ($Qcustomer->numberOfRows() === 1) {
        $this->setIsLoggedOn(true);
        $this->setID($customer_id);
        $this->setGroupID($Qcustomer->value('customers_group_id')); 
        $this->setGender($Qcustomer->value('customers_gender'));
        $this->setFirstName($Qcustomer->value('customers_firstname'));
        $this->setLastName($Qcustomer->value('customers_lastname'));
        $this->setEmailAddress($Qcustomer->value('customers_email_address'));
        $this->setTelephone($Qcustomer->value('customers_telephone'));

        if (is_numeric($Qcustomer->value('customers_default_address_id')) && ($Qcustomer->value('customers_default_address_id') > 0)) {
          $Qab = $lC_Database->query('select entry_country_id, entry_zone_id from :table_address_book where address_book_id = :address_book_id and customers_id = :customers_id');
          $Qab->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
          $Qab->bindInt(':address_book_id', $Qcustomer->value('customers_default_address_id'));
          $Qab->bindInt(':customers_id', $customer_id);
          $Qab->execute();

          if ($Qab->numberOfRows() === 1) {
            $this->setCountryID($Qab->value('entry_country_id'));
            $this->setZoneID($Qab->value('entry_zone_id'));
            $this->setDefaultAddressID($Qcustomer->value('customers_default_address_id'));

            $Qab->freeResult();
          }
        }
        
        if (is_numeric($Qcustomer->value('customers_group_id')) && ($Qcustomer->value('customers_group_id') > 0)) {
          $Qcg = $lC_Database->query('select cg.customers_group_name, cgd.baseline_discount from :table_customers_groups cg left join :table_customers_groups_data cgd on (cg.customers_group_id = cgd.customers_group_id) where cg.customers_group_id = :customers_group_id and cg.language_id = :language_id');
          $Qcg->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
          $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
          $Qcg->bindInt(':customers_group_id', $Qcustomer->value('customers_group_id'));
          $Qcg->bindInt(':language_id', $lC_Language->getID());
          $Qcg->execute();

          if ($lC_Database->isError()) die($lC_Database->getError());
          
          if ($Qcg->numberOfRows() === 1) {
            $this->setGroupName($Qcg->value('customers_group_name'));

            $Qcg->freeResult();
          }
        }
        
      }

      $Qcustomer->freeResult();
    }

    if (sizeof($this->_data) > 0) {
      $_SESSION['lC_Customer_data'] = $this->_data;
    } elseif (isset($_SESSION['lC_Customer_data'])) {
      $this->reset();
    }
  }

  public function setIsLoggedOn($state) {
    if ($state === true) {
      $this->_is_logged_on = true;
    } else {
      $this->_is_logged_on = false;
    }
  }

  public function isLoggedOn() {
    if ($this->_is_logged_on === true) {
      return true;
    }

    return false;
  }

  public function setID($id) {
    if (is_numeric($id) && ($id > 0)) {
      $this->_data['id'] = $id;
    } else {
      $this->_data['id'] = false;
    }
  }
  
  public function setGroupID($group_id) {
    if (is_numeric($group_id) && ($group_id > 0)) {
      $this->_data['customers_group_id'] = $group_id;
    } else {
      $this->_data['customers_group_id'] = 1; 
    }
  }
  
  public function setGroupName($group_name) {
    $this->_data['customers_group_name'] = $group_name;
  }        

  public function setDefaultAddressID($id) {
    if (is_numeric($id) && ($id > 0)) {
      $this->_data['default_address_id'] = $id;
    } else {
      $this->_data['default_address_id'] = false;
    }
  }

  public function hasDefaultAddress() {
    if (isset($this->_data['default_address_id']) && is_numeric($this->_data['default_address_id'])) {
      return true;
    }

    return false;
  }

  public function setGender($gender) {
    if ( (strtolower($gender) == 'm') || (strtolower($gender) == 'f') ) {
      $this->_data['gender'] = strtolower($gender);
    } else {
      $this->_data['gender'] = false;
    }
  }

  public function setFirstName($first_name) {
    $this->_data['first_name'] = $first_name;
  }

  public function setLastName($last_name) {
    $this->_data['last_name'] = $last_name;
  }

  public function setEmailAddress($email_address) {
    $this->_data['email_address'] = $email_address;
  }
  
  public function setTelephone($telephone) {
    $this->_data['telephone'] = $telephone;
  }    

  public function setCountryID($id) {
    $this->_data['country_id'] = $id;
  }

  public function setZoneID($id) {
    $this->_data['zone_id'] = $id;
  }

  public function reset() {
    $this->_is_logged_on = false;
    $this->_data = array();

    if (isset($_SESSION['lC_Customer_data'])) {
      unset($_SESSION['lC_Customer_data']);
    }
  }
}
?>