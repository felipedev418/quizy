<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="quiz_wrap">
	<?php
	if( array_key_exists('questions', $_POST) && count($_POST['questions']) > 0 ):
		require QUIZY_TEMPLATES_DIR.'/quiz-evaluation.php';
	else:
		?>
			<div class="questions-wrap">
				<form action="" method="post">
					<?php 
					/*
					*	quizy_before_questions hook
					*
					*	@hooked : quizy_quiz_description_template - 10
					*/

					do_action('quizy_before_questions', $quiz_post);
					
					foreach ($questions as $key => $question):

						/*
						*	quizy_before_question hook
						*/
						do_action('quizy_before_question', $quiz_post);

						require QUIZY_TEMPLATES_DIR.'/question-content.php';
						
						/*
						*	quizy_after_question hook
						*/
						do_action('quizy_after_question', $quiz_post);

					endforeach;

					/*
					*	quizy_after_questions hook
					*
					*	@hooked : quizy_quiz_submit_button_template - 10
					*/

					do_action('quizy_after_questions', $quiz_post);

					?>
				</form>
			</div>	
		<?php
	endif;
	?>
</div>