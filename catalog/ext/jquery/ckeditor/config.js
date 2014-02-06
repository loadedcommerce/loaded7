/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here.
  // For the complete reference:
  // http://docs.ckeditor.com/#!/api/CKEDITOR.config
  
  config.extraPlugins = 'autogrow,font,colorbutton,image2,pastefromword,oembed,tabletools,imagepaste,uploadcare,blockquote,specialchar,slideshow,stylesheetparser';
 
  //below seeting is for removing editor footer
  //config.removePlugins = 'resize';
  config.resize_enabled = false;

  //config.contentsCss = '../../../templates/core/css/template.css';
  config.stylesSet = [];

  // The Default toolbar groups arrangement.
  config.toolbar = [
    { name: 'styles', items: [ 'FontSize' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'colors' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'TextColor' ] },
    { name: 'paragraph', groups: [ 'list' ], items: [ 'NumberedList', 'BulletedList' ] },
    { name: 'links', groups: ['links','insert'], items: [ 'Link', 'Unlink', '-', 'HorizontalRule', 'Image', 'tliyoutube' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'basicstyles' ], items: [  'PasteFromWord', '-', 'RemoveFormat' ] },
  ];

  // The Minimum toolbar groups arrangement.
  config.toolbar_Minimum = [
    { name: 'styles', items: [ 'FontSize' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'colors' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'TextColor' ] },
    { name: 'paragraph', groups: [ 'list' ], items: [ 'NumberedList', 'BulletedList' ] },
    { name: 'links', groups: ['links','insert'], items: [ 'Link', 'Unlink', '-', 'HorizontalRule', 'Image', 'tliyoutube' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'basicstyles' ], items: [  'PasteFromWord', '-', 'RemoveFormat' ] },
  ];

  // The Standard toolbar groups arrangement.
  config.toolbar_Standard = [
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
    { name: 'links', groups: ['links'], items: [ 'Link', 'Unlink', 'Anchor' ] },
    { name: 'insert',  groups: ['table','insert'], items: [  'Image', 'Table', 'tliyoutube', 'HorizontalRule', 'SpecialChar' ] },
    { name: 'clipboard', groups: [ 'clipboard' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
    { name: 'tools', items: [ 'Maximize' ] },
    { name: 'about', items: [ 'About' ] }
  ];

  // The Full toolbar groups arrangement.
  config.toolbar_Full = [
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
    { name: 'links', groups: ['links'], items: [ 'Link', 'Unlink', 'Anchor' ] },
    { name: 'insert',  groups: ['insert', 'table'], items: [  'Image', 'Table', 'oembed', 'HorizontalRule', 'SpecialChar', '-', 'Slideshow', 'Uploadcare' ] },
    { name: 'clipboard', groups: [ 'clipboard' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
    { name: 'tools', items: [ 'Maximize' ] },
    { name: 'about', items: [ 'About' ] }
  ];

  // Remove some buttons, provided by the standard plugins, which we don't
  // need to have in the Standard(s) toolbar.
  // config.removeButtons = 'Styles';

  // Se the most common block elements.
  config.format_tags = 'p;h1;h2;h3;pre';

  // Make dialogs simpler.
  //config.removeDialogTabs = 'image:advanced;link:advanced';
 
  // allow inline div and styles
  config.extraAllowedContent = 'p(*)[*]{*};div(*){*}[*];span(*)[*]{*}';
};