<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: countries.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template;
if ( !empty($_GET[$lC_Template->getModule()]) && is_numeric($_GET[$lC_Template->getModule()]) ) {  // zones
  ?>
  <script>
    $(document).ready(function() {
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()]  . '&action=getAllZones&media=MEDIA'); ?>';
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": 25,
        "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColCheck" },
                      { "sWidth": "60%", "bSortable": true, "sClass": "dataColZone" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColCode" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
           
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#actionText').hide();
        $('.on-mobile').show();
        setTimeout('hideMobile()', 500); // because of server-side processing we need to delay for race condition
      }      
    });
  </script>
  <?php
} else { // countries
  ?>
  <script>
    $(document).ready(function() {
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA' . $cSearch); ?>';
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,     
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
        "aaSorting": [[1,'desc']],
        "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile dataColCheck" },
                      { "sWidth": "40%", "bSortable": true, "sClass": "dataColCountry" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColCode" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColTotal" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
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
  <?php
}
?>