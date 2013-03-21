<?php
/*
  $Id: customers.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template;
$cSearch = (isset($_GET['cID']) && $_GET['cID'] != null ? '&cSearch=' . $_GET['cID'] : null);
?>
<script>
  $(document).ready(function() {
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA' . $cSearch); ?>';
    var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>';
    
  oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
      "sPaginationType": paginationType,
      "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile dataColCheck" },
                    { "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile dataColIcon" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataColLast" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "hide-on-mobile dataColFirst" },
                    { "sWidth": "30%", "bSortable": true, "sClass": "hide-on-mobile dataColEmail" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColGroup" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-tablet hide-on-mobile dataColDate" },
                    { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
    });
    $('#dataTable').responsiveTable();
        
    setTimeout('hideElements()', 500); // because of server-side processing we need to delay for race condition
         
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
      $('#main-title > h1').attr('style', 'font-size:1.8em;');
      $('#main-title').attr('style', 'padding: 0 0 0 20px;');
      $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
      $('#dataTable_length').hide();
      $('#floating-button-container').hide();
      $('#actionText').hide();
      $('.on-mobile').show();
      $('.selectContainer').hide();   
    } else {
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
    }
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    } 
  
    if (quickAdd) {
      newCustomer();
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