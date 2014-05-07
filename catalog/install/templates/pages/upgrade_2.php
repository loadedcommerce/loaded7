<?php
/**
  @package    catalog::install::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upgrade_2.php v1.0 2013-08-08 datazen $
*/
$db_table_types = array(array('id' => 'mysqli', 'text' => 'MySQL - MyISAM (Default)'));
$dir_fs_www_root = dirname(__FILE__);
$dir_fs_www_root = lc_realpath(dirname(__FILE__) . '/../../');
$form_action = 'upgrade.php?step=2'; // default form action
$script_filename = $_SERVER['SCRIPT_FILENAME'];
$script_filename = str_replace('\\', '/', $script_filename);
$fs_array = explode('/', dirname($script_filename));
$fs_root_array = array();

for ($i=0, $n=sizeof($fs_array)-1; $i<$n; $i++) {
  $fs_root_array[] = $fs_array[$i];
}

$fs_www_root = implode('/', $fs_root_array) . '/';

if (isset($_POST['INSTALL_PATH']) && $_POST['INSTALL_PATH'] != '') {
  if (substr($_POST['INSTALL_PATH'], -1) != '/') $_POST['INSTALL_PATH'] .= '/';
  if (substr($_POST['INSTALL_PATH'], 0, 1) == '/') $_POST['INSTALL_PATH'] = substr($_POST['INSTALL_PATH'], 1);
  if (substr($_POST['INSTALL_PATH'], 0, 1) != '/') $_POST['INSTALL_PATH'] = '/' . $_POST['INSTALL_PATH'];
  $cre_path = $_POST['INSTALL_PATH'];  
} else {
  $cre_path = '';
}

$page_title_text = $lC_Language->get('upgrade_step2_page_title');
$page_description_text = $lC_Language->get('upgrade_step2_page_desc');
$db_imported = false; 
$error = '';

if ((isset($_POST['db_switch']) && $_POST['db_switch'] != -1)) {              
  if ((isset($_POST['save_settings']) && $_POST['upgrade_method'] == 'S')) {	
    $db = array('DB_SERVER' => trim(urldecode($_POST['DB_SERVER'])),
                'DB_SERVER_USERNAME' => trim(urldecode($_POST['DB_SERVER_USERNAME'])),
                'DB_SERVER_PASSWORD' => trim(urldecode($_POST['DB_SERVER_PASSWORD'])),
                'DB_DATABASE' => trim(urldecode($_POST['DB_DATABASE'])),
                'DB_DATABASE_CLASS' => trim(urldecode($_POST['DB_DATABASE_CLASS'])),
                'DB_INSERT_SAMPLE_DATA' => ((trim(urldecode($_POST['DB_INSERT_SAMPLE_DATA'])) == '1') ? 'true' : 'false'),
                'DB_TABLE_PREFIX' => trim(urldecode($_POST['DB_TABLE_PREFIX']))
                );
	  
    $lC_Database = lC_Database::connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE_CLASS']);

    if ($lC_Database->isError() === false) {
      $lC_Database->selectDatabase($db['DB_DATABASE']);
    }
    
    if ($lC_Database->isError() === false) {
      if ($_POST['class'] == 'mysqli_innodb') {
        $sql_file = $dir_fs_www_root . '/loadedcommerce_innodb.sql';
      } else {
        $sql_file = $dir_fs_www_root . '/loadedcommerce.sql';
      }
      $lC_Database->importSQL($sql_file, $db['DB_DATABASE'], $db['DB_TABLE_PREFIX']);
    }

    if (($lC_Database->isError() === false) && ($db['DB_INSERT_SAMPLE_DATA'] == 'true')) {
      $sql_file = $dir_fs_www_root . '/loadedcommerce_sample_data.sql';
      $lC_Database->importSQL($sql_file, $db['DB_DATABASE'], $db['DB_TABLE_PREFIX']);
    }

    if ($lC_Database->isError() === false) {
      foreach ($lC_Language->extractDefinitions('en_US.xml') as $def) {
        $Qdef = $lC_Database->query('insert into :table_languages_definitions (languages_id, content_group, definition_key, definition_value) values (:languages_id, :content_group, :definition_key, :definition_value)');
        $Qdef->bindTable(':table_languages_definitions', $db['DB_TABLE_PREFIX'] . 'languages_definitions');
        $Qdef->bindInt(':languages_id', 1);
        $Qdef->bindValue(':content_group', $def['group']);
        $Qdef->bindValue(':definition_key', $def['key']);
        $Qdef->bindValue(':definition_value', $def['value']);
        $Qdef->execute();
      }
             
      $lC_DirectoryListing = new lC_DirectoryListing('../includes/languages/en_US');
      $lC_DirectoryListing->setRecursive(true);
      $lC_DirectoryListing->setIncludeDirectories(false);
      $lC_DirectoryListing->setAddDirectoryToFilename(true);
      $lC_DirectoryListing->setCheckExtension('xml');

      foreach ($lC_DirectoryListing->getFiles() as $files) {
        foreach ($lC_Language->extractDefinitions('en_US/' . $files['name']) as $def) {
          $Qdef = $lC_Database->query('insert into :table_languages_definitions (languages_id, content_group, definition_key, definition_value) values (:languages_id, :content_group, :definition_key, :definition_value)');
          $Qdef->bindTable(':table_languages_definitions', $db['DB_TABLE_PREFIX'] . 'languages_definitions');
          $Qdef->bindInt(':languages_id', 1);
          $Qdef->bindValue(':content_group', $def['group']);
          $Qdef->bindValue(':definition_key', $def['key']);
          $Qdef->bindValue(':definition_value', $def['value']);
          $Qdef->execute();
        }
      }
    }

    if ($lC_Database->isError() === false) {
      define('DB_TABLE_PREFIX', $db['DB_TABLE_PREFIX']);
      include('../includes/database_tables.php');

      $services = array('output_compression',
                        'session',
                        'language',
                        'currencies',
                        'core',
                        'simple_counter',
                        'category_path',
                        'breadcrumb',
                        'whos_online',
                        'banner',
                        'specials',
                        'reviews',
                        'recently_visited');

      $installed = array();

      foreach ($services as $service) {
        include('../admin/includes/modules/services/' . $service . '.php');
        $class = 'lC_Services_' . $service . '_Admin';
        $module = new $class();
        $module->install();

        if (isset($module->depends)) {
          if (is_string($module->depends) && (($key = array_search($module->depends, $installed)) !== false)) {
            if (isset($installed[$key+1])) {
              array_splice($installed, $key+1, 0, $service);
            } else {
              $installed[] = $service;
            }
          } elseif (is_array($module->depends)) {
            foreach ($module->depends as $depends_module) {
              if (($key = array_search($depends_module, $installed)) !== false) {
                if (!isset($array_position) || ($key > $array_position)) {
                  $array_position = $key;
                }
              }
            }
            if (isset($array_position)) {
              array_splice($installed, $array_position+1, 0, $service);
            } else {
              $installed[] = $service;
            }
          }
        } elseif (isset($module->precedes)) {
          if (is_string($module->precedes)) {
            if ((($key = array_search($module->precedes, $installed)) !== false)) {
              array_splice($installed, $key, 0, $service);
            } else {
              $installed[] = $service;
            }
          } elseif (is_array($module->precedes)) {
            foreach ($module->precedes as $precedes_module) {
              if (($key = array_search($precedes_module, $installed)) !== false) {
                if (!isset($array_position) || ($key < $array_position)) {
                  $array_position = $key;
                }
              }
            }
            if (isset($array_position)) {
              array_splice($installed, $array_position, 0, $service);
            } else {
              $installed[] = $service;
            }
          }
        } else {
          $installed[] = $service;
        }
        unset($array_position);
      }

      $Qs = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ("Service Modules", "MODULE_SERVICES_INSTALLED",  :configuration_value, "Installed services modules", "6", "0", now())');
      $Qs->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qs->bindValue(':configuration_value', implode(';', $installed));
      $Qs->execute();

      include('includes/classes/payment.php');
      include('includes/classes/shipping.php');
      include('includes/classes/order_total.php');
      include('../admin/includes/applications/modules_order_total/classes/modules_order_total.php');
      include('../admin/includes/modules/order_total/sub_total.php');
      
      $module = new lC_OrderTotal_sub_total();
      $module->install();

      include('../admin/includes/modules/order_total/shipping.php');
      $module = new lC_OrderTotal_shipping();
      $module->install();

      include('../admin/includes/modules/order_total/tax.php');
      $module = new lC_OrderTotal_tax();
      $module->install();

      include('../admin/includes/modules/order_total/total.php');
      $module = new lC_OrderTotal_total();
      $module->install();
    }

    if (($lC_Database->isError() === false) && ($db['DB_DATABASE_CLASS'] == 'mysql_innodb')) {
      $Qinno = $lC_Database->query('show variables like "have_innodb"');
      if (($Qinno->numberOfRows() === 1) && (strtolower($Qinno->value('Value')) == 'yes')) {
        $database_tables = array('address_book', 'categories', 'categories_description', 'customers', 'manufacturers', 'manufacturers_info', 'orders', 'orders_products', 'orders_status', 'orders_status_history', 'orders_products_attributes', 'orders_products_download', 'orders_total', 'products', 'products_attributes', 'products_attributes_download', 'products_description', 'products_options', 'products_options_values', 'products_options_values_to_products_options', 'products_to_categories', 'reviews', 'shopping_carts', 'shopping_carts_custom_variants_values', 'weight_classes', 'weight_classes_rules');

        foreach ($database_tables as $table) {
          $lC_Database->simpleQuery('alter table ' . $db['DB_TABLE_PREFIX'] . $table . ' type = innodb');
        }
      }
    }

    if ($lC_Database->isError()) {
      $error = $lC_Database->getError();
    } else {
      $db_imported = true; 
      $form_action = "upgrade.php?step=3";
      $page_title_text = $lC_Language->get('upgrade_step2_page_title_success');
      $page_description_text = $lC_Language->get('upgrade_step2_page_desc_success');
    }
  }
} else {
  $db = array('DB_SERVER' => trim(urldecode($_POST['DB_SERVER'])),
              'DB_SERVER_USERNAME' => trim(urldecode($_POST['DB_SERVER_USERNAME'])),
              'DB_SERVER_PASSWORD' => trim(urldecode($_POST['DB_SERVER_PASSWORD'])),
              'DB_DATABASE' => trim(urldecode($_POST['DB_DATABASE'])),
              'DB_DATABASE_CLASS' => trim(urldecode($_POST['DB_DATABASE_CLASS'])),
              'DB_INSERT_SAMPLE_DATA' => ((trim(urldecode($_POST['DB_INSERT_SAMPLE_DATA'])) == '1') ? 'true' : 'false'),
              'DB_TABLE_PREFIX' => trim(urldecode($_POST['DB_TABLE_PREFIX']))
              );
  
  $lC_Database = lC_Database::connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE_CLASS']);

  if ($lC_Database->isError() === false) {  
    $form_action = "upgrade.php?step=3";
    $page_title_text = $lC_Language->get('upgrade_step2_page_title_success');
    $page_description_text = $lC_Language->get('upgrade_step2_page_desc_success');
  } else {
    $error = $lC_Database->getError();
  }
}      
?>
<script language="javascript" type="text/javascript">
<!--
	function formValid(){
    var bValid = $("#upgradeForm").validate({
      rules: {
        DB_SERVER: { required: true },
        DB_SERVER_USERNAME: { required: true },
        DB_SERVER_PASSWORD: { required: true },
        DB_DATABASE: { required: true },
        DB_DATABASE_CLASS: { required: true },
        DB_TABLE_PREFIX: { required: true },
      },
      invalidHandler: function() {
        return false;
      }
    }).form();
		if (!bValid) return false;
    document.getElementById('upgradeForm').submit();
    return true;
	}
//-->
</script>
<style>
  .field-block {
    padding: 0 30px 0 170px;
  }
</style>
<form class="block wizard-enabled" name="upgrade" id="upgradeForm" action="<?php echo $form_action; ?>" method="post" onsubmit="return formValid();">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="hide-on-mobile"><span class="wizard-step">1</span><?php echo $lC_Language->get('upgrade_nav_text_1'); ?></li>
    <li class="active"><span class="wizard-step">2</span><?php echo $lC_Language->get('upgrade_nav_text_2'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">3</span><?php echo $lC_Language->get('upgrade_nav_text_3'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span><?php echo $lC_Language->get('upgrade_nav_text_4'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span><?php echo $lC_Language->get('upgrade_nav_text_5'); ?></li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend"><?php echo $lC_Language->get('text_legend_database'); ?></legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $page_title_text; ?></h4>
      <p><?php echo $page_description_text; ?></p>
    </div>
    <div id="pBox" style="display:none; padding: 0px 20px 10px;">
      <p class="message blue-gradient align-center">  
        <span class="stripes animated"></span>
        <span id="pBoxContents"></span>
        <span class="block-arrow bottom"></span>
      </p>      
    </div>
    <div id="mBox" style="display:none; padding:0px 20px 20px 20px"> 
      <p class="message icon-warning red-gradient">   
        <span class="stripes animated"></span>
        <span id="mBoxContents"></span>
      </p> 
    </div>   
    <?php
	    if (!$db_imported) {
    ?> 
    <div>         
      <div class="field-block button-height small-margin-top large-margin-left large-margin-right">
        <label for="DB_SERVER" class="label"><b><?php echo $lC_Language->get('param_database_server'); ?></b></label>
        <input type="text" name="DB_SERVER" id="LC7_SERVER" value="" class="input" style="width:93%;">
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="DB_SERVER_USERNAME" class="label"><b><?php echo $lC_Language->get('param_database_username'); ?></b></label>
        <input type="text" name="DB_SERVER_USERNAME" id="LC7_USER" value="" class="input" style="width:93%;">
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="DB_SERVER_PASSWORD" class="label"><b><?php echo $lC_Language->get('param_database_password'); ?></b></label>
        <input type="password" name="DB_SERVER_PASSWORD" id="LC7_PASSWORD" value="" class="input" style="width:93%;">
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="DB_DATABASE" class="label"><b><?php echo $lC_Language->get('param_database_name'); ?></b></label>
        <input type="text" name="DB_DATABASE" id="LC7_DB" value="" class="input" style="width:93%;">
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="DB_DATABASE_CLASS" class="label"><b><?php echo $lC_Language->get('param_database_type'); ?></b></label>
        <?php echo lc_draw_pull_down_menu('DB_DATABASE_CLASS', $db_table_types, null, 'class="input with-small-padding" style="width:96%;"'); ?>
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="DB_TABLE_PREFIX" class="label"><b><?php echo $lC_Language->get('param_database_prefix'); ?></b></label>
        <input type="text" name="DB_TABLE_PREFIX" id="LC7_PREFIX" value="lc_" class="input" style="width:93%;">
      </div>      
    </div>
    <?php
	    }
    ?>    
    <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
    	<input type="hidden" name="save_settings" value="">
      <a id="btn_continue" href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide();$('#upgradeForm').submit();" class="button">
        <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
      </a>
    </div>   
  </fieldset>
  <?php
    foreach ($_POST as $key => $value) {
      if (($error == "") || ($key != 'DB_SERVER' && $key != 'DB_SERVER_USERNAME' && $key != 'DB_SERVER_PASSWORD' && $key != 'DB_DATABASE' && $key != 'DB_TABLE_PREFIX' && $key != 'save_settings')) {
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
</form>
<?php
	if (!empty($error)) {
?>
<script>
	$('#mBoxContents').html("<?php echo $error; ?>");
	$('#mBox').show();
</script>
<?php
	}
?>	
