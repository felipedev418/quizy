<?php get_header(); ?>
<div>
	<?php
global $post;

$cats = get_the_terms(get_the_ID(),'quiz_cat');
$cats_array = array();

foreach ($cats as $key => $cat) {
	array_push($cats_array, $cat->name);
}

$quiz_meta = get_post_meta( get_the_ID() );

$quiz_type = $quiz_meta['type'][0];
$quiz_duration_per_question = $quiz_meta['duration'][0];
$quiz_questions = $quiz_meta['questions_nbr'][0];
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
<h1>Questions :</h1>
<ul class="questions">
	<?php foreach ($questions as $key => $question):?>
		<li><?php echo esc_html($question->post_content); ?></li>
	<?php endforeach; ?>
</ul>

<?php get_footer(); ?>