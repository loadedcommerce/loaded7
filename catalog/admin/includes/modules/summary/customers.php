<?php
/*
  $Id: customers.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_customers extends lC_Summary {

  var $enabled = TRUE,
      $sort_order = 10;
  
  /* Class constructor */
  function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/customers.php');
    $lC_Language->loadIniFile('customers.php');

    $this->_title = $lC_Language->get('summary_customers_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'customers');

    if ( lC_Access::hasAccess('customers') ) {
      $this->_setData();
    }
  }

  /* Private methods */
  function _setData() {
    global $lC_Database, $lC_Language, $lC_Template;

    if (!$this->enabled) {
      $this->_data = '';
    } else {    
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile">' .
                     '  <h2 class="relative thin">' . $this->_title . '</h2>' .
                     '  <ul class="list spaced">';

      $Qcustomers = $lC_Database->query('select customers_id, customers_gender, customers_lastname, customers_firstname, customers_status, date_account_created from :table_customers order by date_account_created desc limit 6');
      $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomers->execute();

      while ( $Qcustomers->next() ) {      
        $full = $Qcustomers->valueProtected('customers_firstname') . ' ' . $Qcustomers->valueProtected('customers_lastname');
        $this->_data .= '    <li>' .
                        '      <span class="list-link icon-user icon-blue" title="' . $lC_Language->get('edit') . '">' .  
                        '        <strong>' . $Qcustomers->value('customers_firstname') . '</strong> ' . $Qcustomers->value('customers_lastname') .
                        '      </span>' .
                        '      <div class="absolute-right compact show-on-parent-hover">' .
                        '        <a href="' . ((int)($_SESSION['admin']['access']['customers'] < 3) ? '#' : 'javascript://" onclick="editCustomer(\'' . $Qcustomers->valueInt('customers_id') . '\')') . ';" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['customers'] < 3) ? ' disabled' : NULL) . '">' .  $lC_Language->get('icon_view') . '</a>' . 
                        '        <a href="' . ((int)($_SESSION['admin']['access']['customers'] < 2) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'orders&cID=' . $Qcustomers->valueInt('customers_id'))) . '" class="button icon-price-tag with-tooltip' . ((int)($_SESSION['admin']['access']['customers'] < 2) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_orders') . '"></a>' . 
                        '        <!-- a href="' . ((int)($_SESSION['admin']['access']['customers'] < 4) ? '#' : 'javascript://" onclick="deleteCustomer(\'' . $Qcustomers->valueInt('customers_id') . '\', \'' . urlencode($full) . '\')') . ';" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['customers'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a -->' . 
                        '      </div>' .
                        '    </li>';
      }

      $this->_data .= '  </ul>' . 
                      '</div>';

      $Qcustomers->freeResult();
      
      $this->_data .= $this->loadModal();
    }
  }
  
  function loadModal() {
    global $lC_Database, $lC_Language, $lC_Template;
    
    if ( is_dir('includes/applications/customers/modal') ) {
      if ( file_exists('includes/applications/customers/modal/edit.php') ) include_once('includes/applications/customers/modal/edit.php');
      //if ( file_exists('includes/applications/customers/modal/delete.php') ) include_once('includes/applications/customers/modal/delete.php');
    }
  }
}
?>