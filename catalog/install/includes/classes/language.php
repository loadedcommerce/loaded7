<?php
/*
  $Id: language.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require('../admin/includes/classes/language.php');

class lC_LanguageInstall extends lC_Language_Admin {

/* Private variables */
  var $_languages = array();
  
/* Class constructor */

  function lC_LanguageInstall() {
    $lC_DirectoryListing = new lC_DirectoryListing('../includes/languages');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('xml');

    foreach ($lC_DirectoryListing->getFiles() as $file) {
      $lC_XML = new lC_XML(file_get_contents('../includes/languages/' . $file['name']));
      $lang = $lC_XML->toArray();

      $this->_languages[$lang['language']['data']['code']] = array('name' => $lang['language']['data']['title'],
                                                                   'code' => $lang['language']['data']['code'],
                                                                   'charset' => $lang['language']['data']['character_set']);
    }

    unset($lang);

    $language = (isset($_GET['language']) && !empty($_GET['language']) ? $_GET['language'] : '');

    $this->set($language);

    $this->loadIniFile();
    $this->loadIniFile(basename($_SERVER['SCRIPT_FILENAME']));
  }
}
?>
