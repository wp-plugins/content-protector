jQuery( document ).ready( function() {

	var rot13AdminColorOptions = {
		defaultColor: false,
		hide: true,
		palettes: true
	};
	jQuery( "input#rot13_encoder_decoder_popup_border_color, input#rot13_encoder_decoder_popup_text_color, input#rot13_encoder_decoder_popup_background_color" ).wpColorPicker( rot13AdminColorOptions );
});