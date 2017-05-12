<?php
// get $quiz_id from shortcode attribs
$quiz_post = get_post($quiz_id);

// Quit if not a quiz
if( !$quiz_post || $quiz_post->post_type != Qzy_Quiz_CPT::get_post_type_name() ){
	?>
	<p>No such Quiz! <?php echo $quiz_post->post_type; ?></p>
	<?php
	return;
}

$cats = get_the_terms($quiz_id,'quiz_cat');
$cats_array = array();

if($cats){
	foreach ($cats as $key => $cat) {
		array_push($cats_array, $cat->name);
	}
}

$quiz_meta = get_post_meta( $quiz_id );

$quiz_type = ($quiz_meta['type'][0] ? $quiz_meta['type'][0] : get_option('qzy_default_quiz_type'));
$quiz_duration_per_question = ($quiz_meta['duration'][0] ? $quiz_meta['duration'][0] : get_option('qzy_default_duration'));
$max_questions_per_quiz = ($quiz_meta['questions_nbr'][0] ? $quiz_meta['questions_nbr'][0] : get_option('qzy_default_questions'));
?>
<div class="quiz_wrap">
	<div class="quiz_info">
		<h2>Quiz information</h2>
		<ul>
			<li><strong>Title :</strong> <?php echo $quiz_post->post_title; ?></li>
			<li><strong>Description :</strong> <?php echo $quiz_post->post_content; ?></li>
			<li><strong>Categories :</strong> <?php echo implode(',', $cats_array); ?></li>
			<li><strong>Type :</strong> <?php echo $quiz_type; ?></li>
			<li><strong>Duration/Question :</strong> <?php echo $quiz_duration_per_question; ?></li>
			<li><strong>Max questions/Quiz :</strong> <?php echo $max_questions_per_quiz; ?></li>
		</ul>
	</div>
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
					<div class="question">
						<?php require QUIZY_TEMPLATES_DIR.'/question-content.php'; ?>
					</div>
				<?php endforeach; ?>
				<input type="submit" value="Send">
			</form>
		</div>	
	<?php
	}
	?>

</div>