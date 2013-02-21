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
/*
$findDataArr = lC_Updates_Admin::findAvailablePackages('7.0'); 
$availInfoDataArr = lC_Updates_Admin::getAvailablePackageInfo('0'); 
$downloaded = lC_Updates_Admin::downloadPackage(); 
$localPackageExists = lC_Updates_Admin::localPackageExists(); 
$getPackageInfo = lC_Updates_Admin::getPackageInfo(); 
$getPackageContents = lC_Updates_Admin::getPackageContents(); 
$findPackageContents = lC_Updates_Admin::findPackageContents('osc'); 
*/
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
          <a id="install-update" href="javascript://" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onclick="installUpdate();"'); ?> class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
            <span class="button-icon green-gradient glossy"><span class="icon-down-fat"></span></span>
            <?php echo $lC_Language->get('button_install_update'); ?>
          </a>
          <?php 
        } else {
          ?>
          <a id="check-again" href="javascript://" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 1) ? NULL : 'onclick="checkForUpdates();"'); ?> class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 1) ? ' disabled' : NULL); ?>">
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
          <tr><td colspan="3"><span class="loader"></span><span id="lastCheckedContainer"></span></td></tr>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td align="left">
              <a id="reinstall" href="javascript://" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onclick="installUpdate(\'full\');"'); ?> class="button re-install<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
                <span class="button-icon orange-gradient glossy"><span class="icon-redo"></span></span>
                <?php echo $lC_Language->get('button_reinstall_update'); ?>
              </a>               
            </td>
            <td align="center">
              <a id="download" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? 'href="#"' : 'href="https://github.com/loadedcommerce/loaded7/archive/' . $checkArr['toVersion'] . '.zip" '); ?> class="button download-zip<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>">
                <span class="button-icon blue-gradient glossy"><span class="icon-download"></span></span>
                <?php echo $lC_Language->get('button_download_zip'); ?>
              </a>
            </td>
            <td align="right">
              <a id="undo" href="javascript://" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : ((!file_exists(DIR_FS_WORK . 'updates/full-file-backup.zip')) ? NULL : 'onclick="undoUpdate();"')); ?> class="button undo-last<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : ((!file_exists(DIR_FS_WORK . 'updates/full-file-backup.zip')) ? ' disabled' : NULL)); ?>">
                <span class="button-icon red-gradient glossy"><span class="icon-undo"></span></span>
                <?php echo $lC_Language->get('button_undo_last_update'); ?>
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
        <script>
        $(document).ready(function() {
          var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getHistory&media=MEDIA'); ?>';   
          oTable = $('#dataTable').dataTable({
            "bProcessing": true,
            "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "aaSorting": [[3,'desc']],
            "aoColumns": [{ "sWidth": "20%", "bSortable": true, "sClass": "dataColAction" },
                          { "sWidth": "40%", "bSortable": true, "sClass": "dataColResult hide-on-mobile-portrait" },
                          { "sWidth": "15%", "bSortable": true, "sClass": "dataColUser" },
                          { "sWidth": "25%", "bSortable": true, "sClass": "dataColTime hide-on-mobile-portrait" }]
          });
          $('#dataTable').responsiveTable();
        });        
        
        </script>

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
<script>
$(document).ready(function() {
  checkForUpdates();  
});

function updateHistoryLog() {
  // write to the update history log
  __writeHistory('<?php echo $lC_Language->get('text_history_action_backup'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_download'), $checkArr['toVersion']); ?>');
  oTable.fnReloadAjax(); 
  
  return true; 
}

function checkForUpdates() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }  
  $('#lastCheckedContainer').empty();
  $('.loader').show();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=hasUpdates'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      $('.loader').hide();
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $('#lastCheckedContainer').html(data.lastChecked);
      $('#version-table th.version').html('<?php echo utility::getVersion(); ?>');
      $('#version-table th.after').html('<?php echo sprintf($lC_Language->get('text_released'), utility::getVersionDate()); ?>');
      $('#version-table td.version').html('<?php echo $checkArr['toVersion']; ?>');
      $('#version-table td.after').html('<?php echo sprintf($lC_Language->get('text_released'), $checkArr['toVersionDate']); ?>');  
      if (data.hasUpdates == true) {
        $('#versionContainer .fieldset').addClass('orange-gradient');
        $('#version-table thead').removeClass('green').addClass('red');
        $('#version-table tbody').removeClass('green').addClass('red');
        $('#updateText').html('<?php echo $lC_Language->get('text_update_avail'); ?>');
        $('#updateButtonset').html('<a id="install-update" href="javascript://" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onclick="installUpdate();"'); ?> class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>"><span class="button-icon green-gradient glossy"><span class="icon-down-fat"></span></span><?php echo $lC_Language->get('button_install_update'); ?></a>');
      } else {
        $('#versionContainer .fieldset').removeClass('orange-gradient');
        $('#version-table thead').removeClass('red').addClass('green');
        $('#version-table tbody').removeClass('red').addClass('green');
        $('#updateText').html('<?php echo $lC_Language->get('text_up_to_date'); ?>');
        $('#updateButtonset').html('<a id="check-again" href="javascript://" <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 1) ? NULL : 'onclick="checkForUpdates();"'); ?> class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 1) ? ' disabled' : NULL); ?>"><span class="button-icon green-gradient glossy"><span class="icon-cloud-upload"></span></span><?php echo $lC_Language->get('button_check_again'); ?></a>');
      }
    }
  );  
}

function installUpdate(t) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var confirmText = (t != undefined && t == 'full') ? '<?php echo $lC_Language->get('text_confirm_full_update');?>' : '<?php echo $lC_Language->get('text_confirm_update');?>';
  $.modal.confirm(confirmText, function() {
    // set maint mode=on
    __setMaintenanceMode('on');
    
    var fromVersion = '<?php echo utility::getVersion(); ?>';
    var toVersion = '<?php echo $checkArr['toVersion']; ?>';
    $('#versionContainer .fieldset').removeClass('orange-gradient');
    $('#version-table tbody').removeClass('green').removeClass('red');
    $('#version-table > tbody').empty();
    $('#version-table').css("margin-bottom", "10px");
    $('#version-table > thead').html('<tr><td class="before"><?php echo $lC_Language->get('text_latest_version'); ?></td><td class="version">' + toVersion + '</td><td class="after"><?php echo sprintf($lC_Language->get('text_released'), utility::getVersionDate()); ?></td></tr>').addClass('red'); 
    $('#version-table > tbody').html('<tr><td colspan="3"><span id="updateProgressContainer" style="display:none;"></span></td></tr>');  
    // start the update process
    $('#updateButtonset').slideUp();
    $('.update-text').html('<p><?php echo $lC_Language->get('text_initializing'); ?></p>').attr('style', 'text-align:center').blink({ maxBlinks: 5, blinkPeriod: 1000 });
    
    setTimeout(function() { 
      __setup(); 
      __showStep(1,0);
      $('#vFooterText').html(__cancelBlock()).show();
      
      // backup the database
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doDBBackup'); ?>';
      $.getJSON(jsonLink,   
      function (data) {
        if (data.rpcStatus == -10) { // no session
          __showStep(1,2);
          __setMaintenanceMode('off');
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }       
        if (data.rpcStatus != 1) {
          __showStep(1,2);
          
          // write to the update history log
          __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_error'), $checkArr['toVersion']); ?>');
          oTable.fnReloadAjax();
                                    
          __setMaintenanceMode('off');
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }
        __showStep(1,1);
        __showStep(2,0);

        // full file backup
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doFullFileBackup'); ?>'
        $.getJSON(jsonLink,        
          function (cData) {
            if (cData.rpcStatus != 1) {
              __showStep(2,2);
              
              // write to the update history log
              __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_error'), $checkArr['toVersion']); ?>');
              oTable.fnReloadAjax();
                                        
              __setMaintenanceMode('off');
              $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
              return false;
            }
            __showStep(2,1);
            __showStep(3,0);

              // download the update package
            var version = '<?php echo $checkArr['toVersion']; ?>';
            var type = (t != undefined) ? t : null;
            var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getUpdatePackage&version=VERSION&type=TYPE'); ?>';
            $.getJSON(jsonLink.replace('VERSION', version).replace('TYPE', type),            
              function (dData) {
                if (dData.rpcStatus != 1) {
                  __showStep(3,2);
                  
                  // write to the update history log
                  __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_error'), $checkArr['toVersion']); ?>');
                  oTable.fnReloadAjax();
                                            
                  __setMaintenanceMode('off');
                  $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                  return false;
                }
                __showStep(3,1);
                __showStep(4,0);
                  
                // prepare the contents
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getContents'); ?>';
                $.getJSON(jsonLink,                
                  function (dData) {
                    if (dData.rpcStatus != 1) {
                      __showStep(4,2);
                      
                      // write to the update history log
                      __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_error'), $checkArr['toVersion']); ?>');
                      oTable.fnReloadAjax();
                                                
                      __setMaintenanceMode('off');
                      $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                      return false;
                    }
                    __showStep(4,1);
                    __showStep(5,0);
                    
                    // apply the update
                    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=installUpdate'); ?>';
                    $.getJSON(jsonLink,                
                      function (dData) {
                        if (dData.rpcStatus != 1) {
                          __showStep(5,2);

                          // write to the update history log
                          __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_error'), $checkArr['toVersion']); ?>');
                          oTable.fnReloadAjax();                          

                          __setMaintenanceMode('off');
                          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                          return false;
                        }
                        __showStep(5,1);
                        __showStep(99,1);
                        $('#vFooterText').html(__okBlock());
                        $('#version-table thead').removeClass('red').addClass('green');
                        
                        // write to the update history log
                        __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_success'), $checkArr['toVersion']); ?>');
                        oTable.fnReloadAjax(); 
                              
                        // set maint mode=off
                        __setMaintenanceMode('off');                      
                      }
                    );                  
                  }
                );                
              }
            );             
          }
        );        
      });     
    }, 3000);
  }, function() {
    return false;
  });  
}

function undoUpdate() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }  
  $.modal.confirm('<?php echo $lC_Language->get('text_confirm_undo');?>', function() {
    // set maint mode=on
    __setMaintenanceMode('on');
    
    var fromVersion = '<?php echo utility::getVersion(); ?>';
    var toVersion = '<?php echo $checkArr['toVersion']; ?>';  
    $('#versionContainer .fieldset').removeClass('orange-gradient');
    $('#version-table tbody').removeClass('green').removeClass('red');
    $('#version-table > tbody').empty();  
    $('#version-table > tbody').empty();
    $('#version-table').css("margin-bottom", "10px");
    $('#version-table > thead').html('<tr><td class="before"><?php echo $lC_Language->get('text_latest_version'); ?></td><td class="version">' + toVersion + '</td><td class="after"><?php echo sprintf($lC_Language->get('text_released'), utility::getVersionDate()); ?></td></tr>').addClass('red'); 
    $('#version-table > tbody').html('<tr><td colspan="3"><span id="updateProgressContainer" style="display:none;"></span></td></tr>');  
    $('#updateButtonset').slideUp();
    $('.update-text').html('<p><?php echo $lC_Language->get('text_initializing'); ?></p>').attr('style', 'text-align:center').blink({ maxBlinks: 5, blinkPeriod: 1000 });

    setTimeout(function() { 
      __setup(); 
      __showUndoStep(1,0);
      $('#vFooterText').html(__cancelBlock()).show();
      
      // restore files
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doFullFileRestore'); ?>';
      $.getJSON(jsonLink,   
      function (data) {
        if (data.rpcStatus == -10) { // no session
          __showUndoStep(1,2);
          __setMaintenanceMode('off');
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }      
        if (data.rpcStatus != 1) {
          __showUndoStep(1,2);
          
          // write to the update history log
          __writeHistory('<?php echo $lC_Language->get('text_history_action_undo'); ?>', '<?php echo $lC_Language->get('text_history_result_undo_error'); ?>');
          oTable.fnReloadAjax();
                        
          __setMaintenanceMode('off');
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }
        __showUndoStep(1,1);
        __showUndoStep(2,0);

        // restore DB
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doDBRestore'); ?>'
        $.getJSON(jsonLink,        
          function (cData) {
            if (cData.rpcStatus != 1) {
              __showUndoStep(2,2);
              
              // write to the update history log
              __writeHistory('<?php echo $lC_Language->get('text_history_action_undo'); ?>', '<?php echo $lC_Language->get('text_history_result_undo_error'); ?>');
              oTable.fnReloadAjax();
                          
              __setMaintenanceMode('off');
              $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
              return false;
            }
            __showUndoStep(2,1);
            __showUndoStep(99,1);

            $('#vFooterText').html(__okBlock());
            $('#version-table thead').removeClass('green').addClass('red');                
            $('#version-table > thead').html('<tr><td class="before"><?php echo $lC_Language->get('text_current_version'); ?></td><td class="version">' + fromVersion + '</td><td class="after"><?php echo sprintf($lC_Language->get('text_released'), utility::getVersionDate()); ?></td></tr>').addClass('red'); 
                  
            // write to the update history log
            __writeHistory('<?php echo $lC_Language->get('text_history_action_undo'); ?>', '<?php echo $lC_Language->get('text_history_result_undo_success'); ?>');
            oTable.fnReloadAjax(); 
                                          
            // set maint mode=off
            __setMaintenanceMode('off');  
   
          }
        );        
      });     
    }, 3000); 
  }, function() {
    return false;
  });      
}

function __showStep(step, fini) {
  var loader = '<span class="icon-right icon-blue margin-left margin-right"></span><span class="loader"></span>';
  var done = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-tick icon-green margin-left margin-right"></span>';
  var error = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-cross icon-red margin-left margin-right"></span>';
  var html1 = '<span class="update-text"><?php echo $lC_Language->get('text_step_1'); ?></span>';
  var html2 = '<span class="update-text"><?php echo $lC_Language->get('text_step_2'); ?></span>';
  var html3 = '<span class="update-text"><?php echo $lC_Language->get('text_step_3'); ?></span>';
  var html4 = '<span class="update-text"><?php echo $lC_Language->get('text_step_4'); ?></span>';
  var html5 = '<span class="update-text"><?php echo sprintf($lC_Language->get('text_step_5'), $checkArr['toVersion']); ?></span>';
  var successHtml = '<span class="update-text"><?php echo $lC_Language->get('text_step_success'); ?></span>';
  var errorHtml = '<span class="update-text"><?php echo $lC_Language->get('text_step_error'); ?></span>';
                
  if (step == 1) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 +  '</div>');
    } else if (fini == 2) {
      $('#updateProgressContainer').html('<div>' + error + html1 +  '</div>');
    } else {
      $('#updateProgressContainer').html('<div>' + loader + html1 + '</div>');  
    }
  }  
  
  if (step == 2) {
    if (fini == 1) {
      $('#updateProgressContainer div:last').html(done + html2);
    } else if (fini == 2) { 
      $('#updateProgressContainer div:last').html(error + html2);
    } else {
      $('#updateProgressContainer').append('<div>' + loader + html2 + '</div>');
    }
  }   
  
  if (step == 3) {
    if (fini == 1) {
      $('#updateProgressContainer div:last').html(done + html3);
    } else if (fini == 2) {
      $('#updateProgressContainer div:last').html(error + html3);
    } else { 
      $('#updateProgressContainer').append('<div>' + loader + html3 + '</div>');
    }
  }   
  
  if (step == 4) {
    if (fini == 1) {
      $('#updateProgressContainer div:last').html(done + html4);
    } else if (fini == 2) {
      $('#updateProgressContainer div:last').html(error + html4);
    } else { 
      $('#updateProgressContainer').append('<div>' + loader + html4 + '</div>');
    }
  }    
  
  if (step == 5) {
    if (fini == 1) {
      $('#updateProgressContainer div:last').html(done + html5);
    } else if (fini == 2) {
      $('#updateProgressContainer div:last').html(error + html5);
    } else { 
      $('#updateProgressContainer').append('<div>' + loader + html5+ '</div>');
    }
  } 
  
  if (step == 99) {  // success
    $('#updateProgressContainer').append('<div>' + done + successHtml + '</div>');
  } 
  
  if (step == -1) {  // error
    $('#updateProgressContainer').append('<div>' + done + errorHtml + '</div>');
  }   
  
  return true;
}   

function __showUndoStep(step, fini) {
  var loader = '<span class="icon-right icon-blue margin-left margin-right"></span><span class="loader"></span>';
  var done = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-tick icon-green margin-left margin-right"></span>';
  var error = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-cross icon-red margin-left margin-right"></span>';
  var html1 = '<span class="update-text"><?php echo $lC_Language->get('text_undo_step_1'); ?></span>';
  var html2 = '<span class="update-text"><?php echo $lC_Language->get('text_undo_step_2'); ?></span>';
  var successHtml = '<span class="update-text"><?php echo $lC_Language->get('text_undo_step_success'); ?></span>';
  var errorHtml = '<span class="update-text"><?php echo $lC_Language->get('text_undo_step_error'); ?></span>';
                
  if (step == 1) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 +  '</div>');
    } else if (fini == 2) {
      $('#updateProgressContainer').html('<div>' + error + html1 +  '</div>');
    } else {
      $('#updateProgressContainer').html('<div>' + loader + html1 + '</div>');  
    }
  }  
  
  if (step == 2) {
    if (fini == 1) {
      $('#updateProgressContainer div:last').html(done + html2);
    } else if (fini == 2) { 
      $('#updateProgressContainer div:last').html(error + html2);
    } else {
      $('#updateProgressContainer').append('<div>' + loader + html2 + '</div>');
    }
  }   
  
  if (step == 99) {  // success
    $('#updateProgressContainer').append('<div>' + done + successHtml + '</div>');
  } 
  
  if (step == -1) {  // error
    $('#updateProgressContainer').append('<div>' + done + errorHtml + '</div>');
  }   
  
  return true;
}  

function __setup() {
  $('.update-text').empty();
  $('#version-ul').addClass('margin-bottom');
  $('#updateProgressContainer').delay(500).slideDown('slow');
}

function __cancelBlock() {
  return '<span class="cancel-text intro"><?php echo $lC_Language->get('text_warning_do_not_interrupt'); ?></span>';
}

function __okBlock() {
  return '<span class="buttonset large-margin-top"><a id="ok" href="javascript://" onclick="location.reload(true); mask();" class="button ok"><span class="button-icon green-gradient glossy"><span class="icon-tick"></span></span><?php echo $lC_Language->get('button_ok'); ?></a></span>';
}

function __setMaintenanceMode(s) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=setMaintMode&s=MODE'); ?>'
  $.getJSON(jsonLink.replace('MODE', s),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
    }
  );  
}

function __writeHistory(ua, ur) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=writeHistory&ua=ACTION&ur=RESULT'); ?>'
  $.getJSON(jsonLink.replace('ACTION', ua).replace('RESULT', ur),
    function (data) {

    }
  );  
}
</script>