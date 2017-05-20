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

function quizy_add_hidden( $quiz_list_mode = false ){
	
	if ( $quiz_list_mode )
		return;

	$old_questions = '';
	$old_answers = '';

	if( array_key_exists('old_questions', $_POST) ){
		$old_questions = json_decode( stripslashes($_POST['old_questions']), true);
		$old_answers = json_decode( stripslashes($_POST['old_answers']), true);

		if( !is_array($old_questions) ){ $old_questions = array(); }

		if( !is_array($old_answers) ){ $old_answers = array(); }

		// add new 
		foreach ($_POST['questions'] as $key => $question_id) {
			if( !in_array($question_id, $old_questions) ){
				array_push($old_questions, $question_id);
			}
		}

		if( array_key_exists('answer', $_POST) ){
			foreach ($_POST['answer'] as $qst_key => $answers) {								
				$old_answers[$qst_key] = $answers;
			}
		}else{
			foreach ($_POST['questions'] as $key => $question_id) {
				unset($old_answers[$question_id]);
			}
		}

		// add to form again
		$old_questions = json_encode( $old_questions );
		$old_answers = json_encode( $old_answers );
	}
	?>
		<input name="old_questions" type="text" value="<?php echo esc_attr($old_questions); ?>">
		<input name="old_answers" type="text" value="<?php echo esc_attr($old_answers); ?>">
	<?php
}