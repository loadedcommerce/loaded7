/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here.
  // For the complete reference:
  // http://docs.ckeditor.com/#!/api/CKEDITOR.config

  config.extraPlugins = 'font,colorbutton';
  
  //below seeting is for removing editor footer
  config.removePlugins = 'resize';
  config.resize_enabled = false;

  // The toolbar groups arrangement, optimized for two toolbar rows.
  config.toolbarGroups = [
    { name: 'styles' },
    { name: 'basicstyles', groups: [ 'basicstyles','Subscript','Superscript','Strike' ] },
    { name: 'colors' },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi'] },
    { name: 'links' },
    { name: 'insert', groups: ['Table','Anchor','HorizontalRule','SpecialChar'] },
    { name: 'clipboard', groups: [ 'clipboard' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    { name: 'tools' },
    { name: 'others'}
  ];



  // Remove some buttons, provided by the standard plugins, which we don't
  // need to have in the Standard(s) toolbar.
  config.removeButtons = 'Styles';

  // Se the most common block elements.
  config.format_tags = 'p;h1;h2;h3;pre';

  // Make dialogs simpler.
  config.removeDialogTabs = 'image:advanced;link:advanced';
  
  // allow inline div and styles
  config.extraAllowedContent = 'p(*)[*]{*};div(*){*}[*];span(*)[*]{*}';
};