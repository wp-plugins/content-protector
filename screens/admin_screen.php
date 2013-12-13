<div class="wrap">
  <h2><img name="<?php echo CONTENT_PROTECTOR_HANDLE; ?>_icon32" src="<?php echo CONTENT_PROTECTOR_PLUGIN_URL . "/img/lock32.png"; ?>" align="absmiddle" width="32" height="32" alt="" />
      <?php _e( "Content Protector", CONTENT_PROTECTOR_SLUG ); ?></h2>
  <form action="options.php" method="post">
    <?php settings_fields( CONTENT_PROTECTOR_HANDLE ); ?>
    <?php do_settings_sections( CONTENT_PROTECTOR_HANDLE ); ?>
    <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php _e( "Save Settings", CONTENT_PROTECTOR_SLUG ); ?>" />
  </form>
</div>
