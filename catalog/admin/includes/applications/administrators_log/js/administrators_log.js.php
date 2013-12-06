<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: administrators_log.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template;
?>
<script>
$(document).ready(function() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>';   
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType, 
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aoColumns": [{ "sWidth": "25%", "bSortable": true, "sClass": "dataColModule" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColId hide-on-tablet" }, 
                  { "sWidth": "15%", "bSortable": true, "sClass": "dataColType hide-on-mobile-portrait" },
                  { "sWidth": "15%", "bSortable": true, "sClass": "dataColUser hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColDate hide-on-tablet" },
                  { "sWidth": "15%", "bSortable": false, "sClass": "dataColAction" }]
  });
  oTable.dataTable().fnSetFilteringDelay(500);
  $('#dataTable').responsiveTable();
      
  setTimeout('hideElements()', 500); // because of server-side processing we need to delay for race condition
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
    $('.selectContainer').hide();   
  }  
  var error = '<?php echo $_SESSION['error']; ?>';
  if (error) {
    var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
    $.modal.alert(errmsg);
  }      
});

function hideElements() {  
  if ($.template.mediaQuery.name === 'mobile-portrait') { 
    $('.hide-on-mobile-portrait').hide();
    $('.hide-on-mobile').hide();
  } else if ($.template.mediaQuery.name === 'mobile-landscape') {  
    $('.hide-on-mobile-portrait').hide();
    $('.hide-on-mobile-landscape').hide();
    $('.hide-on-mobile').hide();
  } else if ($.template.mediaQuery.name === 'tablet-portrait') {  
    $('.hide-on-tablet-portrait').hide();    
    $('.hide-on-tablet').hide();              
  } else if ($.template.mediaQuery.name === 'tablet-landscape') {  
    $('.hide-on-tablet-portrait').hide();
    $('.hide-on-tablet-landscape').hide();      
    $('.hide-on-tablet').hide();      
  }    
}  
</script>