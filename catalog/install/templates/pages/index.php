<?php
/*
  $Id: index.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$vInfo = explode('|', array_shift(array_values(preg_split('/\r\n|\r|\n/', file_get_contents('..//includes/version.txt'), 2))));
$version = $vInfo[0];
$ok = TRUE;
?>
<style>
.noticeBox { border:1px solid red; padding:5px; background-color:#fdecea; margin-bottom:10px; }
.info-block { border:1px solid #2964af; float:left; }
.info-block-title { background-color:#278dcb; color:white; padding:5px; }
.info-pane-contents { background-color:#dbeefa; padding:10px; }
/* .content-pane { margin-left:-32%; margin-right:20px; } */
.block-title { text-shadow:0 1px 0 black; } 
TD { height:19px; }
</style>
<form method="post" action="index.html" class="block wizard-enabled">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="active"><span class="wizard-step">1</span> Welcome</li>
    <li class="hide-on-mobile"><span class="wizard-step">2</span> Database</li>
    <li class="hide-on-mobile"><span class="wizard-step">3</span> Server</li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span> Settings</li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span> Finished!</li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Welcome</legend>
    <div id="languageContainer">
      <ul style="list-style-type: none; padding-right: 5px; margin: 0px; display: inline; float: right;">
        <li style="display: inline;"><?php echo $lC_Language->get('title_language'); ?></li>
        <?php
          foreach ($lC_Language->getAll() as $available_language) {
          ?>
          <li style="display: inline;"><?php echo '<a href="index.php?language=' . $available_language['code'] . '">' . $lC_Language->showImage($available_language['code']) . '</a>'; ?></li>
          <?php      
          }
        ?>
      </ul>
    </div>
    <div class="thin">
      <h3><?php echo sprintf($lC_Language->get('page_title_welcome'), $version); ?></h3>
    </div>
    <noscript>
      <div class="noticeBox">    
        <?php echo $lC_Language->get('error_javascript_disabled'); ?>
      </div>
    </noscript>  
    <?php
    if (file_exists(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php') && !is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php')) {
      @chmod(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php', 0777);
    }
    if (file_exists(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php') && !is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php')) {
      ?>
      <p class="message icon-warning red-gradient">   
        <span class="stripes animated"></span>
        <?php echo sprintf($lC_Language->get('error_configuration_file_not_writeable'), lc_realpath(dirname(__FILE__) . '/../../../includes') . '/config.php'); ?>
        <?php echo '<br /><br />' . $lC_Language->get('error_configuration_file_alternate_method'); ?>        
      </p>    
      <?php
    }
    ?> 
    <div class="left-column-200px">
      <div class="left-column">
        <div class="block">
          <h4 class="block-title green-gradient glossy"><?php echo $lC_Language->get('box_server_title'); ?></h4>
          <div style="padding: 0 7px 7px 7px;">

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><b><?php echo $lC_Language->get('box_server_php_version'); ?></b></td>
                <td align="right"><?php echo phpversion(); ?></td>
                <?php if (version_compare(PHP_VERSION, '5.3') !== 1) $ok = FALSE; ?>
                <td align="right" width="25"><img src="templates/img/icons/<?php echo ((version_compare(PHP_VERSION, '5.3') === 1) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
            </table><br />
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><b><?php echo $lC_Language->get('box_server_php_settings'); ?></b></td>
                <td align="right"></td>
                <td align="right" width="25"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_register_globals'); ?></td>
                <td align="right"><?php echo (((int)ini_get('register_globals') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('register_globals') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('register_globals') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_magic_quotes'); ?></td>
                <td align="right"><?php echo (((int)ini_get('magic_quotes') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('magic_quotes') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('magic_quotes') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_file_uploads'); ?></td>
                <td align="right"><?php echo (((int)ini_get('file_uploads') !== 1) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('file_uploads') !== 1) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('file_uploads') === 1) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_session_auto_start'); ?></td>
                <td align="right"><?php echo (((int)ini_get('session.auto_start') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('session.auto_start') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('session.auto_start') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_session_use_trans_sid'); ?></td>
                <td align="right"><?php echo (((int)ini_get('session.use_trans_sid') === 0) ? $lC_Language->get('box_server_off') : $lC_Language->get('box_server_on')); ?></td>
                <?php if ((int)ini_get('session.use_trans_sid') !== 0) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('session.use_trans_sid') === 0) ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_post_max_size'); ?></td>
                <td align="right"><?php echo $lC_Language->get('post_max_size_text'); ?></td>
                <?php if ((int)ini_get('post_max_size') < 10) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('post_max_size') < 10) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_upload_max_filesize'); ?></td>
                <td align="right"><?php echo $lC_Language->get('upload_max_filesize_text'); ?></td>
                <?php if ((int)ini_get('upload_max_filesize') < 10) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (((int)ini_get('upload_max_filesize') < 10) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
            </table><br />
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><b><?php echo $lC_Language->get('box_server_php_extensions'); ?></b></td>
                <td align="right" width="25"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_mysqli'); ?></td>
                <?php if (!extension_loaded('mysqli')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('mysqli') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_gd'); ?></td>
                <?php if (!extension_loaded('gd')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('gd') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_curl'); ?></td>
                <?php if (!extension_loaded('curl')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('curl') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
              <tr>
                <td><?php echo $lC_Language->get('box_server_openssl'); ?></td>
                <?php if (!extension_loaded('openssl')) $ok = FALSE; ?>
                <td align="right"><img src="templates/img/icons/<?php echo (extension_loaded('openssl') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
              </tr>
            </table>
          </div>
        </div> 
      </div>
      <div class="right-column">
        <div class="content-pane">
          <p><?php echo $lC_Language->get('text_welcome'); ?></p>
          <h4 class="no-margin-top mid-margin-bottom"><?php echo $lC_Language->get('page_heading_server_requirements'); ?></h4>
          <ul>
            <li>PHP v5.3+ (with MySQLi extension)</li>
            <li>MySQL v4.1.13+ or v5.0.7+</li>
          </ul>
          <h4 class="no-margin-top mid-margin-bottom"><?php echo $lC_Language->get('page_heading_permissions'); ?></h4>
          <table id="permissionsTable" border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>        
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../includes/work/')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../includes/work/')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../includes/work/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">includes/work/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>           
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../images')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../images')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../images/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">images/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>            
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/backups')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../admin/backups')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../admin/backups/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">admin/backups/</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>  
            <tr>
              <?php $writeable = (is_dir(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/graphs')) && is_writeable(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/graphs')) && substr(sprintf('%o', fileperms(lc_realpath(dirname(__FILE__) . '/../../../admin/includes/graphs/'))), -4) >= "0755") ? TRUE : FALSE; ?>
              <td width="23px" align="right"><img src="templates/img/icons/<?php echo ($writeable) ? 'tick.gif' : 'cross.gif'; ?>" border="0" width="16" height="16"></td>
              <td style="padding-left:5px;">admin/includes/graphs</td>
              <td align="left"><?php echo ($writeable) ? $lC_Language->get('box_server_writeable') : $lC_Language->get('box_server_not_writeable'); ?></td>
              <?php if (!$writeable) $ok = FALSE; ?>
            </tr>                           
          </table>
          <!-- p class="message icon-warning margin-top margin-right" style="color:#c09853; background:#fcf8e3; border-color:#fbeed5;">   
            <span class="stripes animated"></span>
            <?php //echo sprintf($lC_Language->get('text_under_development'), $version); ?>
          </p -->   
        </div>
      </div>
      <?php
      if ($ok) {
        ?>
        <!-- div id="buttonContainer" class="large-margin-top margin-left" style="float:left">
          <a href="javascript:void(0);" class="button" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#upgrade_method').val('S'); window.location.href = 'upgrade.php?step=1'">
            <span class="button-icon orange-gradient glossy"><span class="icon-cloud-upload"></span></span>
            <?php //echo addslashes($lC_Language->get('image_button_upgrade')); ?>
          </a>          
        </div -->
        <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
          <a href="install.php" class="button">
            <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
            <?php echo addslashes($lC_Language->get('image_button_install')); ?>
          </a>
        </div>
        <?php          
      } else {
        ?>
        <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
          <a href="index.php" class="button">
            <span class="button-icon red-gradient glossy"><span class="icon-refresh"></span></span>
            <?php echo addslashes($lC_Language->get('image_button_retry')); ?>
          </a>
        </div>
        <?php 
      }
      ?>
    </div>    
  </fieldset>  
</form>