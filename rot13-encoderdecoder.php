<?php
/*
Plugin Name: ROT13 Encoder/Decoder
Plugin URI: http://wordpress.org/plugins/rot13-encoderdecoder
Description: Plugin to apply the ROT13 cipher to selected content, along with various methods to display decoded content.
Author: K. Tough
Version: 1.5
Author URI: http://wordpress.org/plugins/rot13-encoderdecoder
*/

define( "ROT13_ENCODER_VERSION", "1.5" );
define( "ROT13_ENCODER_DECODER_TAG", "rot13" );
define( "ROT13_ENCODER_DECODER_CSS_CLASS", "rot13_encoded" );
define( "ROT13_ENCODER_DECODER_PLUGIN_URL", plugins_url() . "/rot13-encoderdecoder" );
define( "ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP", "Double-click to toggle ROT13" );
define( "ROT13_ENCODER_DECODER_DEFAULT_COMMENTERS", true );
define( "ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE", "2" ); // Double-click
define( "ROT13_ENCODER_DECODER_DEFAULT_DECODE_METHOD", "0" );  // Inline
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_WIDTH", "300" );  // In pixels
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_COLOR", "#000000" );  // Black
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_STYLE", "solid" );
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_WIDTH", "1" );
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_RADIUS", "0" );
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_TEXT_COLOR", "#000000" );  // Black
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BACKGROUND_COLOR", "#FFFFFF" );  // White
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE", "0" );
define( "ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_COLOR", "#CCC" );  // Gray
define( "ROT13_ENCODER_DECODER_COLOR_REGEX", "/\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/" );  // Color regular expression

if ( !class_exists( "rot13EncoderDecoderPlugin" ) ) {
	class rot13EncoderDecoderPlugin {
				
		/**
		 * Constructor
		 *
		 */
		function rot13EncoderDecoderPlugin() {}

        /**
         * Gets the colors from the active Theme's stylesheet (style.css)
         *
         * @return array    Array of colors in hexadecimal notation
         */
        function __getThemeColors() {
            $colors = array();
            $stylesheet = file_get_contents( get_stylesheet_directory() . "/style.css");
            preg_match_all( ROT13_ENCODER_DECODER_COLOR_REGEX, $stylesheet, $matches, PREG_SET_ORDER );
            foreach ( $matches as $m ) $colors[] = $m[0];
            sort( $colors );
            return array_unique( $colors );
        }

		/**
		 * Encodes the tagged content in the post and applies <span> tags.
		 *
		 * @param string $attr
		 * @param string $content The post content before processing for the shortcode
		 * @return string The post content after processing
		 */
		function encodePostText( $atts, $content = null ) {
			$the_HTML = "<span class='" . ROT13_ENCODER_DECODER_CSS_CLASS. "' title='" . get_option( 'rot13_encoder_decoder_tooltip', ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP ) . "' style='cursor: pointer;'>" . do_shortcode( str_rot13( $content ) ) . "</span>";

			return $the_HTML;
		}
		
		/**
		 * Encodes the tagged content in the comment and applies <span> tags.
		 *
		 * @param string $content The comment content before processing for the shortcode
		 * @return string The comment content after processing
		 */
		function encodeCommentText( $content = null )  {
			if ( !( get_option( 'rot13_encoder_decoder_commenters' ) ) )
				return $content;

			if ( is_admin() )
				return $content;
				
			$regex = "#\[" . ROT13_ENCODER_DECODER_TAG . "]((?:[^[]|\[(?!/?" . ROT13_ENCODER_DECODER_TAG . "])|(?R))+)\[/" . ROT13_ENCODER_DECODER_TAG . "]#";
		
			if ( is_array( $content ) ) {
				$content = "<span class='" . ROT13_ENCODER_DECODER_CSS_CLASS. "' title='" . get_option( 'rot13_encoder_decoder_tooltip', ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP ) . "' style='cursor: pointer;'>" . str_rot13( $content[1] ) . "</span>";
			}
		
			return preg_replace_callback( $regex, array( &$this, "encodeCommentText" ), $content );
		} 
		
		/**
		 * Adds instructions for commenters.
		 *
		 * @param array $defaults Default messages
		 * @return array Messages with instructions added
		 */
		function addCommentNotesAfter( $defaults ) {
			if ( !( get_option( 'rot13_encoder_decoder_commenters' ) ) )
				return $defaults;

			$trigger_decode = get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE );
			$decode_instructions = "";
			
			if ( $trigger_decode > 0 )
				$decode_instructions .= "  Readers can decode the content by " . (  ( $trigger_decode > 1 ) ? "double-" : "" ) . "clicking on the encoded content.";

			$defaults['comment_notes_after'] .= "<p class='form-allowed-tags'>You may use <code>[" . ROT13_ENCODER_DECODER_TAG . "]example text[/" . ROT13_ENCODER_DECODER_TAG . "]</code> to encode parts of your comment with the ROT13 cipher (replace <code>example text</code> with the text you want encoded)." . $decode_instructions . "</p> ";
			return $defaults;
		}
		
		/**
		 * Adds front-facing Javascript for decoding/displaying ROT13 content.
		 *
		 */
		function addHeaderCode()  {
			if ( ( ! is_admin() ) && ( 0 < (int)get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE ) ) ) {
				wp_enqueue_script( 'rot13_encoder_decoder_js', ROT13_ENCODER_DECODER_PLUGIN_URL . '/js/rot13-encoderdecoder.js', array( 'jquery' ), ROT13_ENCODER_VERSION );
				wp_localize_script( 'rot13_encoder_decoder_js', 
									'rot13Options',
									array( 'rot13_class' => ROT13_ENCODER_DECODER_CSS_CLASS,
										'trigger_decode' => get_option( 'rot13_encoder_decoder_trigger_decode', ROT13_ENCODER_DECODER_DEFAULT_TRIGGER_DECODE ),
										'decode_method' => get_option( 'rot13_encoder_decoder_decode_method', ROT13_ENCODER_DECODER_DEFAULT_DECODE_METHOD ),
										'popup_width' => get_option( 'rot13_encoder_decoder_popup_width', ROT13_ENCODER_DECODER_DEFAULT_POPUP_WIDTH ),
                                        'popup_border_style' => get_option( 'rot13_encoder_decoder_popup_border_style', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_STYLE ),
                                        'popup_border_color' => get_option( 'rot13_encoder_decoder_popup_border_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_COLOR ),
                                        'popup_border_width' => get_option( 'rot13_encoder_decoder_popup_border_width', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_WIDTH ),
                                        'popup_border_radius' => get_option( 'rot13_encoder_decoder_popup_border_radius', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_RADIUS ),
										'popup_text_color' => get_option( 'rot13_encoder_decoder_popup_text_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_TEXT_COLOR ),
                                        'popup_background_color' => get_option( 'rot13_encoder_decoder_popup_background_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BACKGROUND_COLOR ),
                                        'popup_box_shadow_h_offset' => get_option( 'rot13_encoder_decoder_popup_box_shadow_h_offset', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE ),
                                        'popup_box_shadow_v_offset' => get_option( 'rot13_encoder_decoder_popup_box_shadow_v_offset', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE ),
                                        'popup_box_shadow_blur' => get_option( 'rot13_encoder_decoder_popup_box_shadow_blur', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE ),
                                        'popup_box_shadow_spread' => get_option( 'rot13_encoder_decoder_popup_box_shadow_spread', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE ),
                                        'popup_box_shadow_color' => get_option( 'rot13_encoder_decoder_popup_box_shadow_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_COLOR ) ) );
				}
		}
		
		/**
		 * Adds back-facing Javascript for form fields on the Settings page.
		 *
		 */
		function addAdminHeaderCode()  {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'rot13_encoder_decoder_admin_js', ROT13_ENCODER_DECODER_PLUGIN_URL . '/js/rot13-encoderdecoder-admin.js', array( 'jquery', 'wp-color-picker' ), ROT13_ENCODER_VERSION );
            wp_localize_script( 'rot13_encoder_decoder_admin_js', 'rot13AdminOptions',  array( 'theme_colors' => "['" . join( "','", $this->__getThemeColors() ) . "']" ) );
        }

		/**
		 * Initialize the Settings page and associated fields.
		 *
		 */
		function initSettingsPage() {
		 			
			$plugin_page = add_options_page( 'ROT13 Encoder/Decoder', 'ROT13 Encoder/Decoder', 'edit_posts', 'rot13_encoder_decoder', array( &$this, 'drawSettingsPage' ) );
			add_action( "admin_print_styles-" . $plugin_page, array( &$this, "addAdminHeaderCode" ) );

			add_settings_section( 'rot13_encoder_decoder_general_settings_section', 'General Settings', array( &$this, 'rot13_encoder_decoder_general_settings_section_callback_function' ), 'rot13_encoder_decoder');
			
			// Add the fields for the General Settings section
			add_settings_field( 'rot13_encoder_decoder_trigger_decode', 'Decoding Trigger', array( &$this, 'rot13_encoder_decoder_trigger_decode_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_decode_method', 'Decoding Method', array( &$this, 'rot13_encoder_decoder_decode_method_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_tooltip', 'Tooltip', array( &$this, 'rot13_encoder_decoder_tooltip_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_commenters', 'Allow In Comments', array( &$this, 'rot13_encoder_decoder_commenters_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_general_settings_section' );
			
			add_settings_section( 'rot13_encoder_decoder_popup_settings_section', 'Popup Window Settings', array( &$this, 'rot13_encoder_decoder_popup_settings_section_callback_function' ), 'rot13_encoder_decoder');
			
			// Add the fields for the Popup Window Settings section
			add_settings_field( 'rot13_encoder_decoder_popup_width', 'Popup Window Width', array( &$this, 'rot13_encoder_decoder_popup_width_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_border_style', 'Border Style', array( &$this, 'rot13_encoder_decoder_popup_border_style_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_border_color', 'Border Color', array( &$this, 'rot13_encoder_decoder_popup_border_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_border_width', 'Border Width', array( &$this, 'rot13_encoder_decoder_popup_border_width_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_border_radius', 'Border Radius', array( &$this, 'rot13_encoder_decoder_popup_border_radius_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
			add_settings_field( 'rot13_encoder_decoder_popup_text_color', 'Text Color', array( &$this, 'rot13_encoder_decoder_popup_text_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_background_color', 'Background Color', array( &$this, 'rot13_encoder_decoder_popup_background_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_settings_section' );

            add_settings_section( 'rot13_encoder_decoder_popup_box_shadow_settings_section', 'Popup Box Shadow Settings', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_settings_section_callback_function' ), 'rot13_encoder_decoder');

            // Add the fields for the Popup Box Shadow Settings section
            add_settings_field( 'rot13_encoder_decoder_popup_box_shadow_h_offset', 'Box Shadow Horizontal Offset', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_h_offset_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_box_shadow_v_offset', 'Box Shadow Vertical Offset', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_v_offset_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_box_shadow_blur', 'Box Shadow Blur', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_blur_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_box_shadow_spread', 'Box Shadow Spread', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_spread_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_settings_section' );
            add_settings_field( 'rot13_encoder_decoder_popup_box_shadow_color', 'Box Shadow Color', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_color_callback_function' ), 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_settings_section' );

			// Register our setting so that $_POST handling is done for us and our callback function just has to echo the <input>
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_trigger_decode', 'absint' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_decode_method', 'absint' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_tooltip', 'esc_attr' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_commenters' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_width', 'intval' );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_border_style', 'esc_attr' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_border_color', array( &$this, 'rot13_encoder_decoder_popup_border_color_validate' ) );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_border_width', 'intval' );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_border_radius', 'intval' );
			register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_text_color', array( &$this, 'rot13_encoder_decoder_popup_text_color_validate' ) );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_background_color', array( &$this, 'rot13_encoder_decoder_popup_background_color_validate' ) );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_h_offset', 'intval' );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_v_offset', 'intval' );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_blur', 'intval' );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_spread', 'intval' );
            register_setting( 'rot13_encoder_decoder', 'rot13_encoder_decoder_popup_box_shadow_color', array( &$this, 'rot13_encoder_decoder_popup_box_shadow_color_validate' ) );
        }

        function rot13_encoder_decoder_popup_border_style_callback_function() {
            $options = array( "dotted", "dashed", "solid", "double", "groove", "ridge", "inset", "outset" );
            $option_values = array_combine( $options, $options );
            $current_value = get_option( "rot13_encoder_decoder_popup_border_style", ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_STYLE );

            echo "<select name=\"rot13_encoder_decoder_popup_border_style\" id=\"rot13_encoder_decoder_popup_border_style\">";
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . '</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Border style of the ROT13 popup window.";
        }

        function __validateColorNotation( $input ) {
            if ( 1 === preg_match( ROT13_ENCODER_DECODER_COLOR_REGEX, $input, $matches ) )
                return $matches[0];
            else
                return false;
        }

        function rot13_encoder_decoder_popup_border_color_validate( $input ) {
            $valid = $this->__validateColorNotation( $input );
            if ( false === $valid )
                add_settings_error(
                    "rot13_encoder_decoder_popup_border_color", // setting title
                    "rot13_encoder_decoder_popup_border_color_hex_error", // error ID
                    "Border Color must be in hex notation (e.g., #AABBCC, #09F, etc.)! Please fix.", // error message
                    "error" // type of message
                );
            else
                return $valid;
        }

        function rot13_encoder_decoder_popup_text_color_validate( $input ) {
            $valid = $this->__validateColorNotation( $input );
            if ( false === $valid )
                add_settings_error(
                    "rot13_encoder_decoder_popup_text_color", // setting title
                    "rot13_encoder_decoder_popup_text_color_hex_error", // error ID
                    "Text Color must be in hex notation (e.g., #AABBCC, #09F, etc.)! Please fix.", // error message
                    "error" // type of message
                );
            else
                return $valid;
        }

        function rot13_encoder_decoder_popup_background_color_validate( $input ) {
            $valid = $this->__validateColorNotation( $input );
            if ( false === $valid )
                add_settings_error(
                    "rot13_encoder_decoder_popup_background_color", // setting title
                    "rot13_encoder_decoder_popup_background_color_hex_error", // error ID
                    "Background Color must be in hex notation (e.g., #AABBCC, #09F, etc.)! Please fix.", // error message
                    "error" // type of message
                );
            else
                return $valid;
        }

        function rot13_encoder_decoder_popup_box_shadow_color_validate( $input ) {
            $valid = $this->__validateColorNotation( $input );
            if ( false === $valid )
                add_settings_error(
                    "rot13_encoder_decoder_popup_box_shadow_color", // setting title
                    "rot13_encoder_decoder_popup_box_shadow_color_hex_error", // error ID
                    "Box Shadow Color must be in hex notation (e.g., #AABBCC, #09F, etc.)! Please fix.", // error message
                    "error" // type of message
                );
            else
                return $valid;
        }

		// The following functions set up each section and option field
		// for the plugin
		function rot13_encoder_decoder_general_settings_section_callback_function() {
			echo 'These settings apply to all ROT13 encoded content.';
		}
		 
		function rot13_encoder_decoder_trigger_decode_callback_function() {
			$option_values = array( 0 => "None (i.e., do not decode)",
									1 => "Single click",
                                    2 => "Double click",
                                    3 => "Hover" );
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
			echo "<br />" . "Select a method to display ROT13 encoded content.";
		}
		
		function rot13_encoder_decoder_tooltip_callback_function() {
			echo '<input type="text" class="regular-text" name="rot13_encoder_decoder_tooltip" id="rot13_encoder_decoder_tooltip" value="' . get_option( 'rot13_encoder_decoder_tooltip', ROT13_ENCODER_DECODER_DEFAULT_TOOLTIP ) . '" />';
			echo "<br /> Add a message to show as a tooltip when the mouse hovers over the ROT13 encoded content so your readers know how to decode it.";
		}
		
		function rot13_encoder_decoder_commenters_callback_function() {
			echo '<input type="checkbox" name="rot13_encoder_decoder_commenters" id="rot13_encoder_decoder_commenters" value="' . ROT13_ENCODER_DECODER_DEFAULT_COMMENTERS . '"' . checked( ROT13_ENCODER_DECODER_DEFAULT_COMMENTERS, get_option( 'rot13_encoder_decoder_commenters' ), false ) . ' />';
			echo "&nbsp;" . "Allow your commenters to use the <code>[" . ROT13_ENCODER_DECODER_TAG . "]</code> shortcode?";
		}
		
		function rot13_encoder_decoder_popup_settings_section_callback_function() {
			echo 'These settings apply only when the Decoding Method is set to &quot;Popup&quot;.';
		}
		 
		function rot13_encoder_decoder_popup_width_callback_function() {
		 	$option_values = array_combine( range( 250, 600, 50 ), range( 250, 600, 50 ) );
			$current_value = get_option( 'rot13_encoder_decoder_popup_width', ROT13_ENCODER_DECODER_DEFAULT_POPUP_WIDTH );

			echo '<select name="rot13_encoder_decoder_popup_width" id="rot13_encoder_decoder_popup_width">';
			foreach ( $option_values as $value => $label)  {
				echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
			}
			echo '</select>';
			echo "&nbsp;" . "Width of the ROT13 popup window.";
		}
		
		function rot13_encoder_decoder_popup_border_color_callback_function() {
			$current_value = get_option( 'rot13_encoder_decoder_popup_border_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_COLOR );
			echo '<input type="text" name="rot13_encoder_decoder_popup_border_color" id="rot13_encoder_decoder_popup_border_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
			echo "&nbsp;" . "Border color of the ROT13 popup window.";
		}

        function rot13_encoder_decoder_popup_border_width_callback_function() {
            $option_values = array_combine( range( 0, 5 ), range( 0, 5 ) );
            $current_value = get_option( 'rot13_encoder_decoder_popup_border_width', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_WIDTH );

            echo '<select name="rot13_encoder_decoder_popup_border_width" id="rot13_encoder_decoder_popup_border_width">';
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Border width of the ROT13 popup window.";
        }
        function rot13_encoder_decoder_popup_border_radius_callback_function() {
            $option_values = array_combine( range( 0, 45, 5 ), range( 0, 45, 5 ) );
            $current_value = get_option( 'rot13_encoder_decoder_popup_border_radius', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BORDER_RADIUS );

            echo '<select name="rot13_encoder_decoder_popup_border_radius" id="rot13_encoder_decoder_popup_border_radius">';
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Border radius (curvature of the corners) of the ROT13 popup window.";
        }

        function rot13_encoder_decoder_popup_text_color_callback_function() {
			$current_value = get_option( 'rot13_encoder_decoder_popup_text_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_TEXT_COLOR );
			echo '<input type="text" name="rot13_encoder_decoder_popup_text_color" id="rot13_encoder_decoder_popup_text_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
			echo "&nbsp;" . "Text color of the ROT13 popup window.";
		}
		
		function rot13_encoder_decoder_popup_background_color_callback_function() {
			$current_value = get_option( 'rot13_encoder_decoder_popup_background_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BACKGROUND_COLOR );
			echo '<input type="text" name="rot13_encoder_decoder_popup_background_color" id="rot13_encoder_decoder_popup_background_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
			echo "&nbsp;" . "Background color of the ROT13 popup window.";
		}

        function rot13_encoder_decoder_popup_box_shadow_settings_section_callback_function() {
            echo 'Add a shadow to the ROT13 popup window. These settings apply only when the Decoding Method is set to &quot;Popup&quot;.';
        }

        function rot13_encoder_decoder_popup_box_shadow_h_offset_callback_function() {
            $option_values = array_combine( range( -10, 10 ), range( -10, 10 ) );
            $current_value = get_option( 'rot13_encoder_decoder_popup_box_shadow_h_offset', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE );

            echo '<select name="rot13_encoder_decoder_popup_box_shadow_h_offset" id="rot13_encoder_decoder_popup_box_shadow_h_offset">';
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Horizontal offset of the ROT13 popup box shadow.";
        }

        function rot13_encoder_decoder_popup_box_shadow_v_offset_callback_function() {
            $option_values = array_combine( range( -10, 10 ), range( -10, 10 ) );
            $current_value = get_option( 'rot13_encoder_decoder_popup_box_shadow_v_offset', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE );

            echo '<select name="rot13_encoder_decoder_popup_box_shadow_v_offset" id="rot13_encoder_decoder_popup_box_shadow_v_offset">';
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Vertical offset of the ROT13 popup box shadow.";
        }

        function rot13_encoder_decoder_popup_box_shadow_blur_callback_function() {
            $option_values = array_combine( range( 0, 10 ), range( 0, 10 ) );
            $current_value = get_option( 'rot13_encoder_decoder_popup_box_shadow_blur', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE );

            echo '<select name="rot13_encoder_decoder_popup_box_shadow_blur" id="rot13_encoder_decoder_popup_box_shadow_blur">';
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Blur of the ROT13 popup box shadow.";
        }

        function rot13_encoder_decoder_popup_box_shadow_spread_callback_function() {
            $option_values = array_combine( range( -10, 10 ), range( -10, 10 ) );
            $current_value = get_option( 'rot13_encoder_decoder_popup_box_shadow_spread', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_DISTANCE );

            echo '<select name="rot13_encoder_decoder_popup_box_shadow_spread" id="rot13_encoder_decoder_popup_box_shadow_spread">';
            foreach ( $option_values as $value => $label)  {
                echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . ' px</option>';
            }
            echo '</select>';
            echo "&nbsp;" . "Spread of the ROT13 popup box shadow.";
        }

        function rot13_encoder_decoder_popup_box_shadow_color_callback_function() {
            $current_value = get_option( 'rot13_encoder_decoder_popup_box_shadow_color', ROT13_ENCODER_DECODER_DEFAULT_POPUP_BOX_SHADOW_COLOR );
            echo '<input type="text" name="rot13_encoder_decoder_popup_box_shadow_color" id="rot13_encoder_decoder_popup_box_shadow_color" value="' . $current_value . '" size="7" maxlength="7" style="width: 100px;" />';
            echo "&nbsp;" . "Color of the ROT13 popup box shadow.";
        }

        /**
		 * Prints out the Settings page.
		 *
		 */
		function drawSettingsPage() {
			ob_start();
			include("screens/admin_screen.php"); 
			$content = ob_get_contents();
			ob_end_clean();
			
			echo $content;
		}

		/**
		 * Sets up variables to use in the TinyMCE plugin's editor_plugin_src.js.
		 *
		 */
		function setTinyMCEPluginVars()  {
			if ( ( ! current_user_can( 'edit_posts' ) ) && ( ! current_user_can( 'edit_pages' ) ) )
				return;
					 
			// Add only in Rich Editor mode
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				wp_enqueue_script( 'rot13_encoder_decoder_admin_tinymce_js', ROT13_ENCODER_DECODER_PLUGIN_URL . '/js/rot13-encoderdecoder-admin-tinymce.js', array(), ROT13_ENCODER_VERSION );
				wp_localize_script( 'rot13_encoder_decoder_admin_tinymce_js', 
									'rot13AdminTinyMCEOptionsVars', 
									array( 'version' => ROT13_ENCODER_VERSION,				
											'tag' => ROT13_ENCODER_DECODER_TAG ) );				
			}
		}
		
		/**
		 * Initialize the TinyMCE plugin.
		 *
		 */
		function initTinyMCEPlugin()  {
			if ( ( ! current_user_can( 'edit_posts' ) ) && ( ! current_user_can( 'edit_pages' ) ) )
				return;
					 
			// Add only in Rich Editor mode
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( "mce_external_plugins", array( &$this, "addRot13EncoderTinyMCEPlugin" ) );
				add_filter( "mce_buttons", array( &$this, "registerTinyMCEButton" ) );
			}
		}
		
		/**
		 * Adds the button for the TinyMCE plugin onto the editor's menu bar.
		 *
		 * @param array $buttons Default buttons
		 * @return array Buttons with ROT13 button added
		 */
		function registerTinyMCEButton( $buttons ) {
			array_push( $buttons, "separator", "rot13_encoder_decoder" );
			return $buttons;
		}
		 
		/**
		 * Initialize the Settings page.
		 *
		 * @param array $plugin_array Default plugins
		 * @return array Plugins with ROT13 plugin added
		 */
		function addRot13EncoderTinyMCEPlugin( $plugin_array ) {
			$plugin_array['rot13_encoder_decoder'] = ROT13_ENCODER_DECODER_PLUGIN_URL . "/tinymce_plugin/editor_plugin_src.js";
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
	add_action( "admin_init", array( &$rot13EncoderDecoderPluginInstance, "setTinyMCEPluginVars" ), 1 );
	add_action( "admin_init", array( &$rot13EncoderDecoderPluginInstance, "initTinyMCEPlugin" ), 2 );
	add_filter( "comment_text", array( &$rot13EncoderDecoderPluginInstance, "encodeCommentText" ), 1 );
	add_filter( "comment_form_defaults", array( &$rot13EncoderDecoderPluginInstance, "addCommentNotesAfter" ), 1 );
	add_shortcode( ROT13_ENCODER_DECODER_TAG, array( &$rot13EncoderDecoderPluginInstance, "encodePostText" ), 1 );
}
?>