/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here.
  // For the complete reference:
  // http://docs.ckeditor.com/#!/api/CKEDITOR.config

  config.extraPlugins = 'font,colorbutton,autogrow';
  
  //below seeting is for removing editor footer
  config.removePlugins = 'elementspath,resize';
  config.resize_enabled = false;

  // The toolbar groups arrangement, optimized for two toolbar rows.
  config.toolbarGroups = [
    { name: 'styles' },
    { name: 'basicstyles', groups: [ 'basicstyles' ] },
    { name: 'colors' },
    { name: 'paragraph', groups: [ 'list'] },
    { name: 'links' },
    { name: 'insert'},
    { name: 'clipboard', groups: [ 'clipboard' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    { name: 'tools' },
    { name: 'others' }/*,
    { name: 'about' }*/
  ];



  // Remove some buttons, provided by the standard plugins, which we don't
  // need to have in the Standard(s) toolbar.
  config.removeButtons = 'Subscript,Superscript,Strike,Table,Styles,Format,Font,BGColor,Cut,Copy,Anchor,HorizontalRule,SpecialChar';

  // Se the most common block elements.
  config.format_tags = 'p;h1;h2;h3;pre';

  // Make dialogs simpler.
  config.removeDialogTabs = 'image:advanced;link:advanced';
  
  // allow inline div and styles
  config.extraAllowedContent = 'p(*)[*]{*};div(*){*}[*];span(*)[*]{*}';
};