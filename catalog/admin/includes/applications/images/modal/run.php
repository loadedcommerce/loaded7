<?php
/*
  $Id: run.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#runGroup { padding-bottom:20px; }
</style>
<script>
function doAction(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  if (id == 'check') {  
        $.modal({
            content: '<div id="checkContent">'+
                     '  <style>'+
                     '  .dataColGroup { text-align: left; }'+ 
                     '  .dataColTotal { text-align: center; }'+ 
                     '  #imagesCheckDataTable TD { padding: 5px 0 0 0; }'+
                     '  #imagesCheckDataTable thead { border-bottom: 1px solid grey; }'+ 
                     '  </style>'+ 
                     '  <div class="checkPageContainer">'+ 
                     '    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="display" id="imagesCheckDataTable">'+ 
                     '      <thead>'+ 
                     '        <tr>'+ 
                     '          <th align="left" style="font-weight:bold;"><?php echo $lC_Language->get('table_heading_groups'); ?></th>'+ 
                     '          <th align="center" style="font-weight:bold;"><?php echo $lC_Language->get('table_heading_totals'); ?></th>'+ 
                     '        </tr>'+ 
                     '      </thead>'+ 
                     '      <tbody></tbody>'+ 
                     '      <tfoot></tfoot>'+ 
                     '    </table>'+ 
                     '  </div>'+ 
                     '</div>',
            title: '<?php echo $lC_Language->get('modal_heading_check_images'); ?>',
            width: 500,
            scrolling: false,
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
        var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=checkImages'); ?>';   
        oTable = $('#imagesCheckDataTable').dataTable({
          "bProcessing": true,
          "sAjaxSource": dataTableDataURL,
          "bPaginate": false,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": false,
          "bInfo": false,
          "bAutoWidth": false,
          "bDestroy": true,
          "aoColumns": [{ "sWidth": "400px", "sClass": "dataColGroup" },
                        { "sWidth": "400px", "sClass": "dataColTotal" }]
        });  
        if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
          $('.modal').attr('style', 'top:20% !important; left: 19%;  margin-left: -50px;');  
        }         
  } else {
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getResizeInfo'); ?>'  
    $.getJSON(jsonLink,     
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
          $(location).attr('href',url);
        } 
        $.modal({
            content: '<div id="resizeContent">'+
                     '  <div class="resizePageContainer">'+
                     '    <div id="statusResults" class="dataTables_processing" style="display:none;"><?php echo $lC_Language->get('icon_processing'); ?></div>'+  
                     '    <form name="resizeInfo" id="resizeInfo" action="" method="post">'+
                     '    <div id="resizeSelection"></div>'+
                     '    </form>'+
                     '  </div>'+
                     '</div>',
            title: '<?php echo $lC_Language->get('modal_heading_resize_images'); ?>',
            width: 500,
            scrolling: false,
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
              },
              '<?php echo $lC_Language->get('button_execute'); ?>': {
                classes:  'blue-gradient glossy',
                click:    function(win) {   
                  var nvp = $("#resizeInfo").serialize();  
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=resizeImages&BATCH'); ?>'  
                  $.getJSON(jsonLink.replace('BATCH', nvp),        
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
                        $(location).attr('href',url);
                      }                
                      win.closeModal();
                      //  we want to display the result listing html
                      $.modal({
                          content: '<div id="resizeResultsContent">'+
                                   '  <style>'+
                                   '  .dataColResultsGroup { text-align: left; }'+ 
                                   '  .dataColResultsTotal { text-align: center; }'+  
                                   '  #resizeResultsDataTable TD { padding: 5px 0 0 0; }'+
                                   '  #resizeResultsDataTable thead { border-bottom: 1px solid grey; }'+                                    
                                   '  </style>'+
                                   '  <div class="resizeResultsPageContainer">'+
                                   '    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="display" id="resizeResultsDataTable">'+
                                   '      <thead>'+
                                   '        <tr>'+
                                   '          <th align="left" style="font-weight:bold;"><?php echo $lC_Language->get('table_heading_groups'); ?></th>'+
                                   '          <th align="center" style="font-weight:bold;"><?php echo $lC_Language->get('table_heading_total_resized'); ?></th>'+
                                   '        </tr>'+
                                   '      </thead>'+
                                   '      <tbody id="resizeResultsTbody"></tbody>'+
                                   '      <tfoot></tfoot>'+
                                   '    </table>'+
                                   '  </div>'+
                                   '</div>',
                          title: '<?php echo $lC_Language->get('modal_heading_resize_images'); ?>',
                          width: 500,
                          scrolling: false,
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
                      $("#resizeResultsTbody").html(rdata.html);
                    }              
                  );                  

                  win.closeModal();
                }
              }
            },
            buttonsLowPadding: true
        });        
        $("#resizeSelection").html(data.html);
        if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
          $('.modal').attr('style', 'top:20% !important; left: 19%;  margin-left: -50px;');  
        }         
      }
    );    
  }  
}
</script>