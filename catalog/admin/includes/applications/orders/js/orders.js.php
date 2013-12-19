<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: orders.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language, $cSearch;
$cSearch = (isset($_SESSION['cIDFilter']) && $_SESSION['cIDFilter'] != null) ? '&cSearch=' . $_SESSION['cIDFilter'] : '';
?>
<script>
  $(document).ready(function() {
    updateOrderList();
  });

  function updateOrderList() {
    var filter = $("#filter").val();
    if (filter == null) filter = '<?php echo DEFAULT_ORDERS_STATUS_ID; ?>';  
    var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
    var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA&filter=FILTER' . $cSearch); ?>';
    
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&filter=FILTER'); ?>';
    $.getJSON(jsonLink.replace('FILTER', filter),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }

        oTable = $('#dataTable').dataTable({
          "bProcessing": true,
          "bServerSide": true,
          "sAjaxSource": dataTableDataURL.replace('FILTER', filter).replace('MEDIA', $.template.mediaQuery.name),
          "sPaginationType": paginationType,     
          "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
          "aaSorting": [[1,'desc']],
          "bDestroy": true,
          "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                        { "sWidth": "5%", "bSortable": true, "sClass": "dataColOID" },
                        { "sWidth": "25%", "bSortable": true, "sClass": "dataColName hide-on-mobile-portrait" },
                        { "sWidth": "10%", "bSortable": true,"sClass": "dataColCountry hide-on-tablet" },
                        { "sWidth": "8%", "bSortable": true,"sClass": "dataColItems hide-on-tablet" },
                        { "sWidth": "11%", "bSortable": true, "sClass": "dataColOTotal" },
                        { "sWidth": "13%", "bSortable": true,"sClass": "dataColDate hide-on-tablet" },
                        { "sWidth": "5%", "bSortable": true,"sClass": "dataColTime hide-on-tablet" },
                        { "sWidth": "10%", "bSortable": true, "sClass": "dataColStatus hide-on-mobile" },
                        { "sWidth": "15%", "bSortable": false, "sClass": "dataColAction" }]
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
      
        $("#order_statuses").change(function() {
          var text = $("#order_statuses > option:selected").text();
          $('#comment').val('<?php echo $lC_Language->get('text_status_update'); ?> ' + text);
        });
         
      }
    );
    var v = parseInt('<?php echo $_GET["editProduct"];?>');    
    if(v == 1) {
      $("#id_tab_orders_summary").removeClass("active");
      $("#id_tab_orders_products").addClass("active");

      // Display Address form (Hide Personal Form)
      $('#section_orders_summary').hide();
      $('#section_orders_products').show();
    }
  }

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
  
  function orderProductDetails(oid, pid) {
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getProduct&oid=OID&pid=PID'); ?>'  
    $.getJSON(jsonLink.replace('OID', parseInt(oid)).replace('PID', parseInt(pid)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        }
        $.modal({
            content: '<div id="product_details"></div>',
            title: '<?php echo $lC_Language->get('text_product_details'); ?>',
            width: 600,
            scrolling: true,
            actions: {
              'Close' : {
                color: 'red',
                click: function(win) { win.closeModal(); }
              }
            },
            buttons: {
              '<?php echo $lC_Language->get('button_close'); ?>': {
                classes:  'glossy',
                click:    function(win) { win.closeModal(); }
              }
            },
            buttonsLowPadding: true
        });
        $("#product_details").html(data.orderProduct);
        $.modal.all.centerModal();
      }
    );
  }
  /********************************/
  function getFormData(oid, opid) {
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getProduct&oid=OID&pid=PID'); ?>'  
    $.getJSON(jsonLink.replace('OID', parseInt(oid)).replace('PID', parseInt(opid)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        }
        // populate product data   
        $("#oId").html(oid);
        $("#opId").html(opid);
        $("#editPrice").val(data.price);
        $("#editQuantity").val(data.quantity);
        $("#editProduct").empty();
        $.each(data.productsArray, function(val, text) {          
          var selected = (data.products_id == text['products_id']) ? 'selected="selected"' : '';
          if (data.products_id == text['products_id']) {
            $("#editProduct").closest("span + *").prevAll("span.select-value:first").text(text['products_name']);
          }
          $("#editProduct").append(
            $("<option " + selected + "></option>").val(text['products_id']).html(text['products_name'])
          );
        });
        $("#editTaxclass").empty();
        var cnt = 1;
        $.each(data.taxclassArray.entries, function(val, text) {
          var selected = (data.tax_class_id == text['tax_class_id']) ? 'selected="selected"' : '';
          if (cnt == 1) {
            $("#editTaxclass").append(
              $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
            );
            cnt++;
          }
          if (data.tax_class_id == text['tax_class_id']) {
            $("#editTaxclass").closest("span + *").prevAll("span.select-value:first").text(text['tax_class_title']);
          }
          $("#editTaxclass").append(
            $("<option " + selected + "></option>").val(text['tax_class_id']).html(text['tax_class_title'])
          );
        });
        $.modal.all.centerModal();        
      }
    );
  }

  function getProductFormData(pid) {    
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getProductData&pid=PID'); ?>'  
    $.getJSON(jsonLink.replace('PID', parseInt(pid)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        } 
        // populate product data   
        //$("#oId").html(oid);
        //$("#opId").html(opid);
        $("#editPrice").val(data.price);
        $("#editQuantity").val(1);
        $("#editProduct").empty();
        $.each(data.productsArray, function(val, text) {          
          var selected = (data.products_id == text['products_id']) ? 'selected="selected"' : '';
          if(data.products_id == text['products_id']) {
            $("#editProduct").closest("span + *").prevAll("span.select-value:first").text(text['products_name']);
          }
          $("#editProduct").append(
            $("<option " + selected + "></option>").val(text['products_id']).html(text['products_name'])
          );
        });
         $("#editTaxclass").empty();
        var cnt = 1;
        $.each(data.taxclassArray.entries, function(val, text) {
          var selected = (data.tax_class_id == text['tax_class_id']) ? 'selected="selected"' : '';
          if (cnt == 1) {
            $("#editTaxclass").append(
              $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
            );
            cnt++;
          }
          if (data.tax_class_id == text['tax_class_id']) {
            $("#editTaxclass").closest("span + *").prevAll("span.select-value:first").text(text['tax_class_title']);
          }
          $("#editTaxclass").append(
            $("<option " + selected + "></option>").val(text['tax_class_id']).html(text['tax_class_title'])
          );
        });
        $.modal.all.centerModal();        
      }
    );
  }
  function updateEditProduct() {    
    var pid = $("#editProduct").val();
    var oid = parseInt($("#oId").html());
    var opid = parseInt($("#opId").html());
    getProductFormData(pid);
  }
  function saveEditproduct() {    
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    var pid = $("#editProduct").val();
    var oid = parseInt($("#oId").html());
    var opid = parseInt($("#opId").html());
    var formData = $("#editProductForm").serialize();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateOrderProductData&oid=OID&opid=OPID&FORMDATA'); ?>'  
    $.getJSON(jsonLink.replace('OID', parseInt(oid)).replace('OPID', parseInt(opid)).replace('FORMDATA', formData),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        } 
        updateOrderList();        
        url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders=OID&action=save&editProduct=1'); ?>';
        $(location).attr('href',url.replace('OID', oid));
      }
    );
  }
  function editOrderProduct(oid, opid) {
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    $.modal({
    content: '<div id="editProductContainer">'+
             '  <div id="section_editProduct">'+
           '      <form name="editProductForm" id="editProductForm" autocomplete="off" action="" method="post">'+               
           '        <p class="button-height inline-label">'+
             '      <label for="product" class="label"><?php echo $lC_Language->get('text_products'); ?>'+
             '      <?php echo lc_draw_pull_down_menu('product', null, null, 'class="input with-small-padding mid-margin-top" id="editProduct" onchange="updateEditProduct();"'); ?>'+
             '      </label>'+
           '        </p>'+
           '        <p class="button-height inline-label">'+
             '      <label for="taxClass" class="label"><?php echo $lC_Language->get('text_tax_class'); ?>'+
             '      <?php echo lc_draw_pull_down_menu('taxClass', null, null, 'class="input with-small-padding mid-margin-top" id="editTaxclass"'); ?>'+
             '      </label>'+
           '        </p>'+
           '        <p class="button-height inline-label">'+
             '      <label for="price" class="label"><?php echo $lC_Language->get('text_price'); ?>'+
             '      <?php echo lc_draw_input_field('price', null, 'class="input mid-margin-top" id="editPrice"'); ?>'+
             '      </label>'+
           '        </p>'+
           '        <p class="button-height inline-label">'+
             '      <label for="quantity" class="label"><?php echo $lC_Language->get('text_quantity'); ?>'+
             '      <?php echo lc_draw_input_field('quantity', null, 'class="input mid-margin-top" id="editQuantity"'); ?>'+
             '      </label>'+
           '        </p>'+               
           '      </form>'+
           '    </div>'+               
             '  <span id="oId" style="display:none;"></span>'+
             '  <span id="pId" style="display:none;"></span>'+
             '  <span id="opId" style="display:none;"></span>'+
           '</div>',
        title: '<?php echo $lC_Language->get('text_product_details'); ?>',
        width: 600,
        scrolling: true,
        actions: {
          'Close' : {
            color: 'red',
            click: function(win) { win.closeModal(); }
          }
        },
        buttons: {
      '<?php echo $lC_Language->get('button_save'); ?>': {
        classes:  'glossy',
        click:    function(win) { saveEditproduct(); }
      },
          '<?php echo $lC_Language->get('button_close'); ?>': {
            classes:  'glossy',
            click:    function(win) { win.closeModal(); }
          }
        },
        buttonsLowPadding: true
    });

    getFormData(oid, opid);
    $.modal.all.centerModal();
  }
  /*
  function editOrderProduct(val) {
    $("#buttons_" + val).html('<p><a class="button compact small-margin-top op-action" href="javascript:void(0);" onclick="saveOrderProduct(' + val + ');"><?php echo $lC_Language->get('text_save'); ?></a></p>'+
                              '<p><a class="button compact small-margin-bottom op-action" href="javascript:void(0)" onclick="cancelOrderProductEdit(' + val + ');"><?php echo $lC_Language->get('text_cancel'); ?></a></p>');
  }
  */
  /***********************************/
  
  function cancelOrderProductEdit(val) {
    $("#buttons_" + val).html('<span class="button-group">'+
                              '  <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct(' + val + ');"><?php echo $lC_Language->get('text_edit'); ?></a>'+
                              '  <a class="button compact icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct(' + val + ');"></a>'+
                              '</span>');
  }
  
  function ordersEditSelect(cid, oid, val) {
    if (val == 'invoice') {
      url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders&oid=OID&action=invoice'); ?>';
      window.open(url.replace('OID', oid));
    } else if (val == 'packing') {
      url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders&oid=OID&action=packaging_slip'); ?>';
      window.open(url.replace('OID', oid));
    } else if (val == 'customer') {
      url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=CID'); ?>';
      window.location = url.replace('CID', cid);
    }
    $('#orders_edit_select').val('');
  }
  
  $(function(){
    $('.transCommentsTrigger').click(function() {
      $(this).parents("tr").next().toggle(300);
      return false;
    });
  });
</script>