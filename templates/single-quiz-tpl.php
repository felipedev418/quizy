<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="quiz_wrap">

	<?php 
	/*
		@hooked : quizy_quiz_description_template, 10
	*/

	do_action('quizy_before_questions', $quiz_post);

	if( array_key_exists('questions', $_POST) && count($_POST['questions']) > 0 ):
		require QUIZY_TEMPLATES_DIR.'/quiz-evaluation.php';
	else:
		?>
			<div class="questions-wrap">
				<form action="" method="post">
					<?php foreach ($questions as $key => $question): ?>
						<?php require QUIZY_TEMPLATES_DIR.'/question-content.php'; ?>
					<?php endforeach; ?>
					<input type="submit" value="Send">
				</form>
			</div>	
		<?php
	endif;
	?>
</div>