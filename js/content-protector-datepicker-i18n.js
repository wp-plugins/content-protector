/* 	I18n initialization for the JQuery UI Datepicker plugin. 
	
	We feed strings in from the main 'content-protector.php' file so that translators can provide the localized
	versions from the corresponding .POT file.  We don't expect them to become developers in order to
	provide translations. :)
*/
jQuery(function($){
    $.datepicker.regional['content-protector-i18n'] = {
        closeText: ContentProtectorJQDatepickerI18n.closeText,
        prevText: ContentProtectorJQDatepickerI18n.prevText,
        nextText: ContentProtectorJQDatepickerI18n.nextText,
        currentText: ContentProtectorJQDatepickerI18n.currentText,
        monthNames: ContentProtectorJQDatepickerI18n.monthNames,
        monthNamesShort: ContentProtectorJQDatepickerI18n.monthNamesShort,
        dayNames: ContentProtectorJQDatepickerI18n.dayNames,
        dayNamesShort: ContentProtectorJQDatepickerI18n.dayNamesShort,
        dayNamesMin: ContentProtectorJQDatepickerI18n.dayNamesMin,
        weekHeader: ContentProtectorJQDatepickerI18n.weekHeader,
        dateFormat: ContentProtectorJQDatepickerI18n.dateFormat,
        firstDay: ContentProtectorJQDatepickerI18n.firstDay,
        isRTL: ContentProtectorJQDatepickerI18n.isRTL,
        showMonthAfterYear: ContentProtectorJQDatepickerI18n.showMonthAfterYear,
        yearSuffix: ContentProtectorJQDatepickerI18n.yearSuffix	};
    $.datepicker.setDefaults($.datepicker.regional['content-protector-i18n']);
});