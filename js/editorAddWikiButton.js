/***************************************
* Add yada_wiki Button
***************************************/
(function() {  
    tinymce.create('tinymce.plugins.yada_wiki', {  
        init : function(editor, url) {  
            editor.addButton('yada_wiki', {  
                title : 'Add Wiki Link',  
                image : url+'/img/wiki-link.png',  
                onclick : function() {  
                     editor.insertContent('[yadawiki link="" show=""]');  
                }  
            });  
        },  
       
        createControl : function(n, cm) {  
            return null;  
        }    
    });  
    tinymce.PluginManager.add('yada_wiki', tinymce.plugins.yada_wiki);  
})();
