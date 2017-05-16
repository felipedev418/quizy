<?php if ( ! defined( 'ABSPATH' ) ) exit;
/*
	Quizy default hooks
*/

add_action('quizy_before_questions', 'quizy_quiz_description_template', 10);

if( !function_exists('quizy_quiz_description_template') ){

	function quizy_quiz_description_template( $quiz_post ){
		require QUIZY_TEMPLATES_DIR.'/elements/quiz-info.php';
	}

}