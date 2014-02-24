<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customers.php v1.0 2013-08-08 datazen $
*/
class lC_Customers_Admin {
 /*
  * Returns the customers datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $media = $_GET['media'];
    
    $result = array('aaData' => array());

    /* Total Records */
    $QresultTotal = $lC_Database->query('SELECT count(customers_id) as total from :table_customers');
    $QresultTotal->bindTable(':table_customers', TABLE_CUSTOMERS);
    $QresultTotal->execute();
    $result['iTotalRecords'] = $QresultTotal->valueInt('total');
    $QresultTotal->freeResult();

    /* Paging */
    $sLimit = "";
    if (isset($_GET['iDisplayStart'])) {
      if ($_GET['iDisplayLength'] != -1) {
        $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
      }
    }

    /* Ordering */
    if (isset($_GET['iSortCol_0'])) {
      $sOrder = " ORDER BY ";
      for ($i=0 ; $i < (int)$_GET['iSortingCols'] ; $i++ ) {
        $sOrder .= lC_Customers_Admin::fnColumnToField($_GET['iSortCol_'.$i] ) . " " . $_GET['sSortDir_'.$i] .", ";
      }
      $sOrder = substr_replace( $sOrder, "", -2 );
    }

    /* Filtering */
    $sWhere = "";
    if ($_GET['sSearch'] != "") {
      $sWhere = " WHERE c.customers_lastname LIKE '%" . $_GET['sSearch'] . "%' OR " .
                       "c.customers_firstname LIKE '%" . $_GET['sSearch'] . "%' OR " .
                       "c.customers_email_address LIKE '%" . $_GET['sSearch'] . "%' ";
    } else if (isset($_GET['cSearch']) && $_GET['cSearch'] != null) {
      $sWhere = " WHERE c.customers_id = '" . $_GET['cSearch'] . "' ";
    }

    /* Total Filtered Records */
    $QresultFilterTotal = $lC_Database->query("SELECT count(c.customers_id) as total, c.customers_id, c.customers_group_id, c.customers_gender, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_status, c.customers_ip_address, c.date_account_created, c.date_account_last_modified, c.date_last_logon, c.number_of_logons, a.entry_country_id
                                                 from :table_customers c
                                               LEFT JOIN :table_address_book a
                                                 on (c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id) " . $sWhere . $sOrder);

    $QresultFilterTotal->bindTable(':table_customers', TABLE_CUSTOMERS);
    $QresultFilterTotal->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $QresultFilterTotal->execute();
    $result['iTotalDisplayRecords'] = $QresultFilterTotal->valueInt('total');
    $QresultFilterTotal->freeResult();

    /* Main Listing Query */
    $Qcustomers = $lC_Database->query("SELECT c.customers_id, c.customers_group_id, c.customers_gender, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_status, c.customers_ip_address, c.date_account_created, c.date_account_last_modified, c.date_last_logon, c.number_of_logons, c.customers_default_address_id, a.entry_country_id
                                         from :table_customers c
                                       LEFT JOIN :table_address_book a
                                         on (c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id) " . $sWhere . $sOrder . $sLimit);

    $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qcustomers->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qcustomers->execute();
    
    $cnt = 1;
    $tagArr = array();
    while ( $Qcustomers->next() ) {
      $customer_icon = lc_icon_admin('people.png', 'cID[' . $Qcustomers->value('customers_id') . ']');
      //$customer_icon = '<span class="icon-user title="cID[' . $Qcustomers->value('customers_id') . ']"></span>';
      if ( ACCOUNT_GENDER > -1 ) {
        switch ( $Qcustomers->value('customers_gender') ) {
          case 'm':
            $customer_icon = '<span style="cursor:pointer;" class="with-tooltip" title="cID[' . $Qcustomers->value('customers_id') . '] ' . $lC_Language->get('gender_male') . '">' . lc_icon_admin('male.png') . '</span>';
            //$customer_icon = '<span class="icon-user icon-blue" title="cID[' . $Qcustomers->value('customers_id') . '] ' . $lC_Language->get('gender_male') . '"></span>';
            break;

          case 'f':
            $customer_icon = '<span style="cursor:pointer;" class="with-tooltip" title="cID[' . $Qcustomers->value('customers_id') . '] ' . $lC_Language->get('gender_female') . '">' . lc_icon_admin('female.png') . '</span>';
            //$customer_icon = '<span class="icon-user icon-red" title="cID[' . $Qcustomers->value('customers_id') . '] ' . $lC_Language->get('gender_female') . '"></span>';
            break;
            
          default:
            //$customer_icon = lc_icon_admin('female.png', 'cID[' . $Qcustomers->value('customers_id') . '] ' . $lC_Language->get('gender_female'));
            $customer_icon = '<span style="cursor:pointer;" class="icon-user icon-anthracite with-tooltip" title="cID[' . $Qcustomers->value('customers_id') . ']"></span>';
        }
      }

      if (!key_exists($Qcustomers->valueInt('customers_group_id'), $tagArr)) {
        $tagArr[$Qcustomers->valueInt('customers_group_id')] = self::nextColor($tagArr);
      }
      $tagColor = $tagArr[$Qcustomers->valueInt('customers_group_id')];

      $full = $Qcustomers->valueProtected('customers_firstname') . ' ' . $Qcustomers->valueProtected('customers_lastname');
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qcustomers->valueInt('customers_id') . '" id="' . $Qcustomers->valueInt('customers_id') . '"></td>';
      $gender = '<td>' . $customer_icon . '</td>';
      $name = '<td>' . $Qcustomers->valueProtected('customers_firstname'). ' '.$Qcustomers->valueProtected('customers_lastname') . '</td>';
      
      $Qcustomers_orders = $lC_Database->query("SELECT count(*) as order_count 
                                         from :table_orders 
                                         where customers_id = '".$Qcustomers->valueInt('customers_id')."'");
      $Qcustomers_orders->bindTable(':table_orders', TABLE_ORDERS);
      $Qcustomers_orders->execute();
      
      $order_count = '<td>' . $Qcustomers_orders->valueInt('order_count') . '</td>';
      $email = '<td>' . $Qcustomers->value('customers_email_address') . '</td>';
      $group = '<td><small class="tag ' . $tagColor . ' glossy">' . lc_get_customer_groups_name($Qcustomers->valueInt('customers_group_id')) . '</small></td>';
      $date = '<td>' . lC_DateTime::getShort($Qcustomers->value('date_account_created')) . '</td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group" style="white-space:nowrap;"><a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editCustomer(\'' . $Qcustomers->valueInt('customers_id') . '\')') . '" class="button icon-pencil  with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '"  title="' . $lC_Language->get('icon_edit_customer') . '">' . '</a></span>&nbsp;
                   <span class="button-group" style="white-space:nowrap;"><a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="createOrder(\'' . $Qcustomers->valueInt('customers_id') . '\',\''.$Qcustomers->valueInt('customers_default_address_id').'\')') . '" class="button icon-plus-round with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_create_order') . '"></a></span>&nbsp;								
                   <span class="button-group" style="white-space:nowrap;"><a href="' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'orders&cID=' . $Qcustomers->valueInt('customers_id'))) . '" class="button icon-price-tag with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_view_orders') . '"></a></span>
                 </td>';
      $result['aaData'][] = array("$check", "$gender", "$name", "$email", "$order_count", "$group", "$date", "$action");
      $cnt++;
    }
    $result['sEcho'] = intval($_GET['sEcho']);

    $Qcustomers->freeResult();

    return $result;
  }
 /*
  * Return the next color used for tag blocks
  *
  * @param array $tagArr The array of tags
  * @access public
  * @return string
  */
  public static function nextColor($tagArr) {
    $colors = array('red-gradient', 'blue-gradient', 'purple-gradient', 'green-gradient', 'orange-gradient', 'anthracite-gradient', 'silver-gradient', 'black-gradient');

    foreach ($colors as $color) {
      if (!in_array($color, $tagArr)) {
        return $color;
        break;
      }
    }
    // all colors taken, choose random
    return array_rand(shuffle($colors), 1);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The customer id
  * @access public
  * @return array
  */
  public static function formData($id = null) {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('customers.php');

    $result = array();
    $Qgroups = $lC_Database->query('select customers_group_id, customers_group_name from :table_customers_groups where language_id = :language_id order by customers_group_name');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindInt(':language_id', $lC_Language->getID());
    $Qgroups->execute();
    $groups_array = array();
    while ( $Qgroups->next() ) {
      $groups_array[$Qgroups->value('customers_group_id')] = $Qgroups->value('customers_group_name');
    }
    $result['groupsArray'] = $groups_array;

    if ($id != null) {
      $result['customerData'] = lC_Customers_Admin::getData($id);

      $Qaddresses = lC_Customers_Admin::getAddressBookData($id);

      $cnt = 0;
      $result['addressBook'] = '';
      $body .= '<ul class="list spaced">';
      while ( $Qaddresses->next() ) {

        $primary = ( $result['customerData']['customers_default_address_id'] == $Qaddresses->valueInt('address_book_id') ) ? 'true' : 'false';
                
        $body .= '<li class="">';

        $body .= '<span class="button-group compact float-right">' .
                 '  <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editAddress(\'' . $Qaddresses->valueInt('address_book_id') . '\', \'' . $primary . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  $lC_Language->get('icon_edit') . '</a>' .
                 '  <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteAddress(\'' . $Qaddresses->valueInt('address_book_id') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>' .
                 '</span>';

        if ( ACCOUNT_GENDER > -1 ) {
          switch ( $Qaddresses->value('gender') ) {
            case 'm':
              $body .= '<span>' . lc_icon_admin('male.png') . '</span>';
              break;
            case 'f':
              $body .= '<span>' . lc_icon_admin('female.png') . '</span>';
              break;
            default:
              $body .= '<span>' . lc_icon_admin('people.png') . '</span>';
              break;
          }
        } else {
          $body .= '<span>' . lc_icon_admin('people.png') . '</span>';
        }

        $body .= '<span class="small-margin-left">' . lC_Address::format($Qaddresses->toArray(), '&nbsp;<br /> ') . '</span>';

        if ( $primary == 'true' ) {
          $body .= '<small class="tag small-margin-left purple-gradient glossy" style="position:absolute; top:12px; right:88px;">' . $lC_Language->get('primary_address') . '</small>';
        }

        $body .= '<span class="icon-phone icon-blue" style="position:absolute; top:17px; left:200px;">';
        if ( !lc_empty($Qaddresses->valueProtected('telephone_number')) ) {
          $body .= $Qaddresses->valueProtected('telephone_number');
        } else {
          $body .= '<small class="tag silver-gradient glossy"><i>' . $lC_Language->get('no_telephone_number') . '</i></small>';
        }
        $body .= '</span>';

        $body .= '<span class="icon-printer icon-orange" style="position:absolute; top:46px; left:200px;">';
        if ( !lc_empty($Qaddresses->valueProtected('fax_number')) ) {
          $body .= $Qaddresses->valueProtected('fax_number');
        } else {
          $body .= '<small class="tag silver-gradient glossy"><i>' . $lC_Language->get('no_fax_number') . '</i></small>';
        }
        $body .= '</span>';

        $body .= '</li>';
        $cnt++;
      }
      $body .= '</ul>';

      $result['addressBook'] = $body;

      // set default country to store country
      $country_id = STORE_COUNTRY;
      $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
      $Qzones->bindTable(':table_zones', TABLE_ZONES);
      $Qzones->bindInt(':zone_country_id', $country_id);
      $Qzones->execute();
      $zones_array = array();
      while ( $Qzones->next() ) {
        $zones_array[] = array('id' => $Qzones->value('zone_name'),
                               'text' => $Qzones->value('zone_name'));
      }
      $result['abState'] = lc_draw_pull_down_menu('ab_state', $zones_array, null, 'class="input with-small-padding" style="width:73%;"');
    }

    $countries_array = array();
    foreach ( lC_Address::getCountries() as $country ) {
      $countries_array[$country['id']] = $country['name'];
    }
    $result['countriesArray'] = $countries_array;

    return $result;
  }
 /*
  * Get the customer information
  *
  * @param integer $id The customer id
  * @param string $key The customer key
  * @access public
  * @return array
  */
  public static function getData($id, $key = null) {
    global $lC_Database;

    $Qcustomer = $lC_Database->query('select c.*, date_format(c.customers_dob, "%Y") as customers_dob_year, date_format(c.customers_dob, "%m") as customers_dob_month, date_format(c.customers_dob, "%d") as customers_dob_date, ab.* from :table_customers c left join :table_address_book ab on (c.customers_default_address_id = ab.address_book_id and c.customers_id = ab.customers_id) where c.customers_id = :customers_id');
    $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qcustomer->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qcustomer->bindInt(':customers_id', $id);
    $Qcustomer->execute();

    $data = $Qcustomer->toArray();

    $Qgroup = $lC_Database->query('select c.customers_id, c.customers_group_id, cg.customers_group_name from :table_customers c left join :table_customers_groups cg on (c.customers_group_id = cg.customers_group_id) where c.customers_id = :customers_id');
    $Qgroup->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qgroup->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroup->bindInt(':customers_id', $id);
    $Qgroup->execute();

    $data = array_merge((array)$data, (array)$Qgroup->toArray());

    $Qreviews = $lC_Database->query('select count(*) as total from :table_reviews where customers_id = :customers_id');
    $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreviews->bindInt(':customers_id', $id);
    $Qreviews->execute();

    $data['total_reviews'] = $Qreviews->valueInt('total');
    $data['customers_full_name'] = $data['customers_firstname'] . ' ' . $data['customers_lastname'];
    $data['customers_dob_short'] = $Qcustomer->value('customers_dob_month') . '/' . $Qcustomer->value('customers_dob_date') . '/' . $Qcustomer->value('customers_dob_year');

    $Qreviews->freeResult();
    $Qgroup->freeResult();
    $Qcustomer->freeResult();

    if ( !empty($key) ) {
      return $data[$key];
    }

    return $data;
  }
 /*
  * Get the customer address book information
  *
  * @param integer $customer_id The customer id
  * @param integer $address_book_id The address book id
  * @access public
  * @return array
  */
  public static function getAddressBookData($customer_id, $address_book_id = null) {
    global $lC_Database;

    $Qab = $lC_Database->query('select ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, ab.entry_telephone as telephone_number, ab.entry_fax as fax_number, z.zone_code as zone_code, c.countries_name as country_title from :table_address_book ab left join :table_zones z on (ab.entry_zone_id = z.zone_id), :table_countries c where');

    if ( is_numeric($address_book_id) ) {
      $Qab->appendQuery('ab.address_book_id = :address_book_id and');
      $Qab->bindInt(':address_book_id', $address_book_id);
    }

    $Qab->appendQuery('ab.customers_id = :customers_id and ab.entry_country_id = c.countries_id');
    $Qab->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
    $Qab->bindTable(':table_zones', TABLE_ZONES);
    $Qab->bindTable(':table_countries', TABLE_COUNTRIES);
    $Qab->bindInt(':customers_id', $customer_id);
    $Qab->execute();

    if ( is_numeric($address_book_id) ) {
      $data = $Qab->toArray();
      $data['zoneData'] = lc_get_zone_data($Qab->value('zone_id'));

      $Qab->freeResult();

      return $data;
    }

    return $Qab;
  }
 /*
  * Return the zones for a country
  *
  * @param integer $id The zone country id
  * @access public
  * @return array
  */
  public static function getZones($id) {
    global $lC_Database;

    $Qcheck = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id limit 1');
    $Qcheck->bindTable(':table_zones', TABLE_ZONES);
    $Qcheck->bindInt(':zone_country_id', $id);
    $Qcheck->execute();

    $entry_state_has_zones = ( $Qcheck->numberOfRows() > 0 );

    $Qcheck->freeResult();

    $result = array();
    if ( isset($entry_state_has_zones) && ($entry_state_has_zones === true) ) {
      $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
      $Qzones->bindTable(':table_zones', TABLE_ZONES);
      $Qzones->bindInt(':zone_country_id', $id);
      $Qzones->execute();
      $zones_array = array();
      while ( $Qzones->next() ) {
        $zones_array[] = array('id' => $Qzones->value('zone_name'),
                               'text' => $Qzones->value('zone_name'));
      }
      $result['abZonesDropdown'] = lc_draw_pull_down_menu('ab_state', $zones_array,  null, 'class="input with-small-padding" style="width:73%;"');
    } else {
      $result['abZonesDropdown'] = lc_draw_input_field('ab_state');
    }

    return $result;
  }
 /*
  * Save the customer information
  *
  * @param integer $id The customer id used on update, null on insert
  * @param array $data An array containing the customer group information
  * @param boolean $send_email True = send email
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data, $send_email = true) { 
    global $lC_Database, $lC_Language, $lC_DateTime;

    $lC_Language->loadIniFile('customers.php'); 

    $error = false;
    $result = array();

    if (!is_numeric($id)||is_numeric($id)) {
      // check that email doesnt exist
      $Qcheck = $lC_Database->query('select customers_id from :table_customers where customers_email_address = :customers_email_address');
      if ( isset($id) && is_numeric($id) ) {
        $Qcheck->appendQuery('and customers_id != :customers_id');
        $Qcheck->bindInt(':customers_id', $id);
      }
      $Qcheck->appendQuery('limit 1');
      $Qcheck->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcheck->bindValue(':customers_email_address', $data['email_address']);
      $Qcheck->execute();

      if ( $Qcheck->numberOfRows() > 0 ) {
        $error = true;
        $result['rpcStatus'] = -2;
      }

      $Qcheck->freeResult();

      if (trim($data['password']) != null) {
        // check that passwords match
        if (trim($data['password']) != trim($data['confirmation'])) {
          $error = true;
          $result['rpcStatus'] = -3;
        }
      }
    } else {
      // check that passwords match
      if (trim($data['password']) != trim($data['confirmation'])) {
        $error = true;
        $result['rpcStatus'] = -3;
      }
    }

    if ( $error === false ) {
      $lC_Database->startTransaction();

      if ( is_numeric($id) ) {
        $Qcustomer = $lC_Database->query('update :table_customers set customers_group_id = :customers_group_id, customers_gender = :customers_gender, customers_firstname = :customers_firstname, customers_lastname = :customers_lastname, customers_email_address = :customers_email_address, customers_dob = :customers_dob, customers_newsletter = :customers_newsletter, customers_status = :customers_status, date_account_last_modified = :date_account_last_modified where customers_id = :customers_id');
        $Qcustomer->bindRaw(':date_account_last_modified', 'now()');
        $Qcustomer->bindInt(':customers_id', $id);
      } else {
        $Qcustomer = $lC_Database->query('insert into :table_customers (customers_group_id, customers_gender, customers_firstname, customers_lastname, customers_email_address, customers_dob, customers_newsletter, customers_status, number_of_logons, date_account_created) values (:customers_group_id, :customers_gender, :customers_firstname, :customers_lastname, :customers_email_address, :customers_dob, :customers_newsletter, :customers_status, :number_of_logons, :date_account_created)');
        $Qcustomer->bindInt(':number_of_logons', 0);
        $Qcustomer->bindRaw(':date_account_created', 'now()');
      }

      $dob = (isset($data['dob']) && !empty($data['dob'])) ? lC_DateTime::toDateTime($data['dob']) : '0000-00-00 00:00:00';

      $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomer->bindValue(':customers_gender', $data['gender']);
      $Qcustomer->bindValue(':customers_firstname', $data['firstname']);
      $Qcustomer->bindValue(':customers_lastname', $data['lastname']);
      $Qcustomer->bindValue(':customers_email_address', $data['email_address']);
      $Qcustomer->bindValue(':customers_dob', $dob);
      $Qcustomer->bindInt(':customers_newsletter', $data['newsletter']);
      $Qcustomer->bindInt(':customers_status', $data['status']);
      $Qcustomer->bindInt(':customers_group_id', $data['group']);
      $Qcustomer->setLogging($_SESSION['module'], $id);
      $Qcustomer->execute();      

      if ( !$lC_Database->isError() ) {
        if ( !empty($data['password']) ) {
          $customer_id = ( !empty($id) ) ? $id : $lC_Database->nextID();
          $result['new_customer_id'] = $customer_id;

          $Qpassword = $lC_Database->query('update :table_customers set customers_password = :customers_password where customers_id = :customers_id');
          $Qpassword->bindTable(':table_customers', TABLE_CUSTOMERS);
          $Qpassword->bindValue(':customers_password', lc_encrypt_string(trim($data['password'])));
          $Qpassword->bindInt(':customers_id', $customer_id);
          $Qpassword->setLogging($_SESSION['module'], $customer_id);
          $Qpassword->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            $result['rpcStatus'] = -1;
          } 
        }
      }
    }
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      if ( $send_email === true ) {
        if ( empty($id) ) {
          $full_name = trim($data['firstname'] . ' ' . $data['lastname']);
          $email_text = '';
          if ( ACCOUNT_GENDER > -1 ) {
            if ( $data['gender'] == 'm' ) {
              $email_text .= sprintf($lC_Language->get('email_greet_mr'), trim($data['lastname'])) . "\n\n";
            } else {
              $email_text .= sprintf($lC_Language->get('email_greet_ms'), trim($data['lastname'])) . "\n\n";
            }
          } else {
            $email_text .= sprintf($lC_Language->get('email_greet_general'), $full_name) . "\n\n";
          }
          $email_text .= sprintf($lC_Language->get('email_text'), STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, trim($data['password']));
          $email_subject = sprintf($lC_Language->get('email_subject'), STORE_NAME);
          lc_email($full_name, $data['email_address'], $email_subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
      }

      return $result;
    }

    $lC_Database->rollbackTransaction();

    return $result;
  }
 /*
  * Delete the customer record
  *
  * @param integer $id The customer id to delete
  * @param boolean $delete_reviews True = delete reviews associated with this customer id
  * @access public
  * @return boolean
  */
  public static function delete($id, $delete_reviews = true) {
    global $lC_Database, $lC_Session;

    $error = false;

    $lC_Database->startTransaction();

    if ( $delete_reviews === true ) {
      $Qreviews = $lC_Database->query('delete from :table_reviews where customers_id = :customers_id');
      $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreviews->bindInt(':customers_id', $id);
      $Qreviews->setLogging($_SESSION['module'], $id);
      $Qreviews->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    } else {
      $Qcheck = $lC_Database->query('select reviews_id from :table_reviews where customers_id = :customers_id limit 1');
      $Qcheck->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qcheck->bindInt(':customers_id', $id);
      $Qcheck->execute();

      if ( $Qcheck->numberOfRows() > 0 ) {
        $Qreviews = $lC_Database->query('update :table_reviews set customers_id = null where customers_id = :customers_id');
        $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qreviews->bindInt(':customers_id', $id);
        $Qreviews->setLogging($_SESSION['module'], $id);
        $Qreviews->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $Qab = $lC_Database->query('delete from :table_address_book where customers_id = :customers_id');
      $Qab->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qab->bindInt(':customers_id', $id);
      $Qab->setLogging($_SESSION['module'], $id);
      $Qab->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $Qsc = $lC_Database->query('delete from :table_shopping_carts where customers_id = :customers_id');
      $Qsc->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
      $Qsc->bindInt(':customers_id', $id);
      $Qsc->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $Qsccvv = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where customers_id = :customers_id');
      $Qsccvv->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
      $Qsccvv->bindInt(':customers_id', $id);
      $Qsccvv->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $Qpn = $lC_Database->query('delete from :table_products_notifications where customers_id = :customers_id');
      $Qpn->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
      $Qpn->bindInt(':customers_id', $id);
      $Qpn->setLogging($_SESSION['module'], $id);
      $Qpn->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $Qcheck = $lC_Database->query('select session_id from :table_whos_online where customer_id = :customer_id');
      $Qcheck->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
      $Qcheck->bindInt(':customer_id', $id);
      $Qcheck->execute();

      if ( $Qcheck->numberOfRows() > 0 ) {
        $lC_Session->delete($Qcheck->value('session_id'));

        $Qwho = $lC_Database->query('delete from :table_whos_online where customer_id = :customer_id');
        $Qwho->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
        $Qwho->bindInt(':customer_id', $id);
        $Qwho->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $Qcustomers = $lC_Database->query('delete from :table_customers where customers_id = :customers_id');
      $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomers->bindInt(':customers_id', $id);
      $Qcustomers->setLogging($_SESSION['module'], $id);
      $Qcustomers->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete customer records
  *
  * @param array $batch The customer id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Customers_Admin::delete($id);
    }
    return true;
  }
 /*
  * Save the customer address book information
  *
  * @param integer $id The customer id used on update, null on insert
  * @param array $data An array containing the customer information
  * @access public
  * @return boolean
  */
  public static function saveAddress($id = null, $data) {
    global $lC_Database;
    
    if ($id == 0) $id = null;

    $error = false;
    $result = array();

    $lC_Database->startTransaction();

    if ( ACCOUNT_STATE > 0 ) {
      $Qcheck = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id limit 1');
      $Qcheck->bindTable(':table_zones', TABLE_ZONES);
      $Qcheck->bindInt(':zone_country_id', $data['ab_country_id']);
      $Qcheck->execute();

      $entry_state_has_zones = ( $Qcheck->numberOfRows() > 0 );

      $Qcheck->freeResult();

      if ( $entry_state_has_zones === true ) {
        $Qzone = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id and zone_code = :zone_code');
        $Qzone->bindTable(':table_zones', TABLE_ZONES);
        $Qzone->bindInt(':zone_country_id', $data['ab_country_id']);
        $Qzone->bindValue(':zone_code', strtoupper($data['ab_state']));
        $Qzone->execute();

        if ( $Qzone->numberOfRows() === 1 ) {
          $data['zone_id'] = $Qzone->valueInt('zone_id');
        } else {
          $Qzone = $lC_Database->query('select zone_id from :table_zones where zone_country_id = :zone_country_id and zone_name like :zone_name');
          $Qzone->bindTable(':table_zones', TABLE_ZONES);
          $Qzone->bindInt(':zone_country_id', $data['ab_country_id']);
          $Qzone->bindValue(':zone_name', $data['ab_state'] . '%');
          $Qzone->execute();

          if ( $Qzone->numberOfRows() === 1 ) {
            $data['zone_id'] = $Qzone->valueInt('zone_id');
          } else {
            $error = true;
            $result['rpcStatus'] = -2;
          }
        }

        $Qzone->freeResult();
      } else {
        if ( strlen(trim($data['state'])) < ACCOUNT_STATE ) {
          $error = true;
          $result['rpcStatus'] = -3;
        }
      }
    }

    if ( $error === false ) {
      $Qcustomer = $lC_Database->query('select customers_gender, customers_firstname, customers_lastname, customers_email_address, customers_default_address_id from :table_customers where customers_id = :customers_id');
      $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomer->bindInt(':customers_id', $data['customer_id']);
      $Qcustomer->execute();

      if ( is_numeric($id) && $id != 0 ) {
        $Qab = $lC_Database->query('update :table_address_book set entry_gender = :entry_gender, entry_company = :entry_company, entry_firstname = :entry_firstname, entry_lastname = :entry_lastname, entry_street_address = :entry_street_address, entry_suburb = :entry_suburb, entry_postcode = :entry_postcode, entry_city = :entry_city, entry_state = :entry_state, entry_country_id = :entry_country_id, entry_zone_id = :entry_zone_id, entry_telephone = :entry_telephone, entry_fax = :entry_fax where address_book_id = :address_book_id and customers_id = :customers_id');
        $Qab->bindInt(':address_book_id', $id);
      } else {
        $Qab = $lC_Database->query('insert into :table_address_book (customers_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id, entry_telephone, entry_fax) values (:customers_id, :entry_gender, :entry_company, :entry_firstname, :entry_lastname, :entry_street_address, :entry_suburb, :entry_postcode, :entry_city, :entry_state, :entry_country_id, :entry_zone_id, :entry_telephone, :entry_fax)');
      }

      $Qab->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qab->bindInt(':customers_id', $data['customer_id']);
      $Qab->bindValue(':entry_gender', $data['ab_gender']);
      $Qab->bindValue(':entry_company', $data['ab_company']);
      $Qab->bindValue(':entry_firstname', $data['ab_firstname']);
      $Qab->bindValue(':entry_lastname', $data['ab_lastname']);
      $Qab->bindValue(':entry_street_address', $data['ab_street_address']);
      $Qab->bindValue(':entry_suburb', $data['ab_suburb']);
      $Qab->bindValue(':entry_postcode', $data['ab_postcode']);
      $Qab->bindValue(':entry_city', $data['ab_city']);
      $Qab->bindValue(':entry_state', $data['ab_state']);
      $Qab->bindInt(':entry_country_id', $data['ab_country_id']);
      $Qab->bindInt(':entry_zone_id', $data['zone_id']);
      $Qab->bindValue(':entry_telephone', $data['ab_telephone']);
      $Qab->bindValue(':entry_fax', $data['ab_fax']);
      $Qab->setLogging($_SESSION['module'], $id);
      $Qab->execute();

      if ( !$lC_Database->isError() ) {
        if ( ( $Qcustomer->valueInt('customers_default_address_id') < 1 ) || ( $data['ab_primary'] == 'on' ) ) {
          $address_book_id = ( is_numeric($id) ? $id : $lC_Database->nextID() );

          $Qupdate = $lC_Database->query('update :table_customers set customers_default_address_id = :customers_default_address_id where customers_id = :customers_id');
          $Qupdate->bindTable(':table_customers', TABLE_CUSTOMERS);
          $Qupdate->bindInt(':customers_default_address_id', $address_book_id);
          $Qupdate->bindInt(':customers_id', $data['customer_id']);
          $Qupdate->setLogging($_SESSION['module'], $address_book_id);
          $Qupdate->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            $result['rpcStatus'] = -1;
          }
        }
      } else {
        $error = true;
        $result['rpcStatus'] = -1;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return $result;
    }

    $lC_Database->rollbackTransaction();

    return $result;
  }
 /*
  * Delete the customer address book information
  *
  * @param integer $id The customer address book id
  * @param integer $customer_id The customer id
  * @access public
  * @return boolean
  */
  public static function deleteAddress($id, $customer_id = null) {
    global $lC_Database;

    $customers_default_address_id = 0;
    if ($customer_id != null) {
      $Qcheck = $lC_Database->query('select customers_default_address_id from :table_customers where customers_id = :customers_id limit 1');
      $Qcheck->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcheck->bindInt(':customers_id', $customer_id);
      $Qcheck->execute();

      $customers_default_address_id = $Qcheck->valueInt('customers_default_address_id');

      $Qcheck->freeResult();
    }
    if ($customers_default_address_id != $id) {
      $Qdelete = $lC_Database->query('delete from :table_address_book where address_book_id = :address_book_id');
      if ( !empty($customer_id) ) {
        $Qdelete->appendQuery('and customers_id = :customers_id');
        $Qdelete->bindInt(':customers_id', $customer_id);
      }
      $Qdelete->bindTable(':table_address_book', TABLE_ADDRESS_BOOK);
      $Qdelete->bindInt(':address_book_id', $id);
      $Qdelete->setLogging($_SESSION['module'], $id);
      $Qdelete->execute();
      if ( $lC_Database->isError() ) {
        $error = true;
        $result['rpcStatus'] = -1;
      }
    } else {
      $error = true;
      $result['rpcStatus'] = -2;
    }

    return $result;
  }
 /*
  * Return the field name for the selected column (used for datatable ordering)
  *
  * @param integer $i The datatable column id
  * @access private
  * @return string
  */
  private static function fnColumnToField($i) {
   if ( $i == 0 )
    return "c.customers_id";
   else if ( $i == 1 )
    return "c.customers_lastname";
   else if ( $i == 2 )
    return "c.customers_firstname";
   else if ( $i == 3 )
    return "c.customers_group_id";
   else if ( $i == 4 )
    return "c.date_account_created";
  }
}
?>