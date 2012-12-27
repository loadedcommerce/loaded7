<?php
/*
  $Id: application_bottom.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
// show the current template name
//if (isset($_SESSION['lC_Customer_data'])) {
//  echo "<pre>";
//  print_r($_SESSION['lC_Customer_data']);
//  echo "</pre>";
//}

if (isset($_SESSION['lC_Customer_data'])) {
  $lC_MessageStack->add('debug', 'Customer Group: [' . $_SESSION['lC_Customer_data']['customers_group_id'] . '] ' . $_SESSION['lC_Customer_data']['customers_group_name'] , 'warning'); 
}    
$lC_MessageStack->add('debug', 'Template: ' . $_SESSION['template']['code'], 'warning');
$lC_MessageStack->add('debug', 'Number of queries: ' . $lC_Database->numberOfQueries() . ' [' . $lC_Database->timeOfQueries() . 's]', 'warning');
$lC_Services->stopServices();

?>