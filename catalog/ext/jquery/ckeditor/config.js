/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here.
  // For the complete reference:
  // http://docs.ckeditor.com/#!/api/CKEDITOR.config


  config.extraPlugins = 'autogrow,font,colorbutton,tliyoutube,pastefromword';
  
  //below seeting is for removing editor footer
  config.removePlugins = 'elementspath,resize';
  config.resize_enabled = false;

  // The toolbar groups arrangement, optimized for two toolbar rows.
  config.toolbar = [
        { name: 'styles', items: [ 'FontSize' ] },
	      { name: 'basicstyles', groups: [ 'basicstyles', 'colors' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'TextColor' ] },
	      { name: 'paragraph', groups: [ 'list' ], items: [ 'NumberedList', 'BulletedList' ] },
	      { name: 'links', groups: ['links','insert'], items: [ 'Link', 'Unlink', '-', 'HorizontalRule', 'Image', 'tliyoutube' ] },
	      { name: 'clipboard', groups: [ 'clipboard', 'basicstyles' ], items: [  'PasteFromWord', '-', 'RemoveFormat' ] },
        ];

  // Remove some buttons, provided by the standard plugins, which we don't
  // need to have in the Standard(s) toolbar.
  config.removeButtons = 'Superscript,Strike,Table,Styles,Font,BGColor,Cut,Copy,Anchor';

  // Se the most common block elements.
  config.format_tags = 'p;h1;h2;h3;pre';

  // Make dialogs simpler.
  config.removeDialogTabs = 'image:advanced;link:advanced';
  
  // allow inline div and styles
  config.extraAllowedContent = 'p(*)[*]{*};div(*){*}[*];span(*)[*]{*}';
};