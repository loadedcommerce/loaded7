<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info.php v1.0 2013-08-08 datazen $
*/
?>
<script>
function showInfo(id, img, title) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="showInfo">'+
               '  <div id="showInfoForm">'+
               '    <p class="align-center"><img src="../addons/' + id + '/images/' + img + '.png"></p>'+
               '  </div>'+
               '</div>',
      title: '<span id="infoContentTitle">' + title + '</span>',
      width: 680,
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
}
</script>