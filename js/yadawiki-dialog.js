// Yada Wiki popup dialog functions
jQuery(document).ready(function() {
     jQuery("#popup_yw_dialog").dialog({
        autoOpen: false,
        modal: true,
        height: 270,
        width: 325
    });        
     jQuery("#popup_yw_toc_dialog").dialog({
        autoOpen: false,
        modal: true,
        height: 275,
        width: 300
    });        
});
function doYWLookup(what) {
    if(what.length > 2) {
        jQuery("#popup_yw_link").autocomplete({
            source: function(request, response) {
                jQuery.ajax({
                    url: ajaxurl,
                    dataType: "json",
                    data: {
                        action : 'yada_wiki_suggest',
                        term : jQuery('#popup_yw_link').val()
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },                
            type: "POST",
            minLength: 2,
            delay: 100
        });        
    }
}
function checkYWSubmit(event) {
    if (event.keyCode == 13) {
        doYWSubmit();
    }        
}
function checkYWTOCSubmit(event) {
    if (event.keyCode == 13) {
        doYWTOCSubmit();
    }        
}
function doYWSubmit() {
    if (!jQuery('#popup_yw_link').val()) {
        alert('Please enter a link');
        return false;
    }
    try {
        ywEditor = tinyMCE.activeEditor;
    } catch(e) {
        alert('Unable to insert shortcode: ' + e); return;
    }
    if (ywEditor) {
        jQuery('#popup_yw_link').val(
            jQuery('#popup_yw_link').val().replace(/\"/g, "&quot;")
        );
        jQuery('#popup_yw_show').val(
            jQuery('#popup_yw_show').val().replace(/\"/g, "'")
        );
        var newShortCode = '[yadawiki link="' + jQuery('#popup_yw_link').val()
            + '" show="' + jQuery('#popup_yw_show').val() + '"' + ']';
        ywEditor.selection.setContent(newShortCode);
        jQuery('#popup_yw_link').val("");
        jQuery('#popup_yw_show').val("");
        jQuery('#popup_yw_dialog').dialog("close");
        ywEditor.focus();
    }
}
function doYWTOCSubmit() {
    try {
        ywEditor = tinyMCE.activeEditor;
    } catch(e) {
        alert('Unable to insert shortcode: ' + e); return;
    }
    if (ywEditor) {
        jQuery('#popup_yw_category').val(
            jQuery('#popup_yw_category').val().replace(/\"/g, "&quot;")
        );
        jQuery('#popup_yw_order').val(
            jQuery('#popup_yw_order').val().replace(/\"/g, "'")
        );
        var newShortCode = '[yadawikitoc show_toc="true"'; 
        if( jQuery.trim(jQuery('#popup_yw_category').val()) !== "" ) {
    		newShortCode = newShortCode + ' category="' + jQuery('#popup_yw_category').val() + '" order="' + jQuery('#popup_yw_order').val() + '"';
    	}
        newShortCode = newShortCode +  ']';
        ywEditor.selection.setContent(newShortCode);
        jQuery('#popup_yw_category').val("");
        jQuery('#popup_yw_order').val("");
        jQuery('#popup_yw_toc_dialog').dialog("close");
        ywEditor.focus();
    }
}
