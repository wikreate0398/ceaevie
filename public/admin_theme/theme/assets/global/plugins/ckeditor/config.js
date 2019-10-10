/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.forcePasteAsPlainText = true;
	//config.allowedContent = true;

	config.toolbar = [
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', items: [ 'Scayt' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor', 'Youtube' ] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
		{ name: 'tools', items: [ 'Maximize' ] },
		{ name: 'document', items: [ 'Source' ] },
		'/',
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
		{ name: 'styles', items: [ 'Styles', 'Format' ] },
		{ name: 'about', items: [ 'About' ] }
	];

	config.extraPlugins = 'youtube';

	config.filebrowserBrowseUrl = '/theme/theme/assets/global/plugins/ckfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl = '/theme/theme/assets/global/plugins/ckfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = '/theme/theme/assets/global/plugins/ckfinder/browse.php?type=flash';
	config.filebrowserUploadUrl = '/theme/theme/assets/global/plugins/ckfinder/upload.php?type=files';
	config.filebrowserImageUploadUrl = '/theme/theme/assets/global/plugins/ckfinder/upload.php?type=images';
	config.filebrowserFlashUploadUrl = '/theme/theme/assets/global/plugins/ckfinder/upload.php?type=flash';

	config.removeButtons = 'Underline,Subscript,Superscript,PasteFromWord,PasteText,Undo,Redo,Scayt,About';
	config.enterMode = CKEDITOR.ENTER_BR; 

	config.allowedContent = true;
	//CKEDITOR.config.autoParagraph = true;
};
