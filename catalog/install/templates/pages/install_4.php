<?php
/*
  $Id: install_4.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
define('DB_TABLE_PREFIX', $_POST['DB_TABLE_PREFIX']);
include('../includes/classes/datetime.php');
include('../includes/database_tables.php');

$lC_Database = lC_Database::connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD'], $_POST['DB_DATABASE_CLASS']);
$lC_Database->selectDatabase($_POST['DB_DATABASE']);

$Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
$Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
$Qupdate->bindValue(':configuration_value', $_POST['CFG_STORE_NAME']);
$Qupdate->bindValue(':configuration_key', 'STORE_NAME');
$Qupdate->execute();

$Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
$Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
$Qupdate->bindValue(':configuration_value', $_POST['CFG_STORE_OWNER_NAME']);
$Qupdate->bindValue(':configuration_key', 'STORE_OWNER');
$Qupdate->execute();

$Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
$Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
$Qupdate->bindValue(':configuration_value', $_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']);
$Qupdate->bindValue(':configuration_key', 'STORE_OWNER_EMAIL_ADDRESS');
$Qupdate->execute();

if (!empty($_POST['CFG_STORE_OWNER_NAME']) && !empty($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS'])) {
  $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
  $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
  $Qupdate->bindValue(':configuration_value', '"' . $_POST['CFG_STORE_OWNER_NAME'] . '" <' . $_POST['CFG_STORE_OWNER_EMAIL_ADDRESS'] . '>');
  $Qupdate->bindValue(':configuration_key', 'EMAIL_FROM');
  $Qupdate->execute();
}

$Qcheck = $lC_Database->query('select user_name from :table_administrators where user_name = :user_name');
$Qcheck->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
$Qcheck->bindValue(':user_name', $_POST['CFG_ADMINISTRATOR_USERNAME']);
$Qcheck->execute();

if ($Qcheck->numberOfRows()) {
  $Qadmin = $lC_Database->query('update :table_administrators set user_password = :user_password where user_name = :user_name');
} else {
  $Qadmin = $lC_Database->query('insert into :table_administrators (user_name, user_password) values (:user_name, :user_password, :first_name, :last_name)');
}
$cfg_store_owner_name = trim($_POST['CFG_STORE_OWNER_NAME']);
$split_position = strpos($cfg_store_owner_name," ");  
if($split_position > 0) {
  $cfg_store_owner_name_first = substr($cfg_store_owner_name,0,$split_position);
  $cfg_store_owner_name_last = substr($cfg_store_owner_name,$split_position);
} else {
  $cfg_store_owner_name_first = $cfg_store_owner_name;
  $cfg_store_owner_name_last = '';
}
$Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
$Qadmin->bindValue(':user_password', lc_encrypt_string(trim($_POST['CFG_ADMINISTRATOR_PASSWORD'])));
$Qadmin->bindValue(':user_name', $_POST['CFG_ADMINISTRATOR_USERNAME']);
$Qadmin->bindValue(':first_name', trim($cfg_store_owner_name_first));
$Qadmin->bindValue(':last_name', trim($cfg_store_owner_name_last));
$Qadmin->execute();

$Qadmin = $lC_Database->query('select id from :table_administrators where user_name = :user_name');
$Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
$Qadmin->bindValue(':user_name', $_POST['CFG_ADMINISTRATOR_USERNAME']);
$Qadmin->execute();

$Qcheck = $lC_Database->query('select module from :table_administrators_access where administrators_id = :administrators_id limit 1');
$Qcheck->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
$Qcheck->bindInt(':administrators_id', $Qadmin->valueInt('id'));
$Qcheck->execute();

if ($Qcheck->numberOfRows()) {
  $Qdel = $lC_Database->query('delete from :table_administrators_access where administrators_id = :administrators_id');
  $Qdel->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
  $Qdel->bindInt(':administrators_id', $Qadmin->valueInt('id'));
  $Qdel->execute();
}

$Qaccess = $lC_Database->query('insert into :table_administrators_access (administrators_id, module) values (:administrators_id, :module)');
$Qaccess->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
$Qaccess->bindInt(':administrators_id', $Qadmin->valueInt('id'));
$Qaccess->bindValue(':module', '*');
$Qaccess->execute();
?>
<form name="install" id="installForm" action="install.php?step=4" method="post" class="block wizard-enabled">  
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="completed hide-on-mobile"><span class="wizard-step">1</span> <a style="color:#ccc;" href="index.php">Welcome</a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">2</span> <a style="color:#ccc;" href="install.php">Database</a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">3</span> <a style="color:#ccc;" href="install.php?step=3">Server</a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">4</span> <a style="color:#ccc;" href="install.php?step=4">Settings</a></li>
    <li class="active"><span class="wizard-step">5</span> Finished!</li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Finished</legend>         
    <?php
    $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
    if ((substr($dir_fs_document_root, -1) != '\\') && (substr($dir_fs_document_root, -1) != '/')) {
      if (strrpos($dir_fs_document_root, '\\') !== false) {
        $dir_fs_document_root .= '\\';
      } else {
        $dir_fs_document_root .= '/';
      }
    }
    
    $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
    $https_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
    $enable_ssl = 'false';
    
    $http_server = $http_url['scheme'] . '://' . $http_url['host'];
    $https_server = $http_url['scheme'] . '://' . $http_url['host'];
    $http_catalog = $http_url['path'];
    $https_catalog = $http_url['path'];
    if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
    }
    
    if (substr($http_catalog, -1) != '/') {
      $http_catalog .= '/';
    }
    
    if (isset($_POST['HTTPS_WEB_ADDRESS']) && !empty($_POST['HTTPS_WEB_ADDRESS']) && isset($_POST['web_use_ssl']) && $_POST['web_use_ssl'] == true ) {
      $enable_ssl = 'true';
      $https_url = parse_url($_POST['HTTPS_WEB_ADDRESS']);
      $https_server = $https_url['scheme'] . '://' . $https_url['host'];
      $https_catalog = $https_url['path'];
      if (isset($https_url['port']) && !empty($https_url['port'])) {
        $https_server .= ':' . $https_url['port'];
      }

      if (substr($https_catalog, -1) != '/') {
        $https_catalog .= '/';
      }      
    }

    $http_work_directory = $_POST['HTTP_WORK_DIRECTORY'];
    if (substr($http_work_directory, -1) != '/') {
      $http_work_directory .= '/';
    }

    $lC_DirectoryListing = new lC_DirectoryListing($http_work_directory . 'cache/');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('cache');

    foreach ($lC_DirectoryListing->getFiles() as $files) {
      @unlink($lC_DirectoryListing->getDirectory() . '/' . $files['name']);
    }

    $file_contents = '<?php' . "\n" .
    '  define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n" .
    '  define(\'HTTPS_SERVER\', \'' . $https_server . '\');' . "\n" .
    '  define(\'ENABLE_SSL\', ' . $enable_ssl . ');' . "\n" .
    '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_url['host'] . '\');' . "\n" .
    '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $https_url['host'] . '\');' . "\n" .
    '  define(\'HTTP_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" .
    '  define(\'HTTPS_COOKIE_PATH\', \'' . $https_catalog . '\');' . "\n" .
    '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
    '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $https_catalog . '\');' . "\n" .
    '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n\n" .
    '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
    '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
    '  define(\'DIR_FS_WORK\', \'' . $http_work_directory . '\');' . "\n" .
    '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
    '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n" .
    '  define(\'DIR_FS_BACKUP\', \'' . $dir_fs_document_root . 'admin/backups/\');' . "\n\n" .
    '  define(\'DB_SERVER\', \'' . $_POST['DB_SERVER'] . '\');' . "\n" .
    '  define(\'DB_SERVER_USERNAME\', \'' . $_POST['DB_SERVER_USERNAME'] . '\');' . "\n" .
    '  define(\'DB_SERVER_PASSWORD\', \'' . $_POST['DB_SERVER_PASSWORD']. '\');' . "\n" .
    '  define(\'DB_DATABASE\', \'' . $_POST['DB_DATABASE']. '\');' . "\n" .
    '  define(\'DB_DATABASE_CLASS\', \'' . $_POST['DB_DATABASE_CLASS'] . '\');' . "\n" .
    '  define(\'DB_TABLE_PREFIX\', \'' . $_POST['DB_TABLE_PREFIX']. '\');' . "\n" .
    '  define(\'USE_PCONNECT\', \'false\');' . "\n" .
    '  define(\'STORE_SESSIONS\', \'database\');' . "\n" .
    '?>';

    if (file_exists($dir_fs_document_root . 'includes/config.php') && !is_writeable($dir_fs_document_root . 'includes/config.php')) {
      @chmod($dir_fs_document_root . 'includes/config.php', 0777);
    }

    if (file_exists($dir_fs_document_root . 'includes/config.php') && is_writeable($dir_fs_document_root . 'includes/config.php')) {
      $fp = fopen($dir_fs_document_root . 'includes/config.php', 'w');
      fputs($fp, $file_contents);
      fclose($fp);  
      ?>
      
      <div class="field-block margin-bottom" style="padding-left:20px;">
        <h4><?php echo $lC_Language->get('box_info_step_4_title'); ?></h4>
        <p><?php echo $lC_Language->get('box_info_step_4_text'); ?></p>
      </div>      
       <?php
    } else {
      ?>       
      <div class="noticeBox">
        <?php echo sprintf($lC_Language->get('error_configuration_file_not_writeable'), $dir_fs_document_root . 'includes/config.php'); ?>
        <p align="right"><?php echo '<input type="image" src="templates/' . $template . '/languages/' . $lC_Language->getCode() . '/images/buttons/retry.gif" border="0" alt="' . $lC_Language->get('image_button_retry') . '" />'; ?></p>
        <?php echo $lC_Language->get('error_configuration_file_alternate_method'); ?>
        <?php echo lc_draw_textarea_field('contents', $file_contents, 60, 5, 'readonly="readonly" style="width: 100%; height: 120px;"', false); ?>
      </div>
      <?php
        foreach ($_POST as $key => $value) {
          if ($key != 'x' && $key != 'y') {
            if (is_array($value)) {
              for ($i=0, $n=sizeof($value); $i<$n; $i++) {
                echo lc_draw_hidden_field($key . '[]', $value[$i]);
              }
            } else {
              echo lc_draw_hidden_field($key, $value);
            }
          }
        }
      ?>
      <?php
    }
    ?>       
    <br /><br />
    <div class="field-block button-height">
      <div id="buttonContainer">
        <div id="buttonSet" style="float:right;">
          <span class="margin-right"><a class="button blue-gradient glossy" style="float:right;" href="<?php echo $http_server . $http_catalog . 'admin/index.php'; ?>" target="_blank">Administration Tool</a></span>
          <span class="margin-left"><a class="button blue-gradient glossy" style="float:left;" href="<?php echo $http_server . $http_catalog . 'index.php'; ?>" target="_blank">Catalog</a></span>
        </div>
      </div><div class="clear-both"></div>
    </div>    
  </fieldset>
</form>