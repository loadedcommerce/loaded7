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
  var isVisible = false;
  var clickedAway = false;
  $('[data-toggle="popover-mobile"]').popover({
    trigger: 'click',
    'placement': 'left'
  }).click(function(e) {
      $('[data-toggle="popover-mobile"]').not(this).popover('hide');
      isVisible = true;
      clickedAway = false;
  });
  $(document).click(function (e) {
    if (isVisible & clickedAway) {
      $('[data-toggle="popover-mobile"]').popover('hide');
      isVisible = clickedAway = false;
    } else {
      clickedAway = true;
    }
  });
  
  // added for h1 titles to auto fit window width
  $.fn.fitText = function( kompressor, options ) {
    // Setup options
    var compressor = kompressor || 1,
        settings = $.extend({
          'minFontSize' : 14,
          'maxFontSize' : 40
        }, options);
    return this.each(function(){
      // Store the object
      var $this = $(this);
      // modified for Loaded7 
      // Resizer() resizes items based on the object width divided by the compressor * 17
      var resizer = function () {
        $this.css('font-size', Math.max(Math.min($this.width() / (compressor*17), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
      };
      // do the magic.
      resizer();
      // Call on resize. Opera debounces their resize by default. 
      $(window).on('resize', resizer);
    });
  };
  $("h1.logo").fitText();
});