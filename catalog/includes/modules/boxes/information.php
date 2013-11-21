<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: information.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_information extends lC_Modules {
  var $_title,
      $_code = 'information',
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_information() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_information_heading');
  }

  public function initialize() {
    global $lC_Language, $lC_Customer;

    $this->_title_link = lc_href_link(FILENAME_INFO);
   
    $this->_content = (($lC_Customer->isLoggedOn()) ? '  <li class="box-information-account">' . lc_link_object(lc_href_link(FILENAME_ACCOUNT), $lC_Language->get('my_account')) . '</li>' : '') . "\n" .
                                                      '  <li class="box-information-shipping">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=2_3'), $lC_Language->get('box_information_shipping')) . '</li>' . "\n" .
                                                      '  <li class="box-information-privacy">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=2_4'), $lC_Language->get('box_information_privacy')) . '</li>' . "\n" .
                                                      '  <li class="box-information-conditions">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=2_5'), $lC_Language->get('box_information_conditions')) . '</li>' . "\n" .
                                                      '  <li class="box-information-contact">' . lc_link_object(lc_href_link(FILENAME_INFO, 'cookie'), $lC_Language->get('box_information_cookie')) . '</li>' . "\n" .
                                                      '  <li class="box-information-contact">' . lc_link_object(lc_href_link(FILENAME_INFO, 'ssl_check'), $lC_Language->get('box_information_ssl_check')) . '</li>' . "\n" .
                                                      '  <li class="box-information-contact">' . lc_link_object(lc_href_link(FILENAME_INFO, 'contact'), $lC_Language->get('box_information_contact')) . '</li>' . "\n" .
                                                      '  <li class="box-information-sitemap">' . lc_link_object(lc_href_link(FILENAME_INFO, 'sitemap'), $lC_Language->get('box_information_sitemap')) . '</li>' . "\n";
  }
}
?>
