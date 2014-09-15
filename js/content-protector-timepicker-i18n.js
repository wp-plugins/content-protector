/* 	I18n initialization for the JQuery UI Timepicker plugin. 
	
	We feed strings in from the main 'content-protector.php' file so that translators can provide the localized
	versions from the corresponding .POT file.  We don't expect them to become developers in order to
	provide translations. :)
*/
jQuery(function($){
    $.timepicker.regional['content-protector-i18n'] = {
        hourText: ContentProtectorJQTimepickerI18n.hourText,
        minuteText: ContentProtectorJQTimepickerI18n.minuteText,
        amPmText: ContentProtectorJQTimepickerI18n.amPmText,
        showPeriod: ContentProtectorJQTimepickerI18n.showPeriod,
        timeSeparator: ContentProtectorJQTimepickerI18n.timeSeparator,
        closeButtonText: ContentProtectorJQTimepickerI18n.closeButtonText,
        nowButtonText: ContentProtectorJQTimepickerI18n.nowButtonText,
        deselectButtonText: ContentProtectorJQTimepickerI18n.deselectButtonText };
    $.timepicker.setDefaults($.timepicker.regional['content-protector-i18n']);
});