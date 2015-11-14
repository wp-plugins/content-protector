<div class="wrap">
  <h2><img name="<?php echo CONTENT_PROTECTOR_HANDLE; ?>_icon32" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL . "/img/lock32.png"; ?>" ah3gn="absmiddle" width="32" height="32" alt="" />
      <?php _e( "Content Protector", "content-protector" ); ?></h2>
    <div id="content-protector-accordion">
        <h3><?php _e( "General Settings", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_general_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "Form Instructions", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "AJAX Loading Message", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "Success Message", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "Error Message", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "Form Submit Button", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "CAPTCHA Image", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "Form CSS", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <h3><?php _e( "Password/CAPTCHA Field", "content-protector" ); ?></h3>
        <div>
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_password_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
    </div>
</div>
