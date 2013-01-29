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
/*
$updatesDataArr = lC_Updates_Admin::getAvailablePackages(); 
$findDataArr = lC_Updates_Admin::findAvailablePackages('7.0'); 
$availInfoDataArr = lC_Updates_Admin::getAvailablePackageInfo('0'); 
$downloaded = lC_Updates_Admin::downloadPackage(); 
$localPackageExists = lC_Updates_Admin::localPackageExists(); 
$getPackageInfo = lC_Updates_Admin::getPackageInfo(); 
$getPackageContents = lC_Updates_Admin::getPackageContents(); 
$findPackageContents = lC_Updates_Admin::findPackageContents('osc'); 
*/

$hasUpdate = true;
$from_version = '7.0.0.1.1';
$to_version = '7.0.0.1.2';
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin small-margin-bottom">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  #versionContainer .stats > li { padding: 0 1%; }
  #versionContainer .stats > li:first-child { margin-top: -5px; }
  #versionContainer .stats > li:last-child { padding-top: 25px; }
  #versionContainer .stats > li > strong { display: inline; float:none; position:relative; top:-13px; left:14%; }
  #versionContainer .stats .before { float:left; }
  #versionContainer .stats .after { float:right; }
  #versionContainer .fieldset { padding-bottom:1px; }
  #version-ul > span .green { color:#009900; }
  #version-ul > span .red { color:#CC0000; }  
  #versionContainer .button-set { margin-left:37%; }
  #versionContainer .update-text { padding:0 0 5% 0; }
  #versionContainer .update-button { padding-left:1%; }
  #versionContainer .legend { font-weight:bold; font-size: 1.1em; }

  </style>
  
  <div class="columns small-margin-left large-margin-right">
  
    <div id="versionContainer" class="six-columns twelve-columns-tablet">
      <fieldset class="fieldset <?php echo ($hasUpdate) ? 'orange-gradient' : ''; ?>">
        <legend class="legend">Version Info</legend>
        <ul id="version-ul" class="stats">
          <li>
            <span class="before">Current Version</span><strong>7.0.0.1.1</strong><span class="after">released 01/15/2013</span>
          </li>
          <li>
            <span class="before" style="padding-right:9px;">Latest Version</span><strong>7.0.0.1.2</strong><span class="after">released 01/25/2013</span>
          </li>   
      
        </ul>        
      </fieldset> 
      <div class="button-set">
        <div class="update-text big-text"><?php echo ($hasUpdate) ? 'An update is available!' : 'You are up to date!'; ?></div>
        <div class="update-button">
          <?php 
          if ($hasUpdate) {
            ?>
            <a id="install-update" href="javascript://" onclick="installUpdate();" class="button">
              <span class="button-icon green-gradient glossy"><span class="icon-down-fat"></span></span>
              Install Update
            </a>
            <?php 
          } else {
            ?>
            <a id="check-again" href="javascript://" onclick="checkForUpdates();" class="button">
              <span class="button-icon green-gradient glossy"><span class="icon-cloud-upload"></span></span>
              <?php echo $lC_Language->get('text_check_again'); ?>
            </a>
            <?php
          }
          ?>      
        </div>
      </div>
    </div>


    <style>
    #toolsButtonSet { margin: 21px 0 7px 30px; }  
    #toolsButtonSet .re-install{ margin: 0 20px 0 0; }  
    #toolsButtonSet .download-zip { margin:0px 20px 0 20px; }  
    #toolsButtonSet .undo-last { margin:0 20px; }  
    #lastCheckedContainer { margin:0 0 0 30px; }  
    #lastCheckedContainer a { margin:0 10px 10px 10px; }  
    #toolsContainer .legend { font-weight:bold; font-size: 1.1em; }
    </style>    
    <div id="toolsContainer" class="six-columns twelve-columns-tablet">
      <fieldset class="fieldset">
        <legend class="legend">Tools</legend>
        <div id="lastCheckedContainer">
          <span id="updateCheckText"><?php echo $lC_Language->get('text_last_checked') . ' ' . lC_DateTime::getLong($lastChecked, TRUE); ?></span>
        </div>
        <div id="toolsButtonSet">
          <a id="reinstall" href="javascript://" onclick="reinstallUpdate();" class="button re-install">
            <span class="button-icon orange-gradient glossy"><span class="icon-redo"></span></span>
            Re-install Now
          </a>               
          <a id="download" href="javascript://" onclick="downloadZip();" class="button download-zip">
            <span class="button-icon green-gradient glossy"><span class="icon-download"></span></span>
            Download ZIP
          </a>
          <a id="undo" href="javascript://" onclick="undoUpdate();" class="button undo-last">
            <span class="button-icon red-gradient glossy"><span class="icon-undo"></span></span>
            Undo Last Update
          </a>
        </div>
      </fieldset>    
    </div>
  


    <style>
    .dataColAction { text-align: left; }
    .dataColResult { text-align: left; }
    .dataColUser { text-align: left; }
    .dataColTime { text-align: left; }
    #historyContainer .legend { font-weight:bold; font-size: 1.1em; }
    </style>  
    <div id="historyContainer" class="twelve-columns-tablet">
      <fieldset class="fieldset">
        <legend class="legend no-margin-bottom">History</legend>
        <form name="batch" id="batch" action="#" method="post">
          <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
            <thead>
              <tr>
                <th scope="col" class="align-left">Action</th>
                <th scope="col" class="align-left">Result</th>
                <th scope="col" class="align-left">User</th>
                <th scope="col" class="align-left">Time Stamp</th>
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
#updateTitle > span > strong { }
</style>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- Main content end -->
<script>
function checkForUpdates() {
  alert('If I only had a brain!');
}
function reinstallUpdate() {
  alert('Rome wasn\'t built in a day!');
}
function downloadZip() {
  alert('Today is not the day!');
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
  // clear the li for progress info  
  $('#version-ul li:first-child').html('<span class="before" style="padding-right:9px;">Latest Version</span><strong>' + toVersion + '</strong><span class="after">released 01/25/2013</span>'); 
  $('#version-ul li:last-child').html('<span id="updateProgressContainer" style="display:none;"></span>'); 
             
  $('#version-ul').addClass('margin-bottom');
  $('#version-ul li:last-child').removeClass('red').attr('style', 'padding-top:0');
  $('#updateProgressContainer').delay(500).slideDown('slow');
      
   
  $('.update-text').html('Initializing Update Engine').attr('style', 'color:red; margin-left:-14px').blink({ maxBlinks: 60, blinkPeriod: 1000 });
  
      
return false;      
  //step 1
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
  
//  alert('Yea Yea keep your shirt on!!');
}

function __showStep(step, fini) {
  var loader = '<span class="icon-right icon-blue margin-left margin-right"></span><span class="loader"></span>';
  var done = '<span class="icon-right icon-blue margin-left margin-right"><span class="icon-tick icon-green margin-left margin-right"></span>';
  var error = '<span class="icon-cross icon-red"></span>';

  var html1 = '<span class="update-text">Backing up Files and Database</span>';
  var html2 = '<span class="update-text">Retrieving Update from Server</span>';
  var html3 = '<span class="update-text">Installing Update</span>';

  if (step == 1) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 +  '</div>');
    } else {
      $('#updateProgressContainer').html('<div>' + loader + html1 + '</div>');  
    }
  }  
  
  if (step == 2) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + done + html2 + '</div>');
    } else {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + loader + html2 + '</div>');
    }
  }   
  
  if (step == 3) {
    if (fini == 1) {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + done + html2 + '</div>' + '<div>' + done + html3 + '</div>');
    } else {
      $('#updateProgressContainer').html('<div>' + done + html1 + '</div><div>' + done + html2 + '</div>' + '<div>' + loader + html3 + '</div>');
    }
  }   
  
  return true;
}

function sleep() {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > 5000){
      break;
    }
  }
}

$(document).ready(function() {
  var hasUpdate = '<?php echo ($hasUpdate) ? true : false; ?>';
  if (hasUpdate) {
    $('#version-ul > li').addClass('red');
    $('.update-button').attr('style', 'padding-left:3%;');
  } else {
    $('#version-ul > li').addClass('green');
  }  
});
</script>