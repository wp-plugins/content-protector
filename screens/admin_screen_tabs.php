<style>
    .ui-tabs-vertical { width: 100%; }
    .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 15em; }
    .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
    .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
    .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
    .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 67%;}
</style>

<div class="wrap">
  <h2><img name="<?php echo CONTENT_PROTECTOR_HANDLE; ?>_icon32" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL . "/img/lock32.png"; ?>" align="absmiddle" width="32" height="32" alt="" />
      <?php _e( "Content Protector", "content-protector" ); ?></h2>
    <div id="content-protector-tabs">
        <ul>
            <li><a href="#content-protector-tabs-gen"><?php _e( "General Settings", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-form-instr"><?php _e( "Form Instructions", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-ajax"><?php _e( "AJAX Loading Message", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-success"><?php _e( "Success Message", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-error"><?php _e( "Error Message", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-form-submit"><?php _e( "Form Submit Button", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-captcha"><?php _e( "CAPTCHA Image", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-form-css"><?php _e( "Form CSS", "content-protector" ); ?></a></li>
            <li><a href="#content-protector-tabs-password"><?php _e( "Password/CAPTCHA Field", "content-protector" ); ?></a></li>
        </ul>
        <div id="content-protector-tabs-gen">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_general_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-form-instr">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-ajax">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-success">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-error">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-form-submit">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-captcha">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-form-css">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-password">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_password_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
    </div>
</div>
