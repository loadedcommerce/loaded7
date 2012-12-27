(function($){
  $.fn.fixFloat = function(options){

    var defaults = {
      enabled: true
    };
    var options = $.extend(defaults, options);

    var offsetTop;    /**Distance of the element from the top of window**/
    var s;        /**Scrolled distance from the top of window through which we have moved**/
    var fixMe = true;
    var repositionMe = true;
    var headerOffset = 35;  /** header height offset **/

    var tbh = $(this);
    var originalOffset = tbh.css('top');  /**Get the actual distance of the element from the top**/

    tbh.css({'position':'absolute'});

    if(options.enabled){
      $(window).scroll(function(){
        var offsetTop = tbh.offset().top;  /**Get the current distance of the element from the top**/
        var s = parseInt($(window).scrollTop(), 10) + headerOffset;  /**Get the from the top of window through which we have scrolled**/
        var menuOpen = (isMenuOpen() == 1) ? true : false;

        var fixMe = true;
        if(s > offsetTop){
          fixMe = true;
        }else{
          fixMe = false;
        }

        if(s < parseInt(originalOffset, 10)){
          repositionMe = true;
        }else{
          repositionMe = false;
        }

        leftOffset = (menuOpen) ? '370px;' : '110px';
        rightOffset = (menuOpen) ? '259px' : '0px';

        if(fixMe){
          var cssObj = {
            'position' : 'fixed',
            'top' : headerOffset,
            'right' : rightOffset
          }
          tbh.css(cssObj);
          $('#floating-menu-div').addClass('bg');
          $('#floating-menu-div-listing').addClass('bg');
          $('#floating-button-container-title').removeClass('hidden').attr('style', 'left:' + leftOffset);
        }
        if(repositionMe){
          var cssObj = {
            'position' : 'absolute',
            'top' : originalOffset,
            'right' : '0px'
          }
          $('#floating-menu-div').removeClass('bg');
          $('#floating-menu-div-listing').removeClass('bg');
          $('#floating-button-container-title').addClass('hidden');
          tbh.css(cssObj);
        }
      });
    }
  };
})(jQuery);