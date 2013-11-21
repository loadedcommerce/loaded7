<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: showImage.php v1.0 2013-08-08 datazen $
*/
?>
<script>
function showImage(src, wid, hgt) {
  if (wid < 1) wid = 300;
  m = wid * .25;
  w = parseInt(wid) + m;
 
  size = ''
  if ((wid != 'undefined' && hgt != 'undefined') && (wid > 0 && hgt > 0)) size = '(' + wid + 'x' + hgt + ')'
    
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="showImage">'+
               '  <p align="center"><img src="' + src + '" border="0"></p>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_preview_image'); ?> ' + size,
      width: w,
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