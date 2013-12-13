/**
 * Created by Ken on 22/11/13.
 */
jQuery( document ).ready( function() {
    jQuery('#form-instructions-reset').click( function() {
        jQuery(contentProtectorAdminOptions.form_instructions_id).val(contentProtectorAdminOptions.form_instructions_default);
    });
    jQuery('#ajax-loading-message-reset').click( function() {
        jQuery(contentProtectorAdminOptions.ajax_loading_message_id).val(contentProtectorAdminOptions.ajax_loading_message_default);
    });
    jQuery('#success-message-reset').click( function() {
        jQuery(contentProtectorAdminOptions.success_message_id).val(contentProtectorAdminOptions.success_message_default);
    });
    jQuery('#error-message-reset').click( function() {
        jQuery(contentProtectorAdminOptions.error_message_id).val(contentProtectorAdminOptions.error_message_default);
    });
    jQuery('#form-submit-reset').click( function() {
        jQuery(contentProtectorAdminOptions.form_submit_label_id).val(contentProtectorAdminOptions.form_submit_label_default);
        return false;
    });
    jQuery('#form-css-all').click( function() {
        jQuery(contentProtectorAdminOptions.form_css_id).val(jQuery(contentProtectorAdminOptions.form_css_id).val() + contentProtectorAdminOptions.form_css_all_default);
    });
    jQuery('#form-css-ident').click( function() {
        var the_id = window.prompt(contentProtectorAdminOptions.form_css_ident_dialog);
        if (the_id.length > 0) {
            jQuery(contentProtectorAdminOptions.form_css_id).val(jQuery(contentProtectorAdminOptions.form_css_id).val() + contentProtectorAdminOptions.form_css_ident_default.replace(/{id}/g, the_id));
        }
    });
    jQuery('#form-css-reset').click( function() {
        jQuery(contentProtectorAdminOptions.form_css_id).val("");
    });
});