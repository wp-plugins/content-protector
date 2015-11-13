<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 17/09/15
 * Time: 2:08 PM
 */
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

if ( !defined( 'CONTENT_PROTECTOR_HANDLE' ) )
    define( "CONTENT_PROTECTOR_HANDLE", "content_protector" );;

// Make sure $default_options has the matching set of entries as in the contentProtectorPlugin definition (~line 63)
$default_options = array( 'form_instructions',
    'form_instructions_font_size',
    'form_instructions_font_weight',
    'form_instructions_color',
    'ajax_loading_message',
    'ajax_loading_message_font_weight',
    'ajax_loading_message_font_style',
    'ajax_loading_message_color',
    'success_message_display',
    'success_message',
    'success_message_font_size',
    'success_message_font_weight',
    'success_message_color',
    'error_message',
    'error_message_font_size',
    'error_message_font_weight',
    'error_message_color',
    'form_submit_label',
    'form_submit_label_color',
    'form_submit_button_color',
    'captcha_instructions',
    'captcha_instructions_display',
    'captcha_width',
    'captcha_height',
    'captcha_text_chars',
    'captcha_text_length',
    'captcha_text_height',
    'captcha_text_angle_variance',
    'captcha_background_color',
    'captcha_text_color',
    'captcha_noise_color',
    'border_style',
    'border_color',
    'border_radius',
    'border_width',
    'padding',
    'background_color',
    'form_css',
    'encryption_algorithm',
    'share_auth',
    'share_auth_duration',
    'store_encrypted_passwords',
    'delete_options_on_uninstall',
    'password_field_type',
    'captcha_field_type',
    'password_field_placeholder',
    'captcha_field_placeholder',
    'password_field_length',
    'captcha_field_length' );

$prefix = CONTENT_PROTECTOR_HANDLE . '_';
if ( "1" === get_option( $prefix . 'delete_options_on_uninstall' ) ) {
    foreach ( $default_options as $option => $value ) {
        delete_option( $prefix . $option );
    }
}
?>