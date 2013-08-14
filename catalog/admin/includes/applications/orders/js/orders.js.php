<?php
/*
  $Id: orders.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $cSearch;
$cSearch = (isset($_SESSION['cIDFilter']) && $_SESSION['cIDFilter'] != null) ? '&cSearch=' . $_SESSION['cIDFilter'] : '';
?>
<script>
  $(document).ready(function() {
    if (document.getElementById('dataTable')) {    
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA' . $cSearch); ?>';
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,     
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
        "aaSorting": [[1,'desc']],
        "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "dataColOID" },
                      { "sWidth": "25%", "bSortable": true, "sClass": "dataColName hide-on-mobile-portrait" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "dataColCID hide-on-tablet hide-on-mobile" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "dataColOTotal" },
                      { "sWidth": "15%", "bSortable": true,"sClass": "dataColDate hide-on-mobile-portrait" },
                      { "sWidth": "10%", "bSortable": true, "sClass": "dataColStatus hide-on-mobile" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
          
      setTimeout('hideElements()', 500); // because of server-side processing we need to delay for race condition
           
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#floating-button-container').hide();
        $('#actionText').hide();
        $('.on-mobile').show();
        $('.selectContainer').hide();   
      }   
    }
    
    // on screen resize get the new menu width and apply it for click functions
    $(window).resize(function() {
      // if window width drops below 1280px change orders edit tabs from side to top
      if ($(window).width() < 1380) {
        $("#order_tabs").removeClass("side-tabs");
        $("#order_tabs").addClass("standard-tabs");
      } if ($(window).width() >= 1380) {
        $("#order_tabs").removeClass("standard-tabs");
        $("#order_tabs").addClass("side-tabs");
      }
    });
    // if window width drops below 1280px change orders edit tabs from side to top
    if ($(window).width() < 1380) {
      $("#order_tabs").removeClass("side-tabs");
      $("#order_tabs").addClass("standard-tabs");
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
  
  function updateOrderStatus() {
    var nvp = $("#updateOrder").serialize();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', 'orders&action=updateOrderStatus&NVP'); ?>'  
    $.getJSON(jsonLink.replace('NVP', nvp),     
      function (data) {
        $("[name=comment]").val(""); 
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }   
        if (data.rpcStatus == 1) {
          if (typeof oTable !== 'undefined') {   
            oTable.fnReloadAjax();
          }
          $("#orderStatusTableData > tbody").html(data.orderStatusHistory);
        } else {    
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
      }
    );
    return false;
  }

  function executePostTransaction() {
    var nvp = $("#updateOrder").serialize();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', '?orders&action=executePostTransaction&NVP'); ?>'  
    $.getJSON(jsonLink.replace('NVP', nvp),     
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        }    
        if (data.rpcStatus == 1) {
          $("#transactionInfoTable > tbody").html(data.transactionHistory);
        } else {    
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
      }
    );
    return false;
  }
  
  function saveOrderProduct(val) {
    alert('save product: ' + val + ' changes');
    $("#buttons_" + val).html('<span class="button-group">'+
                              '  <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct(' + val + ');"><?php echo $lC_Language->get('text_edit'); ?></a>'+
                              '  <a class="button compact icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct(' + val + ');"></a>'+
                              '</span>');
  }
  
  function deleteOrderProduct(val) {
    alert('delete product: ' + val + ' from the order');
  }
  
  function orderProductDetails(id) {
    $.modal({
      title: '<?php echo $lC_Language->get('text_product_details'); ?>',
      content: '<p>This is the orders products detail modal window for ' + id + '.</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'+
               '<p>More</p>'
    });
  }
  
  function editOrderProduct(val) {
    $("#buttons_" + val).html('<p><a class="button compact small-margin-top op-action" href="javascript:void(0);" onclick="saveOrderProduct(' + val + ');"><?php echo $lC_Language->get('text_save'); ?></a></p>'+
                              '<p><a class="button compact small-margin-bottom op-action" href="javascript:void(0)" onclick="cancelOrderProductEdit(' + val + ');"><?php echo $lC_Language->get('text_cancel'); ?></a></p>');
  }
  
  function cancelOrderProductEdit(val) {
    $("#buttons_" + val).html('<span class="button-group">'+
                              '  <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct(' + val + ');"><?php echo $lC_Language->get('text_edit'); ?></a>'+
                              '  <a class="button compact icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct(' + val + ');"></a>'+
                              '</span>');
  }  
</script>