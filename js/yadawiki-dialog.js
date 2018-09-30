// Yada Wiki popup dialog functions
jQuery(document).ready(function() {
     jQuery("#popup_yw_dialog").dialog({
        autoOpen: false,
        modal: true,
        height: 300,
        width: 370
    });        
     jQuery("#popup_yw_toc_dialog").dialog({
        autoOpen: false,
        modal: true,
        height: 330,
        width: 350
    });  
   	jQuery('.popup-yw-cat-select').hide();
   	jQuery('.popup-yw-order-select').hide();
	jQuery('.popup-yw-output-select').hide();
  	jQuery('.popup-yw-columns-select').hide();          
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
function doYWOutput() {
	if( jQuery('#popup_yw_output').val()=="category-name" || jQuery('#popup_yw_output').val()=="category-slug" ) {
    	jQuery("#popup_yw_toc_dialog").dialog({height: 370});
    	jQuery('.popup-yw-cat-select').show();
	}
	else {
    	jQuery("#popup_yw_toc_dialog").dialog({height: 330});
		jQuery('.popup-yw-cat-select').hide();
	}	
}
function doYWListingType() {
	if(jQuery('#popup_yw_listing').val()=="toc") {
		jQuery('#popup_yw_category').val("");
		jQuery('#popup_yw_order').val("");
		jQuery('.popup-yw-cat-select').hide();
		jQuery('.popup-yw-order-select').hide();
		jQuery('.popup-yw-output-select').hide();
		jQuery('.popup-yw-columns-select').hide();
    	jQuery('.popup-yw-toc-doc').show();
	}
	else if(jQuery('#popup_yw_listing').val()=="category") {
		jQuery('#popup_yw_category').css("border","1px solid green");
		jQuery('.popup-yw-cat-select').show();
		jQuery('.popup-yw-order-select').show();
		jQuery('.popup-yw-output-select').hide();
		jQuery('.popup-yw-columns-select').hide();
		jQuery('.popup-yw-toc-doc').hide();
	}	
	else if(jQuery('#popup_yw_listing').val()=="index") {
		jQuery('#popup_yw_category').val("");
		jQuery('#popup_yw_order').val("");
		jQuery('.popup-yw-cat-select').hide();
		jQuery('.popup-yw-order-select').hide();
		jQuery('.popup-yw-output-select').show();
		jQuery('.popup-yw-columns-select').show();
		jQuery('.popup-yw-toc-doc').hide();
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
        var newShortCode = '[yadawiki link="' + jQuery('#popup_yw_link').val() + '" show="' + jQuery('#popup_yw_show').val() + '"' + ']';
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
    	var newShortCode = "";
        jQuery('#popup_yw_category').val(
            jQuery('#popup_yw_category').val().replace(/\"/g, "&quot;")
        );
        jQuery('#popup_yw_order').val(
            jQuery('#popup_yw_order').val().replace(/\"/g, "'")
        );

    	var outputType = jQuery('#popup_yw_listing').val();
    	if(outputType=="index") {
				if( jQuery('#popup_yw_output').val()=="category-name" || jQuery('#popup_yw_output').val()=="category-slug" ) {
			      	if( jQuery.trim(jQuery('#popup_yw_category').val()) !== "" ) {
				    		newShortCode = '[yadawiki-index type="' + jQuery('#popup_yw_output').val() + '"  category="' + jQuery('#popup_yw_category').val() + '" columns="' + jQuery('#popup_yw_columns').val() + '"]';					
			        }
			        else {
			        	jQuery('#popup_yw_category').css("border","1px solid red");
			        	return false;
			        }
				}
				else {
		    		newShortCode = '[yadawiki-index type="' + jQuery('#popup_yw_output').val() + '" columns="' + jQuery('#popup_yw_columns').val() + '"]';					
				}
    	}
    	else if(outputType=="toc") {
	        newShortCode = '[yadawikitoc show_toc="true"]'; 
    	}
		else { 
			if( jQuery.trim(jQuery('#popup_yw_category').val()) !== "" ) {
				newShortCode = '[yadawikitoc show_toc="true" category="' + jQuery('#popup_yw_category').val() + '" order="' + jQuery('#popup_yw_order').val() + '"]';
			}
			else {
				jQuery('#popup_yw_category').css("border","1px solid red");
				return false;
			}
		}
		ywEditor.selection.setContent(newShortCode);
		jQuery('#popup_yw_toc_dialog').dialog("close");
		jQuery('#popup_yw_listing').val("toc");
		jQuery('#popup_yw_category').val("");
		jQuery('#popup_yw_category').css("border","");
		jQuery('#popup_yw_order').val("");
		jQuery('#popup_yw_output').val("pages");
		jQuery('#popup_yw_columns').val("3");
		jQuery('.popup-yw-cat-select').hide();
		jQuery('.popup-yw-order-select').hide();
		jQuery('.popup-yw-output-select').hide();
		jQuery('.popup-yw-columns-select').hide();
		jQuery("#popup_yw_toc_dialog").dialog({height: 330});
		jQuery('.popup-yw-toc-doc').show();

		ywEditor.focus();
    }
}
