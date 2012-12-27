<?php
/*
  $Id: countries.js.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template;
if ( !empty($_GET[$lC_Template->getModule()]) && is_numeric($_GET[$lC_Template->getModule()]) ) {  // entries
  ?>
  <script>
  $(document).ready(function() {
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()]  . '&action=getAllEntries&media=MEDIA'); ?>';
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck" },
                    { "sWidth": "40%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColCountry" },
                    { "sWidth": "40%", "bSortable": true, "sClass": "dataColZone" },
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
      setTimeout('hideMobile()', 500); // because of server-side processing we need to delay for race condition
    } else {
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
    }     
  });
  </script>
  <?php
} else { // zone groups
  ?>
  <script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()]  . '&action=getAll&media=MEDIA'); ?>';
    oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,    
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck" },
                    { "sWidth": "60%", "bSortable": true, "sClass": "dataColGroups" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColEntries" },
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
      setTimeout('hideMobile()', 500); // because of server-side processing we need to delay for race condition
    } else {
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
    }    
  });
  </script>
  <?php
}
?>