<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: configuration.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Template, $lC_Language;
?>
<script>
$(document).ready(function() {
  var gid = '<?php echo (int)$_GET['gID']; ?>';
  var title = '<?php echo lC_Configuration_Admin::getGroupTitle($_GET['gID']); ?>';
  if (gid == 0) {
    gid = 1;
    title = '<?php echo $lC_Language->get('default_group'); ?>';
  }
  showGroup(gid, title);
});

function showGroup(id, name) {
  var isLoggedIn = '<?php echo (isset($_SESSION['admin'])) ? 1 : 0; ?>';
  if (isLoggedIn == 0) {
    var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
    $(location).attr('href',url);
  }
  $("#cfgTitle").html('<strong>' + name + '</strong>');
  var maxGrpID = '<?php echo lC_Configuration_Admin::getMaxGroupId(); ?>';
  for( i=1; i < maxGrpID+1; i++) {
    //$("#cfgGroup" + i).css('listStyle', 'circle').find('a').css({'fontWeight': 'normal', 'textDecoration': 'none', 'color': 'black'});
    $("#cfgLink" + i).removeClass('cfg-menu-selected');
  }
  $("#cfgLink" + id).addClass('cfg-menu-selected');

  // update big-menu current marker
  $('.cfg-open').each(function() {
    $(this).removeClass('current navigable-current');
  });
  $('#big-menu_' + name.toLowerCase().replace(" ", "_")).addClass('current navigable-current').change();

  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&gid=GID&action=getAll&view=VIEW'); ?>';
  oTable = $('#dataTable').dataTable({
      "bProcessing": true,
      "sAjaxSource": dataTableDataURL.replace('GID', parseInt(id)).replace('VIEW', $.template.mediaQuery.name),
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bSort": false,
      "bInfo": false,
      "bDestroy": true,
      "aoColumns": [{ "sWidth": "50%", "sClass": "dataColTitle" },
                    { "sWidth": "30%", "sClass": "dataColValue" },
                    { "sWidth": "20%", "sClass": "dataColAction" }]
  });
}
</script>