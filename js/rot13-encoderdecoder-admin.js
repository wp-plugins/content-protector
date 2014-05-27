jQuery( document ).ready( function() {
    // Configure the Iris color pickers
    var colors =  eval( rot13AdminOptions.theme_colors );
    var num_colors_per_row = 9;
    var swatch_margin = 2; // px
    var num_rows = parseInt( colors.length / num_colors_per_row ) + 1;

	var rot13AdminColorOptions = {
		defaultColor: false,
		hide: true,
		palettes: colors
	};
    jQuery("input#rot13_encoder_decoder_popup_border_color, input#rot13_encoder_decoder_popup_text_color, input#rot13_encoder_decoder_popup_background_color, input#rot13_encoder_decoder_popup_box_shadow_color").wpColorPicker(rot13AdminColorOptions);
    var picker_inner_width = jQuery("div.iris-square").first().width() + jQuery("div.iris-slider").first().width();
    var picker_height = jQuery("div.iris-picker").first().height();
    var swatch_size = ( picker_inner_width / num_colors_per_row ) - swatch_margin;
    jQuery("div.iris-picker").css("height", picker_height + ( ( ( swatch_size + swatch_margin ) * num_rows ) + ( 4 * swatch_margin ) ) + "px");
    jQuery("a.iris-palette").css("height", swatch_size + "px").css("width", swatch_size + "px").css("margin", swatch_margin + "px");
    jQuery("div.iris-palette-container").css("bottom", ( 2 * swatch_margin ) + "px");

    // Process the administrative ROT13'ing of a comment
    jQuery('.rot13_comment_submit').on('click', function() {
        var the_form = jQuery('<form></form>')
            .attr('method', 'post')
            .attr('action', '/wp-admin/edit-comments.php');
        var the_div = jQuery(this).closest('div.rot13_comment_div');
        jQuery(the_form).css('display', 'none');
        jQuery('body').append(the_form);
        jQuery(the_form).append(jQuery('<input>')
            .attr('name', 'rot13_comment')
            .attr('value', jQuery(the_div).find('input.rot13_comment:checked').val()));
        jQuery(the_form).append(jQuery('<input>')
            .attr('name', 'rot13_comment_reason')
            .attr('value', jQuery(the_div).find('textarea.rot13_comment_reason').val()));
        if (jQuery(the_div).find('input.rot13_comment_show_tooltip:checked')) {
            jQuery(the_form).append(jQuery('<input>')
                .attr('name', 'rot13_comment_show_tooltip')
                .attr('value', jQuery(the_div).find('input.rot13_comment_show_tooltip:checked').val()));
        }
        jQuery(the_form).append(jQuery('<input>')
            .attr('name', 'rot13_comment_nonce')
            .attr('value', jQuery(the_div).find('input[name="rot13_comment_nonce"]').val()));
        jQuery(the_form).append(jQuery('<input>')
            .attr('name', 'comment_ID')
            .attr('value', jQuery(the_div).find('input.rot13_comment_ID').val()));
        jQuery(the_form).submit();

    });

    jQuery('input.rot13_comment').on('click', function() {
        var the_div = jQuery(this).siblings('div.rot13_comment_reason_div');
        if (jQuery(this).val() == '0')
            jQuery(the_div).hide("slow");
        else if (jQuery(this).val() == '1')
            jQuery(the_div).show("slow");

    }).trigger();
});