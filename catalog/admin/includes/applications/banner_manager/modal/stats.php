<?php
/*
  $Id: stats.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#bannerStats TD, TH { padding: 0 5px; }
</style>
<script>
function showStats(id) {
  $.modal({
      content: '<div id="statsContent">'+
               '  <form name="statsType" action="#" method="post">'+
               '    <p id="statsFormElements" align="right"></p>'+
               '  </form>'+
               '  <div class="standard-tabs">'+
               '    <ul class="tabs">'+
               '      <li><?php echo lc_link_object('#section_graph', $lC_Language->get('section_graph')); ?></li>'+
               '      <li><?php echo lc_link_object('#section_stats', $lC_Language->get('section_stats')); ?></li>'+
               '    </ul>'+
               '    <div class="tabs-content">'+
               '      <div id="section_graph"><label for="section_graph"></label>'+
               '        <p id="graphInfo"></p>'+
               '      </div>'+
               '      <div id="section_stats"><label for="section_stats"></label>'+
               '        <table id="bannerStats" border="0" width="580" cellspacing="0" cellpadding="2" class="dataTable" align="center">'+
               '          <thead>'+
               '            <tr>'+
               '              <th align="left"><?php echo $lC_Language->get('table_heading_source'); ?></th>'+
               '              <th align="left"><?php echo $lC_Language->get('table_heading_views'); ?></th>'+
               '              <th align="left"><?php echo $lC_Language->get('table_heading_clicks'); ?></th>'+
               '            </tr>'+
               '          </thead>'+
               '          <tbody>'+
               '          </tbody>'+
               '        </table>'+
               '      </div>'+
               '    </div>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_banner_stats'); ?>',
      width: 600,
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
  updateStats(id);
} 

function updateStats(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var type = $("#type").val();
  var month = $("#month").val();
  var year = $("#year").val();
  var d = new Date();
  if (month === undefined) {
    month = d.getMonth() + 1;
  }
  if (year === undefined) {
    year = d.getFullYear();
  }
  if (type === undefined) {
    type = 'daily';
  }  
  
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getStats&bid=BID&type=TYPE&month=MONTH&year=YEAR'); ?>'  
  $.getJSON(jsonLink.replace('BID', parseInt(id)).replace('TYPE', type).replace('MONTH', month).replace('YEAR', year),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }  
      $("#statsFormElements").html(data.formElements);       
      $("#graphInfo").html(data.graphInfo);       
      $("#bannerStats > tbody").html(data.bannerStats); 
    }
  );
}           
</script>