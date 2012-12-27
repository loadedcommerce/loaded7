<?php
/*
  $Id: languages.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $modulesArr, $accessArr;
if (!empty($_GET['group'])) { // definition listing
  ?>
<script>
  $(document).ready(function() {
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getDefinitions&media=MEDIA&group=' . $_GET['group']); ?>';
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile-portrait" },
                    { "sWidth": "40%", "bSortable": true, "sClass": "dataColKey hide-on-mobile-portrait" },
                    { "sWidth": "40%", "bSortable": true, "sClass": "dataColValue" },
                    { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
    });
    $('#dataTable').responsiveTable();
         
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
      $('#main-title > h1').attr('style', 'font-size:1.8em;');
      $('#main-title').attr('style', 'padding: 0 0 0 20px;');
      $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
      $('#floating-button-container').hide();
      $('#actionText').hide();
      $('.on-mobile').show();
    } else {
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
    }     
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    }
  });
</script>
<?php
} else if (!empty($_GET['languages'])) { // groups listing
?>
<script>
  $(document).ready(function() {
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getDefinitionGroups&media=MEDIA'); ?>';
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType, 
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "aoColumns": [{ "sWidth": "40%", "bSortable": true, "sClass": "dataColGroups" },
                    { "sWidth": "40%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColTotal" },
                    { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
    });
    $('#dataTable').responsiveTable();
         
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
      $('#main-title > h1').attr('style', 'font-size:1.8em;');
      $('#main-title').attr('style', 'padding: 0 0 0 20px;');
      $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
      $('#floating-button-container').hide();
      $('#actionText').hide();
      $('.on-mobile').show();
    } else {
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
    }    
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    }
  });
</script>
<?php
} else { // languages listing
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
    "aoColumns": [{ "sWidth": "40%", "bSortable": true, "sClass": "dataColLanguages" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColCode" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColTotal" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#floating-button-container').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
  } else {
    // instantiate floating menu
    $('#floating-menu-div-listing').fixFloat();
  }    
  var error = '<?php echo $_SESSION['error']; ?>';
  if (error) {
    var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
    $.modal.alert(errmsg);
  }
});
</script>
<?php
}
?>