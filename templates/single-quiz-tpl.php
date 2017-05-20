<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="quiz_wrap quiz_<?php echo $quiz_post->ID; ?>">
	<?php
	if( 
		!$quiz_list_mode && ( count($questions) == 0 || ( array_key_exists('old_questions', $_POST) && count(json_decode( stripslashes($_POST['old_questions']) ) )+1 >= intval($max_questions_per_quiz) ) )
		||
		$quiz_list_mode && array_key_exists('questions', $_POST) && count($_POST['questions']) > 0 ):

		quizy_get_template( 'quiz-evaluation.php', array('quiz_list_mode' => $quiz_list_mode));

	else:
		?>
			<div class="questions-wrap">
				<form action="" method="post">
					<?php 
					/**
					 *	quizy_before_questions hook
					 *
					 *	@hooked : quizy_quiz_description_template - 10
					 */

					do_action('quizy_before_questions', $quiz_post);

					$question_num = 1;

					foreach ($questions as $question):

						/**
						 *	quizy_before_question hook
						 */
						do_action('quizy_before_question', $question);

						$passing_args = array(
							'question' => $question,
							'quiz_type' => $quiz_type,
							'question_num' => $question_num++
						);

						quizy_get_template( 'question-content.php', $passing_args);

						/**
						 *	quizy_after_question hook
						 */
						do_action('quizy_after_question', $question);

					endforeach;

					// Add hidden imputs in which we save data for previous questions
					quizy_add_hidden( $quiz_list_mode );

					/**
					 *	quizy_after_questions hook
					 *
					 *	@hooked : quizy_show_questions_number - 9
					 *	@hooked : quizy_quiz_submit_button_template - 10
					 */

					do_action('quizy_after_questions', $quiz_post, $questions);

					?>
				</form>
			</div>	
		<?php
	endif;
	?>
</div>