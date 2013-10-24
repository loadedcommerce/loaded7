<?php
/*
  $Id: upgrade.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$db_table_types = array(array('id' => 'mysqli', 'text' => 'MySQL - MyISAM (Default)'),
                        array('id' => 'mysqli_innodb', 'text' => 'MySQL - InnoDB (Transaction-Safe)'));
?>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/xmlhttp.js"></script>
<style>
	.upgrade_option{
		padding : 20px;
		border: 1px solid gray;
		position: relative;
		text-align:justify;
		width: 500px;
		margin-bottom: 10px;
		margin: 0 auto;
	}
	.upgrade_option_title{
		line-height: 40px;
		font-size: 12pt;
		font-weight: bold;
	}
</style>
<form class="block wizard-enabled" name="upgrade" id="upgradeForm" action="upgrade.php?step=1" method="post" onsubmit="return true;">
	<input type="hidden" id='upgrade_method' name='upgrade_method' value="S">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="active"><span class="wizard-step">1</span><?php echo $lC_Language->get('upgrade_nav_text_1'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">2</span><?php echo $lC_Language->get('upgrade_nav_text_2'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">3</span><?php echo $lC_Language->get('upgrade_nav_text_3'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span><?php echo $lC_Language->get('upgrade_nav_text_4'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span><?php echo $lC_Language->get('upgrade_nav_text_5'); ?></li>
  </ul>

  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Database</legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('upgrade_main_page_title'); ?></h4>
      <p><?php echo $lC_Language->get('upgrade_main_page_desc'); ?></p>
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
          
    <div class="small-margin-top">
    	<div class="upgrade_option">
      <a href="javascript://" id="btn_checkpath" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#upgrade_method').val('S'); $('#upgradeForm').submit();">
        <span class="upgrade_option_title"><?php echo addslashes($lC_Language->get('upgrade_main_option_same')).'<br>'; ?></span>
        <?php echo ($lC_Language->get('upgrade_main_option_same_desc')); ?>
      </a>
      </div>
    </div>

    <div class="small-margin-top" style="display:block;">
    	<div class="upgrade_option">
      <a href="upgrade.php?step=1&utype=R" onclick="return false;">
        <span class="upgrade_option_title"><?php echo addslashes($lC_Language->get('upgrade_main_option_remote')).'<br>'; ?></span>
        <?php echo ($lC_Language->get('upgrade_main_option_remote_desc')); ?>
      </a>
      </div>
    </div>

    <div class="small-margin-top" style="display:block;">
    	<div class="upgrade_option">
      <a href="upgrade.php?step=1&utype=D" onclick="return false;">
        <span class="upgrade_option_title"><?php echo addslashes($lC_Language->get('upgrade_main_option_dbfile')).'<br>'; ?></span>
        <?php echo ($lC_Language->get('upgrade_main_option_dbfile_desc')); ?>
      </a>
      </div>
    </div>
  </fieldset>
</form>