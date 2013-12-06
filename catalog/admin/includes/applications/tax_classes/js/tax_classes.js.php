<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tax_classes.js.php v1.0 2013-08-08 datazen $
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
        $('#actionText').hide();
        $('.selectContainer').hide();
        $('.on-mobile').show();
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
        $('#actionText').hide();
        $('.selectContainer').hide();
        $('.on-mobile').show();
      }  
    });    
  </script>
  <?php
}
?>