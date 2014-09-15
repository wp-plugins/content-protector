<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <title><?php _ex( 'Add Content Protector shortcode', 'TinyMCE Dialog - Dialog titlebar', "content-protector" ); ?></title>
    <?php wp_print_styles(); ?>
    <style type="text/css">
        #use_captcha {
            font-weight: bold;
            text-decoration: underline;
            cursor: pointer;
        }

    </style>
    <script type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/tiny_mce_popup.js"></script>
    <?php wp_print_scripts(); ?>
    <script type="text/javascript">
        var shortcode = "<?php echo CONTENT_PROTECTOR_SHORTCODE; ?>";
        var captcha_pw = "<?php echo CONTENT_PROTECTOR_CAPTCHA_KEYWORD; ?>";
        var errorMessages = { 'noCookieExpires': '<?php _e( 'Please set an expiry time, or uncheck the Set Cookie checkbox.', "content-protector" ); ?>',
            'noPassword': '<?php _e( 'Please set a password to protect your content.', "content-protector" ); ?>',
            'badPassword': '<?php echo sprintf( _x( 'Password can only contain letters, numbers, %1$s, and %2$s.', "%1\$s refers to the period character, %2\$s refers to the slash character", "content-protector" ), "\".\"", "\"\/\"" ); ?>',
            'durationIsNaN': '<?php _e( 'Duration must be an natural number (1, 2, 3, ... ).', "content-protector" ); ?>',
            'noIdentifier': '<?php _e( 'Please set an identifier to use AJAX.', "content-protector" ); ?>' };
    </script>
    <script type="text/javascript" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL; ?>/tinymce_plugin/dialog.js"></script>
</head>
<body>
<form name="contentProtectorDialogForm" id="contentProtectorDialogForm"
      onsubmit="contentProtectorDialog.action();return false;" action="#">
    <fieldset>
        <legend>
            <?php _ex( 'Properties', 'TinyMCE Dialog - Properties fieldset label', "content-protector" ); ?> </legend>
        <p><label
                for="password"><?php _ex( 'Set password:', 'TinyMCE Dialog - Password textbox label', "content-protector" ); ?></label>
            <input id="password" name="password" type="text" class="text"/>
            <span id="use_captcha">Protect with CAPTCHA</span>
        </p>

        <p><label
                for="identifier"><?php _ex( 'Set identifier:', 'TinyMCE Dialog - Identifier textbox label', "content-protector" ); ?></label>
            <input id="identifier" name="identifier" type="text" class="text"/><br/>
            <em><?php _ex( '(Optional - Set an identifier if you want to use AJAX or custom CSS on this form.)', 'TinyMCE Dialog - Identifier description', "content-protector" ); ?></em>
        </p>

        <p>
            <input name="ajax" type="checkbox" id="ajax" value="1"/>
            <label
                for="ajax"><?php _ex( 'Use AJAX for inline-loading of the protected content.', 'TinyMCE Dialog - Use AJAX label', "content-protector" ); ?></label>
        </p>
    </fieldset>
    <fieldset>
        <legend>
            <input name="set_pc_cookie" type="checkbox" id="set_pc_cookie" value="1"/>
            <label
                for="set_pc_cookie"><?php _ex( 'Set Cookie For Unlocked Content', 'TinyMCE Dialog - Set Cookie fieldset label', "content-protector" ); ?> </label>
        </legend>
        <p>
            <em><?php _ex( '(Although optional, you should also set an identifier if you want to set a cookie; otherwise, editing the protected content in the future will require your users to re-enter the password.)', 'TinyMCE Dialog - Expiry HTML Select label', "content-protector" ); ?></em>
        </p>

        <p>
            <input name="expiry_type" type="radio" id="expiry_type_duration" value="duration" disabled="disabled"/>
            <label
                for="expiry_type_duration"> <?php _ex( 'Cookie expires after', 'TinyMCE Dialog - Expiry duration radio label', "content-protector" ); ?></label>&nbsp;
            <input id="expiry_duration_quantity" name="expiry_duration_quantity" type="text" size="2"
                   disabled="disabled"/>&nbsp;
            <select id="expiry_duration_unit" name="expiry_duration_unit" disabled="disabled">
                <option value="minutes"><?php _ex( 'minutes', 'Units of time', "content-protector" ); ?></option>
                <option value="hours"><?php _ex( 'hours', 'Units of time', "content-protector" ); ?></option>
                <option value="days"><?php _ex( 'days', 'Units of time', "content-protector" ); ?></option>
                <option value="weeks"><?php _ex( 'weeks', 'Units of time', "content-protector" ); ?></option>
                <option value="months"><?php _ex( 'months', 'Units of time', "content-protector" ); ?></option>
                <option value="years"><?php _ex( 'years', 'Units of time', "content-protector" ); ?></option>
            </select>
        </p>
        <p>
            <input name="expiry_type" type="radio" id="expiry_type_datetime" value="datetime" disabled="disabled"/>
            <label
                for="expiry_type_datetime"> <?php _ex( 'Cookie expires at this specific date and time:', 'Expiry date/time radio label', "content-protector" ); ?></label>
        </p>

        <p style="padding-left: 25px;">
            <label for="expiry_date"> <?php _ex( 'Date:', 'Date field label', "content-protector" ); ?></label>
            <input name="expiry_date" type="text" disabled="disabled" class="text" id="expiry_date"
                   style="width: 150px;"/>
            <label for="expiry_time"> <?php _ex( 'Time:', 'Time field label', "content-protector" ); ?></label>
            <input name="expiry_time" type="text" disabled="disabled" class="text" id="expiry_time"
                   style="width: 125px;"/>
        </p>

        <p style="padding-left: 25px;">
            <label
                for="expiry_tz"> <?php _ex( 'Timezone:', 'TinyMCE Dialog - Timezone SELECT HTML label', "content-protector" ); ?>
                &nbsp;
                <select name="expiry_tz" id="expiry_tz" disabled="disabled" style="width: auto;">
                    <?php echo $this->__generateTimezoneSelectOptions( get_option('timezone_string' ) ); ?>
                </select>
        </p>

        <p>
            <input name="expiry_type" type="radio" id="expiry_type_end_of_session" value="end_of_session"
                   disabled="disabled" checked="checked"/>
            <label
                for="expiry_type_end_of_session"><?php _ex( 'Cookie expires when the browser closes.', 'TinyMCE Dialog - Cookie expires when the browser closes radio label', "content-protector" ); ?></label>
        </p>
    </fieldset>
    <div class="mceActionPanel">
        <input type="button" id="insert" name="insert"
               value="<?php _ex( 'Insert', 'TinyMCE Dialog - Insert button HTML label', "content-protector" ); ?>"
               onclick="contentProtectorDialog.action();"/>
        <input type="button" id="cancel" name="cancel"
               value="<?php _ex( 'Cancel', 'TinyMCE Dialog - Cancel button HTML label', "content-protector" ); ?>"
               onclick="tinyMCEPopup.close();"/>
    </div>
</form>
</body>
</html>