<?php
//evaluation template

$user_questions_ids = $_POST['questions'];
$user_good_answers = 0;

if( array_key_exists('answer', $_POST) ){
	$user_answers_keys = $_POST['answer'];
}else{
	$user_answers_keys = array();
}

$q_args = array(
	'post_type' => Qzy_Question_CPT::get_post_type_name(),
	'posts_per_page' => -1,
	'meta_key' => 'quiz_related',
	'post__in' => $user_questions_ids
	);

$user_questions = get_posts( $q_args );

?>
<div class="questions-eval-wrap">
	<?php
	foreach ($user_questions as $question) {
		?>
		<div class="question">
			<h2><?php echo esc_html($question->post_content); ?> :</h2>
			<?php

			$answers = get_post_meta($question->ID,'answers', true);
			$good_answers = get_post_meta($question->ID,'goods', true);

			if( array_key_exists( $question->ID, $user_answers_keys) ){
				// user answers
				?>
				<ul>
				<?php
					if( !is_array($user_answers_keys[$question->ID]) ){
						$user_answers_keys[$question->ID] = array( $user_answers_keys[$question->ID] );
					}
					foreach ($user_answers_keys[$question->ID] as $answer_key) {
						?>
						<li><?php echo esc_html($answers[$answer_key]); ?></li>
						<?php
					}
				?>
				</ul>
				<?php
				// good answers
				$are_good_answers = true;

				if( count($user_answers_keys[$question->ID]) == count($good_answers) ){
					foreach ($good_answers as $answer_key => $value) {
						if( !in_array($answer_key, $user_answers_keys[$question->ID]) ){
							$are_good_answers = false;
						}
					}
				}else{
					$are_good_answers = false;
				}
			}else{
				?>
				<p class='no-answers'>-- No answers --</p>
				<?php

				$are_good_answers = false;
			}

			// Evaluation
			if( $are_good_answers ){
				// Good answers
				$user_good_answers++;
				?>
				<div class="good_answer">Good answer</div>
				<?php
			}else{
				// Bad answers
				?>
				<div class="bad_answer">Bad answer</div>
				<?php
			}
			?>
		</div>
		<?php
	}
?>
</div>
<?php
// Good answers rate
$user_good_rate = $user_good_answers/count($user_questions_ids);
$score = $user_good_rate*100;
$score = round( $score, 2 );

?>
<div class="result">Result : <?php echo $score; ?>%</div>
<?php