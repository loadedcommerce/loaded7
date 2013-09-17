<?php
/*
  $Id: product_variants.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $modulesArr, $accessArr;
if (!empty($_GET['product_variants']) && is_numeric($_GET['product_variants'])) { // list the product variant entries
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
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                    { "sWidth": "60%", "bSortable": true, "sClass": "dataColEntries" },
                    { "sWidth": "20%", "bSortable": true, "sClass": "dataColSort hide-on-mobile-portrait" },
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
      $('.selectContainer').hide();
    }    
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    }     
  });
  </script>  
  <?php 
} else { // list the product variant groups 
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
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                    { "sWidth": "50%", "bSortable": true, "sClass": "dataColGroup" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataColTotal hide-on-mobile-portrait" },
                    { "sWidth": "15%", "bSortable": true, "sClass": "dataColSort hide-on-mobile-portrait" },
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
      $('.selectContainer').hide();
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