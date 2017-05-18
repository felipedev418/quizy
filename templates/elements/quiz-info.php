<?php if ( ! defined( 'ABSPATH' ) ) exit;
/*
	Quiz information element template
*/
?>
<div class="quiz_info">
	<h2>Quiz information</h2>
	<ul>
		<li><strong>Title :</strong> <?php echo $quiz_post->post_title; ?></li>
		<li><strong>Description :</strong> <?php echo $quiz_post->post_content; ?></li>
		<li><strong>Categories :</strong> <?php echo implode(',', Qzy_Quiz_CPT::get_quiz_cats( $quiz_post->ID ) ); ?></li>
		<li><strong>Type :</strong> <?php echo Qzy_Quiz_CPT::get_quiz_information( $quiz_post->ID, 'type' ); ?></li>
		<li><strong>Duration/Question :</strong> <?php echo Qzy_Quiz_CPT::get_quiz_information( $quiz_post->ID, 'question_duration' ); ?></li>
		<li><strong>Max questions/Quiz :</strong> <?php echo Qzy_Quiz_CPT::get_quiz_information( $quiz_post->ID, 'questions_number' ); ?></li>
	</ul>
</div>