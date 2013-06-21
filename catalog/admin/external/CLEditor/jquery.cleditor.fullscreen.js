/**
* added for fullscreen mode of clEditor by (verybadbug)
* http://stackoverflow.com/questions/10525448/cleditor-text-editor-on-fullscreen-mode  
*/

(function($) {
  //Style for fullscreen mode
  var fullscreen = 'height:100%; left:0; position:absolute; top:0; width:100%; z-index:1;',
  style = '';

  // Define the fullscreen button
  $.cleditor.buttons.fullscreen = {
    name: 'fullscreen',
    image: 'fullscreen.gif',
    title: 'Fullscreen',
    command: '',
    popupName: '',
    popupClass: '',
    popupContent: '',
    getPressed: fullscreenGetPressed,
    buttonClick: fullscreenButtonClick,
  };

  // Add the button to the default controls before the bold button
  $.cleditor.defaultOptions.controls = $.cleditor.defaultOptions.controls.replace("source", "source | fullscreen");

  function fullscreenGetPressed(data) {
    return data.editor.$main.hasClass('fullscreen');
  };

  function fullscreenButtonClick(e, data) {
    var main = data.editor.$main;

    if (main.hasClass('fullscreen')) main.attr('style', style).removeClass('fullscreen');
    else {
      style = main.attr('style');
      main.attr('style', fullscreen).addClass('fullscreen');
    };

    editor.focus();
    return false;
  }
})(jQuery);
