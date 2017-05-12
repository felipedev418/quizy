<?php
$answers = get_post_meta($question->ID,'answers', true);
foreach ($answers as $key => $answer) {
	$answers[$key] = array(
						'key' => $key,
	 					'answer' => $answer
	 				);
}

// Randomize answers elements
shuffle($answers);

$goods = get_post_meta($question->ID,'goods', true);

$image_url = get_the_post_thumbnail_url( $question, 'post-thumbnail' );

// Show question only if there is one good answer at least
if ( count($goods) > 0 ) {
	// show if Multiple Choice Question OR (Good answers is 1 AND Unique Choice Question)
	if( ('mcq' == $quiz_type) || (count($goods) == 1 && 'ucq' == $quiz_type) ){
		$nbr_question++;
		// Questions limit
		if( $nbr_question > $max_questions_per_quiz){
			return;
		}
		?>

		<div class="question">
			<h2><?php echo esc_html($question->post_content); ?></h2>
			<?php if( $image_url ){ ?>
				<div class="thumb">
					<img src="<?php echo $image_url; ?>" alt="">
				</div>
			<?php } ?>
			<ul class="answers">
				<?php foreach ($answers as $key => $answer) { ?>
						<li>
							<label>
								<?php if( 'ucq' == $quiz_type ): ?>
									<input type="radio" name="answer[<?php echo $question->ID; ?>]" value="<?php echo $answer['key']; ?>">
								<?php else: ?>
									<input type="checkbox" name="answer[<?php echo $question->ID; ?>][<?php echo $answer['key']; ?>]" value="<?php echo $answer['key']; ?>">
								<?php endif; ?>
									<span><?php echo esc_html($answer['answer']) ; ?></span>
							</label>
						</li>
				<?php } ?>
			</ul>
			<input type="hidden" name="questions[]" value="<?php echo $question->ID; ?>">
		</div>
		<?php
	}
}