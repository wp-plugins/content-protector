<?php if ( !isset( $is_ajax_processed ) ) { ?><div id="content-protector<?php echo $identifier; ?>" class="content-protector-access-form"><?php } ?>
    <form id="content-protector-access-form<?php echo $identifier; ?>" method="post" action="">
        <?php
        // Error message on unsuccessful attempt. Check $_POST['content-protector-ident'] to make sure
        // we're showing the error message on the right Content Protector access form
        if ( ( isset( $_POST['content-protector-ident'] ) ) && ( $_POST['content-protector-ident'] == $ident ) ) { ?>
            <div id="content-protector-incorrect-password<?php echo $identifier; ?>"
                 class="content-protector-incorrect-password"><?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_error_message', CONTENT_PROTECTOR_DEFAULT_ERROR_MESSAGE ); ?></div>
        <?php } ?>
        <?php if ( !( ( $captcha ) && ( $captcha_instr_mode == 2 ) ) ) { ?>
            <div id="content-protector-form-instructions<?php echo $identifier; ?>"
                 class="content-protector-form-instructions"><?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_instructions', CONTENT_PROTECTOR_DEFAULT_FORM_INSTRUCTIONS ); ?></div>
        <?php } ?>
        <?php if ( $captcha ) { ?>
            <?php if ( $captcha_instr_mode > 0 ) { ?>
                <div for="content-protector-captcha-instructions<?php echo $identifier; ?>"
                     id="content-protector-captcha-instructions<?php echo $identifier; ?>"
                     class="content-protector-form-instructions"><?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_captcha_instructions', CONTENT_PROTECTOR_DEFAULT_CAPTCHA_INSTRUCTIONS ); ?></div>
            <?php } ?>
            <img id="content-protector-captcha-img<?php echo $identifier; ?>" class="content-protector-captcha-img"
                 src="<?php echo $this->__generateCaptchaDataUri( $password ); ?>"/><br/>
            <input name="content-protector-captcha" id="content-protector-captcha<?php echo $identifier; ?>"
                   type="hidden" value="1"/>
        <?php } ?>
        <input name="content-protector-password" id="content-protector-password<?php echo $identifier; ?>"
               class="content-protector-password" type="password" value=""/>
        <?php if ( strlen( trim( $cookie_expires ) ) > 0 ) { ?>
            <input name="content-protector-expires" id="content-protector-expires<?php echo $identifier; ?>"
                   type="hidden" value="<?php echo $cookie_expires; ?>"/>
        <?php } ?>
        <input name="content-protector-token" id="content-protector-token<?php echo $identifier; ?>" type="hidden"
               value="<?php echo $this->__hashPassword( $password ); ?>"/>
        <input name="content-protector-ident" id="content-protector-ident<?php echo $identifier; ?>" type="hidden"
               value="<?php echo $ident; ?>"/>
        <input name="content-protector-submit" id="content-protector-submit<?php echo $identifier; ?>"
               class="content-protector-form-submit" type="submit"
               value="<?php echo get_option( CONTENT_PROTECTOR_HANDLE . '_form_submit_label', CONTENT_PROTECTOR_DEFAULT_FORM_SUBMIT_LABEL ); ?>"/>
    </form>
<?php if ( !isset( $is_ajax_processed ) ) { ?></div><?php } ?>
<?php if ( $ajax ) { ?>
    <script type="text/javascript">
        jQuery( document ).ready( function () {
            jQuery( "#content-protector-access-form<?php echo $identifier; ?>" ).ajaxForm( 
                {
                    target: "#content-protector<?php echo $identifier; ?>",
                    data: {
                        post_id: "<?php echo $post_id; ?>",
                        identifier: "<?php echo $identifier; ?>",
                        ajax_security: "<?php echo wp_create_nonce( "view_" . CONTENT_PROTECTOR_HANDLE . "_" . $post_id . $identifier ); ?>",
                        action: "contentProtectorProcessFormAjax"
                    },
                    url: contentProtectorAjax.ajaxurl,
                    beforeSubmit: contentProtectorBeforeSubmit,
                    success: contentProtectorSuccess,
                    error: contentProtectorError
                }
             );
        } );
    </script>
<?php } ?>