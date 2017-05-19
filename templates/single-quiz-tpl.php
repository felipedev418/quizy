<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="quiz_wrap quiz_<?php echo $quiz_post->ID; ?>">
	<?php
	if( array_key_exists('questions', $_POST) && count($_POST['questions']) > 0 ):
		quizy_get_template( 'quiz-evaluation.php');
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
					
					foreach ($questions as $key => $question):

						/**
						 *	quizy_before_question hook
						 */
						do_action('quizy_before_question', $question);

						$passing_args = array(
							'question' => $question,
							'quiz_type' => $quiz_type
						);

						quizy_get_template( 'question-content.php', $passing_args);

						/**
						 *	quizy_after_question hook
						 */
						do_action('quizy_after_question', $question);

					endforeach;

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