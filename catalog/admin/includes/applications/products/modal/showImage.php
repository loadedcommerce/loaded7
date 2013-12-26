<?php
/*
  $Id: showImage.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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