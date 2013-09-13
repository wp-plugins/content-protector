<?php
/*
Plugin Name: ROT13 Encoder/Decoder
Plugin URI: http://wordpress.org/extend/plugins/rot13-encoderdecoder
Description: Plugin to ROT13 a portion of text, along with various methods to display decoded content.
Author: K. Tough
Version: 1.0
Author URI: http://wordpress.org/extend/plugins/rot13-encoderdecoder
*/

define( "ROT13_ENCODER_DECODER_TAG", "rot13" );
define( "ROT13_ENCODER_DECODER_CSS_CLASS", "rot13_encoded" );
define( "ROT13_ENCODER_DECODER_PLUGIN_DIR", "/rot13-encoderdecoder" );
define( "ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP", "Double-click to toggle ROT13" );
define( "ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE", 2 ); // Double-click
define( "ROT13_ENCODER_DECODER_DEFAULT_DECODE_METHOD", 0 );  // Inline
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_WIDTH", 300 );  // Popup window width
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_COLOR", "#000000" );  // Popup window border color
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_TEXT_COLOR", "#000000" );  // Popup window text color
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BACKGROUND_COLOR", "#FFFFFF" );  // Popup window background color

if ( !class_exists( "rot13EncoderDecoderPlugin" ) ) {
	class rot13EncoderDecoderPlugin {
				
		function rot13EncoderDecoderPlugin() { 
			//constructor

		}


		function encodePostText( $atts, $content = null ) {
			$the_HTML = "<span class='" . ROT13_ENCODER_DECODER_CSS_CLASS. "' title='" . get_option( 'rot13_encoder_decoder_tooltip', ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP ) . "'>" . do_shortcode( str_rot13( $content ) ) . "</span>";

			return $the_HTML;
		}
		
		
		function encodeCommentText( $content = null )  {
			if ( is_admin() )
				return $content;
				
			$regex = "#\[" . ROT13_ENCODER_DECODER_TAG . "]((?:[^[]|\[(?!/?" . ROT13_ENCODER_DECODER_TAG . "])|(?R))+)\[/" . ROT13_ENCODER_DECODER_TAG . "]#";
		
			if ( is_array( $content ) ) {
				$content = "<span class='" . ROT13_ENCODER_DECODER_CSS_CLASS. "' title='" . get_option( 'rot13_encoder_decoder_tooltip', ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP ) . "'>" . str_rot13( $content[1] ) . "</span>";
			}
		
			return preg_replace_callback( $regex, array( &$this, "encodeCommentText" ), $content );
		} 
		
		function addCommentNotesAfter( $defaults ) {

			$trigger_decode = get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE );
			$decode_instructions = "";
			
			if ( $trigger_decode > 0 )
				$decode_instructions .= "  Readers can decode the content by " . (  ( $trigger_decode > 1 ) ? "double-" : "" ) . "clicking on the encoded content.";

			$defaults['comment_notes_after'] .= "<p class='form-allowed-tags'>You may use <code>[rot13]example text[/rot13]</code> to encode parts of your comment with the ROT13 cipher (replace <code>example text</code> with the text you want encoded)." . $decode_instructions . "</p> ";
			return $defaults;
		}
		
		function addHeaderCode()  {
			global $wp_query;
			$args = array();
			if ( ( ! is_admin() ) && ( 0 < (int)get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE ) ) ) {
				$args['class'] = ROT13_ENCODER_DECODER_CSS_CLASS;
				$args['trigger_decode'] = get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE );
				$args['decode_method'] = get_option( 'rot13_encoder_decoder_decode_method', ROT13_ENCODER_DECODER_DEFAULT_DECODE_METHOD );
				$args['popup_width'] = get_option( 'rot13_encoder_decoder_popup_width', ROT13_ENCODER_DECODER_DEFAULT_POPUP_WIDTH );
				$args['popup_border_color'] = get_option( 'rot13_encoder_decoder_popup_border_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_COLOR );
				$args['popup_text_color'] = get_option( 'rot13_encoder_decoder_popup_text_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_TEXT_COLOR );
				$args['popup_background_color'] = get_option( 'rot13_encoder_decoder_popup_background_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BACKGROUND_COLOR );
				wp_enqueue_script( 'rot13_encoder_decoder_js', WP_PLUGIN_URL . ROT13_ENCODER_DECODER_PLUGIN_DIR 
					. '/js/rot13-encoderdecoder.js.php?args=' . base64_encode( serialize( $args ) ), array( 'jquery' ), '1.0' );
			}
		}
		
		function addAdminHeaderCode()  {
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_style( 'farbtastic' );
			$color_options = array( "rot13_encoder_decoder_popup_border_color", "rot13_encoder_decoder_popup_text_color", "rot13_encoder_decoder_popup_background_color" ); 
			wp_enqueue_script( 'rot13_encoder_decoder_admin_js', WP_PLUGIN_URL . ROT13_ENCODER_DECODER_PLUGIN_DIR . '/js/rot13-encoderdecoder-admin.js.php?color_options=' . base64_encode( serialize( $color_options ) ), array( 'jquery', 'farbtastic' ), '1.0' );
		}

		function initSettingsPage() {
		 			
			$plugin_page = add_options_page( 'Rot13 Encoder/Decoder', 'Rot13 Encoder/Decoder', 'edit_posts', 'rot13_encoder_decoder', array( &$this, 'drawSettingsPage' ) );
			add_action( "admin_print_styles-" . $plugin_page, array( &$this, "addAdminHeaderCode" ) );

			add_settings_section( 'rot13_encoder_decoder_general_settings_section', 'General Settings', array( &$this, 'rot13_encoder_decoder_general_settings_section_callback_function' ), 'rot13_encoder_decoder');
			
			// Add the field with the names and function to use for our new settings, put it in our new section
			add_settings_field( 'rot13_encoder_decoder_trigger_decode', 'Decoding Trigger', array( &$this, 'rot13_encoder_decoder_trigger_decode_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_decode_method', 'Decoding Method', array( &$this, 'rot13_encoder_decoder_decode_method_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_tooltip', 'Tooltip', array( &$this, 'rot13_encoder_decoder_tooltip_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			
			add_settings_section( 'rot13_encoder_decoder_popup_settings_section', 'Popup Window Settings', array( &$this, 'rot13_encoder_decoder_popup_settings_section_callback_function' ), 'rot13_encoder_decoder');
			
			// Add the field with the names and function to use for our new settings, put it in our new section
			add_settings_field( 'rot13_encoder_decoder_popup_width', 'Width', array( &$this, 'rot13_encoder_decoder_popup_width_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_popup_border_color', 'Border Color', array( &$this, 'rot13_encoder_decoder_popup_border_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_popup_text_color', 'Text Color', array( &$this, 'rot13_encoder_decoder_popup_text_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_popup_background_color', 'Background Color', array( &$this, 'rot13_encoder_decoder_popup_background_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
			
			// Register our setting so that $_POST handling is done for us and our callback function just has to echo the <input>
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_trigger_decode', 'absint' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_decode_method', 'absint' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_tooltip', 'esc_attr' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_width', 'intval' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_border_color' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_text_color' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_background_color' );
		}// initSettingsPage()
		 		 
		function rot13_encoder_decoder_general_settings_section_callback_function() {
			echo 'These settings apply to all ROT13 encoded content.';
		}
		 
		function rot13_encoder_decoder_trigger_decode_callback_function() {
			$option_values = array( 0 => "None (i.e., do not decode)",
									1 => "Single click",
									2 => "Double click" );
			$current_value = get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE );

			echo '<select name="rot13_encoder_decoder_trigger_decode" id="rot13_encoder_decoder_trigger_decode">';
			foreach ( $option_values as $value => $label)  {
				echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . '</option>';
			}
			echo '</select>';
			echo "<br /> Select a mouse action to trigger ROT13 decoding. If Decoding Trigger is set to &quot;None&quot;, consider using the Tooltip to tell your readers about third-party decoding websites like <a href='http://www.rot13.com/'>rot13.com</a>.";
		}
		 
		function rot13_encoder_decoder_decode_method_callback_function() {
			$option_values = array( 0 => "Inline",
									1 => "Popup" );
			$current_value = get_option( 'rot13_encoder_decoder_decode_method', ROT13_ENCODER_DECODER_DEFAULT_DECODE_METHOD );

			echo '<select name="rot13_encoder_decoder_decode_method" id="rot13_encoder_decoder_decode_method">';
			foreach ( $option_values as $value => $label)  {
				echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . '</option>';
			}
			echo '</select>';
			echo "<br /> Select a method to display ROT13 encoded content.";
		}
		
		function rot13_encoder_decoder_tooltip_callback_function() {
			echo '<input type="text" class="regular-text" name="rot13_encoder_decoder_tooltip" id="rot13_encoder_decoder_tooltip" value="' . get_option( 'rot13_encoder_decoder_tooltip', ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP ) . '" />';
			echo "<br /> Add a custom message to show as a tooltip when the mouse hovers over the ROT13 encoded content.";
		}
		
		function rot13_encoder_decoder_popup_settings_section_callback_function() {
			echo 'These settings apply only when the Decoding Method is set to &quot;Popup&quot;.';
		}
		 
		function rot13_encoder_decoder_popup_width_callback_function() {
		 	$option_values = array_combine( range( 250, 600, 50 ), range( 250, 600, 50 ) );
			$current_value = get_option( 'rot13_encoder_decoder_popup_width', ROT13_ENCODER_DECODER_DEFAULT_POPUP_WIDTH );

			echo '<select name="rot13_encoder_decoder_popup_width" id="rot13_encoder_decoder_popup_width">';
			foreach ( $option_values as $value => $label)  {
				echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . '</option>';
			}
			echo '</select>';
			echo "<br /> Width of the ROT13 popup window.";
		}
		
		function rot13_encoder_decoder_popup_border_color_callback_function() {
			$current_value = get_option( 'rot13_encoder_decoder_popup_border_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_COLOR );

			echo '<div id="rot13_encoder_decoder_popup_border_color_sample" style="cursor: pointer; display: inline; border: 1px solid #000000; padding: 2px 10px 2px 10px; background-color: ' . $current_value . ';">&nbsp;&nbsp;</div>&nbsp;&nbsp;<input type="text" name="rot13_encoder_decoder_popup_border_color" id="rot13_encoder_decoder_popup_border_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
			echo '<div id="rot13_encoder_decoder_popup_border_color_colorPickerDiv" style="z-index: 99999; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>';
			echo "<br /> Border color of the ROT13 popup window.";
		}
		
		function rot13_encoder_decoder_popup_text_color_callback_function() {
			$current_value = get_option( 'rot13_encoder_decoder_popup_text_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_TEXT_COLOR );

			echo '<div id="rot13_encoder_decoder_popup_text_color_sample" style="cursor: pointer; display: inline; border: 1px solid #000000; padding: 2px 10px 2px 10px; background-color: ' . $current_value . ';">&nbsp;&nbsp;</div>&nbsp;&nbsp;<input type="text" name="rot13_encoder_decoder_popup_text_color" id="rot13_encoder_decoder_popup_text_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
			echo '<div id="rot13_encoder_decoder_popup_text_color_colorPickerDiv" style="z-index: 99999; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>';
			echo "<br /> Text color of the ROT13 popup window.";
		}
		
		function rot13_encoder_decoder_popup_background_color_callback_function() {
			$current_value = get_option( 'rot13_encoder_decoder_popup_background_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BACKGROUND_COLOR );

			echo '<div id="rot13_encoder_decoder_popup_background_color_sample" style="cursor: pointer; display: inline; border: 1px solid #000000; padding: 2px 10px 2px 10px; background-color: ' . $current_value . ';">&nbsp;&nbsp;</div>&nbsp;&nbsp;<input type="text" name="rot13_encoder_decoder_popup_background_color" id="rot13_encoder_decoder_popup_background_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
			echo '<div id="rot13_encoder_decoder_popup_background_color_colorPickerDiv" style="z-index: 99999; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>';
			echo "<br /> Background color of the ROT13 popup window.";
		}
		
		function drawSettingsPage() {
			ob_start();
			include("screens/admin_screen.php"); 
			$content = ob_get_contents();
			ob_end_clean();
			
			echo $content;
		}

		function initTinyMCEPlugin()  {
			if ( ( ! current_user_can( 'edit_posts' ) ) && ( ! current_user_can( 'edit_pages' ) ) )
				return;
					 
			// Add only in Rich Editor mode
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( "mce_external_plugins", array( &$this, "addRot13EncoderTinyMCEPlugin" ) );
				add_filter( "mce_buttons", array( &$this, "registerTinyMCEButton" ) );
			}
		}
		
		function registerTinyMCEButton( $buttons ) {
			array_push( $buttons, "separator", "rot13_encoder_decoder" );
			return $buttons;
		}
		 
		// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
		function addRot13EncoderTinyMCEPlugin( $plugin_array ) {
			$plugin_array['rot13_encoder_decoder'] = WP_PLUGIN_URL . ROT13_ENCODER_DECODER_PLUGIN_DIR . "/tinymce_plugin/editor_plugin_src.js.php?tag=" . base64_encode( ROT13_ENCODER_DECODER_TAG );
			return $plugin_array;
		}
		
	}

} //End Class rot13EncoderPlugin

// Initialize plugin
if ( class_exists( "rot13EncoderDecoderPlugin" ) ) {
	$rot13EncoderDecoderPluginInstance = new rot13EncoderDecoderPlugin();
}

// Actions and Filters
if ( isset( $rot13EncoderDecoderPluginInstance ) ) {
	add_action( "wp_head", array( &$rot13EncoderDecoderPluginInstance, "addHeaderCode" ), 1 );
	add_action( "admin_menu", array( &$rot13EncoderDecoderPluginInstance, "initSettingsPage" ), 1 );
	add_action( "admin_init", array( &$rot13EncoderDecoderPluginInstance, "initTinyMCEPlugin" ), 1 );
	add_filter( "comment_text", array( &$rot13EncoderDecoderPluginInstance, "encodeCommentText" ), 1 );
	add_filter( "comment_form_defaults", array( &$rot13EncoderDecoderPluginInstance, "addCommentNotesAfter" ), 1 );
	add_shortcode( ROT13_ENCODER_DECODER_TAG, array( &$rot13EncoderDecoderPluginInstance, "encodePostText" ), 1 );
}
?>