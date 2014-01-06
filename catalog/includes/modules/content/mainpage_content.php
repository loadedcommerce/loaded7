<?php
/**
  @package    catalog::modules::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mainpage_banner.php v1.0 2013-08-08 datazen $
*/
class lC_Content_mainpage_content extends lC_Modules {
 /* 
  * Public variables 
  */  
  public $_title,
         $_code = 'mainpage_content',
         $_author_name = 'Loaded Commerce',
         $_author_www = 'http://www.loadedcommerce.com',
         $_group = 'content';
 /* 
  * Class constructor 
  */
  public function __construct() {
    global $lC_Language;           

    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');      
    
    $this->_title = $lC_Language->get('mainpage_content_title');
  }
 /*
  * Returns the also puchased HTML
  *
  * @access public
  * @return string
  */
  public function initialize() {
    global $lC_Database, $lC_Language;
    
    $Qcontent = $lC_Database->query('select homepage_text from :table_branding where language_id = :language_id');
    $Qcontent->bindTable(':table_branding', TABLE_BRANDING);
    $Qcontent->bindInt(':language_id', $lC_Language->getID());
    $Qcontent->execute();
    
    if ($Qcontent->numberOfRows() > 0) {
      while ( $Qcontent->next() ) {
        $this->_content = $Qcontent->value('homepage_text');
      }
    }
  }
 /*
  * Install the module
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    parent::install();
  }
}
?>