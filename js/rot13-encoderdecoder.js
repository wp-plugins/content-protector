function rotThirteen( input ) {
  return input.replace( /[a-zA-Z]/g,
    function( ch ) {
      return String.fromCharCode( ( ch <= "Z" ? 90 : 122 ) >= ( ch = ch.charCodeAt( 0 ) + 13 ) ? ch : ch - 26 );
  });
}

jQuery( document ).ready( function() {
	var the_event = "";
	if ( rot13Options.trigger_decode > 1 ) the_event += "dbl";
	if ( rot13Options.trigger_decode > 0 ) the_event += "click";

    jQuery( "body" ).on( the_event, "span[class='" + rot13Options.rot13_class + "']", function(e) {
		var replaced = rotThirteen( jQuery( this ).text() );

		if ( rot13Options.decode_method == 0 ) jQuery( this ).text( replaced );

		if ( rot13Options.decode_method >= 1 ) {
            jQuery( "span[class='" + rot13Options.rot13_class + "_popup']" ).remove();
			var decoded_popup = jQuery( "<span></span>" );
			decoded_popup.addClass( rot13Options.rot13_class + "_popup");
            decoded_popup.css( 'border', rot13Options.popup_border_width + 'px solid ' + rot13Options.popup_border_color );
            decoded_popup.css( 'border-radius', rot13Options.popup_border_radius + 'px' );
			decoded_popup.css( 'width', rot13Options.popup_width + 'px' );
			decoded_popup.css( 'padding', '5px' );
			decoded_popup.css( 'position', 'absolute' );
			decoded_popup.css( 'left', e.pageX );
			decoded_popup.css( 'top', e.pageY );
			decoded_popup.css( 'background-color', rot13Options.popup_background_color );
			decoded_popup.css( 'color', rot13Options.popup_text_color );
            decoded_popup.css( 'font-family', jQuery( this ).css( 'font-family' ) );
            decoded_popup.css( 'z-index', '99' );
			decoded_popup.text( replaced );
			decoded_popup.click( function( e ) { jQuery( this ).remove(); } );
		}
		jQuery( "body" ).after( decoded_popup );
		decoded_popup = null;
		return false;
	} );
	if ( rot13Options.decode_method >= 1 ) {
		jQuery( 'body' ).bind( 'click', function( e ) {
			jQuery( "span[class='" + rot13Options.rot13_class + "_popup']" ).remove();
		} );
	}
} );					