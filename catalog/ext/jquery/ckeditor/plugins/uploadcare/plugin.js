// Uploadcare CKeditor plugin
// Version: 1.2.0

CKEDITOR.plugins.add('uploadcare', {
    init : function(editor) {
        var me = this;
        var widget_version = '0.17.0';
        var widget_url = 'https://ucarecdn.com/widget/' + widget_version +
                         '/uploadcare/uploadcare-' + widget_version + '.min.js'

        // Check for custom crop
        if (typeof UPLOADCARE_CROP === 'undefined') {
            UPLOADCARE_CROP = true;
        }

        UPLOADCARE_AUTOSTORE = true;
        CKEDITOR.scriptLoader.load(widget_url);

        editor.addCommand('showUploadcareDialog', {
            allowedContent: 'img',
            requiredContent: 'img',
            exec : function() {
                var dialog = uploadcare.openDialog().done(function(file) {
                    file.done(function(fileInfo) {
                        url = fileInfo.cdnUrl;
                        if (fileInfo.isImage) {
                            editor.insertHtml('<img src="'+url+'" />', 'unfiltered_html');
                        } else {
                            editor.insertHtml('<a href="'+url+'">'+fileInfo.name+'</a>', 'unfiltered_html');
                        }
                    });
                });
            }
        });

        editor.ui.addButton('Uploadcare', {
            label : 'Uploadcare',
            toolbar : 'insert',
            command : 'showUploadcareDialog',
            icon : this.path + 'images/logo.png',
            allowedContent: 'img[alt,dir,id,lang,longdesc,!src,title]{*}(*)',
            requiredContent: 'img[alt,src]'
        });
    }
});
