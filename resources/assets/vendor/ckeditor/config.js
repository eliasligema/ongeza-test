/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
    // config.uiColor = '#AADC6E';
    // config.toolbarGroups = [{
    //     "name": "basicstyles",
    //     "groups": ["basicstyles"]
    //   },
    //   {
    //     "name": "links",
    //     "groups": ["links"]
    //   },
    //   {
    //     "name": "paragraph",
    //     "groups": ["list", "blocks"]
    //   }
    // ]

    config.toolbar = [{
        "name": "basicstyles",
        "items": [
            "Styles", "Format", "Bold", 
            "Italic", "Strike", "RemoveFormat", 
            "Blocks", "NumberedList", "BulletedList", 
            "Outdent", "Indent", "Link", "Image"
        ]
      },
    ]
};
