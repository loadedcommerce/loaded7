<?php
/*
  $Id: main.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require_once('includes/applications/updater/classes/updater.php');  

$updateArr = lC_Updater_Admin::getUpdateData(); 

// clean & format the date
$lastChecked = @urldecode($updateArr['data']['params']['ProB2B']['lastUpdateCheck']);
$dlUrl = $updateArr['data']['params']['ProB2B']['downloadLink'];
$nextVersion = $updateArr['data']['params']['ProB2B']['nextVersion'];
$lastCheckedArr = explode(" ", $lastchecked);
$lastCheckedDate = $lastCheckedArr[0]; 
$lastCheckedTime = $lastCheckedArr[1]; 

//echo "<pre>";
//print_r($updateArr);
//echo "</pre>"; 
?>
<style>
.dataColModule { text-align: left; } 
.dataColAction { text-align: right; }  
.warning{ height: 36px; padding: 3px; border: 1px solid #e2b709; color: #000; background-color: #ffe57e;}
.tPad { padding-top:10px; }
.tPad2 { padding-top:15px; }
.lMar { margin-left:5px; }
.dataTables_processing { margin-top:-15px !important; }
</style>
<div class="pageContainer">
  <div><h1><?php echo $lC_Template->getPageTitle(); ?></h1></div>
  <div class="status"><?php echo lc_status(); ?></div>
  <div id="coreUpdateContainer">
    <div id="updateCheckContainer">
      <table border="0" cellspacing="0" cellpadding="0" id="updateCoreTable">
        <tr>  
          <td><span id="updateCheckText"><?php echo $lC_Language->get('text_last_checked') . ' ' . lC_DateTime::getLong($lastChecked, TRUE); ?></span></td>
          <td style="padding-left:5px;"><button onclick="getUpdateData('CORE');" id="checkButton" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onmouseover="$(this).addClass('ui-state-hover');" onmouseout="$(this).removeClass('ui-state-hover');" type="button" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo $lC_Language->get('text_check_again'); ?></span></button></td>
        </tr>
      </table>
    </div>
    <div id="backupWarningContainer" class="tPad2">
      <table border="0" width="100%" cellspacing="0" cellpadding="0" id="backupWarningTable">
        <tr>  
          <td class="warning"><span id="backupWarningText"><b><?php echo $lC_Language->get('text_important'); ?>&nbsp;</b><?php echo $lC_Language->get('text_backup_warning1'); ?>&nbsp;<a href="http://www.loadedcommerce.com"><?php echo $lC_Language->get('text_backup_link1'); ?></a>.&nbsp;<?php echo $lC_Language->get('text_backup_warning2'); ?>&nbsp;<a href="http://www.loadedcommerce.com"><?php echo $lC_Language->get('text_backup_link2'); ?></a>&nbsp;<?php echo $lC_Language->get('text_page'); ?>.</span></td>
        </tr>
      </table>
    </div>
    <div id="updateMessageContainer">
      <span id="updateMessageText"><h2><?php echo $lC_Language->get('text_update_available'); ?></h2></span>
    </div>
    <div id="updateMessage2Container">
      <span id="updateMessage2Text"><?php echo $lC_Language->get('text_update_verbiage1'); ?>&nbsp;<a href="http://www.loadedcommerce.com"><?php echo $lC_Language->get('text_loadedcommerce') . '&nbsp;' . $nextVersion; ?></a>&nbsp;<?php echo $lC_Language->get('text_update_verbiage2'); ?>.</span>
    </div>
    <div id="buttonSetContainer" class="tPad">
      <div class="ui-dialog-buttonset">
        <button onclick="updateNow();" id="updateButton" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onmouseover="$(this).addClass('ui-state-hover');" onmouseout="$(this).removeClass('ui-state-hover');" type="button" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo $lC_Language->get('text_update_now'); ?></span></button>
        <button onclick="window.location='<?php echo $dlUrl; ?>'" id="downloadButton" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onmouseover="$(this).addClass('ui-state-hover');" onmouseout="$(this).removeClass('ui-state-hover');" type="button" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo $lC_Language->get('text_download') . '&nbsp;' . $nextVersion; ?></span></button>
      </div>
    </div>
    <div id="maintenanceModeMessageContainer" class="tPad">
      <span id="maintenanceModeMessageText"><?php echo $lC_Language->get('text_maintenance_mode'); ?></span>
    </div>
    <div id="rollbackMessageContainer" class="tPad">
      <span id="rollbackModeMessageText"><?php echo $lC_Language->get('text_rollback_verbiage'); ?>&nbsp;<a href=""><?php echo $lC_Language->get('text_rollback_page'); ?></a>.</span>
    </div>
    <div id="pluginsTitleContainer">
      <span id="pluginsTitleText"><h2><?php echo $lC_Language->get('text_plugins'); ?></h2></span>
    </div>

  </div>
  <form name="batch" id="batch" action="#" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="display" id="updaterDataTable">
    <thead>
      <tr>
        <th align="left" height="30"><?php echo $lC_Language->get('table_heading_updates'); ?></th> 
        <th align="right" height="30"><?php echo $lC_Language->get('table_heading_action'); ?></th> 
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">
          <?php echo lc_legend(array('info', 'update')); ?>
        </th>
      </tr>
    </tfoot> 
  </table>
  </form>
  <script type="text/javascript" charset="utf-8">
  $(document).ready(function() {  
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll'); ?>';   
    oTable = $('#updaterDataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL,
      "bJQueryUI": false,  
      "sDom": '<"clear"><"H"fl>rt<"F"ip<"clear">',
      "sPaginationType": "full_numbers", 
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
      "aoColumns": [{ "sWidth": "800px", "bSortable": true, "sClass": "dataColModule" },
                    { "sWidth": "140px", "bSortable": false, "sClass": "dataColAction" }]
    }); 
    $("#updaterDataTable_wrapper .ui-widget-header").removeClass('ui-widget-header');
  });

  function getUpdateData(type) {
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getUpdateData&type=TYPE'); ?>';   
    $.getJSON(jsonLink.replace('TYPE', type),
      function (data) {  
alert(print_r(data.data.params.ProB2B, true));
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }   
        $("#status-working").fadeOut('slow');                 
        if (data.rpcStatus != 1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }                  
      }              
    );
  }
  </script>
</div>
<?php $lC_Template->loadDialog($lC_Template->getModule()); ?>
