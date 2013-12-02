<?php
/**
  $Id: updates.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/   
$checkArr = lC_Updates_Admin::hasUpdatesAvailable();  
$backupArr = lC_Updates_Admin::getBackups();
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin small-margin-bottom">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  #versionContainer .fieldset { padding-bottom:1px; }
  #versionContainer .legend { font-weight:bold; font-size: 1.1em; }
  #versionContainer .update-text { text-align:center; }
  #versionContainer .cancel-text { text-align:center; margin-top:20px; font-size:.9em; }
  #versionContainer .buttonset { text-align:center; padding-top:5px; padding-bottom:10px; }
  #versionContainer table { width:100%; margin-top:-14px; margin-bottom:19px; }
  #versionContainer table th { width:33%; padding:5px; font-weight:normal; font-size:1.1em; }
  #versionContainer table td { width:33%; padding:5px; font-size:1.1em; }
  #versionContainer table .before { text-align:left; }
  #versionContainer table .version { font-size:16px; text-align:center; font-weight:bold; font-size:2em; }
  #versionContainer table .after { text-align:right; }
  #versionContainer table .green { color:#009900; }
  #versionContainer table .red { color:#CC0000; } 
  #versionContainer table .hr{ background-color:#ccc; height:1px; border:0px; margin:10px 0 12px 0;} 
  </style>
  
  <div id="blockContainer" class="columns small-margin-left large-margin-right">

    <div id="versionContainer" class="six-columns twelve-columns-tablet">
      <fieldset class="fieldset <?php echo ((isset($checkArr['hasUpdates']) && (int)$checkArr['hasUpdates'] > 0)) ? 'orange-gradient' : null; ?>">
        <legend class="legend"><?php echo $lC_Language->get('heading_legend_version_info'); ?></legend>
        <table id="version-table">
          <thead>
            <tr>
              <th class="before"><?php echo $lC_Language->get('text_current_version'); ?></th>
              <th class="version"><?php echo utility::getVersion(); ?></th>
              <th class="after"><?php echo sprintf($lC_Language->get('text_released'), utility::getVersionDate()); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="3"><div class="hr"></div></td></tr>
            <tr>
              <td class="before"><?php echo $lC_Language->get('text_latest_version'); ?></td>
              <td class="version"><?php echo $checkArr['toVersion']; ?></td>
              <td class="after"><?php echo sprintf($lC_Language->get('text_released'), $checkArr['toVersionDate']); ?></td>
            </tr>  
          </tbody>            
        </table>
        <p id="vFooterText" style="display:none; text-align:center; margin-bottom:20px; margin-top:-10px"></p>
      </fieldset> 
      <p id="updateText" class="update-text big-text"><?php echo ((isset($checkArr['hasUpdates']) && (int)$checkArr['hasUpdates'] > 0)) ? $lC_Language->get('text_update_avail') : $lC_Language->get('text_up_to_date'); ?></p>
      <p id="updateButtonset" class="buttonset">
        <?php 
        if ((isset($checkArr['hasUpdates']) && (int)$checkArr['hasUpdates'] > 0)) {
          ?>
          <a id="install-update" href="javascript:void(0);" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onclick="installUpdate();"'); ?> class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
            <span class="button-icon green-gradient glossy"><span class="icon-down-fat"></span></span>
            <?php echo $lC_Language->get('button_install_update'); ?>
          </a>
          <?php 
        } else {
          ?>
          <a id="check-again" href="javascript:void(0);" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 1) ? NULL : 'onclick="checkForUpdates();"'); ?> class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 1) ? ' disabled' : NULL); ?>">
            <span class="button-icon green-gradient glossy"><span class="icon-cloud-upload"></span></span>
            <?php echo $lC_Language->get('button_check_again'); ?>
          </a>
          <?php
        }
        ?>      
      </p>
    </div>

    <style>
    #toolsContainer .legend { font-weight:bold; font-size: 1.1em; }
    #toolsContainer table { width:100%; margin-top:-14px; }
    </style>    
    <div id="toolsContainer" class="six-columns twelve-columns-tablet">
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('heading_legend_tools'); ?></legend>
        <table id="toolsButtonSet">
          <tr><td colspan="3"><span id="checkedLoader" class="loader"></span><span id="lastCheckedContainer"></span></td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td align="center">
              <a id="reinstall" href="javascript:void(0);" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onclick="installUpdate(\'full\');"'); ?> class="button icon-redo orange-gradient glossy re-install<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
                <span class="hide-on-mobile-portrait"><?php echo $lC_Language->get('button_reinstall_update'); ?></span>
              </a>               
            </td>
            <td align="center">
              <a id="undo" href="javascript:void(0);" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : (is_array($backupArr) && count($backupArr) == 0) ? NULL : 'onclick="undoUpdate();"'); ?> class="button icon-undo undo-last red-gradient glossy<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : (is_array($backupArr) && count($backupArr) == 0) ? ' disabled' : NULL); ?>">
                <span class="hide-on-mobile-portrait"><?php echo $lC_Language->get('button_undo_last_update'); ?></span>
              </a>                                                                                                                                                
            </td>
          </tr>
        </table>
      </fieldset>    
      
      <fieldset class="fieldset">
        <legend class="legend"><?php echo $lC_Language->get('heading_legend_manual'); ?></legend>
        <table id="manualButtonSet" style="margin-top:-4px;">
          <tr>
            <td align="left" id="td-download" class="strong">1)
              <a id="download" href="#" class="button icon-download download-zip blue-gradient glossy small-margin-left<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>">
                <span class="hide-on-mobile-portrait"><?php echo $lC_Language->get('button_download_zip'); ?></span>
              </a>
            </td>
            <td align="center" class="strong" style="padding-top:7px;">2) FTP Files</td>
            <td align="right" id="td-runafter" class="strong">3)
              <a id="runafter" href="javascript:void(0);" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onclick="updateDatabase();"'); ?> class="button icon-database update-db blue-gradient glossy small-margin-left<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
                <span class="hide-on-mobile-portrait"><?php echo $lC_Language->get('button_update_database'); ?></span>
              </a>
            </td>                      
          </tr>
        </table>
      </fieldset>       
    </div>
    <style>
    #historyContainer .legend { font-weight:bold; font-size: 1.1em; }
    .dataColAction { text-align: left; }
    .dataColResult { text-align: left; }
    .dataColUser { text-align: left; }
    .dataColTime { text-align: left; }
    </style>  
    <div id="historyContainer" class="twelve-columns-tablet">
      <fieldset class="fieldset">
        <legend class="legend no-margin-bottom"><?php echo $lC_Language->get('heading_legend_history'); ?></legend>
        <form name="batch" id="batch" action="#" method="post">
          <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table" id="dataTable">
            <thead>
              <tr>
                <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_action'); ?></th>
                <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_result'); ?></th>
                <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_user'); ?></th>
                <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_timestamp'); ?></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4">&nbsp;</th>
              </tr>
            </tfoot>
          </table>
        </form>
        <style>
        .dataTables_wrapper { background:none; box-shadow: 0 0 0 0 #fff inset, 0 0 0 rgba(255, 255, 255, 0.35) inset; }
        .dataTables_paginate { position: absolute; right:0; bottom:-6px; }
        .paginate_disabled_previous, .paginate_enabled_previous, .paginate_disabled_next, .paginate_enabled_next { background-image: none; border: none; box-shadow: none; color: #0059a0; text-shadow: none; }
        .paginate_enabled_previous:hover, .paginate_enabled_next:hover { background: none; color: #0689f1; }
        .paginate_disabled_previous, .paginate_disabled_next { color: #999 !important }   
        </style>
      </fieldset>    
    </div>
 
  </div>
</section>

<style>
#updateProgressContainer { margin-left:5%; }
#updateProgressContainer > div { margin: 0 0 10px 0; }
#updateProgressContainer .update-text {  margin-left:10px; }
</style>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- Main content end -->