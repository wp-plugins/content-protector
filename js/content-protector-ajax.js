// pre-submit callback 
function contentProtectorBeforeSubmit(formData, jqForm, options) {
    var tag = jQuery(options.target);
    tag.find("form").remove();
    tag.append('<div class="content-protector-ajaxLoading" id="content-protector-ajaxLoading' + options.data.identifier + '"><img src="' + contentProtectorAjax.loading_img + '" />&nbsp;' + contentProtectorAjax.loading_label + '</div>');

    return true;
}

// error callback
function contentProtectorError(xhr, textStatus, errorThrown) {
    alert(contentProtectorAjax.error_heading + '\n\n' + contentProtectorAjax.error_desc + '\n\ntextStatus: ' + textStatus + '\n\errorThrown: ' + errorThrown + '\n\nxhr.responseText: \n' + xhr.responseText );
}