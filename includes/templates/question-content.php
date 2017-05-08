<?php
$qst_user_ansers = array();

if( array_key_exists('answer', $_POST) &&  array_key_exists($question->ID, $_POST['answer']) ){
	$qst_user_ansers = $_POST['answer'][$question->ID]; // user answers for the current question 

	if( !is_array($qst_user_ansers) ){
		$qst_user_ansers = array($qst_user_ansers);
	}
}

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
		?>
		<h2><?php echo esc_html($question->post_content); ?></h2>
		<?php if( $image_url ){ ?>
			<div class="thumb">
				<img src="<?php echo $image_url; ?>" alt="">
			</div>
		<?php } ?>
		<ul class="answers">
			<?php foreach ($answers as $key => $answer) { ?>
				<?php if( $quiz_evaluating ){ ?>
							<?php if( in_array($answer['key'], $qst_user_ansers) ){ ?>
									<li>
										<span><?php echo esc_html($answer['answer']); ?></span>
									</li>
							<?php } ?>
				<?php }else{ ?>
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
			<?php } ?>
		</ul>
		<input type="hidden" name="questions[]" value="<?php echo $question->ID; ?>">
		<div class="question_eval">
		<?php 
		if( $quiz_evaluating ){
			$good_answers = Qzy_Question_CPT::are_good_answers($qst_user_ansers, $goods);

			if( $good_answers ){ 
			?>
				<div class="good_answer">Good answer</div>
			<?php }else{ ?>
				<div class="bad_answer">Bad answer!</div>
			<?php } ?>
			</div>
			<?php
		}
	}
}