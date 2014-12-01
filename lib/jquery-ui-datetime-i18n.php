<?php
// Let's figure out i18n for the date- and timepickers
if ( !isset( $jquery_ui_datetime_dayNames ) )
    $jquery_ui_datetime_dayNames = array( __( "Sunday", 'content-protector' ),
        __( "Monday", 'content-protector' ),
        __( "Tuesday", 'content-protector' ),
        __( "Wednesday", 'content-protector' ),
        __( "Thursday", 'content-protector' ),
        __( "Friday", 'content-protector' ),
        __( "Saturday", 'content-protector' ) );

if ( !isset( $jquery_ui_datetime_dayNamesShort ) )
    $jquery_ui_datetime_dayNamesShort = array( _x( "Sun", "Three-letter abbreviation for Sunday", 'content-protector' ),
        _x( "Mon", "Three-letter abbreviation for Monday", 'content-protector' ),
        _x( "Tue", "Three-letter abbreviation for Tuesday", 'content-protector' ),
        _x( "Wed", "Three-letter abbreviation for Wednesday", 'content-protector' ),
        _x( "Thu", "Three-letter abbreviation for Thursday", 'content-protector' ),
        _x( "Fri", "Three-letter abbreviation for Friday", 'content-protector' ),
        _x( "Sat", "Three-letter abbreviation for Saturday", 'content-protector' ) );

if ( !isset( $jquery_ui_datetime_dayNamesMin ) )
    $jquery_ui_datetime_dayNamesMin = array( _x( "Su", "Two-letter abbreviation for Sunday", 'content-protector' ),
        _x( "Mo", "Two-letter abbreviation for Monday", 'content-protector' ),
        _x( "Tu", "Two-letter abbreviation for Tuesday", 'content-protector' ),
        _x( "We", "Two-letter abbreviation for Wednesday", 'content-protector' ),
        _x( "Th", "Two-letter abbreviation for Thursday", 'content-protector' ),
        _x( "Fr", "Two-letter abbreviation for Friday", 'content-protector' ),
        _x( "Sa", "Two-letter abbreviation for Saturday", 'content-protector' ) );

if ( !isset( $jquery_ui_datetime_monthNames ) )
    $jquery_ui_datetime_monthNames = array( __( "January", 'content-protector' ),
        __( "February", 'content-protector' ),
        __( "March", 'content-protector' ),
        __( "April", 'content-protector' ),
        __( "May", 'content-protector' ),
        __( "June", 'content-protector' ),
        __( "July", 'content-protector' ),
        __( "August", 'content-protector' ),
        __( "September", 'content-protector' ),
        __( "October", 'content-protector' ),
        __( "November", 'content-protector' ),
        __( "December", 'content-protector' ) );

if ( !isset( $jquery_ui_datetime_monthNamesShort ) )
    $jquery_ui_datetime_monthNamesShort = array( _x( "Jan", "Three-letter abbreviation for January", 'content-protector' ),
        _x( "Feb", "Three-letter abbreviation for February", 'content-protector' ),
        _x( "Mar", "Three-letter abbreviation for March", 'content-protector' ),
        _x( "Apr", "Three-letter abbreviation for April", 'content-protector' ),
        _x( "May", "Three-letter abbreviation for May", 'content-protector' ),
        _x( "Jun", "Three-letter abbreviation for June", 'content-protector' ),
        _x( "Jul", "Three-letter abbreviation for July", 'content-protector' ),
        _x( "Aug", "Three-letter abbreviation for August", 'content-protector' ),
        _x( "Sep", "Three-letter abbreviation for September", 'content-protector' ),
        _x( "Oct", "Three-letter abbreviation for October", 'content-protector' ),
        _x( "Nov", "Three-letter abbreviation for November", 'content-protector' ),
        _x( "Dec", "Three-letter abbreviation for December", 'content-protector' ) );

if ( !isset( $jquery_ui_datetime_timePeriods ) )
    $jquery_ui_datetime_timePeriods = array( _x( "AM", "Abbreviation for first 12-hour period in a day", 'content-protector' ),
        _x( "PM", "Abbreviation for second 12-hour period in a day", 'content-protector' ) );

if ( !isset( $jquery_ui_datetime_datepicker_i18n ) )
    $jquery_ui_datetime_datepicker_i18n = array(
        "closeText" => _x( "Done", "jQuery UI Datepicker Close label", "content-protector" ), // Display text for close link
        "prevText" => _x( "Prev", "jQuery UI Datepicker Previous label", "content-protector" ), // Display text for previous month link
        "nextText" => _x( "Next", "jQuery UI Datepicker Next label", "content-protector" ), // Display text for next month link
        "currentText" => _x( "Today", "jQuery UI Datepicker Today label", "content-protector" ), // Display text for current month link
        "monthNames" => $jquery_ui_datetime_monthNames, // Names of months for drop-down and formatting
        "monthNamesShort" => $jquery_ui_datetime_monthNamesShort, // For formatting
        "dayNames" => $jquery_ui_datetime_dayNames, // For formatting
        "dayNamesShort" => $jquery_ui_datetime_dayNamesShort, // For formatting
        "dayNamesMin" => $jquery_ui_datetime_dayNamesShort, // Column headings for days starting at Sunday
        "weekHeader" => _x( "Wk", "jQuery UI Datepicker Week label", "content-protector" ), // Column header for week of the year
        /* translators:  http://jqueryui.com/datepicker/#date-formats */
        "dateFormat" => _x( "MM d, yy", "jQuery UI Datepicker Date format", 'content-protector' ),
        "firstDay" => ( int )( _x( "0", "jQuery UI Datepicker 'First day of week' as integer ( Sunday = 0 ( 'zero' ), Monday = 1, ... )", 'content-protector' ) ), // The first day of the week, Sun = 0, Mon = 1, ...
        "isRTL" => ( _x( "false", "jQuery UI Datepicker: Is translated language read right-to-left ( Value must be either English 'true' or 'false' )?", 'content-protector' ) == "false" ? false : true ), // True if right-to-left language, false if left-to-right
        "showMonthAfterYear" => false, // True if the year select precedes month, false for month then year
        "yearSuffix" => '' // Additional text to append to the year in the month headers
     );

if ( !isset( $jquery_ui_datetime_timepicker_i18n ) )
    $jquery_ui_datetime_timepicker_i18n = array(
        "hourText" => _x( "Hour", "jQuery UI Timepicker 'Hour' label", "content-protector" ),
        "minuteText" => _x( "Minute", "jQuery UI Timepicker 'Minute' label", "content-protector" ),
        "amPmText" => $jquery_ui_datetime_timePeriods,
        "showPeriod" => ( _x( "true", "jQuery UI Datepicker: Does translated language show 'AM' or 'PM' (or equivalent) when displaying a time  ( Value must be either English 'true' or 'false' )?", 'content-protector' ) == "true" ? true : false ),
        "timeSeparator" => _x( ":", "jQuery UI Datepicker: Character used to separate hours and minutes in translated language", 'content-protector' ),
        "closeButtonText" => _x( "Done", "jQuery UI Timepicker 'Done' label", "content-protector" ),
        "nowButtonText" => _x( "Now", "jQuery UI Timepicker 'Now' label", "content-protector" ),
        "deselectButtonText" => _x( "Deselect", "jQuery UI Timepicker 'Deselect' label", "content-protector" ) );

?>