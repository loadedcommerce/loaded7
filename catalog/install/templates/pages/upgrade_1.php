<?php
/**
  @package    catalog::install::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upgrade_1.php v1.0 2013-08-08 datazen $
*/
$db_table_types = array(array('id' => 'mysqli', 'text' => 'MySQL - MyISAM (Default)'));

if($_POST){
  // print_r($_POST);
  // echo $_POST['INSTALL_PATH'].'<p>';
}                 

$form_action = 'upgrade.php?step=1'; // default form action

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

$page_title_text = $lC_Language->get('upgrade_step1_page_title');
$page_description_text = $lC_Language->get('upgrade_step1_page_desc');

$error = "";

if(($_POST['upgrade_method'] == 'R')) {
  // later
} else if(($_POST['upgrade_method'] == 'D')) {
  // later
} else {
  if (!isset($_POST['upgrade_method'])) { 
    $_POST['upgrade_method'] = 'S'; 
  }
  if (isset($_POST['INSTALL_PATH'])) {
    // check that you are not accessing the current config file
    // check that source config file is accessible 
    if ($cre_path == $fs_www_root) {
      $error = $lC_Language->get('upgrade_step1_err_pathsame');
    } elseif (!file_exists($cre_path . 'admin/includes/configure.php') || !is_readable($cre_path . 'admin/includes/configure.php')) {
      $error = $lC_Language->get('upgrade_step1_err_noconfig');
    } else {
      $config = file_get_contents($cre_path . 'admin/includes/configure.php');
      $pattern = "/'DB_SERVER',\s*'(.*?)'/";
      $match = array();
      if (preg_match($pattern, $config, $match)) {
        $db_server = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').$lC_Language->get('upgrade_step1_err_noserver');
      }

      $pattern = "/'DB_SERVER_USERNAME',\s*'(.*?)'/";
      $match = array();
      
      if (preg_match($pattern, $config, $match)) {
        $db_username = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').$lC_Language->get('upgrade_step1_err_nouid');
      }

      $pattern = "/'DB_SERVER_PASSWORD',\s*'(.*?)'/";
      $match = array();
      
      if (preg_match($pattern, $config, $match)) {
        $db_password = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').$lC_Language->get('upgrade_step1_err_nopass');
      }

      $pattern = "/'DB_DATABASE',\s*'(.*?)'/";
      $match = array();
      
      if (preg_match($pattern, $config, $match)) {
        $db_database = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').$lC_Language->get('upgrade_step1_err_nodb');
      }

      $pattern = "/'DIR_WS_IMAGES',\s*'(.*?)'/";
      $match = array();
      
      if (preg_match($pattern, $config, $match)) {
        $db_image_path = $match[1];
      } else {
        $error .= ($error != '' ? "<br>" :'').$lC_Language->get('upgrade_step1_err_noimage');
      }
      
      unset($config);

      if ($error == '') {
        require_once('../includes/classes/database/mysqli.php');
        $class = 'lC_Database_mysqli';
        $source_db = new $class($db_server, $db_username, $db_password);

        if ($source_db->isError() === false) {
          $source_db->selectDatabase($db_database);
        }

        if ($source_db->isError()) {
          $error .= ($error != '' ? "<br>" :'').$source_db->getError();
        }
      }
      
      $sourceInfo = null;

      if ($error == '') {
        $sourceInfo = array('source_path' => $cre_path,'source_dbase' => $db_database);

        $sql = "SELECT admin_firstname, admin_lastname FROM admin WHERE admin_groups_id = 1 ORDER BY admin_id";
        $sqlRS = $source_db->query($sql);
        $sqlRS->execute();
        if ($sqlRS->numberOfRows() > 0) {
          $sqlRS->next();
          $sourceInfo['admin'] = $sqlRS->value('admin_firstname').' '.$sqlRS->value('admin_lastname');
        }	
        $sqlRS->freeResult();

        $sql = "SELECT configuration_value FROM configuration WHERE configuration_key = 'STORE_NAME' ";
        $sqlRS = $source_db->query($sql);
        $sqlRS->execute();
        if ($sqlRS->numberOfRows() > 0) {
          $sqlRS->next();
          $sourceInfo['store_name'] = $sqlRS->value('configuration_value');
        }	
        $sqlRS->freeResult();
      }

      if ($error == "") {
        $page_title_text = $lC_Language->get('upgrade_step1_page_title_confirm');
        $page_description_text = $lC_Language->get('upgrade_step1_page_desc_confirm');
        $form_action = "upgrade.php?step=2";
      }
    }	
    // var_dump($sourceInfo);
  }       
}
?>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/xmlhttp.js"></script>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/autocomplete.js"></script>

<form class="block wizard-enabled" name="upgrade" id="upgradeForm" action="<?php echo $form_action; ?>" method="post" onsubmit="return true;">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="hide-on-mobile"><span class="wizard-step">1</span><?php echo $lC_Language->get('upgrade_nav_text_1'); ?></li>
    <li class="active"><span class="wizard-step">2</span><?php echo $lC_Language->get('upgrade_nav_text_2'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">3</span><?php echo $lC_Language->get('upgrade_nav_text_3'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span><?php echo $lC_Language->get('upgrade_nav_text_4'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span><?php echo $lC_Language->get('upgrade_nav_text_5'); ?></li>
  </ul>
  <?php
	  if($_POST['upgrade_method'] == 'F'){
  ?>
  <fieldset class="wizard-fieldset fields-list current active">
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('upgrade_step1_page_title'); ?></h4>
      <p><?php echo $lC_Language->get('upgrade_step1_page_desc'); ?></p>
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
          
    <div class="field-block button-height small-margin-top">
      <label class="label"><b><?php echo $lC_Language->get('param_database_file_path'); ?></b></label>
      <input type="text" name="FILE_PATH" id="FILE_PATH" value="localhost" class="input" style="width:93%;">
    </div>
      <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
      <a href="#" class="button" onclick="window.history.back();">
        <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
      </a>&nbsp;&nbsp;  
      <a href="javascript://" id="btn_checkfile" onclick="$('#mBox').hide(); $('#pBox').hide();" class="button">
        <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
      </a>
    </div>   
  </fieldset>
  <?php
	  }	else if($_POST['upgrade_method'] == 'D') {
  ?>
  <fieldset class="wizard-fieldset fields-list current active">
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('box_info_step_1_title_R'); ?></h4>
      <p><?php echo $lC_Language->get('box_info_step_1_text'); ?></p>
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
          
    <div class="field-block button-height small-margin-top large-margin-left large-margin-right">
      <label class="label"><b><?php echo $lC_Language->get('param_database_server'); ?></b></label>
      <input type="text" name="DB_SERVER" id="DB_SERVER" value="localhost" class="input" style="width:93%;">
    </div>
    <div class="field-block button-height large-margin-left large-margin-right">
      <label for="DB_SERVER_USERNAME" class="label"><b><?php echo $lC_Language->get('param_database_username'); ?></b>
      <input type="text" name="DB_SERVER_USERNAME" id="DB_SERVER_USERNAME" value="" class="input" style="width:93%;"></label>
    </div>
    <div class="field-block button-height large-margin-left large-margin-right">
      <label for="DB_SERVER_PASSWORD" class="label"><b><?php echo $lC_Language->get('param_database_password'); ?></b></label>
      <input type="password" name="DB_SERVER_PASSWORD" id="DB_SERVER_PASSWORD" value="" class="input" style="width:93%;">
    </div> 
    <div class="field-block button-height large-margin-left large-margin-right">
      <label for="DB_DATABASE" class="label"><b><?php echo $lC_Language->get('param_database_name'); ?></b></label>
      <input type="text" name="DB_DATABASE" id="DB_DATABASE" value="" class="input" style="width:93%;">
    </div>
    <div class="field-block button-height large-margin-left large-margin-right">
      <label for="DB_DATABASE_CLASS" class="label"><b><?php echo $lC_Language->get('param_database_type'); ?></b></label>
      <?php echo lc_draw_pull_down_menu('DB_DATABASE_CLASS', $db_table_types, null, 'class="input with-small-padding" style="width:96%;"'); ?>
    </div>
    <div class="field-block button-height large-margin-left large-margin-right">
      <label for="DB_TABLE_PREFIX" class="label"><b><?php echo $lC_Language->get('param_database_prefix'); ?></b></label>
      <input type="text" name="DB_TABLE_PREFIX" id="DB_TABLE_PREFIX" value="lc_" class="input" style="width:93%;">
    </div>      
      <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
      <a href="index.php" class="button">
        <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
      </a>&nbsp;&nbsp;  
      <a href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#installForm').submit();" class="button">
        <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
      </a>
    </div>   
  </fieldset>
  <?php
	  } else {
  ?>
  <fieldset class="wizard-fieldset fields-list current active">
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
		  if (!isset($sourceInfo)) {
    ?>
    <div id="inputContainer" style="display:block;">
    	<div class="field-block button-height small-margin-top">
    	  <label class="label"><b><?php echo $lC_Language->get('upgrade_step1_label'); ?></b></label>
    	  <input type="text" name="INSTALL_PATH" id="INSTALL_PATH" value="<?php echo $fs_www_root; ?>" class="input" style="width:93%;">
    	</div>
    	<div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
    	  <a href="index.php" class="button">
    	    <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
    	    <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
    	  </a>&nbsp;&nbsp;  
    	  <a href="javascript://" id="btn_checkpath" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#upgradeForm').submit();" class="button">
    	    <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
    	    <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
    	  </a>
    	</div>
    </div>
    <?php
		  } else {	
    ?>
    <div id="confirmContainer" style="clear:both; display:block;">
    	<div class="field-block button-height small-margin-top large-margin-left large-margin-right">
    	  <label class="label"><b><?php echo $lC_Language->get('param_upgrade_existing_store'); ?></b></label>
    	  <span id="exist_store_path"><?php echo $sourceInfo['source_path']; ?></span>
    	</div>
    	<div class="field-block button-height small-margin-top large-margin-left large-margin-right">
    	  <label class="label"><b><?php echo $lC_Language->get('param_upgrade_store_name'); ?></b></label>
    	  <span id="exist_store_name"><?php echo $sourceInfo['store_name']; ?></span>
    	</div>
    	<div class="field-block button-height small-margin-top large-margin-left large-margin-right">
    	  <label class="label"><b><?php echo $lC_Language->get('param_upgrade_store_admin'); ?></b></label>
    	  <span id="exist_store_admin"><?php echo $sourceInfo['admin']; ?></span>
    	</div>
    	<div class="field-block button-height small-margin-top large-margin-left large-margin-right">
    	  <label class="label"><b><?php echo $lC_Language->get('param_upgrade_store_dbase'); ?></b></label>
    	  <span id="exist_store_dbase"><?php echo $sourceInfo['source_dbase']; ?></span>
    	</div>
    	<div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
    	  <a href="index.php?step=1" class="button">
    	    <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
    	    <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
    	  </a>&nbsp;&nbsp;  
    	  <a href="javascript://" id="btn_checkpath" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#upgradeForm').submit();" class="button">
    	    <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
    	    <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
    	  </a>
    	</div>
    </div>
    <?php
	    }
    ?>
  </fieldset>
  <?php
	  }

    if ($error == "") {
      foreach ($_POST as $key => $value) {
        if ($key != 'SOURCE_SERVER' && $key != 'SOURCE_USER' && $key != 'SOURCE_PASS' && $key != 'SOURCE_DB' && $key != 'SOURCE_IMAGE_PATH') {
          if (is_array($value)) {
            for ($i=0, $n=sizeof($value); $i<$n; $i++) {
              echo lc_draw_hidden_field($key . '[]', $value[$i]);
            }
          } else {
            echo lc_draw_hidden_field($key, $value);
          }
        }
      }
      
      echo lc_draw_hidden_field('SOURCE_SERVER'    , $db_server) ;
      echo lc_draw_hidden_field('SOURCE_USER'      , $db_username) ;
      echo lc_draw_hidden_field('SOURCE_PASS'      , $db_password) ;
      echo lc_draw_hidden_field('SOURCE_DB'        , $db_database) ;
      echo lc_draw_hidden_field('SOURCE_IMAGE_PATH', $db_image_path) ;
    }
  ?>   
</form>
<script>
  $(document).ready(function() {
    <?php 
      if ($error != "") {
    ?>
	  $('#mBoxContents').html("<?php echo $error; ?>");
    $('#mBox').show();
    <?php
      }
    ?>	
  });
</script>	