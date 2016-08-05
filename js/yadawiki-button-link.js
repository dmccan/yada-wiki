/***************************************
* Add yada_wiki Button
***************************************/
(function($) {  
    tinymce.create('tinymce.plugins.yada_wiki_link', {  
        init : function(editor, url) {  
            editor.addButton('yada_wiki_link', {  
                title : 'Add Wiki Link',  
                image : url+'/img/wiki-link.png',  
                onclick : function() {  
	            	$('#popup_yw_dialog').dialog("open");
                    $("#popup_yw_link").focus();
                }  
            });  
        }
    });  
    tinymce.PluginManager.add('yada_wiki_link', tinymce.plugins.yada_wiki_link);  
})(jQuery);
