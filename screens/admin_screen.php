<div class="wrap">
  <h2><img name="<?php echo CONTENT_PROTECTOR_HANDLE; ?>_icon32" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL . "/img/lock32.png"; ?>" align="absmiddle" width="32" height="32" alt="" />
      <?php _e( "Content Protector", "content-protector" ); ?></h2>
    <div id="content-protector-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="#content-protector-tabs-gen"><?php _e( "General Settings", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-form-instr"><?php _e( "Form Instructions", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-ajax"><?php _e( "AJAX Loading Message", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-success"><?php _e( "Success Message", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-error"><?php _e( "Error Message", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-form-submit"><?php _e( "Form Submit Label", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-captcha"><?php _e( "CAPTCHA", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-form-css"><?php _e( "Form CSS", "content-protector" ); ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs-password"><?php _e( "Password/CAPTCHA Field", "content-protector" ); ?></a></li>
        </ul>
        <div id="content-protector-tabs-gen" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_general_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-form-instr" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-ajax" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-success" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-error" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-form-submit" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-captcha" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_captcha_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-form-css" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs-password" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_password_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_password_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", "content-protector" ); ?>" />
            </form>
        </div>
    </div>
</div>
