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
global $lC_Customer;
?>
<script><!--
$(document).ready(function() {
  
  var isB2B = '<?php echo (defined('ADDONS_SYSTEM_LOADED_7_B2B_STATUS') && ADDONS_SYSTEM_LOADED_7_B2B_STATUS == 1) ? 1 : 0; ?>';
  var custAccess = '<?php echo (defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS') && B2B_SETTINGS_GUEST_CATALOG_ACCESS > 0) ? (int)B2B_SETTINGS_GUEST_CATALOG_ACCESS : 0; ?>';
  var isGuest = '<?php echo (($lC_Customer->isLoggedOn() === false) ? 1 : 0); ?>';
  if (isB2B && isGuest) {
    if (custAccess == 33) { // view catalog
      $('.pricing-row').hide();
      $('.buy-btn-div').hide();
    } else if (custAccess == 66) { // see pricing
      $('.buy-btn-div').hide();
    }  
  }
  
  
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

/* 
  Thanks to CSS Tricks for pointing out this bit of jQuery
  http://css-tricks.com/equal-height-blocks-in-rows/
  It's been modified into a function called at page load and then each time the page is resized. 
  One large modification was to remove the set height before each new calculation. 
 */

equalheight = function(container) {
  var currentTallest = 0,
      currentRowStart = 0,
      rowDivs = new Array(),
      $el,
      topPosition = 0;
  $(container).each(function() {
    $el = $(this);
    $($el).height('auto')
    topPostion = $el.position().top;
    if (currentRowStart != topPostion) {
      for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
        rowDivs[currentDiv].height(currentTallest);
      }
      rowDivs.length = 0; // empty the array
      currentRowStart = topPostion;
      currentTallest = $el.height();
      rowDivs.push($el);
    } else {
      rowDivs.push($el);
      currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
    }
    for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
      rowDivs[currentDiv].height(currentTallest);
    }
  });
}
  
$(window).load(function() {
  equalheight('.content-new-products-container .thumbnail');
  equalheight('.product-listing-module-container .thumbnail');
  equalheight('.content-categories-container .thumbnail');
  equalheight('.content-also-purchased-products-container .thumbnail');
  equalheight('.content-featured-products-container .thumbnail');
  equalheight('.content-recently-visited-container .thumbnail');
  equalheight('.content-upcoming-products-container .thumbnail');
});

$(window).resize(function(){
  equalheight('.content-new-products-container .thumbnail');
  equalheight('.product-listing-module-container .thumbnail');
  equalheight('.content-categories-container .thumbnail');
  equalheight('.content-also-purchased-products-container .thumbnail');
  equalheight('.content-featured-products-container .thumbnail');
  equalheight('.content-recently-visited-container .thumbnail');
  equalheight('.content-upcoming-products-container .thumbnail');
});
//--></script>