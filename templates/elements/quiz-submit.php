<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Submit button element template
*/

	if( $quiz_list_mode ){
		$label = "Send";
	}else{
		$label = "Next";
	}
?>
<input type="submit" value="<?php echo $label; ?>">