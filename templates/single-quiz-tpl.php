<?php if ( ! defined( 'ABSPATH' ) ) exit;
// get $quiz_id from shortcode attribs
$quiz_post = get_post($quiz_id);

// Quit if not a quiz
if( !$quiz_post || $quiz_post->post_type != Qzy_Quiz_CPT::get_post_type_name() ){
	?>
	<p>No such Quiz! <?php echo $quiz_post->post_type; ?></p>
	<?php
	return;
}

?>
<div class="quiz_wrap">

	<?php 
	/*
		@hooked : quizy_quiz_description_template, 10
	*/

	do_action('quizy_before_questions', $quiz_post);

	?>

	<?php
	$args = array(
			'post_type' => Qzy_Question_CPT::get_post_type_name(),
			'posts_per_page' => -1,
			'meta_key' => 'quiz_related',
	 		'orderby' => 'rand',
			'meta_query' => array(
				'key' => 'quiz_related',
				'value' => $quiz_id,
				'compare' => '='
				)
		);
	$questions = get_posts($args);

	$quiz_evaluating = false;
	
	if( array_key_exists('questions', $_POST) && count($_POST['questions']) > 0 ){
		$quiz_evaluating = true;
	}

	$nbr_question = 0;

	if( $quiz_evaluating ){
		require QUIZY_TEMPLATES_DIR.'/quiz-evaluation.php';
	}else{
	?>
		<div class="questions-wrap">
			<form action="" method="post">
				<?php foreach ($questions as $key => $question):?>
					<?php require QUIZY_TEMPLATES_DIR.'/question-content.php'; ?>
				<?php endforeach; ?>
				<input type="submit" value="Send">
			</form>
		</div>	
	<?php
	}
	?>

</div>