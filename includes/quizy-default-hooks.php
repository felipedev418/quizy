<?php if ( ! defined( 'ABSPATH' ) ) exit;
/*
	Quizy default hooks
*/

add_action('quizy_before_questions', 'quizy_quiz_description_template', 10);

if( !function_exists('quizy_quiz_description_template') ){

	function quizy_quiz_description_template( $quiz_post ){

        $passing_args = array(
            'quiz_post' => $quiz_post,
        );

        quizy_get_template( 'elements/quiz-info.php', $passing_args);

	}

}

add_action('quizy_after_questions', 'quizy_quiz_submit_button_template', 10);

if( !function_exists('quizy_quiz_submit_button_template') ){

	function quizy_quiz_submit_button_template( $quiz_post ){

        quizy_get_template( 'elements/quiz-submit.php');

	}

}

add_action('quizy_after_questions','quizy_show_questions_number', 9, 3);

if( !function_exists('quizy_show_questions_number') ){

	function quizy_show_questions_number( $quiz_post, $questions, $quiz_list_mode ){
		$nbr_questions = count($questions);

		if( $quiz_list_mode ){
			?>
			<div>Number of questions : <strong><?php echo $nbr_questions; ?></strong></div>
			<?php 
		}

	}
	
}
