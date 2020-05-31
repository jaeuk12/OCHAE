/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.allowedContent = {
		    $1: {
		        // Use the ability to specify elements as an object.
		        elements: CKEDITOR.dtd,
		        attributes: true,
		        styles: true,
		        classes: true
		    }
		};
	
	config.disallowedContent = 'script; *[on*]';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.font_names = '궁서;굴림;명조;나눔고딕;나눔명조;서울남산체;서울한강체;DINpro;Helvetica Neue;Helvetica;sans-serif;' +  CKEDITOR.config.font_names;
	
	config.toolbar =
    [
     	['Source'],['Cut','Copy','Paste','PasteText'],
		['Bold','Italic','Underline','Strike','FontSize','Font'],['Undo','Redo'],
		['Link','Unlink','Image'],['Maximize']
    ];
};
