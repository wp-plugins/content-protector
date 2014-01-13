jQuery(document).ready(function() {
    jQuery('#content-protector-tabs').tabs();
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

    var colors =  eval( contentProtectorAdminOptions.theme_colors );
    var num_colors_per_row = 9;
    var swatch_margin = 2; // px
    var num_rows = parseInt( colors.length / num_colors_per_row ) + 1;

    var contentProtectorAdminColorOptions = {
        defaultColor: false,
        hide: true,
        palettes: colors
    };
    jQuery(contentProtectorAdminOptions.color_controls).wpColorPicker(contentProtectorAdminColorOptions);
    var picker_inner_width = jQuery("div.iris-square").first().width() + jQuery("div.iris-slider").first().width();
    var picker_height = jQuery("div.iris-picker").first().height();
    var swatch_size = ( picker_inner_width / num_colors_per_row ) - swatch_margin;
    jQuery("div.iris-picker").css("height", picker_height + ( ( ( swatch_size + swatch_margin ) * num_rows ) + ( 4 * swatch_margin ) ) + "px");
    jQuery("a.iris-palette").css("height", swatch_size + "px").css("width", swatch_size + "px").css("margin", swatch_margin + "px");
    jQuery("div.iris-palette-container").css("bottom", ( 2 * swatch_margin ) + "px");

});