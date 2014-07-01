<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customers.js.php v1.0 2013-08-08 datazen $
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
      "iDisplayLength": 25,
      "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile dataColCheck" },
                    { "sWidth": "10px", "bSortable": false, "sClass": "hide-on-mobile dataColIcon" },
                    { "sWidth": "22%", "bSortable": true, "sClass": "dataColLast" },
                    { "sWidth": "25%", "bSortable": true, "sClass": "hide-on-mobile hide-on-tablet dataColEmail" },
                    { "sWidth": "8%", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColFirst" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-mobile-portrait dataColGroup" },
                    { "sWidth": "10%", "bSortable": true, "sClass": "hide-on-tablet hide-on-mobile dataColDate" },
                    { "sWidth": "25%", "bSortable": false, "sClass": "dataColAction" }]
    });
        
    setTimeout('hideElements()', 800); // because of server-side processing we need to delay for race condition
         
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
  function createOrder(cid, aid) {
    if (cid == undefined) cid = 0;   
    if (aid == undefined) aid = 0;   
    if(parseInt(cid) > 0  && parseInt(aid)) {
      window.location = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, "orders&action=quick_add&tabProducts=1&cID=' + cid + '");?>';
    } else {
      var add_addr = 1;
      editCustomer(cid,add_addr=1);
    }
  }
      
</script>