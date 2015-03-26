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
	                editor.windowManager.open({
	                    title: 'Insert Wiki Link',
	                    body: [
	                        {type: 'textbox', name: 'txtWikiLink', label: 'Link:'},
	                        {type: 'textbox', name: 'txtWikiShow', label: 'Show:'}
	                    ],
	                    onsubmit: function(e) {    
	                        editor.focus();
	                        editor.insertContent('[yadawiki link=&quot;' + e.data.txtWikiLink + '&quot; show=&quot;' + e.data.txtWikiShow + '&quot;]');
	                    }
	                });                     
                }  
            });  
        },  
       
        createControl : function(n, cm) {  
            return null;  
        }    
    });  
    tinymce.PluginManager.add('yada_wiki', tinymce.plugins.yada_wiki);  
})();
