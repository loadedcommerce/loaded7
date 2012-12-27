<?php
/*
  $Id: updater.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Updater_Admin class manages zM services
*/

class lC_Updater_Admin { 
 /*
  * Returns the services datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Language, $lC_Template;
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/work/updates');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $files = $lC_DirectoryListing->getFiles();

/*
    $installed = explode(';', MODULE_SERVICES_INSTALLED);

    $result = array('aaData' => array()); 
    foreach ($files as $file) {
      include('includes/modules/services/' . $file['name']);
      $class = substr($file['name'], 0, strrpos($file['name'], '.'));
      $module = 'lC_Updater_' . $class . '_Admin';
      $module = new $module();
      $name = '<span>' . $module->title . '</span>';
      $action = '';
      if ( in_array($class, $installed) && !lc_empty($module->keys()) ) {
        $action .= '<span style="padding:3px;"><a href="javascript://" onclick="editModule(\'' . $class . '\')">' . lc_icon_admin('edit.png') . '</a></span>';
      } else {
        $action .= lc_image('images/pixel_trans.gif', '', '16', '16') . '&nbsp;';
      }
      if ( !in_array($class, $installed) ) {
        $action .= '<span style="padding:3px;"><a href="javascript://" onclick="installModule(\'' . $class . '\')">' . lc_icon_admin('install.png') . '</a></span>';
      } elseif ( $module->uninstallable ) {
        $action .= '<span style="padding:3px;"><a href="javascript://" onclick="uninstallModule(\'' . $class . '\', \'' . $module->title . '\')">' . lc_icon_admin('uninstall.png') . '</a></span>';
      } else {
        $action .= lc_image('images/pixel_trans.gif', '', '18', '16') . '&nbsp;';       
      }                            
      $result['aaData'][] = array("$name", "$action"); 
      $cnt++;  
    }
*/
    return $files;
  }

  public function getUpdateData($_type = 'CORE') {
    global $lC_Database;

    // get the components table info
    if ($_type == NULL) {
      $Qcomponents = $lC_Database->query('SELECT * from :table_components');
    } else {
      $Qcomponents = $lC_Database->query('SELECT * from :table_components WHERE type = :type');
      $Qcomponents->bindValue(':type', $_type);
    }
    $Qcomponents->bindTable(':table_components', TABLE_COMPONENTS);
    $Qcomponents->execute();

    $cArr = array();
    while ( $Qcomponents->next() ) { 
       $cArr[$Qcomponents->value('validationProduct')] = 'version:' . $Qcomponents->value('version') .
                                                         '|type:' . $Qcomponents->value('type') .
                                                         '|serialOne:' . $Qcomponents->value('serial1') .
                                                         '|serialTwo:' . $Qcomponents->value('serial2') .
                                                         '|status:' . $Qcomponents->valueInt('status') .
                                                         '|lastValidated:' . $Qcomponents->value('lastValidated') .
                                                         '|lastUpdateCheck:' . urlencode($Qcomponents->value('lastUpdateCheck')) .
                                                         '|expirationDate:' . $Qcomponents->value('expirationDate');
    }
    $Qcomponents->freeResult();

    $result = self::__sendToHost($cArr);

    return utility::xml2arr($result);
  }
  
  public function updateChecksum($_version = '7.0.0') {
    global $lC_Language, $lC_Template, $lC_Database;
    
    $lC_DirectoryListing = new lC_DirectoryListing('../');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setRecursive(true);
    $lC_DirectoryListing->setStats(true);
    $files = $lC_DirectoryListing->getFiles();

    reset($files);
    foreach ($files as $key => $values) {
      
      $Qfiles = $lC_Database->query('select * from :table_components_files where filename = :filename');
      $Qfiles->bindTable(':table_components_files', TABLE_COMPONENTS_FILES);
      $Qfiles->bindValue(':filename', $values['path']);
      $Qfiles->execute();

      if ($Qfiles->numberOfRows() > 0) {
        $Qupdate = $lC_Database->query('update :table_components_files set checksum = :checksum where filename = :filename');
      } else {
        $Qupdate = $lC_Database->query('insert into :table_components_files (filename, version, checksum) values (:filename, :version, :checksum)');
      }    
      $Qfiles->freeResult();
      
      $Qupdate->bindTable(':table_components_files', TABLE_COMPONENTS_FILES);
      $Qupdate->bindValue(':filename', $values['path']);
      $Qupdate->bindValue(':version', $_version);
      $Qupdate->bindValue(':checksum', md5_file($values['path']));
      $Qupdate->execute();

      $Qupdate->freeResult();
    }
    
    return TRUE;
  }

  private function __sendToHost($_dataArr) {
    
    $username = 'admin';
    $password = 'admin';
                             
    $url = 'http://zen-dev.com/lcAPI/1_0/getUpdateInfo/' . utility::arr2nvp($_dataArr); 

    // initialize the Curl session
    $ch = curl_init();
    
    $header = array();
    $header[] = 'Content-type: text/html; charset=utf-8';
    //$header[] = "Cache-Control: max-age=0";
    //$header[] = "Connection: keep-alive";
    //$header[] = "Keep-Alive: 300";
    //$header[] = "Accept-Charset: ISO-8859-1,utf-8;";
    //$header[] = "Accept-Language: en-us,en,fr-ca,fr;";
    //$header[] = "Pragma: "; // browsers keep this blank. 
  
    // set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);                                                
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, -1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    
    // execute the fetch
    $data = curl_exec($ch);
    // close the connection
    curl_close($ch);
    
    return $data;  
  }

}
?>