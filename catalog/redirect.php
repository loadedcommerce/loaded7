<?php
/**  
*  $Id: redirect.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require($lC_Vqmod->modCheck('includes/application_top.php'));

switch ($_GET['action']) {
  case 'banner':
    if (isset($_GET['goto']) && is_numeric($_GET['goto'])) {
      if ($lC_Services->isStarted('banner') && $lC_Banner->isActive($_GET['goto'])) {
        lc_redirect($lC_Banner->getURL($_GET['goto'], true));
      }
    }
    break;

  case 'url':
    if (isset($_GET['goto']) && !empty($_GET['goto'])) {
      $Qcheck = $lC_Database->query('select products_url from :table_products_description where products_url = :products_url limit 1');
      $Qcheck->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qcheck->bindValue(':products_url', $_GET['goto']);
      $Qcheck->execute();

      if ($Qcheck->numberOfRows() === 1) {
        lc_redirect('http://' . $_GET['goto']);
      }
    }
    break;

  case 'manufacturer':
    if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
      $Qmanufacturer = $lC_Database->query('select manufacturers_url from :table_manufacturers_info where manufacturers_id = :manufacturers_id and languages_id = :languages_id');
      $Qmanufacturer->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qmanufacturer->bindInt(':manufacturers_id', $_GET['manufacturers_id']);
      $Qmanufacturer->bindInt(':languages_id', $lC_Language->getID());
      $Qmanufacturer->execute();

      if ($Qmanufacturer->numberOfRows() && !lc_empty($Qmanufacturer->value('manufacturers_url'))) {
        $Qupdate = $lC_Database->query('update :table_manufacturers_info set url_clicked = url_clicked+1, date_last_click = now() where manufacturers_id = :manufacturers_id and languages_id = :languages_id');
        $Qupdate->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
        $Qupdate->bindInt(':manufacturers_id', $_GET['manufacturers_id']);
        $Qupdate->bindInt(':languages_id', $lC_Language->getID());
        $Qupdate->execute();

        lc_redirect($Qmanufacturer->value('manufacturers_url'));
      } else {
        // no url exists for the selected language, lets use the default language then
        $Qmanufacturer = $lC_Database->query('select mi.languages_id, mi.manufacturers_url from :table_manufacturers_info mi, :table_languages l where mi.manufacturers_id = :manufacturers_id and mi.languages_id = l.languages_id and l.code = :code');
        $Qmanufacturer->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
        $Qmanufacturer->bindTable(':table_languages', TABLE_LANGUAGES);
        $Qmanufacturer->bindInt(':manufacturers_id', $_GET['manufacturers_id']);
        $Qmanufacturer->bindValue(':code', DEFAULT_LANGUAGE);
        $Qmanufacturer->execute();

        if ($Qmanufacturer->numberOfRows() && !lc_empty($Qmanufacturer->value('manufacturers_url'))) {
          $Qupdate = $lC_Database->query('update :table_manufacturers_info set url_clicked = url_clicked+1, date_last_click = now() where manufacturers_id = :manufacturers_id and languages_id = :languages_id');
          $Qupdate->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
          $Qupdate->bindInt(':manufacturers_id', $_GET['manufacturers_id']);
          $Qupdate->bindInt(':languages_id', $Qmanufacturer->valueInt('languages_id'));
          $Qupdate->execute();

          lc_redirect($Qmanufacturer->value('manufacturers_url'));
        }
      }
    }
    break;
}
lc_redirect(lc_href_link(FILENAME_DEFAULT));
?>