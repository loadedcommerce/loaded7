<?php
/*
  $Id: countries.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template;
if ( !empty($_GET[$lC_Template->getModule()]) && is_numeric($_GET[$lC_Template->getModule()]) ) {  // entries
  ?>
  <script>
    $(document).ready(function() {
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getAllEntries&media=MEDIA'); ?>';
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,      
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColCheck" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColPriority" },
                      { "sWidth": "40%", "bSortable": true, "sClass": "dataColZone" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColRate" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
           
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#floating-button-container').hide();
        $('#actionText').hide();
        $('.selectContainer').hide();
        $('.on-mobile').show();
      } else {
        // instantiate floating menu
        $('#floating-menu-div-listing').fixFloat();
      }     
    });
  </script>
  <?php
} else { // tax classes
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
        "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColCheck" },
                      { "sWidth": "60%", "bSortable": true, "sClass": "dataColClasses" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColRates" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
           
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#floating-button-container').hide();
        $('#actionText').hide();
        $('.selectContainer').hide();
        $('.on-mobile').show();
      } else {
        // instantiate floating menu
        $('#floating-menu-div-listing').fixFloat();
      }  
    });    
  </script>
  <?php
}
?>