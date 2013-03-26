<?php
/*
  $Id: updates.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $checkArr, $backupArr;
?>
<script>
$(document).ready(function() {
  // check for updates
  checkForUpdates(); 
  
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

$('#download').click(function(e) {
  var access = '<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? 0 : 1); ?>';
  if (access == 0) return false;
  
  var file = '<?php echo 'https://github.com/loadedcommerce/loaded7/archive/' . $checkArr['toVersion'] . '.zip' ?>';
  e.preventDefault();
  window.location.href = file;
    
  // write to the update history log
  __writeHistory('<?php echo $lC_Language->get('text_history_action_backup'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_download'), $checkArr['toVersion']); ?>');
  
  setTimeout('oTable.fnReloadAjax()', 500); 
  
  return false;
});

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
  var version = '<?php echo $checkArr['toVersion']; ?>';
  var count = '<?php echo ($checkArr['total'] > 0) ? $checkArr['total'] : 0; ?>';
  var type = (t != undefined) ? t : (count > 1) ? 'cumu' : null;  
  var confirmText = (t != undefined && t == 'full') ? '<?php echo $lC_Language->get('text_confirm_full_update');?>' : '<?php echo $lC_Language->get('text_confirm_update');?>';
  $.modal.confirm(confirmText, function() {
    // set maint mode=on
    __setMaintenanceMode('on');
    
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
                        
                        // write to the update history log
                        __writeHistory('<?php echo $lC_Language->get('text_history_action_update'); ?>', '<?php echo sprintf($lC_Language->get('text_history_result_update_success'), $checkArr['toVersion']); ?>' + ((type != null) ? ('-' + type) : ''));

                        $('#vFooterText').html(__okBlock());
                        $('#version-table thead').removeClass('red').addClass('green');
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
  $.modal({
      content: '<div id="undoUpdate">'+
               '  <div id="undoUpdateForm">'+
               '    <form name="undo" id="undo" method="post">'+
               '      <p><?php echo $lC_Language->get('introduction_new_undo'); ?></p>'+
               '      <p class="button-height inline-label">'+
               '        <label for="version" class="label" style="width:50%;"><?php echo $lC_Language->get('field_product'); ?></label>'+
               '        <?php echo lc_draw_pull_down_menu('version', $backupArr, null, 'class="input with-small-padding"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_new_undo'); ?>',
      width: 600,
      scrolling: false,
      actions: {
        'Close' : {
          color: 'red',
          click: function(win) { win.closeModal(); return false; }
        }
      },
      buttons: {
        '<?php echo $lC_Language->get('button_cancel'); ?>': {
          classes:  'glossy',
          click:    function(win) { win.closeModal(); }
        },
        '<?php echo $lC_Language->get('button_save'); ?>': {
          classes:  'blue-gradient glossy',
          click:    function(win) {
            var bValid = $("#undo").validate({
              rules: {
              },
              invalidHandler: function() {
              }
            }).form();
            if (bValid) {
              // set maint mode=on
              __setMaintenanceMode('on');
              
              win.closeModal();

              var toVersion = '<?php echo $checkArr['toVersion']; ?>';  
              $('#versionContainer .fieldset').removeClass('orange-gradient');
              $('#version-table tbody').removeClass('green').removeClass('red');
              $('#version-table > tbody').empty();  
              $('#version-table > tbody').empty();
              $('#version-table').css("margin-bottom", "10px");
              $('#version-table > thead').html('<tr><td class="before">&nbsp;</td><td class="version">Undo Update</td><td class="after">&nbsp;</td></tr>').addClass('red'); 
              $('#version-table > tbody').html('<tr><td colspan="3"><span id="updateProgressContainer" style="display:none;"></span></td></tr>');  
              $('#updateButtonset').slideUp();
              $('.update-text').html('<p><?php echo $lC_Language->get('text_initializing'); ?></p>').attr('style', 'text-align:center').blink({ maxBlinks: 5, blinkPeriod: 1000 });

              setTimeout(function() { 
                __setup(); 
                __showUndoStep(1,0);
                $('#vFooterText').html(__cancelBlock()).show();
                
                // restore files
                var nvp = $("#undo").serialize();
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doFullFileRestore&NVP'); ?>';
                $.getJSON(jsonLink.replace('NVP', nvp),   
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
                            
                      // write to the update history log
                      __writeHistory('<?php echo $lC_Language->get('text_history_action_undo'); ?>', '<?php echo $lC_Language->get('text_history_result_undo_success'); ?>');
                      
                      $('#vFooterText').html(__okBlock());
                      $('#version-table thead').removeClass('green').addClass('red');                
                      $('#version-table > thead').html('<tr><td class="before">&nbsp;</td><td class="version">Undo Update</td><td class="after">&nbsp;</td></tr>').addClass('red');            
                      oTable.fnReloadAjax(); 
                                                    
                      // set maint mode=off
                      __setMaintenanceMode('off');  
             
                    }
                  );        
                }); 
              }, 3000);              
            }
          }
        }
      },
      buttonsLowPadding: true
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