// Preload the spinner image
var spinner = jQuery("<img />").attr("src", contentProtectorAjax.loading_img);

// pre-submit callback
function contentProtectorBeforeSubmit(formData, jqForm, options) {
    var tag = jQuery(options.target);
    tag.find("form").remove();
    tag.append('<div class="content-protector-ajaxLoading" id="content-protector-ajaxLoading'
        + options.data.identifier
        + '"><img src="'
        + contentProtectorAjax.loading_img
        + '" />&nbsp;'
        + contentProtectorAjax.loading_label
        + '</div>');

    return true;
}
// success callback
function contentProtectorSuccess(data, status, xhr, $form) {
    jQuery(this).trigger("bindJsToContentProtectorDiv");
}
// error callback
function contentProtectorError(xhr, textStatus, errorThrown) {
    alert(contentProtectorAjax.error_heading
        + '\n\n'
        + contentProtectorAjax.error_desc
        + '\n\ntextStatus: '
        + textStatus
        + '\n\errorThrown: '
        + errorThrown
        + '\n\nxhr.responseText: \n'
        + xhr.responseText);
}
// setup AJAX form
function setupAjaxForm(form) {
    jQuery(form.form_id).ajaxForm({
        target: form.target,
        data: {
            post_id: form.post_id,
            identifier: form.identifier,
            ajax_security: form.ajax_security_nonce,
            action: "contentProtectorProcessFormAjax"
        },
        url: contentProtectorAjax.ajaxurl,
        beforeSubmit: contentProtectorBeforeSubmit,
        success: contentProtectorSuccess,
        error: contentProtectorError
    });
}

jQuery(document).ready(function () {
    for (var i = 0; i < contentProtectorAjax.forms.length; i++)
        setupAjaxForm(contentProtectorAjax.forms[i]);
});

jQuery(document).ready(function () {
    // Support for Contact Form 7
    jQuery('div.content-protector-access-form').on('bindJsToContentProtectorDiv', function (e) {
        var the_form = jQuery(e.target).find('div.wpcf7 form.wpcf7-form');
        if (jQuery(the_form).length === 0)
            return;
        jQuery(the_form).each(function () {
            var the_action = jQuery(this).attr('action');
            var the_action_parts = the_action.split('#');
            jQuery(this).wpcf7InitForm();
            jQuery(this).attr('action', location.href + '#' + the_action_parts[1]);
        });
    });
});