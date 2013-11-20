<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: language.php v1.0 2013-08-08 datazen $
*/
class lC_Services_language {
  function start() {
    global $lC_Language, $lC_Session, $lC_Vqmod;

    require($lC_Vqmod->modCheck('includes/classes/language.php'));
    $lC_Language = new lC_Language();

    if (isset($_GET['language']) && !empty($_GET['language'])) {
      $lC_Language->set($_GET['language']);
    }

    $lC_Language->load('general');
    $lC_Language->load('modules-boxes');
    $lC_Language->load('modules-content');

    header('Content-Type: text/html; charset=' . $lC_Language->getCharacterSet());

    lc_setlocale(LC_TIME, explode(',', $lC_Language->getLocale()));

    return true;
  }

  function stop() {
    return true;
  }
}
?>