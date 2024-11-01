function sctplugin() {
	sct_date = new Date();
	sct_date = (sct_date.getMonth()+1)+'/'+sct_date.getDate()+'/'+sct_date.getFullYear()+' '+sct_date.getHours()+':'+sct_date.getMinutes();
    return '[sct date="'+sct_date+'" align="none" size="1"]';
}

(function() {
    tinymce.create('tinymce.plugins.sctplugin', {
 
        init : function(ed, url){
            ed.addButton('sctplugin', {
            title : 'Insert CountDown Code',
                onclick : function() {
                    ed.execCommand(
                    'mceInsertContent',
                    false,
                    sctplugin()
                    );
                },
                image: url + "/sct.png"
            });
        }
    });

    tinymce.PluginManager.add('sctplugin', tinymce.plugins.sctplugin);

})();