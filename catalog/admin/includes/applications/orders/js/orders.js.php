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
    var p = parseInt('<?php echo $_GET["editProduct"];?>');    
    var o = parseInt('<?php echo $_GET["orderstotal"];?>');    
    if(p == 1) {
      $("#id_tab_orders_summary").removeClass("active");
      $("#id_tab_orders_products").addClass("active");

      // Display Address form (Hide Personal Form)
      $('#section_orders_summary').hide();
      $('#section_orders_products').show();
    } else if(o == 1) {
      $("#id_tab_orders_summary").removeClass("active");
      $("#id_tab_order_totals").addClass("active");

      // Display Address form (Hide Personal Form)
      $('#section_orders_summary').hide();
      $('#section_order_totals').show();
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
  function addOrderProduct(oId) {    
    var pid = parseInt($("#add_product").val()); 
    url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders&action=add_product&oID=OID&pID=PID&editProduct=1'); ?>';
    window.location = url.replace('OID', oId).replace('PID',pid);
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

  function removeOrderTotal(oid,ot_class) {
    var name1 = "#title_"+ot_class;
    var name2 = "#value_"+ot_class;
    var name = $(name1).val() + $(name2).val();    

    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 4) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    $.modal({
      content: '<div id="deleteOrdersTotal">'+
             '  <div id="deleteConfirm">'+
             '    <p id="deleteConfirmMessage"><?php echo $lC_Language->get('introduction_delete_orders_total'); ?>'+
             '      <p><b>' + decodeURI(name.replace(/\+/g, '%20')) + '</b></p>'+
             '    </p>'+
             '  </div>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_delete_orders_total'); ?>',
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
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=removeOrderTotal&oId=OID&otClass=OTCLASS'); ?>'  
        $.getJSON(jsonLink.replace('OID', oid).replace('OTCLASS', ot_class),
            function (data) {
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                $(location).attr('href',url);
              }
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              }

              window.location = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, "orders=' + oid + '&action=save&orderstotal=1");?>';
            }            
          );
         
          win.closeModal();
        }
      }
    },
    buttonsLowPadding: true
  });
    
  }

  
  function addOrderTotal(oid) {
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;
    }
    $.modal({
    content: '<div id="addOrderTotalContainer">'+
             '  <div id="section_OrderTotal">'+
           '      <form name="addOrderTotalForm" id="addOrderTotalForm" autocomplete="off" action="" method="post">'+               
           '        <p class="button-height inline-label">'+
             '      <label for="type" class="label"><?php echo $lC_Language->get('text_orders_total_type'); ?>'+
             '      <?php echo lc_draw_pull_down_menu('orders_total_type', null, null, 'class="input with-small-padding mid-margin-top" id="id_orders_total_type" onchange="updateSubOrderTotal(this.value);"'); ?>'+
             '      </label>'+
           '        </p>'+
           '        <span id = "id_shipping" style = "display:none"><p class="button-height inline-label" >'+
             '      <label for="shipping" class="label"><?php echo $lC_Language->get('text_orders_total_shipping'); ?>'+
             '      <?php echo lc_draw_pull_down_menu('orders_total_shipping', null, null, 'class="input with-small-padding mid-margin-top" id="id_orders_total_shipping"'); ?>'+
             '      </label>'+
           '        </p></span>'+
            '        <span id = "id_coupon" style = "display:none"><p class="button-height inline-label" >'+
             '      <label for="coupon" class="label"><?php echo $lC_Language->get('text_orders_total_coupon'); ?>'+
             '      <?php echo lc_draw_pull_down_menu('orders_total_coupon', null, null, 'class="input with-small-padding mid-margin-top" id="id_orders_total_coupon"'); ?>'+
             '      </label>'+
           '        </p></span>'+
           '        <span id="id_counter" style = "display:none">0</span>'+
           '      </form>'+
           '    </div>'+ 
           '</div>',
        title: '<?php echo $lC_Language->get('text_add_orders_total'); ?>',
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
          classes:  'glossy',
          click:    function(win) { showAddedOrderTotal(oid); }
          },
          '<?php echo $lC_Language->get('button_cancel'); ?>': {
            classes:  'glossy',
            click:    function(win) { win.closeModal(); }
          }
        },
        buttonsLowPadding: true
    });

    //getFormData(oid, opid);
    //$.modal.all.centerModal();

    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getOrdersTotalData&oid=OID'); ?>'  
    $.getJSON(jsonLink.replace('OID', parseInt(oid)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        }        

        $("#id_orders_total_type").empty();
        var cnt = 1;
        $.each(data.order_total_modules.entries, function(val, text) {
          var selected = (data.module_class == text['module_class']) ? 'selected="selected"' : '';
          if (cnt == 1) {
            $("#id_orders_total_type").append(
              $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
            );
            cnt++;
          }
          if (data.module_class == text['module_class']) {
            $("#id_orders_total_type").closest("span + *").prevAll("span.select-value:first").text(text['module_title']);
          }
          
          $("#id_orders_total_type").append(
              $("<option " + selected + "></option>").val(text['module_class']).html(text['module_title'])
            );

        });        
        /*$.each($('input[type="text"]', '#order'),function(k){      
          var name = $(this).attr('name');
          if(name.substr(0,6) == "value_") {
            alert(name.substr(6));
            $("#id_orders_total_type option[value='"+name.substr(6)+"']").remove();
          }
        });*/


        $("#id_orders_total_coupon").empty();
        var cnt = 1;
        $.each(data.coupons.entries, function(val, text) {
          var selected = (data.coupons_id == text['coupons_id']) ? 'selected="selected"' : '';
          if (cnt == 1) {
            $("#id_orders_total_coupon").append(
              $("<option></option>").val('0').html('<?php echo $lC_Language->get('text_none'); ?>')
            );
            cnt++;
          }
          if (data.coupons_id == text['coupons_id']) {
            $("#id_orders_total_coupon").closest("span + *").prevAll("span.select-value:first").text(text['name']);
          }
          $("#id_orders_total_coupon").append(
            $("<option " + selected + "></option>").val(text['coupons_id']).html(text['name'])
          );
        });

        $.modal.all.centerModal();



/*
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
        */
      }
    );


  }

  function updateSubOrderTotal(type) { 
    if(type == 'coupon') {
      $('#id_coupon').show();
      $('#id_shipping').hide();
    } else if(type == 'shipping') {
      $('#id_coupon').hide();
      $('#id_shipping').show();
    } else {
      $('#id_shipping').hide();
      $('#id_coupon').hide();
    }
  }
  function showAddedOrderTotal(oID) {

    var id_counter = parseInt($('#id_counter').html())+1;
    var id_orders_total_type = $('#id_orders_total_type').val();    
    var id_orders_total_type_title = $('#id_orders_total_type option:selected').text();    
    var id_orders_total_shipping = $('#id_orders_total_shipping option:selected').text();   
    var id_orders_total_coupon = $('#id_orders_total_coupon option:selected').text();

    var title = id_orders_total_type_title;

    if(id_orders_total_shipping != '' && id_orders_total_shipping != 'None') {
      title += '' + id_orders_total_shipping; 
    } else if (id_orders_total_coupon != ''  && id_orders_total_coupon != 'None') {
      title += '' + id_orders_total_coupon; 
    } 

     var result = '<p id = "addedOrderTotalRow_'+id_orders_total_type+'" class="button-height inline-label"><span class="icon-list icon-anthracite ">&nbsp;<input type = "text" name = "title_'+id_orders_total_type+'" value = "'+title+'" style="width:30%;"></span>&nbsp;&nbsp; <input type = "text" name = "value_'+id_orders_total_type+'" value = "" style="width:10%;" onkeyup = "updateGrandTotal();">&nbsp;&nbsp;<a href="javascript://" onclick="removeOrderTotalRow('+oID+','+id_orders_total_type+')" class="icon-minus-round icon-red with-tooltip" title="remove"></a></p>';

     var flag = true;
     $.each($('input[type="text"]', '#order'),function(k){      
      var name = $(this).attr('name');
      if(name.substr(0,6) == "value_" && name.substr(6) == id_orders_total_type) {
        flag = false;
      }
    });
    if(flag == true) {
     $('#addedOrderTotal').append(result);
    }
     $('#id_counter').html(id_counter);
     $.modal.all.closeModal();

  }
  function removeOrderTotalRow(oId,rowId) {
    var row = "#addedOrderTotalRow_"+rowId; 
    $(row).hide();    
  }
  function updateGrandTotal() { 
    var total = 0;
    $.each($('input[type="text"]', '#order'),function(k){      
      var name = $(this).attr('name');
      if(name.substr(0,6) == "value_" && name.substr(6) != 'total') {        
        total += parseFloat($(this).val()); 
      } else if(name.substr(0,6) == "value_" && name.substr(6) == 'coupon') {        
        total += parseFloat($(this).val()); 
      }
    });   
    $('#value_total').val(total);
    $('#id_grand_total').html(total);
  }
  function saveOrderTotal(oId) {
    
    var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
    if (parseInt(accessLevel) < 2) {
      $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
      return false;    }
    
    var formData = $("#order").serialize();
    $("#order").submit();
   
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveOrderTotal&oid=OID'); ?>' 
      alert("111");
    $.getJSON(jsonLink.replace('OID', parseInt(oId)),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
          return false;
        }        
        url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders=OID&action=save&orderstotal=1'); ?>';
        $(location).attr('href',url.replace('OID', oId));
      });  
      

        
  }
</script>