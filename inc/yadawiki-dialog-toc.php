<style>
 .ui-widget-overlay {
 	z-index: 10000 !important;
 	position: fixed !important;
 }
 .ui-dialog {
 	z-index: 100001 !important;
 }
#popup_yw_submit {
	width: auto;
	background-color: #6EA9D3;
	color: #FFF;
	padding: 8px;
	border: none;
	border-radius: 4px;
}
#popup_yw_submit:hover {
	cursor: pointer;
}    
</style>
<div id="popup_yw_toc_dialog" title="<?php _e("Yada Wiki Listing", 'yada_wiki_domain'); ?>" class="popup_dialog">
    <form id="popup_yw_toc_form">
        <table class="form-table">
            <tr>
                <th><label for="popup_yw_listing"><?php _e("Listing Type:", 'yada_wiki_domain'); ?></label></th>
                <td align="right">
									<select id="popup_yw_listing" name="popup_yw_listing" style="width:170px" onchange="javascript:doYWListingType();">
										<option value="toc" selected><?php _e('Output TOC Page', 'yada_wiki_domain'); ?></option>
										<option value="category"><?php _e('Output Wiki Category', 'yada_wiki_domain'); ?></option>
										<option value="index"><?php _e('Output Index', 'yada_wiki_domain'); ?></option>
									</select>
                </td>
            </tr>
            <tr class="popup-yw-output-select">
                <th><label for="popup_yw_output"><?php _e("Index of:", 'yada_wiki_domain'); ?></label></th>
                <td align="right">
									<select id="popup_yw_output" name="popup_yw_output" style="width:170px" onchange="javascript:doYWOutput();">
										<option value="pages" selected><?php _e('Pages', 'yada_wiki_domain'); ?></option>
										<option value="category-name"><?php _e('A Category By Name', 'yada_wiki_domain'); ?></option>
										<option value="category-slug"><?php _e('A Category By Slug', 'yada_wiki_domain'); ?></option>
										<option value="all-categories-name"><?php _e('All Categories By Name', 'yada_wiki_domain'); ?></option>
										<option value="all-categories-slug"><?php _e('All Categories By Slug', 'yada_wiki_domain'); ?></option>
									</select>
                </td>
            </tr>
            <tr class="popup-yw-cat-select">
                <th><label for="popup_yw_category"><?php _e("Category (req):", 'yada_wiki_domain'); ?></label></th>
                <td align="right">
									<select id="popup_yw_category" name="popup_yw_category" style="width:170px">
					                <?php 
								   		$args = array(
											'type'                     => 'yada_wiki',
											'child_of'                 => 0,
											'parent'                   => '',
											'orderby'                  => 'name',
											'order'                    => 'ASC',
											'hide_empty'               => 1,
											'hierarchical'             => 1,
											'taxonomy'                 => 'wiki_cats',
											'pad_counts'               => false 
										); 
										$categories = get_categories( $args );
										echo '<option value="" selected></option>';
										foreach ( $categories as $category ) {
											$option = '<option value="'.$category->cat_name.'">'.$category->cat_name.' ('.$category->category_count.')</option>';
											echo $option;
										}
									?>
									</select>
                </td>
            </tr>
            <tr class="popup-yw-order-select">
                <th><label for="popup_yw_order"><?php _e("Order:", 'yada_wiki_domain'); ?></label></th>
                <td align="right">
									<select id="popup_yw_order" name="popup_yw_order" style="width:170px">
										<option value="" selected></option>
										<option value="<?php _e('title', 'yada_wiki_domain'); ?>"><?php _e('Title A-Z', 'yada_wiki_domain'); ?></option>
										<option value="<?php _e('date', 'yada_wiki_domain'); ?>"><?php _e('Creation Date', 'yada_wiki_domain'); ?></option>
									</select>
                </td>
            </tr>
            <tr class="popup-yw-columns-select">
                <th><label for="popup_yw_columns"><?php _e("Columns:", 'yada_wiki_domain'); ?></label></th>
                <td align="right">
									<select id="popup_yw_columns" name="popup_yw_columns" style="width:170px">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3" selected>3</option>
										<option value="4">4</option>
									</select>
                </td>
            </tr>
            <tr class="popup-yw-toc-doc">
            	<td colspan="2">
            		<?php _e("Use this option to output the contents of the TOC page into another wiki page.", 'yada_wiki_domain'); ?>
            	</td>
            </tr>

            <tr>
                <th>&nbsp;</th>  
                <td align="right"><input type="button" onclick="javascript:doYWTOCSubmit();" name="popup_yw_submit" id="popup_yw_submit" value="<?php _e('Insert Shortcode', 'yada_wiki_domain'); ?>" /></td>
            </tr>
        </table>
    </form>
</div>
