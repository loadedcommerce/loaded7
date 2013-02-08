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
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors", 1);
require_once('includes/applications/updates/classes/updates.php');  

$from_version = utility::getVersion();
$from_version_date = utility::getVersionDate();

$checkArr = lC_Updates_Admin::hasUpdatesAvailable();
$hasUpdate = (isset($checkArr['hasUpdates']) && (int)$checkArr['hasUpdates'] > 0) ? true : false;

if ($hasUpdate) {
  $updatesDataArr = lC_Updates_Admin::getAvailablePackages();
  $to_version = 0;
  $to_version_date = '';
  foreach ($updatesDataArr['entries'] as $k => $v) {
    if (version_compare($to_version, $v['version'], '<')) { 
      $to_version = $v['version'];
      $to_version_date = $v['date'];
    }
  }
} else {
  $to_version = $from_version;
  $to_version_date = $from_version_date;
}

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
      <fieldset class="fieldset <?php echo ($hasUpdates) ? 'orange-gradient' : null; ?>">
        <legend class="legend"><?php echo $lC_Language->get('heading_legend_version_info'); ?></legend>
        <table id="version-table">
          <thead>
            <tr>
              <th class="before"><?php echo $lC_Language->get('text_current_version'); ?></th>
              <th class="version"><?php echo $from_version; ?></th>
              <th class="after"><?php echo sprintf($lC_Language->get('text_released'), $from_version_date); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="3"><div class="hr"></div></td></tr>
            <tr>
              <td class="before"><?php echo $lC_Language->get('text_latest_version'); ?></td>
              <td class="version"><?php echo $to_version; ?></td>
              <td class="after"><?php echo sprintf($lC_Language->get('text_released'), $to_version_date); ?></td>
            </tr>  
          </tbody>            
        </table>
      </fieldset> 
      <p id="updateText" class="update-text big-text"><?php echo ($hasUpdate) ? $lC_Language->get('text_update_avail') : $lC_Language->get('text_up_to_date'); ?></p>
      <p id="updateButtonset" class="buttonset">
        <?php 
        if ($hasUpdate) {
          ?>
          <a id="install-update" href="javascript://" onclick="installUpdate();" class="button">
            <span class="button-icon green-gradient glossy"><span class="icon-down-fat"></span></span>
            <?php echo $lC_Language->get('button_install_update'); ?>
          </a>
          <?php 
        } else {
          ?>
          <a id="check-again" href="javascript://" onclick="checkForUpdates();" class="button">
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
              <a id="reinstall" href="javascript://" onclick="reinstallUpdate();" class="button re-install">
                <span class="button-icon orange-gradient glossy"><span class="icon-redo"></span></span>
                <?php echo $lC_Language->get('button_reinstall_update'); ?>
              </a>               
            </td>
            <td align="center">
              <a id="download" href="https://github.com/loadedcommerce/loaded7/archive/<?php echo $to_version; ?>.zip" class="button download-zip">
                <span class="button-icon blue-gradient glossy"><span class="icon-download"></span></span>
                <?php echo $lC_Language->get('button_download_zip'); ?>
              </a>
            </td>
            <td align="right">
              <a id="undo" href="javascript://" onclick="undoUpdate();" class="button undo-last">
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
          <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
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


function checkForUpdates() {
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
      // update last checked
      $('#lastCheckedContainer').html(data.lastChecked);
      var fromVersion = '<?php echo $from_version; ?>';
      var fromVersionDate = '<?php echo sprintf($lC_Language->get('text_released'), $from_version_date); ?>';
      var toVersion = '<?php echo $to_version; ?>';
      var toVersionDate = '<?php echo sprintf($lC_Language->get('text_released'), $to_version_date); ?>'; 
  //    var updateMsg = '<?php echo ($hasUpdate) ? $lC_Language->get('text_update_avail') : $lC_Language->get('text_up_to_date'); ?>';   
      var updateButton = '<a id="install-update" href="javascript://" onclick="installUpdate();" class="button"><span class="button-icon green-gradient glossy"><span class="icon-down-fat"></span></span><?php echo $lC_Language->get('button_install_update'); ?></a>';
      var recheckButton = '<a id="check-again" href="javascript://" onclick="checkForUpdates();" class="button"><span class="button-icon green-gradient glossy"><span class="icon-cloud-upload"></span></span><?php echo $lC_Language->get('button_check_again'); ?></a>';
      $('#version-table th.version').html(fromVersion);
      $('#version-table th.after').html(fromVersionDate);
      $('#version-table td.version').html(toVersion);
      $('#version-table td.after').html(toVersionDate);  
//      $('#updateText').html(updateMsg);  
          
      if (data.hasUpdates == true) {
        $('#versionContainer .fieldset').addClass('orange-gradient');
        $('.version').removeClass('green');
        $('#version-table td').addClass('red');
        $('#version-table th').addClass('red');
        $('#updateText').html('<?php echo $lC_Language->get('text_update_avail'); ?>');
        $('#updateButtonset').html(updateButton);
      } else {
        $('#versionContainer .fieldset').removeClass('orange-gradient');
        $('.version').addClass('green');
        $('#updateText').html('<?php echo $lC_Language->get('text_up_to_date'); ?>');
        $('#updateButtonset').html(recheckButton);
      }      
    }
  );  
}


function reinstallUpdate() {
  alert('Rome wasn\'t built in a day!');
}

function undoUpdate() {
  alert('Derp!');
}

function installUpdate() {
  var fromVersion = '<?php echo $from_version; ?>';
  var toVersion = '<?php echo $to_version; ?>';
  var loader = '<span class="icon-right icon-blue margin-left margin-right"></span><span class="loader"></span>';
  var done = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-tick icon-green margin-left margin-right"></span>';
  var error = '<span class="icon-cross icon-red"></span>';
  $('#versionContainer .fieldset').removeClass('orange-gradient');
  $('#version-table > tbody').empty();
  $('#version-table').css("margin-bottom", "10px");
  $('#version-table > thead').html('<tr><td class="before"><?php echo $lC_Language->get('text_latest_version'); ?></td><td class="version">' + toVersion + '</td><td class="after"><?php echo sprintf($lC_Language->get('text_latest_version'), $to_version); ?></td></tr>').addClass('red'); 
  $('#version-table > tbody').html('<tr><td colspan="3"><span id="updateProgressContainer" style="display:none;"></span></td></tr>');  
  // start the update process
  $('#updateButtonset').slideUp();
  $('.update-text').html('<p><?php echo $lC_Language->get('text_initializing'); ?></p>').attr('style', 'text-align:center').blink({ maxBlinks: 5, blinkPeriod: 1000 });

  setTimeout(function() { 
    __setup(); 

    __showStep(1,0);
    setTimeout(function() { 
      __showStep(2,0); 
      setTimeout(function() { 
        __showStep(3,0); 
        setTimeout(function() { 
          __showStep(3,1);
      
        }, 3000);
      }, 3000);
    }, 3000);
  }, 3000);
  
}

function __showStep(step, fini) {
  var loader = '<span class="icon-right icon-blue margin-left margin-right"></span><span class="loader"></span>';
  var done = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-tick icon-green margin-left margin-right"></span>';
  var error = '<span class="icon-cross icon-red"></span>';

  var html1 = '<span class="update-text"><?php echo $lC_Language->get('text_step_1'); ?></span>';
  var html2 = '<span class="update-text"><?php echo $lC_Language->get('text_step_2'); ?></span>';
  var html3 = '<span class="update-text"><?php echo $lC_Language->get('text_step_3'); ?></span>';
  var html4 = '<span class="update-text"><?php echo $lC_Language->get('text_step_4'); ?></span>';
  var html4 = '<span class="update-text"><?php echo $lC_Language->get('text_step_5'); ?></span>';
  var html4 = '<span class="update-text"><?php echo $lC_Language->get('text_step_success'); ?></span>';
  var html4 = '<span class="update-text"><?php echo $lC_Language->get('text_step_error'); ?></span>';
        
  if (step == 1) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 +  '</div>' + __cancelBlock());
    } else {
      $('#updateProgressContainer').html('<div>' + loader + html1 + '</div>' + __cancelBlock());  
    }
  }  
  
  if (step == 2) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + done + html2 + '</div>' + __cancelBlock());
    } else {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + loader + html2 + '</div>' + __cancelBlock());
    }
  }   
  
  if (step == 3) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + done + html2 + '</div>' + '<div>' + done + html3 + '</div><div>' + done + html4 + '</div>' + __okBlock());
    } else {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + done + html2 + '</div>' + '<div>' + loader + html3 + '</div>' + __cancelBlock());
    }
  }   
  
  return true;
}

function __setup() {
  $('.update-text').empty();
  $('#version-ul').addClass('margin-bottom');
  $('#updateProgressContainer').delay(500).slideDown('slow');
}

function __cancelBlock() {
  return '<p class="cancel-text intro"><?php echo $lC_Language->get('text_warning_do_not_interrupt'); ?></p>';
}

function __okBlock() {
  return '<p class="buttonset large-margin-top"><a id="ok" href="javascript://" onclick="location.reload(true);" class="button ok"><span class="button-icon green-gradient glossy"><span class="icon-tick"></span></span><?php echo $lC_Language->get('button_ok'); ?></a></p>';
}

</script>
<?php
echo "<pre style='margin:30px; padding-left:100px;'>";
print_r($updatesDataArr);

echo 'hasUpdates[' . $hasUpdate . ']<br>';
echo 'from[' . $from_version . ']<br>';
echo 'fromDate[' . $from_version_date . ']<br>';
echo 'to[' . $to_version . ']<br>';
echo 'toDate[' . $to_version_date . ']<br>';

echo "</pre>";
?>