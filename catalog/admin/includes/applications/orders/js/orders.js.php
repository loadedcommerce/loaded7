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
    setActiveTab();
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
          $(location).attr('href', url);
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
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateOrderStatus&NVP'); ?>'  
    $.getJSON(jsonLink.replace('NVP', nvp),     
      function (data) {
        $("[name=comment]").val(""); 
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href', url);
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
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=executePostTransaction&NVP'); ?>'  
    $.getJSON(jsonLink.replace('NVP', nvp),     
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href', url);
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
  
  function deleteOrderProduct(opid, pid, name) {
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    $.modal({
      content: '<div id="deleteOrderProduct">'+
               '  <div id="deleteProductConfirm">'+
               '    <form name="opDelete" id="opDelete" action="" method="post">'+
               '      <p id="deleteProductConfirmMessage"><?php echo $lC_Language->get('introduction_delete_order_product'); ?>'+
               '        <p><b>' + name.replace(/\+/g, '%20') + '</b></p>'+
               // later we add restock functionality
               // '        <p><label for="restock" class="label"><?php echo $lC_Language->get('field_restock_product_quantity'); ?></label><?php echo '&nbsp;' . lc_draw_checkbox_field('restock', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>'+
               '      </p>'+
               '    </form>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_delete_order_product'); ?>',
      width: 300,
      actions: {
        'Close' : {
          color: 'red',
          click: function(win) { win.closeModal(); }
        }
      },
      buttons: {
        '<?php echo $lC_Language->get('button_cancel'); ?>': {
          classes:  'glossy',
          click:    function(win) { win.closeModal(); }
        },
        '<?php echo $lC_Language->get('button_delete'); ?>': {
          classes:  'blue-gradient glossy',
          click:    function(win) {
            var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteOrderProduct&opid=OPID&pid=PID&oid=OID'); ?>'  
            $.getJSON(jsonLink.replace('OPID', opid).replace('PID', pid).replace('OID', '<?php echo $_GET[$lC_Template->getModule()]; ?>'),
              function (data) {
                if (data.rpcStatus == -10) { // no session
                  var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                  $(location).attr('href', url);
                }
                if (data.rpcStatus != 1) {
                  $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
                  return false;
                }
                if (data.rpcStatus == 1) {
                  url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=OID&action=save&tabProducts=1'); ?>';
                  $(location).attr('href', url.replace('OID', '<?php echo $_GET[$lC_Template->getModule()]; ?>'));
                }
              }
            );
            win.closeModal();
          }
        }
      },
      buttonsLowPadding: true
    });
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
          $(location).attr('href', url);
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
          $(location).attr('href', url);
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
        /*$("#editTaxclass").empty();
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
        });*/
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
          $(location).attr('href', url);
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
        /*$("#editTaxclass").empty();
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
        });*/
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
          $(location).attr('href', url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        } 
        updateOrderList();        
        url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=OID&action=save&tabProducts=1'); ?>';
        $(location).attr('href', url.replace('OID', oid));
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
             '    <form name="editProductForm" id="editProductForm" autocomplete="off" action="" method="post">'+               
             '      <p class="button-height inline-label">'+
             '        <label for="product" class="label"><?php echo $lC_Language->get('text_products'); ?></label>'+
             '        <?php echo lc_draw_pull_down_menu('product', null, null, 'class="input with-small-padding mid-margin-top" id="editProduct" onchange="updateEditProduct();"'); ?>'+
             '      </p>'+
             //'      <p class="button-height inline-label">'+
             //'        <label for="taxClass" class="label"><?php //echo $lC_Language->get('text_tax_class'); ?>'+
             //'        <?php //echo lc_draw_pull_down_menu('taxClass', null, null, 'class="input with-small-padding mid-margin-top" id="editTaxclass"'); ?>'+
             //'        </label>'+
             //'      </p>'+
             '      <p class="button-height inline-label">'+
             '        <label for="price" class="label"><?php echo $lC_Language->get('text_price'); ?></label>'+
             '        <?php echo lc_draw_input_field('price', null, 'class="input mid-margin-top" id="editPrice"'); ?>'+
             '      </p>'+
             '      <p class="button-height inline-label">'+
             '        <label for="quantity" class="label"><?php echo $lC_Language->get('text_quantity'); ?></label>'+
             '        <?php echo lc_draw_input_field('quantity', null, 'class="input mid-margin-top" id="editQuantity"'); ?>'+
             '      </p>'+               
             '    </form>'+
             '  </div>'+               
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
        classes: 'glossy',
        click: function(win) { saveEditproduct(); }
      },
          '<?php echo $lC_Language->get('button_close'); ?>': {
            classes: 'glossy',
            click: function(win) { win.closeModal(); }
          }
        },
        buttonsLowPadding: true
    });

    getFormData(oid, opid);
    $.modal.all.centerModal();
  }
  
  function addOrderProduct(oId, field) {    
    var pid = $("#" + field).val();
    url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=add_product&oID=OID&pID=PID&tabProducts=1'); ?>';
    window.location = url.replace('OID', oId).replace('PID', pid);
  }

  function cancelOrderProductEdit(val) {
    $("#buttons_" + val).html('<span class="button-group">'+
                              '  <a class="button compact icon-pencil" href="javascript:void(0);" onclick="editOrderProduct(' + val + ');"><?php echo $lC_Language->get('text_edit'); ?></a>'+
                              '  <a class="button compact icon-trash with-tooltip" title="<?php echo $lC_Language->get('text_delete'); ?>" href="javascript:void(0)" onclick="deleteOrderProduct(' + val + ');"></a>'+
                              '</span>');
  }
  
  function ordersEditSelect(cid, oid, val) {
    if (val == 'invoice') {
      url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&oid=OID&action=invoice'); ?>';
      window.open(url.replace('OID', oid));
    } else if (val == 'packing') {
      url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&oid=OID&action=packaging_slip'); ?>';
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

  function removeOrderTotal(oid, ot_class, currencySymbolLeft) {
    var name1 = "#title_" + ot_class;
    var name2 = "#value_" + ot_class;
    var name = $(name1).val() + ' ' + $(name2).val();    

    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 4) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    
    $.modal({
      content: '<div id="deleteOrdersTotal">'+
               '  <div id="deleteConfirm">'+
               '    <p id="deleteConfirmMessage"><?php echo $lC_Language->get('introduction_delete_order_total'); ?>'+
               '      <p><b>' + decodeURI(name.replace(/\+/g, '%20').replace(/%/g, '')) + '</b></p>'+
               '    </p>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_delete_order_total'); ?>',
      width: 300,
      actions: {
        'Close' : {
          color: 'red',
          click: function(win) { win.closeModal(); }
        }
      },
      buttons: {
        '<?php echo $lC_Language->get('button_cancel'); ?>': {
          classes: 'glossy',
          click: function(win) { win.closeModal(); }
        },
        '<?php echo $lC_Language->get('button_delete'); ?>': {
          classes: 'blue-gradient glossy',
          click: function(win) {
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=removeOrderTotal&oId=OID&otClass=OTCLASS'); ?>'  
          $.getJSON(jsonLink.replace('OID', oid).replace('OTCLASS', ot_class),
              function (data) {
                if (data.rpcStatus == -10) { // no session
                  var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                  $(location).attr('href', url);
                }
                if (data.rpcStatus != 1) {
                  $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                  return false;
                }              
                removeOrderTotalRow(oid, ot_class, currencySymbolLeft);
              }            
            );
            win.closeModal();
          }
        }
      },
      buttonsLowPadding: true
    });    
  }
  
  function addOrderTotal(oid,currencySymbolLeft) {
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    $.modal({
      content: '<div id="addOrderTotalContainer">'+
               '  <div id="section_OrderTotal">'+
               '    <form name="addOrderTotalForm" id="addOrderTotalForm" autocomplete="off" action="" method="post">'+               
               '      <p class="button-height inline-label">'+
               '        <label for="type" class="label"><?php echo $lC_Language->get('text_order_total_type'); ?>'+
               '          <?php echo lc_draw_pull_down_menu('order_total_type', null, null, 'class="input with-small-padding mid-margin-top" id="id_order_total_type" onchange="updateSubOrderTotal(this.value);"'); ?>'+
               '        </label>'+
               '        <div id="id_shipping" style="display:none;">'+
               '          <p class="button-height inline-label">'+
               '            <label for="shipping" class="label"><?php echo $lC_Language->get('text_order_total_shipping'); ?>'+
               '              <?php echo lc_draw_pull_down_menu('order_total_shipping', null, null, 'class="input with-small-padding mid-margin-top" id="id_order_total_shipping"'); ?>'+
               '            </label>'+
               '          </p>'+
               '        </div>'+
               '        <div id="id_coupon" style="display:none;">'+
               '          <p class="button-height inline-label">'+
               '            <label for="coupon" class="label"><?php echo $lC_Language->get('text_order_total_coupon'); ?>'+
               '              <?php echo lc_draw_pull_down_menu('order_total_coupon', null, null, 'class="input with-small-padding mid-margin-top" id="id_order_total_coupon"'); ?>'+
               '            </label>'+
               '          </p>'+
               '        </div>'+
               '        <div id="id_tax" style="display:none;">'+
               '          <p class="button-height inline-label">'+
               '            <label for="tax" class="label"><?php echo $lC_Language->get('text_order_total_tax'); ?>'+
               '              <?php echo lc_draw_pull_down_menu('order_total_tax', null, null, 'class="input with-small-padding mid-margin-top" id="id_order_total_tax"'); ?>'+
               '            </label>'+
               '          </p>'+
               '        </div>'+
               '        <span id="id_counter" style="display:none;">0</span>'+
               '      </p>'+  
               '    </form>'+
               '  </div>'+ 
               '</div>',
      title: '<?php echo $lC_Language->get('text_add_order_total'); ?>',
      width: 600,
      scrolling: true,
      actions: {
        'Close' : {
          color: 'red',
          click: function(win) { win.closeModal(); }
        }
      },
      buttons: {
        '<?php echo $lC_Language->get('button_continue'); ?>': {
        classes: 'glossy',
        click: function(win) { showAddedOrderTotal(oid,currencySymbolLeft); }
        },
        '<?php echo $lC_Language->get('button_cancel'); ?>': {
          classes: 'glossy',
          click: function(win) { win.closeModal(); }
        }
      },
      buttonsLowPadding: true
    });

    //getFormData(oid, opid);
    $.modal.all.centerModal();

    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getOrderTotalsData&oid=OID'); ?>'  
    $.getJSON(jsonLink.replace('OID', parseInt(oid)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href', url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        }        

        $("#id_order_total_type").empty();
                
        var cnt = 1;
        
        $.each(data.order_total_modules.entries, function(val, text) {
          var selected = (data.module_class == text['module_class']) ? 'selected="selected"' : '';
          if (cnt == 1) {
            $("#id_order_total_type").append(
              $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
            );
            cnt++;
          }
          if (data.module_class == text['module_class']) {
            $("#id_order_total_type").closest("span + *").prevAll("span.select-value:first").text(text['module_title']);
          }          
          $("#id_order_total_type").append(
            $("<option " + selected + "></option>").val(text['module_class']).html(text['module_title'])
          );
        });
      }
    );
  }

  function updateSubOrderTotal(type) {
    if (type == 'coupon') {

      $('#id_shipping').hide();      
      $('#id_tax').hide();

      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCouponOrderTotalsData'); ?>'  
      $.getJSON(jsonLink,
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href', url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          $("#id_order_total_coupon").empty();
          if (data.coupons) {
            var cnt = 1;
            $.each(data.coupons.entries, function(val, text) {
              var selected = (data.coupons_id == text['coupons_id']) ? 'selected="selected"' : '';
              if (cnt == 1) {
                $("#id_order_total_coupon").append(
                  $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
                );
                cnt++;
              }
              if (data.coupons_id == text['coupons_id']) {
                $("#id_order_total_coupon").closest("span + *").prevAll("span.select-value:first").text(text['name']);
              }
              $("#id_order_total_coupon").append(
                $("<option " + selected + "></option>").val(text['coupons_id']).html(text['name'])
              );
            });
            $('#id_coupon').show();
          } else {
            $("#id_coupon").html('<?php echo $lC_Language->get('text_no_coupons_exist'); ?>');
            $('#id_coupon').show();
          }
        }
      );
    } else if (type == 'shipping') {
      
      $('#id_coupon').hide();
      $('#id_tax').hide();

      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getShippingMethodsData'); ?>'  
      $.getJSON(jsonLink,
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href', url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          $("#id_order_total_shipping").empty();
                   
          if (data.shipping) {
            var cnt = 1;
            $.each(data.shipping.methods, function(val, text) {
              var selected = (data.code == text['code']) ? 'selected="selected"' : '';
              if (cnt == 1) {
                $("#id_order_total_shipping").append(
                  $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
                );
                cnt++;
              }
              if (data.coupons_id == text['code']) {
                $("#id_order_total_shipping").closest("span + *").prevAll("span.select-value:first").text(text['title']);
              }
              $("#id_order_total_shipping").append(
                $("<option " + selected + "></option>").val(text['code']).html(text['title'])
              );
            });
            $('#id_shipping').show();
          } else {
            $("#id_shipping").html('<?php echo $lC_Language->get('text_no_shipping_methods_exist'); ?>');
            $('#id_shipping').show();
          } 
          
        }
      );
    } else if (type == 'tax') {
      
      $('#id_shipping').hide();
      $('#id_coupon').hide();
      
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getTaxMethodsData'); ?>'  
      $.getJSON(jsonLink,
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href', url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          $("#id_order_total_tax").empty();
                   
          if (data.tax) {
            var cnt = 1;
            $.each(data.tax.methods, function(val, text) {
              var selected = (data.tax_rates_id == text['tax_rates_id']) ? 'selected="selected"' : '';
              if (cnt == 1) {
                $("#id_order_total_tax").append(
                  $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
                );
                cnt++;
              }
              if (data.tax_rates_id == text['tax_rates_id']) {
                $("#id_order_total_tax").closest("span + *").prevAll("span.select-value:first").text(text['tax_description']);
              }
              $("#id_order_total_tax").append(
                $("<option " + selected + "></option>").val(text['tax_rates_id']).html(text['tax_description'])
              );
            });
            $('#id_tax').show();
          } else {
            $("#id_tax").html('<?php echo $lC_Language->get('text_no_tax_rate_exist'); ?>');
            $('#id_tax').show();
          } 
          
        }
      );
    } else {
      $('#id_shipping').hide();
      $('#id_coupon').hide();
      $('#id_tax').hide();
    }
  }
  
  function showAddedOrderTotal(oID, currencySymbolLeft) {
    
    var id_counter = parseInt($('#id_counter').html())+1;
    var type = $('#id_order_total_type').val();    
    var title = $('#id_order_total_type option:selected').text();    
    var shipping = $('#id_order_total_shipping option:selected').text();   
    var coupon = $('#id_order_total_coupon option:selected').text();
    var coupon_id = $('#id_order_total_coupon').val();
    var tax = $('#id_order_total_tax option:selected').text();
    var tax_rates_id = $('#id_order_total_tax').val();    
    var num_value = 0;
    
   if (type == 'coupon') {
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCouponData&cId=CID'); ?>'  
      $.getJSON(jsonLink.replace('CID', parseInt(coupon_id)),
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href', url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          
          var couponAmount = data.reward;          
          var couponType = data.type; 
          if(couponType == 'T') {
            var value = $('#value_sub_total').val();
            var number = Number(value.replace(/[^0-9\.]+/g,""));
            couponAmount = (couponAmount/100) * number;
          }
            
          num = parseFloat(couponAmount);
          num_value = ' - ' + currencySymbolLeft + num.toFixed(2);
          title += ' (' + coupon + ')'; 
          $('#value_coupon').val(num_value);
          $('#title_coupon').val(title);
          updateGrandTotal(currencySymbolLeft);
        }
      );
    } else if (type == 'shipping') {
      title =  shipping ;
      //$('#value_shipping').val(num_value);
      $('#title_shipping').val(title);

      /*
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getShippingData'); ?>'  
      $.getJSON(jsonLink,
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href', url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          var shippingAmount = data.shippingAmount;
          
          num = parseFloat(shippingAmount);
          num_value = ' - ' + currencySymbolLeft + num.toFixed(2);
          title += ' (' + shipping + ')';
          $('#value_shipping').val(num_value);
          $('#title_shipping').val(title);
          updateGrandTotal(currencySymbolLeft);
        }
      );*/
    } else if (type == 'tax') {      
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getTaxData&tId=TID'); ?>'  
      $.getJSON(jsonLink.replace('TID', parseInt(tax_rates_id)),
        function (data) {        
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href', url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
            return false;
          }
          var tax_rate = data.tax_rate;          
          var value = $('#value_sub_total').val();
          var number = Number(value.replace(/[^0-9\.]+/g,""));
          taxAmount = (tax_rate/100) * number;

          num = parseFloat(taxAmount);
          num_value = currencySymbolLeft + num.toFixed(2);  
          title = tax ;
          $('#value_tax').val(num_value);
          $('#title_tax').val(title);
          updateGrandTotal(currencySymbolLeft);
        }
      );
    }

    var result = '<div class="with-small-padding align-right" id="addedOrderTotalRow_' + type + '">' + 
                 '  <span class="icon-list icon-anthracite">&nbsp;' +
                 '    <input type="text" id="title_' + type + '" name="title_' + type + '" value="' + title + '" class="input with-small-padding" style="width:30%;margin-left:-4px;margin-right:-4px;">' +
                 '  </span>&nbsp;&nbsp;' +
                 '  <input type="text" id = "value_' + type + '" name="value_' + type + '" value="' + currencySymbolLeft + num_value + '" class="input with-small-padding" style="width:10%;text-align:right;min-width:65px;" onkeyup="updateGrandTotal(\'' + currencySymbolLeft + '\');">&nbsp;&nbsp;' +
                 '  <a href="javascript:void(0);" onclick="removeOrderTotalRow(' + oID + ', \'' + type + '\', \'' + currencySymbolLeft + '\')" class="icon-minus-round icon-red with-tooltip" title="remove"></a>' +
                 '</div>';

    var flag = true;
    $.each($('input[type="text"]', '#order'), function(k) {      
      var name = $(this).attr('name');
      if (name.substr(0, 6) == "value_" && name.substr(6) == type) {
        flag = false;
      }
    });
   
    if (flag == true && type != 0) {
    //if (type != 0) {
      $('#addedOrderTotal').append(result);      
      updateGrandTotal(currencySymbolLeft);
    } 
    
    $('#id_counter').html(id_counter);
    $.modal.all.closeModal();
  }  
  
  function removeOrderTotalRow(oId, rowId, currencySymbolLeft) {
    var row = "#addedOrderTotalRow_"+rowId; 
    $(row).remove(); 
    updateGrandTotal(currencySymbolLeft);
  }
  
  function updateGrandTotal(currencySymbolLeft) { 
    var total = 0;
    $.each($('input[type="text"]', '#order'),function(k) {      
      var name = $(this).attr('name');
      var value = $(this).val();
      var number = Number(value.replace(/[^0-9\.]+/g,""));
      if(name.substr(0,6) == "value_" && name.substr(6) != 'total' && name.substr(6) != 'coupon') {        
        total += parseFloat(number); 
      } else if(name.substr(0,6) == "value_" && name.substr(6) == 'coupon') {        
        total = parseFloat(total) - parseFloat(number); 
      }
    });  
    num = parseFloat(total);
    num_value = currencySymbolLeft + num.toFixed(2);

    $('#value_total').val(num_value);
    $('#id_grand_total').html(num_value);
  }
  
  function saveOrderTotal(oId) {    
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    
    var formData = $("#order").serialize();

    $('#action_order_total').val('save_order_total');; // for temporary use
    $("#order").submit(); // for temporary use
    
    /* 
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveOrderTotal&oid=OID'); ?>' 
    alert("111");
    $.getJSON(jsonLink.replace('OID', parseInt(oId)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href', url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        }        
        url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=OID&action=save&orderstotal=1'); ?>';
        $(location).attr('href', url.replace('OID', oId));
      }
    );  
    */
  }
  
  function setActiveTab(tab) {
    if (tab) { // if the tab is not sent in the function call 
      if (tab == 'tabProducts') var tabProducts = 1;
      if (tab == 'tabCustomer') var tabCustomer = 1;
      if (tab == 'tabShipping') var tabShipping = 1;
      if (tab == 'tabStatus') var tabStatus = 1;
      if (tab == 'tabFraud') var tabFraud = 1;
      if (tab == 'tabPayments') var tabPayments = 1;
      if (tab == 'tabTransactions') var tabTransactions = 1;
      if (tab == 'tabTotals') var tabTotals = 1;
    } else { //get it from url
      var tabProducts = parseInt('<?php echo $_GET["tabProducts"];?>');
      var tabCustomer = parseInt('<?php echo $_GET["tabCustomer"];?>');    
      var tabShipping = parseInt('<?php echo $_GET["tabShipping"];?>');    
      var tabStatus = parseInt('<?php echo $_GET["tabStatus"];?>');    
      var tabFraud = parseInt('<?php echo $_GET["tabFraud"];?>');    
      var tabPayments = parseInt('<?php echo $_GET["tabPayments"];?>');    
      var tabTransactions = parseInt('<?php echo $_GET["tabTransactions"];?>');    
      var tabTotals = parseInt('<?php echo $_GET["tabTotals"];?>');
    }
        
    if (tabProducts == 1) {
      // hide all but products 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide();
      // show products
      $("#id_tab_orders_products").addClass("active");
      $('#section_orders_products').show();
    } else if (tabCustomer == 1) {
      // hide all but customers 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide();
      // show customers
      $("#id_tab_orders_customers").addClass("active");
      $('#section_orders_customers').show();
    } else if (tabShipping == 1) {
      // hide all but shipping 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide(); 
      // show shipping
      $("#id_tab_orders_shipping").addClass("active");
      $('#section_orders_shipping').show();
    } else if (tabStatus == 1) {
      // hide all but status 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide(); 
      // show status
      $("#id_tab_orders_status").addClass("active");
      $('#section_orders_status').show();
    } else if (tabFraud == 1) {
      // hide all but fraud 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide(); 
      // show fraud
      $("#id_tab_orders_fraud").addClass("active");
      $('#section_orders_fraud').show();
    } else if (tabPayments == 1) {
      // hide all but payments 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide(); 
      // show payments
      $("#id_tab_orders_payments").addClass("active");
      $('#section_orders_payments').show();
    } else if (tabTransactions == 1) {
      // hide all but transactions 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_totals").removeClass("active");
      $('#section_orders_totals').hide(); 
      // show transactions
      $("#id_tab_orders_transactions").addClass("active");
      $('#section_orders_transactions').show();
    } else if (tabTotals == 1) {
      // hide all but totals 
      $("#id_tab_orders_summary").removeClass("active");
      $('#section_orders_summary').hide();
      $("#id_tab_orders_products").removeClass("active");
      $('#section_orders_products').hide();
      $("#id_tab_orders_customer").removeClass("active");
      $('#section_orders_customer').hide();
      $("#id_tab_orders_shipping").removeClass("active");
      $('#section_orders_shipping').hide();
      $("#id_tab_orders_status").removeClass("active");
      $('#section_orders_status').hide();
      $("#id_tab_orders_fraud").removeClass("active");
      $('#section_orders_fraud').hide();
      $("#id_tab_orders_payments").removeClass("active");
      $('#section_orders_payments').hide();
      $("#id_tab_orders_transactions").removeClass("active");
      $('#section_orders_transactions').hide(); 
      // show totals
      $("#id_tab_orders_totals").addClass("active");
      $('#section_orders_totals').show();
    }
  }
</script>