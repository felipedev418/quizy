<?php

function quizy_get_template( $template_name, $args = array() ) {

	// Enable developers overwrite quizy template files in the 'quizy' theme folder 
	$template_path = 'quizy/';

	// Look within passed path within the theme (child then parent)
	$template = locate_template( $template_path.$template_name );

	if ( ! $template ) {
		$template = QUIZY_TEMPLATES_DIR . '/' . $template_name;
	}

	if( count($args) > 0 ){
		extract($args);
	}

	require apply_filters( 'quizy_locate_template', $template, $template_name, $template_path );
}