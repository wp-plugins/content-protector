<div class="wrap">
  <h2><img name="<?php echo CONTENT_PROTECTOR_HANDLE; ?>_icon32" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL . "/img/lock32.png"; ?>" align="absmiddle" width="32" height="32" alt="" />
      <?php _e( "Content Protector", CONTENT_PROTECTOR_SLUG ); ?></h2>
    <div id="content-protector-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="#content-protector-tabs1">General Settings</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs2">Form Instructions</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs3">AJAX Loading Message</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs4">Success Message</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs5">Error Message</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs6">Form Submit Label</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#content-protector-tabs7">Form CSS</a></li>
        </ul>
        <div id="content-protector-tabs1" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_general_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_general_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_instructions_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_ajax_loading_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_success_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs5" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_error_message_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs6" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_submit_label_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
        <div id="content-protector-tabs7" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="display: none;">
            <form action="options.php" method="post">
                <?php settings_fields( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_group' ); ?>
                <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE . '_form_css_settings_subpage' ); ?>
                <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
            </form>
        </div>
    </div>
</div>
