CKEDITOR.editorConfig = function (config) {
    config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
        {name: 'links', groups: ['links']},
        {name: 'forms', groups: ['forms']},
        {name: 'tools', groups: ['tools']},
        {name: 'insert', groups: ['insert']},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others', groups: ['others']},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'paragraph', 'bidi']},
        '/',
        {name: 'styles', groups: ['styles']},
        {name: 'colors', groups: ['colors']}
    ];
    config.toolbar= [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
        // { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
        { name: 'others', items: [ '-' ] },
        { name: 'about', items: [ 'About' ] }
    ];
    config.removeButtons = 'PasteText,PasteFromWord,Undo,Redo,Anchor,RemoveFormat,About,Styles,Blockquote,Outdent,Indent,NumberedList,SpecialChar,Unlink,Scayt';
    config.fillEmptyBlocks = false;
    config.autoParagraph = false;
    config.enterMode = CKEDITOR.ENTER_P;
    config.forcePasteAsPlainText = true;
    config.shiftEnterMode = CKEDITOR.ENTER_P;
    config.language = 'en';
};

CKEDITOR.on('instanceReady', function (ev) {

    var blockTags = ['div','h1','h2','h3','h4','h5','h6','p','pre','li','blockquote','ul','ol',
    'table','thead','tbody','tfoot','td','th', 'br'];
    ev.editor.dataProcessor.writer.lineBreakChars = '\n';
    for (var i = 0; i < blockTags.length; i++)
    {
       ev.editor.dataProcessor.writer.setRules( blockTags[i], {
          indent : false,
          breakBeforeOpen : true,
          breakAfterOpen : false,
          breakBeforeClose : false,
          breakAfterClose : true
       });
    }

    });