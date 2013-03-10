/*
  $Id: placeholder.js v1.0 2011-11-04  datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$(document).ready(function() {                
  if(!Modernizr.input.placeholder){
    $("input").each(
    function(){
      var inputField = $(this);
      if(inputField.val()=="" && inputField.attr("placeholder")!=""){
      
        inputField.val(inputField.attr("placeholder"));
        
        inputField.keydown(function(){
          if(inputField.val()==inputField.attr("placeholder")) inputField.val("");
        });
        
        inputField.focus(function(){
          setTimeout(function() {
            inputField.setSelectionRange(0, 0);
          }, 0);
        });        
        
        inputField.blur(function(){
          if(inputField.val()=="") inputField.val(inputField.attr("placeholder"));
        });
        
        $(inputField).closest('form').submit(function(){
          var form = $(this);
          if(!form.hasClass('placeholderPending')){
            $('input',this).each(function(){
              var clearInput = $(this);
              if(clearInput.val()==clearInput.attr("placeholder")) clearInput.val("");
            });
            form.addClass('placeholderPending');
          }
        });
      
      }
    });
  }
});