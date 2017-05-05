<?php get_header(); ?>
<div>
<?php


$cats = get_the_terms(get_the_ID(),'quiz_cat');
$cats_array = array();

if($cats){
	foreach ($cats as $key => $cat) {
		array_push($cats_array, $cat->name);
	}
}

$quiz_meta = get_post_meta( get_the_ID() );

$quiz_type = ($quiz_meta['type'][0] ? $quiz_meta['type'][0] : get_option('qzy_default_quiz_type'));
$quiz_duration_per_question = ($quiz_meta['duration'][0] ? $quiz_meta['duration'][0] : get_option('qzy_default_duration'));
$quiz_questions = ($quiz_meta['questions_nbr'][0] ? $quiz_meta['questions_nbr'][0] : get_option('qzy_default_questions'));
?>
	<h1>Quiz information</h1>
	<ul>
		<li><strong>Title :</strong> <?php the_title(); ?></li>
		<li><strong>Description :</strong> <?php the_content(); ?></li>
		<li><strong>Categories :</strong> <?php echo implode(',', $cats_array); ?></li>
		<li><strong>Type :</strong> <?php echo $quiz_type; ?></li>
		<li><strong>Duration/Question :</strong> <?php echo $quiz_duration_per_question; ?></li>
		<li><strong>Questions/Quiz :</strong> <?php echo $quiz_questions; ?></li>
	</ul>
</div>
<?php
$args = array(
		'post_type' => Qzy_Question_CPT::get_post_type_name(),
		'posts_per_page' => $quiz_questions,
		'meta_key' => 'quiz_related',
		'meta_query' => array(
			'key' => 'quiz_related',
			'value' => get_the_ID(),
			'compare' => '='
			)
	);
$questions = get_posts($args);

?>

<div class="questions">
	<form action="" method="post">
		<?php foreach ($questions as $key => $question):?>
			<div class="question">
				<?php require QUIZY_TEMPLATES_DIR.'/question-content.php'; ?>
			</div>
		<?php endforeach; ?>
		<input type="submit" value="Send">
	</form>
</div>

<?php get_footer(); ?>