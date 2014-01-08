jQuery( document ).ready( function() {
    var colors =  eval( rot13AdminOptions.theme_colors );
    var num_colors_per_row = 9;
    var swatch_margin = 2; // px
    var num_rows = parseInt( colors.length / num_colors_per_row ) + 1;

	var rot13AdminColorOptions = {
		defaultColor: false,
		hide: true,
		palettes: colors
	};
    jQuery("input#rot13_encoder_decoder_popup_border_color, input#rot13_encoder_decoder_popup_text_color, input#rot13_encoder_decoder_popup_background_color").wpColorPicker(rot13AdminColorOptions);
    var picker_inner_width = jQuery("div.iris-square").first().width() + jQuery("div.iris-slider").first().width();
    var picker_height = jQuery("div.iris-picker").first().height();
    var swatch_size = ( picker_inner_width / num_colors_per_row ) - swatch_margin;
    jQuery("div.iris-picker").css("height", picker_height + ( ( ( swatch_size + swatch_margin ) * num_rows ) + ( 4 * swatch_margin ) ) + "px");
    jQuery("a.iris-palette").css("height", swatch_size + "px").css("width", swatch_size + "px").css("margin", swatch_margin + "px");
    jQuery("div.iris-palette-container").css("bottom", ( 2 * swatch_margin ) + "px");
});