<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <title><?php _ex( 'Add Content Protector shortcode', 'TinyMCE Dialog - Dialog titlebar', CONTENT_PROTECTOR_SLUG ); ?></title>
    <?php wp_print_styles(); ?>
    <script type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/tiny_mce_popup.js"></script>
    <?php wp_print_scripts(); ?>
    <script type="text/javascript">
        var datepickerFormat = "<?php _ex( "MM d, yy", "Date format for jQuery UI Datepicker", CONTENT_PROTECTOR_SLUG ); ?>";
        var shortcode = "<?php echo CONTENT_PROTECTOR_SHORTCODE; ?>";
        var errorMessages = { 'noCookieExpires': '<?php _e( 'Please set an expiry time, or uncheck the Set Cookie checkbox.', CONTENT_PROTECTOR_SLUG ); ?>',
            'noPassword': '<?php _e( 'Please set a password to protect your content.', CONTENT_PROTECTOR_SLUG ); ?>',
            'badPassword': '<?php _e( 'Password can only contain letters, numbers, ".", and "/".', CONTENT_PROTECTOR_SLUG ); ?>',
            'durationIsNaN': '<?php _e( 'Duration must be an natural number (1, 2, 3, ... ).', CONTENT_PROTECTOR_SLUG ); ?>',
            'noIdentifier': '<?php _e( 'Please set an identifier to use AJAX.', CONTENT_PROTECTOR_SLUG ); ?>' };
    </script>
    <script type="text/javascript" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL; ?>/tinymce_plugin/dialog.js"></script>
</head>
<body>
<form name="contentProtectorDialogForm" id="contentProtectorDialogForm"
      onsubmit="contentProtectorDialog.action();return false;" action="#">
    <fieldset>
        <legend>
            <?php _ex( 'Properties', 'TinyMCE Dialog - Properties fieldset label', CONTENT_PROTECTOR_SLUG ); ?> </legend>
        <p><label
                for="password"><?php _ex( 'Set password:', 'TinyMCE Dialog - Password textbox label', CONTENT_PROTECTOR_SLUG ); ?></label>
            <input id="password" name="password" type="text" class="text"/>
        </p>

        <p><label
                for="identifier"><?php _ex( 'Set identifier:', 'TinyMCE Dialog - Identifier textbox label', CONTENT_PROTECTOR_SLUG ); ?></label>
            <input id="identifier" name="identifier" type="text" class="text"/><br/>
            <em><?php _ex( '(Optional - Set an identifier if you want to use AJAX or custom CSS on this form.)', 'TinyMCE Dialog - Identifier description', CONTENT_PROTECTOR_SLUG ); ?></em>
        </p>

        <p>
            <input name="ajax" type="checkbox" id="ajax" value="1"/>
            <label
                for="ajax"><?php _ex( 'Use AJAX for inline-loading of the protected content.', 'TinyMCE Dialog - Use AJAX label', CONTENT_PROTECTOR_SLUG ); ?></label>
        </p>
    </fieldset>
    <fieldset>
        <legend>
            <input name="set_pc_cookie" type="checkbox" id="set_pc_cookie" value="1"/>
            <label
                for="set_pc_cookie"><?php _ex( 'Set Cookie For Unlocked Content', 'TinyMCE Dialog - Set Cookie fieldset label', CONTENT_PROTECTOR_SLUG ); ?> </label>
        </legend>
        <p>
            <em><?php _ex( '(Although optional, you should also set an identifier if you want to set a cookie; otherwise, editing the protected content in the future will require your users to re-enter the password.)', 'TinyMCE Dialog - Expiry HTML Select label', CONTENT_PROTECTOR_SLUG ); ?></em>
        </p>

        <p>
            <input name="expiry_type" type="radio" id="expiry_type_duration" value="duration" disabled="disabled"/>
            <label
                for="expiry_type_duration"> <?php _ex( 'Cookie expires after', 'TinyMCE Dialog - Expiry duration radio label', CONTENT_PROTECTOR_SLUG ); ?></label>&nbsp;
            <input id="expiry_duration_quantity" name="expiry_duration_quantity" type="text" size="2"
                   disabled="disabled"/>&nbsp;
            <select id="expiry_duration_unit" name="expiry_duration_unit" disabled="disabled">
                <option value="minutes"><?php _ex( 'minutes', 'Units of time', CONTENT_PROTECTOR_SLUG ); ?></option>
                <option value="hours"><?php _ex( 'hours', 'Units of time', CONTENT_PROTECTOR_SLUG ); ?></option>
                <option value="days"><?php _ex( 'days', 'Units of time', CONTENT_PROTECTOR_SLUG ); ?></option>
                <option value="weeks"><?php _ex( 'weeks', 'Units of time', CONTENT_PROTECTOR_SLUG ); ?></option>
                <option value="months"><?php _ex( 'months', 'Units of time', CONTENT_PROTECTOR_SLUG ); ?></option>
                <option value="years"><?php _ex( 'years', 'Units of time', CONTENT_PROTECTOR_SLUG ); ?></option>
            </select>
        </p>
        <p>
            <input name="expiry_type" type="radio" id="expiry_type_datetime" value="datetime" disabled="disabled"/>
            <label
                for="expiry_type_datetime"> <?php _ex( 'Cookie expires at this specific date and time:', 'Expiry date/time radio label', CONTENT_PROTECTOR_SLUG ); ?></label>
        </p>

        <p style="padding-left: 25px;">
            <label for="expiry_date"> <?php _ex( 'Date:', 'Date field label', CONTENT_PROTECTOR_SLUG ); ?></label>
            <input name="expiry_date" type="text" disabled="disabled" class="text" id="expiry_date"
                   style="width: 150px;"/>
            <label for="expiry_time"> <?php _ex( 'Time:', 'Time field label', CONTENT_PROTECTOR_SLUG ); ?></label>
            <input name="expiry_time" type="text" disabled="disabled" class="text" id="expiry_time"
                   style="width: 125px;"/>
        </p>

        <p style="padding-left: 25px;">
            <label
                for="expiry_tz"> <?php _ex( 'Timezone:', 'TinyMCE Dialog - Timezone SELECT HTML label', CONTENT_PROTECTOR_SLUG ); ?>
                &nbsp;
                <select name="expiry_tz" id="expiry_tz" disabled="disabled" style="width: auto;">
                    <?php echo $this->__generateTimezoneSelectOptions( get_option('timezone_string' ) ); ?>
                </select>
        </p>

        <p>
            <input name="expiry_type" type="radio" id="expiry_type_end_of_session" value="end_of_session"
                   disabled="disabled"/>
            <label
                for="expiry_type_end_of_session"><?php _ex( 'Cookie expires when the browser closes.', 'TinyMCE Dialog - Cookie expires when the browser closes radio label', CONTENT_PROTECTOR_SLUG ); ?></label>
        </p>
    </fieldset>
    <div class="mceActionPanel">
        <input type="button" id="insert" name="insert"
               value="<?php _ex( 'Insert', 'TinyMCE Dialog - Insert button HTML label', CONTENT_PROTECTOR_SLUG ); ?>"
               onclick="contentProtectorDialog.action();"/>
        <input type="button" id="cancel" name="cancel"
               value="<?php _ex( 'Cancel', 'TinyMCE Dialog - Cancel button HTML label', CONTENT_PROTECTOR_SLUG ); ?>"
               onclick="tinyMCEPopup.close();"/>
    </div>
</form>
</body>
</html>