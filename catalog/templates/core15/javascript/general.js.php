<?php
/**
  @package    catalog::templates::javascript
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.js.php v1.0 2013-08-08 datazen $
*/
?>
$(document).ready(function() {
  $('[data-toggle="popover-mobile"]').popover({
    trigger: 'click',
    'placement': 'left'
  }).click(function(e) {
      $('[data-toggle="popover-mobile"]').not(this).popover('hide');
      $('.popover-content').append('<a class="close" style="position: absolute; top: 3px; right: 6px; color: red;"><i class="fa fa-times-circle red"></i></a>');
      clickedAway = false;
      isVisible = true;
      e.preventDefault();
  });
  $(document).click(function (e) {
    if (isVisible & clickedAway) {
      $('[data-toggle="popover-mobile"]').popover('hide');
      isVisible = clickedAway = false;
    } else {
      clickedAway = true;
    }
  });
});