<?php
/*
Plugin Name: Content Protector
Text Domain: content-protector
Plugin URI: http://wordpress.org/plugins/content-protector/
Description: Plugin to password-protect portions of a Page or Post.
Author: K. Tough
Version: 1.0
Author URI: http://wordpress.org/plugins/content-protector/
*/
if ( !class_exists("contentProtectorPlugin") ) {

    define( "CONTENT_PROTECTOR_VERSION", "1.0" );
    define( "CONTENT_PROTECTOR_SLUG", "content-protector" );
    define( "CONTENT_PROTECTOR_HANDLE", "content_protector" );
    define( "CONTENT_PROTECTOR_COOKIE_ID", CONTENT_PROTECTOR_HANDLE . "_" );
	define( "CONTENT_PROTECTOR_PLUGIN_URL", plugins_url() . '/' . CONTENT_PROTECTOR_SLUG );
	define( "CONTENT_PROTECTOR_SHORTCODE", CONTENT_PROTECTOR_HANDLE );
    // Default form field settings
    define( "CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS", __( "This content is protected. Please enter the password to access it.", CONTENT_PROTECTOR_SLUG ) );
    define( "CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE", __( "Incorrect password. Try again.", CONTENT_PROTECTOR_SLUG ) );
    define( "CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE", __( "Authorized!", CONTENT_PROTECTOR_SLUG ) );
    define( "CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL", _x( "Submit", "Access form submit label", CONTENT_PROTECTOR_SLUG ) );
    define( "CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM", "CRYPT_STD_DES" );
    // Required for styling the JQuery UI Datepicker and JQuery UI Timepicker
    define( "CONTENT_PROTECTOR_JQUERY_UI_CSS", CONTENT_PROTECTOR_PLUGIN_URL . "/css/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" );
    define( "CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_JS", CONTENT_PROTECTOR_PLUGIN_URL . "/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js" );
    define( "CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_CSS", CONTENT_PROTECTOR_PLUGIN_URL . "/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.css" );



    /**
     * Class contentProtectorPlugin
     *
     * Class that contains all of the functions required to run the plugin.  This cuts down on
     * the possibility of name collisions with other plugins.
     */
    class contentProtectorPlugin {
				
		function contentProtectorPlugin() {
			//constructor
		}

        // Inspired by http://ca1.php.net/manual/en/function.timezone-identifiers-list.php#79284
        function __timeZoneChoice( $default_tz ) {
            $timezone_identifiers = timezone_identifiers_list();
            sort( $timezone_identifiers );
            $current_continent = "";
            $options_list = "";

            foreach ( $timezone_identifiers as $timezone_identifier ) {
                list( $continent, ) = explode( "/", $timezone_identifier, 2);
                if ( in_array( $continent, array( "Africa", "America", "Antarctica", "Arctic", "Asia", "Atlantic", "Australia", "Europe", "Indian", "Pacific" ) ) ) {
                    list( , $city ) = explode( "/", $timezone_identifier, 2);
                    if ( strlen( $current_continent ) === 0 ) {
                        $options_list .= "<optgroup label=\"" . $continent . "\">"; // Start first continent optgroup
                    }
                    elseif ( $current_continent != $continent ) {
                        $options_list .= "</optgroup><optgroup label=\"" . $continent . "\">"; // End old optgroup and start new continent optgroup
                    }
                    $options_list .= "<option" . ( ( $timezone_identifier == $default_tz ) ? " selected=\"selected\"" : "" )
                        . " value=\"" . $timezone_identifier . "\">" . str_replace( "_", " ", $city ). "</option>"; //Timezone
                }
                $current_continent = $continent;
            }
            $options_list .= "</optgroup>"; // End last continent optgroup

            return $options_list;
        }


        /**
         * Generate a randomized salt for use in contentProtectorPlugin::__hashPassword()
         *
         * @param int $length   Length of the salt requested
         * @return string       The salt
         */
        function __generateRandomSalt( $length = 2 ) {
            $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./";
            $salt = "";
            for ( $i = 0; $i < $length; $i++ )
                $salt .= substr( str_shuffle( $valid_chars ), mt_rand( 0, 63 ), 1 );
            return $salt;
        }

        /**
         * Creates a hash of the password from the [content_protector] shortcode, using the encryption
         * algorithm set in the "content_protector_encryption_algorithm" option
         *
         * @param string $pw    Password
         * @return string       The hashed password
         */
        function __hashPassword( $pw = "" ) {
            $encryption_algorithm = get_option( CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm", CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM );
            $salt = "";

            switch ( $encryption_algorithm ) {
                case "CRYPT_STD_DES" :
                    $salt = $this->__generateRandomSalt( 2 );
                    break;
                case "CRYPT_EXT_DES" :
                    $salt = '_' . $this->__generateRandomSalt( 8 );
                    break;
                case "CRYPT_MD5" :
                    $salt = '$1$' . $this->__generateRandomSalt( 8 ) . '$';
                    break;
                case "CRYPT_BLOWFISH" :
                    $prefix = ( version_compare( PHP_VERSION, '5.3.7', '>=' ) ? '$2y$' : '$2a$' );
                    $cost = sprintf( "%02d", mt_rand( 12, 15 ) );
                    $salt = $prefix . $cost . '$' . $this->__generateRandomSalt( 22 ) . '$';
                    break;
                case "CRYPT_SHA256" :
                    $cost = mt_rand( 5000, 20000 );
                    $salt = '$5$rounds=' . $cost . '$' . $this->__generateRandomSalt( 16 ) . '$';
                    break;
                case "CRYPT_SHA512" :
                    $cost = mt_rand( 5000, 20000 );
                    $salt = '$6$rounds=' . $cost . '$' . $this->__generateRandomSalt( 16 ) . '$';
                    break;
                default :
                    $salt = "";
            }

            return crypt( $pw, $salt );
        }

        /**
         * Creates a cookie on the user's computer so they won't necessarily need to re-enter the password on every visit.
         */
        function setCookie() {
            global $post;

            if ( isset( $post ) )
                $the_post_id = $post->ID;
            elseif ( isset( $_POST['post_id'] ) )
                $the_post_id = $_POST['post_id'];
            else
                return;

            if ( ( isset( $_POST['content-protector-submit'] ) ) && ( isset( $_POST['content-protector-expires'] ) )
                && ( crypt( $_POST['content-protector-password'], $_POST['content-protector-token'] ) == $_POST['content-protector-token'] ) )  {
                    if ( !is_int( $_POST['content-protector-expires'] ) )
                        $expires = strtotime( $_POST['content-protector-expires'] );
                    else
                        $expires = time() + (int)$_POST['content-protector-expires'];

                    $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $_POST['content-protector-ident'] . get_permalink( $the_post_id ) );
                    $cookie_val = md5( $_POST['content-protector-password'] . $_POST['content-protector-expires'] . $_POST['content-protector-ident'] . get_permalink( $the_post_id ) );
                    setcookie( $cookie_name, $cookie_val, $expires, COOKIEPATH, COOKIE_DOMAIN );
                }
        }

        /**
         * Processes the [content-protector] shortcode.
         *
         * @param array $atts
         * @param null $content
         * @return string
         */
        function processShortcode( $atts, $content = null ) {
            // Get the ID of the current post
            global $post; $post_id = $post->ID;

            extract( shortcode_atts( array( 'password' => "", 'cookie_expires' => "", 'identifier' => "", 'ajax' => "false"  ), $atts ) );

            // Make sure $ajax is boolean and set to true ONLY if the string equals 'true'
            $ajax = ( $ajax === "true" ? true : false );

            // Password empty; show error message to those who can edit this post, but fail silently
            // otherwise.  Never show protected content.
            if ( strlen( trim( $password ) ) == 0 )
                if  ( current_user_can( "edit_post", $post_id ) )
                    return "<div class=\"content-protector-error\">"
                    . "<p class=\"heading\">" . __( "Error", CONTENT_PROTECTOR_SLUG ) . "</p>"
                    . "<p>" . sprintf( __( "No password set in this %s shortcode!", CONTENT_PROTECTOR_SLUG ), "<code>[" . CONTENT_PROTECTOR_SHORTCODE . "]</code>" ) . "</p>"
                    . "<p><em>" . __( "Note: If you can see this message, it means you can edit this post and fix this error.", CONTENT_PROTECTOR_SLUG ) . "</em> :) </p>"
                    . "</div>";
                else
                    return "";

            // $ajax is true but $identifier empty; show error message to those who can edit this post,
            // but fail silently otherwise.  Never show protected content.
            // (no AJAX allowed unless an identifier is set)
            if ( ( $ajax ) && ( strlen( trim( $identifier ) ) == 0 ) )
                if ( current_user_can( "edit_post", $post_id ) )
                    return "<div class=\"content-protector-error\">"
                    . "<p class=\"heading\">" . __( "Error", CONTENT_PROTECTOR_SLUG ) . "</p>"
                    . "<p>" . sprintf( __( "No AJAX allowed in this %s shortcode unless an identifier is set!", CONTENT_PROTECTOR_SLUG ), "<code>[" . CONTENT_PROTECTOR_SHORTCODE . "]</code>" ) . "</p>"
                    . "<p><em>" . __( "Note: If you can see this message, it means you can edit this post and fix this error.", CONTENT_PROTECTOR_SLUG ) . "</em> :) </p>"
                    . "</div>";
                else
                    return "";

            // We need to differentiate between multiple instances of protected content on a single
            // Post/Page.  If $identifier is set, we'll use that; otherwise, we take a message digest of
            // the protected content.
            if ( strlen( trim( $identifier ) ) > 0 ) {
                $ident = md5( $identifier );
            } else{
                $ident = md5( $content );
            }

            // If not empty, add '-$identifier' to the DOM IDs in the form so designers/developers can refer to each
            // individual DOM node in their custom CSS.  If $id is empty or not set, use the MD5 digest of the protected content.
            if ( strlen( trim( $identifier ) )  > 0 ) {
                $identifier = "-" . $identifier;
            } else{
                $identifier = "-" . $ident;
            }

            $isAuthorized = false; $successMessage = "";
            $cookie_name = CONTENT_PROTECTOR_COOKIE_ID . md5( $ident . get_permalink( $post->ID ) );

            if ( ( isset( $_COOKIE[$cookie_name] ) ) && ( $_COOKIE[$cookie_name] == md5( $password . $cookie_expires . $ident . get_permalink( $post->ID ) ) ) )
                $isAuthorized = true;
            elseif ( ( ( isset( $_POST['content-protector-password'] ) ) && ( $_POST['content-protector-password'] === $password ) )
                && ( ( isset( $_POST['content-protector-ident'] ) ) && ( $_POST['content-protector-ident'] === $ident ) ) ) {
                $isAuthorized = true;
                // We only want to see this on initial authorization, not when the cookie authorizes you
                $successMessage = "<div id=\"content-protector-correct-password" . $identifier . "\" class=\"content-protector-correct-password\">" . get_option( CONTENT_PROTECTOR_HANDLE . '_success_message', CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE ) . "</div>";
            }

            if ( $isAuthorized ) {
                return $successMessage . "<div id=\"content-protector" . $identifier . "\">" . do_shortcode( $content ) . "</div>";
            } else {
                ob_start();
                include("screens/access_form.php");
                $the_form = ob_get_contents();
                ob_end_clean();

                return $the_form;
            }
        }

        function contentProtectorProcessFormAjax() {
            // First, make sure the AJAX request is coming from the site, if not,
            // we kill with extreme prejudice.
            check_ajax_referer( "view_" . CONTENT_PROTECTOR_HANDLE . "_" . $_POST['post_id'] . $_POST['identifier'], "ajax_security" );
            // Find the post
            $post = get_post( $_POST['post_id'] ); $post_id = $_POST['post_id'];

            // Find all instances of [content_protector] in the post
            $regex_pattern = get_shortcode_regex();
            if ( preg_match_all( '/' . $regex_pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER ) ) {
                foreach ( $matches as $match ) {
                    if ( array_key_exists( 2, $match ) && ( CONTENT_PROTECTOR_SHORTCODE == $match[2] ) ) {
                        // Got one!  But is it the one we want? Let's check the attributes, which
                        // should be in $match[3].
                        // response output
                        if ( array_key_exists( 3, $match ) ) {
                            $attributes = shortcode_parse_atts( $match[3] );
                            $ajax = ( ( ( isset( $attributes['ajax'] ) ) && ( $attributes['ajax'] === "true" ) ) ? true : false );

                            // If $ajax isn't explicitly set to 'true', keep looking at other shortcodes
                            if ( !$ajax )
                                continue;

                            $cookie_expires = ( ( isset( $attributes['cookie_expires'] ) ) ? $attributes['cookie_expires'] : "" );
                            $identifier = ( ( isset( $attributes['identifier'] ) ) ? $attributes['identifier'] : "" );
                            $password = ( ( isset( $attributes['password'] ) ) ? $attributes['password'] : "" );

                            // We need to differentiate between multiple instances of protected content on a single
                            // Post/Page.  Since we ensured that $attributes['identifier'] is already set set, we'll use that.
                            $ident = md5( $attributes['identifier'] );

                            // Add '-$identifier' to the DOM IDs in the form so designers/developers can refer to each
                            // individual DOM node in their custom CSS.
                            $identifier = "-" . $attributes['identifier'];

                            if ( ( isset( $_POST['content-protector-ident'] ) ) && ( $_POST['content-protector-ident'] === $ident ) ) {
                                if ( ( isset( $_POST['content-protector-password'] ) ) && ( $_POST['content-protector-password'] === $attributes['password'] ) ) {
                                    // Right instance, right password.  Let's roll!
                                    if ( strlen( $cookie_expires ) > 0 )
                                        $this->setCookie();
                                    $response = "<div id=\"content-protector-correct-password" . $identifier . "\" class=\"content-protector-correct-password\">" . get_option( CONTENT_PROTECTOR_HANDLE . '_success_message', CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE ) . "</div>";
                                    $response .= "<div id=\"content-protector" . $identifier . "\">" . apply_filters( 'the_content', $match[5] ) . "</div>";

                                    // response output
                                    header( "Content-Type: text/plain" );
                                    echo $response;
                                    die();
                                } else {

                                    ob_start();
                                    include("screens/access_form.php");
                                    $the_form = ob_get_contents();
                                    ob_end_clean();

                                    // response output
                                    header( "Content-Type: text/plain" );
                                    echo $the_form;
                                    die();
                                }
                            }
                        }
                    }
                }
            }
            $response = "<div id=\"content-protector-incorrect-password" . $identifier . "\" class=\"content-protector-incorrect-password\">Something has gone horribly wrong</div>";

            // response output
            header( "Content-Type: text/plain" );
            echo $response;
            die();
        }

        /**
         * Enqueues the CSS/JavaScript code necessary for the functionality of the [content-protector] shortcode.
         */
        function addHeaderCode()  {
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '_css', CONTENT_PROTECTOR_PLUGIN_URL . '/css/content-protector.css', CONTENT_PROTECTOR_VERSION );
            $css = get_option( CONTENT_PROTECTOR_HANDLE . '_form_css', "" );
            if ( ( ! is_admin() ) && ( strlen( trim( $css ) ) > 0 ) )  {
                ?>
                <!-- Content Protector plugin v. <?php echo CONTENT_PROTECTOR_VERSION; ?> CSS -->
                <style type="text/css">
                    <?php echo $css; ?>
                </style>
            <?php
            }
            wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-ajax_js', CONTENT_PROTECTOR_PLUGIN_URL . '/js/content-protector-ajax.js', array( 'jquery', 'jquery-form' ), CONTENT_PROTECTOR_VERSION );
            // Set up local variables used in the AJAX JavaScript file
            wp_localize_script( CONTENT_PROTECTOR_SLUG . '-ajax_js', 'contentProtectorAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'loading_label' => _x( "Checking Password...", CONTENT_PROTECTOR_SLUG ),
                'loading_img' => CONTENT_PROTECTOR_PLUGIN_URL . '/img/wpspin.gif',
                'error_heading' => __( "Error", CONTENT_PROTECTOR_SLUG ),
                'error_desc' => __( "Something unexpected has happened along the way.  The specific details are below:", CONTENT_PROTECTOR_SLUG ) ) );
		}

        /**
         * Adds back-facing Javascript for form fields on the Settings page.
         *
         */
        function addAdminHeaderCode()  {
            $css_all_default = "/* " . __( "These styles will be applied to all Content Protector access forms.", CONTENT_PROTECTOR_SLUG ) . " */\n" .
                "form.content-protector-access-form {\n" .
                "\t/* " . __( "Style the entire form", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "label.content-protector-form-instructions {\n" .
                "\t/* " . __( "Style the form instructions", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "div.content-protector-correct-password {\n" .
                "\t/* " . __( "Style the message when the correct password is entered", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "div.content-protector-incorrect-password {\n" .
                "\t/* " . __( "Style the error message for an incorrect password", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "input.content-protector-form-submit {\n" .
                "\t/* " . __( "Style the Submit button", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "div.content-protector-ajaxLoading {\n" .
                "\t/* " . __( "Style the AJAX loading message", CONTENT_PROTECTOR_SLUG ) . " */\n}\n";
            $css_ident_default = "/* " . __( "These styles will be applied to the Content Protector access form whose identifier is &quot;{id}&quot;.", CONTENT_PROTECTOR_SLUG ) . " */\n" .
                "#content-protector-access-form-{id} {\n" .
                "\t/* " . __( "Style the entire form", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "#content-protector-form-instructions-{id} {\n" .
                "\t/* " . __( "Style the form instructions", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "#content-protector-correct-password-{id} {\n" .
                "\t/* " . __( "Style the message when the correct password is entered", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "#content-protector-incorrect-password-{id} {\n" .
                "\t/* " . __( "Style the error message for an incorrect password", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "#content-protector-form-submit-{id} {\n" .
                "\t/* " . __( "Style the Submit button", CONTENT_PROTECTOR_SLUG ) . " */\n}\n" .
                "#content-protector-ajaxLoading-{id} {\n" .
                "\t/* " . __( "Style the AJAX loading message", CONTENT_PROTECTOR_SLUG ) . " */\n}\n";

            wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-admin_js', CONTENT_PROTECTOR_PLUGIN_URL . '/js/content-protector-admin.js', array( 'jquery' ), CONTENT_PROTECTOR_VERSION );
            wp_localize_script( CONTENT_PROTECTOR_SLUG . '-admin_js',
                'contentProtectorAdminOptions',
                array( 'form_instructions_default' => CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS,
                    'form_instructions_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_form_instructions',
                    'success_message_default' => CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE,
                    'success_message_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_success_message',
                    'error_message_default' => CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE,
                    'error_message_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_error_message',
                    'form_submit_label_default' => CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL,
                    'form_submit_label_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label',
                    'form_css_all_default' => $css_all_default,
                    'form_css_ident_default' => $css_ident_default,
                    'form_css_ident_dialog' => __( "Enter the Content Protector identifier \nwhose form you want to customize:", CONTENT_PROTECTOR_SLUG ),
                    'form_css_id' => '#' . CONTENT_PROTECTOR_HANDLE . '_form_css' ) );
        }

        /**
         * Initialize the Settings page and associated fields.
         *
         */
        function initSettingsPage() {

            $plugin_page = add_options_page( __( 'Content Protector', CONTENT_PROTECTOR_SLUG ), __( 'Content Protector', CONTENT_PROTECTOR_SLUG ), 'edit_posts', CONTENT_PROTECTOR_HANDLE, array( &$this, 'drawSettingsPage' ) );
            add_action( "admin_print_styles-" . $plugin_page, array( &$this, "addAdminHeaderCode" ) );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_password_settings_section', __( 'Password Settings', CONTENT_PROTECTOR_SLUG ), array( &$this, '__passwordSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE );
            // Add the fields for the Password Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_encryption_algorithm', __( 'Encryption Algorithm', CONTENT_PROTECTOR_SLUG ), array( &$this, '__encryptionAlgorithmFieldCallback' ), CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_password_settings_section' );

            add_settings_section( CONTENT_PROTECTOR_HANDLE . '_form_settings_section', __( 'Access Form Settings', CONTENT_PROTECTOR_SLUG ), array( &$this, '__formSettingsSectionFieldCallback' ), CONTENT_PROTECTOR_HANDLE );

            // Add the fields for the Form Settings section
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_instructions', __( 'Form Instructions', CONTENT_PROTECTOR_SLUG ), array( &$this, '__formInstructionsFieldCallback' ), CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_success_message', __( 'Success Message', CONTENT_PROTECTOR_SLUG ), array( &$this, '__successMessageFieldCallback' ), CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_error_message', __( 'Error Message', CONTENT_PROTECTOR_SLUG ), array( &$this, '__errorMessageFieldCallback' ), CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', __( 'Form Submit Label', CONTENT_PROTECTOR_SLUG ), array( &$this, '__formSubmitLabelFieldCallback' ), CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_settings_section' );
            add_settings_field( CONTENT_PROTECTOR_HANDLE . '_form_css', __( 'Form CSS', CONTENT_PROTECTOR_SLUG ), array( &$this, '__formCSSFieldCallback' ), CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_settings_section' );

            // Register our setting so that $_POST handling is done for us and our callback function just has to echo the HTML
            register_setting( CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_instructions', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_success_message', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_error_message', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_submit_label', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_form_css', 'esc_attr' );
            register_setting( CONTENT_PROTECTOR_HANDLE, CONTENT_PROTECTOR_HANDLE . '_encryption_algorithm', 'esc_attr' );
        }

        function __formSettingsSectionFieldCallback() {
            _e("Customize how Content Protector creates the access forms for your protected content.", CONTENT_PROTECTOR_SLUG );
            echo "<br /><em>" . sprintf( __( "You can style the overall look of all access forms using the %s CSS class.", CONTENT_PROTECTOR_SLUG ), "</em><code>form.content-protector-access-form</code><em>" ) . "</em>";
        }

        function __formInstructionsFieldCallback() {
            echo '<textarea style="vertical-align: top;" rows="4" cols="80" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_instructions' . '">' . get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions', CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS ) . '</textarea>';
            echo "&nbsp;<a href=\"javascript:;\" id=\"form-instructions-reset\">" . __( "Reset To Default", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Instructions for your access form.", CONTENT_PROTECTOR_SLUG );
            echo "<br /><em>" . sprintf( __( "You can style this on all access forms using the %s CSS class.", CONTENT_PROTECTOR_SLUG ), "</em><code>label.content-protector-form-instructions</code><em>" ) . "</em>";
        }

        function __successMessageFieldCallback() {
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_success_message' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_success_message' . '" value="' . get_option( CONTENT_PROTECTOR_HANDLE . '_success_message', CONTENT_PROTECTOR_DEFAULT_SUCCESS_MESSAGE ) . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"success-message-reset\">" . __( "Reset To Default", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Message when your users enter the correct password.", CONTENT_PROTECTOR_SLUG );
            echo "<br /><em>" . sprintf( __( "You can style this on all access forms using the %s CSS class.", CONTENT_PROTECTOR_SLUG ), "</em><code>div.content-protector-correct-password</code><em>" ) . "</em>";
        }

        function __errorMessageFieldCallback() {
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_error_message' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_error_message' . '" value="' . get_option( CONTENT_PROTECTOR_HANDLE . '_error_message', CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE ) . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"error-message-reset\">" . __( "Reset To Default", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Error message when your users enter an incorrect password.", CONTENT_PROTECTOR_SLUG );
            echo "<br /><em>" . sprintf( __( "You can style this on all access forms using the %s CSS class.", CONTENT_PROTECTOR_SLUG ), "</em><code>div.content-protector-incorrect-password</code><em>" ) . "</em>";
        }

        function __formSubmitLabelFieldCallback() {
            echo '<input type="text" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_submit_label' . '" value="' . get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL ) . '" />';
            echo "&nbsp;<a href=\"javascript:;\" id=\"form-submit-reset\">" . __( "Reset To Default", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Customize the &quot;Submit&quot; button label on the form.", CONTENT_PROTECTOR_SLUG );
            echo "<br /><em>" . sprintf( __( "You can style the form submit button on all access forms using the %s CSS class.", CONTENT_PROTECTOR_SLUG ), "</em><code>input.content-protector-form-submit</code><em>" ) . "</em>";
        }

        function __formCSSFieldCallback() {
            echo '<textarea style="vertical-align: top; float: left;" rows="12" cols="80" class="regular-text" name="' . CONTENT_PROTECTOR_HANDLE . '_form_css' . '" id="' . CONTENT_PROTECTOR_HANDLE . '_form_css' . '">' . get_option( CONTENT_PROTECTOR_HANDLE . '_form_css', "" ) . '</textarea>';
            echo "&nbsp;<a href=\"javascript:;\" id=\"form-css-all\">" . __( "Add CSS scaffolding for all access forms", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<br />&nbsp;<a href=\"javascript:;\" id=\"form-css-ident\">" . __( "Add CSS scaffolding for a specific access form", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<br />&nbsp;<a href=\"javascript:;\" id=\"form-css-reset\">" . _x( "Clear", "Clear the textarea", CONTENT_PROTECTOR_SLUG ) . "</a>";
            echo "<div style=\"clear: both;\"></div>";
            echo __( "Apply custom CSS to your access form. <strong>Knowledge of CSS required.</strong>", CONTENT_PROTECTOR_SLUG );
        }

        function __passwordSettingsSectionFieldCallback() {
            echo sprintf( __( "Control how the password for your protected content is encrypted. More info at <a href=\"%1\$s\">%2\$s</a>.", CONTENT_PROTECTOR_SLUG ),
                _x( "http://www.php.net/manual/en/function.crypt.php", "URL for PHP's crypt() man page (language-specific)", CONTENT_PROTECTOR_SLUG ),
                _x( "PHP's crypt() man page", "Link for PHP's crypt() man page (language-specific)", CONTENT_PROTECTOR_SLUG ) );
        }

        function __encryptionAlgorithmFieldCallback() {
            $option_values = array( "CRYPT_STD_DES" => _x( "Standard DES", "Encryption algorithm", CONTENT_PROTECTOR_SLUG ),
                "CRYPT_EXT_DES" => _x( "Extended DES", "Encryption algorithm", CONTENT_PROTECTOR_SLUG ),
                "CRYPT_MD5" => _x( "MD5", "Encryption algorithm", CONTENT_PROTECTOR_SLUG ),
                "CRYPT_BLOWFISH" => _x( "Blowfish", "Encryption algorithm", CONTENT_PROTECTOR_SLUG ),
                "CRYPT_SHA256" => _x( "SHA-256", "Encryption algorithm", CONTENT_PROTECTOR_SLUG ),
                "CRYPT_SHA512" => _x( "SHA-512", "Encryption algorithm", CONTENT_PROTECTOR_SLUG ) );
            $current_value = get_option( CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm", CONTENT_PROTECTOR_DEFAULT_ENCRYPTION_ALGORITHM );

            echo "<select name=\"" . CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm\" id=\"" . CONTENT_PROTECTOR_HANDLE . "_encryption_algorithm\">";
            foreach ( $option_values as $value => $label)  {
                if ( ( defined( $value ) ) && ( constant( $value ) === 1 ) )
                    echo '<option value="' . $value .'" ' . selected( $value, $current_value, false ) . ' >' . $label . '</option>';
            }
            echo '</select>';
            echo "<br />" . __( "Select the encryption algorithm to encrypt the password for your protected content. Only those algorithms supported by your server are listed.", CONTENT_PROTECTOR_SLUG );
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
         *  Initializes the TinyMCE plugin bundled with this Wordpress plugin
         */
        function initTinyMCEPlugin()  {
			if ( ( ! current_user_can( 'edit_posts' ) ) && ( ! current_user_can( 'edit_pages' ) ) )
				return;
					 
			// Add only in Rich Editor mode
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( "mce_external_plugins", array( &$this, "addTinyMCEPlugin" ) );
				add_filter( "mce_buttons", array( &$this, "registerTinyMCEButton" ) );
			}
		}

        /**
         * Sets up variables to use in the TinyMCE plugin's editor_plugin_src.js.
         *
         */
        function setTinyMCEPluginVars()  {
            // Add only in Rich Editor mode
            if ( get_user_option( 'rich_editing' ) == 'true' ) {
                wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-admin_tinymce_js', CONTENT_PROTECTOR_PLUGIN_URL . '/js/content-protector-admin-tinymce.js', array(), CONTENT_PROTECTOR_VERSION );
                wp_localize_script( CONTENT_PROTECTOR_SLUG . '-admin_tinymce_js',
                    'contentProtectorAdminTinyMCEOptionsVars',
                    array( 'version' => CONTENT_PROTECTOR_VERSION,
                        'handle' => CONTENT_PROTECTOR_HANDLE,
                        'desc' => __( "Add Content Protector shortcode", CONTENT_PROTECTOR_SLUG ) ) );
            }
        }

        /**
         * Sets up the button for the associated TinyMCE plugin for use in the editor menubar.
         *
         * @param array $buttons    Array of menu buttons already registered with TinyMCE
         * @return array            The array of TinyMCE menu buttons with ours now loaded in as well
         */
        function registerTinyMCEButton( $buttons ) {
			array_push( $buttons, "|", CONTENT_PROTECTOR_HANDLE );
			return $buttons;
		}

        /**
         * Loads the associated TinyMCE plugin into TinyMCE's plugin array
         *
         * @param array $plugin_array   Array of plugins already registered with TinyMCE
         * @return array                The array of TinyMCE plugins with ours now loaded in as well
         */
        function addTinyMCEPlugin( $plugin_array ) {
			$plugin_array[CONTENT_PROTECTOR_HANDLE] = CONTENT_PROTECTOR_PLUGIN_URL . "/tinymce_plugin/editor_plugin.js";
			return $plugin_array;
		}

        /**
         * Display a dialog box for this plugin's associated TinyMCE plugin.  Called from TinyMCE via AJAX.
         *
         */
		function contentProtectorPluginGetTinyMCEDialog()  {
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-css', CONTENT_PROTECTOR_JQUERY_UI_CSS );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_register_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-css', CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_CSS );
            wp_enqueue_style( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-css' );
            wp_register_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-js', CONTENT_PROTECTOR_JQUERY_UI_TIMEPICKER_JS, array('jquery', 'jquery-ui-datepicker'), TIMED_CONTENT_VERSION );
            wp_enqueue_script( CONTENT_PROTECTOR_SLUG . '-jquery-ui-timepicker-js' );


            ob_start();
			include("tinymce_plugin/dialog.php"); 
			$content = ob_get_contents();
			ob_end_clean();			
			echo $content;
			die();
		}

        /**
         * Loads the appropriate i18n files
         *
         */
		function i18nInit() {
			$plugin_dir = basename( dirname( __FILE__ ) ) . "/lang/";
			load_plugin_textdomain( CONTENT_PROTECTOR_SLUG, null, $plugin_dir );
		}

	}

} //End Class contentProtectorPlugin

// Initialize plugin
if ( class_exists("contentProtectorPlugin") ) {
	$contentProtectorPluginInstance = new contentProtectorPlugin();
}

// Actions and Filters
if ( isset( $contentProtectorPluginInstance ) ) {
    add_action( "init", array( &$contentProtectorPluginInstance, "i18nInit" ), 1 );
    add_action( "wp", array( &$contentProtectorPluginInstance, "setCookie" ), 1 );
	add_action( "wp_head", array( &$contentProtectorPluginInstance, "addHeaderCode" ), 1 );
    add_action( "admin_init", array( &$contentProtectorPluginInstance, "setTinyMCEPluginVars" ), 1 );
	add_action( "admin_init", array( &$contentProtectorPluginInstance, "initTinyMCEPlugin" ), 2 );
    add_action( "admin_menu", array( &$contentProtectorPluginInstance, "initSettingsPage" ), 1 );
    add_action( 'wp_ajax_contentProtectorProcessFormAjax', array( &$contentProtectorPluginInstance, "contentProtectorProcessFormAjax" ), 1 );
    add_action( 'wp_ajax_nopriv_contentProtectorProcessFormAjax', array( &$contentProtectorPluginInstance, "contentProtectorProcessFormAjax" ), 1 );
    add_action( 'wp_ajax_contentProtectorPluginGetTinyMCEDialog', array( &$contentProtectorPluginInstance, "contentProtectorPluginGetTinyMCEDialog" ), 1 );
	add_shortcode( CONTENT_PROTECTOR_SHORTCODE, array( &$contentProtectorPluginInstance, "processShortcode" ), 1 );
}
?>