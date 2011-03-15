<?php
$color_options = unserialize( base64_decode( $_GET['color_options'] ) );

foreach( $color_options as $color_option ) {
?>
var <?php echo $color_option; ?>_farbtastic;
function <?php echo $color_option; ?>_pickColor(color) {
	<?php echo $color_option; ?>_farbtastic.setColor(color);
	jQuery("#<?php echo $color_option; ?>").val(color);
	jQuery("#<?php echo $color_option; ?>_sample").css('background-color', color);
}
jQuery(document).ready(function() {
	jQuery("#<?php echo $color_option; ?>_sample").click(function() {
		jQuery("#<?php echo $color_option; ?>_colorPickerDiv").show();
		return false;
	});
    <?php echo $color_option; ?>_farbtastic = jQuery.farbtastic("#<?php echo $color_option; ?>_colorPickerDiv", function(color) {
		<?php echo $color_option; ?>_pickColor(color);
	});
	<?php echo $color_option; ?>_pickColor(jQuery("#<?php echo $color_option; ?>").val());
	jQuery(document).mousedown(function(){
		jQuery("#<?php echo $color_option; ?>_colorPickerDiv").each(function(){
			var display = jQuery(this).css('display');
			if ( display == 'block' )
				jQuery(this).fadeOut(2);
		});
	});
});
<?php } ?>