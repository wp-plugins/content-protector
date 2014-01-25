<div class="wrap">
  <h2><img name="rot13_encoder_decoder_icon32" src="<?php echo ROT13_ENCODER_DECODER_PLUGIN_URL . "/img/rot13button32.png"; ?>" align="absmiddle" width="32" height="32" alt="" /> ROT13 Encoder/Decoder Settings</h2>
    <form action="options.php" method="post">
    <?php settings_fields( 'rot13_encoder_decoder' ); ?>
    <?php do_settings_sections( 'rot13_encoder_decoder' ); ?>
    <input class="button-primary" name="Submit" id="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
  </form>
</div>
