<?php
$the_props = unserialize( base64_decode( $_GET['args'] ) );
foreach( $the_props as $key => $value ) {
	$$key = $value; 
}
?>
function rot13(input) {
  return input.replace(/[a-zA-Z]/g,
    function(ch) {
      return String.fromCharCode((ch <= "Z" ? 90 : 122) >=
        (ch = ch.charCodeAt(0) + 13) ? ch : ch - 26);
  });
}

jQuery(document).ready( function() {

	jQuery( "span[class='<?php echo $class; ?>']" )
		.bind( '<?php echo ( ( $trigger_decode > 1 ) ? "dbl" : "" ); ?><?php echo ( ( $trigger_decode > 0 ) ? "click" : "" ); ?>', 
			function( e ) {
				var replaced = rot13( jQuery(this).text() );
<?php if ( $decode_method == 0 ) {  ?>
				jQuery( this ).text( replaced );
<?php } ?>
<?php if ( $decode_method >= 1 ) {  ?>
				jQuery( "span[class='rot13_decoded_popup']" ).remove();
				var decoded_popup = jQuery( "<span></span>" );
				decoded_popup.addClass( 'rot13_decoded_popup' );
				decoded_popup.css( 'border', '1px solid <?php echo $popup_border_color; ?>' );
				decoded_popup.css( 'width', '<?php echo $popup_width; ?>px' );
				decoded_popup.css( 'padding', '5px' );
				decoded_popup.css( 'position', 'absolute' );
				decoded_popup.css( 'left', e.pageX );
				decoded_popup.css( 'top', e.pageY );
				decoded_popup.css( 'background-color', '<?php echo $popup_background_color; ?>' );
				decoded_popup.css( 'color', '<?php echo $popup_text_color; ?>' );
				decoded_popup.css( 'z-index', '99999999' );
				decoded_popup.text( replaced );
<?php if ( $decode_method >= 1 ) {  ?>
				decoded_popup.click( function( e ) { jQuery(this).remove(); } );
<?php } ?>
				jQuery( "body" ).after( decoded_popup );
				decoded_popup = null;
				return false;
<?php } ?>
	} );
<?php if ( $decode_method >= 1 ) {  ?>
	jQuery( 'body' )
		.bind( 'click', 
			function( e ) {
				jQuery( "span[class='rot13_decoded_popup']" ).remove();
	} );
<?php } ?>
} );					