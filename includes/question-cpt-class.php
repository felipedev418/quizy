<?php
// Question CPT class goes here
if (!defined('ABSPATH')) exit;

/**
 * Qzy_Question_CPT core class.
 *
 * Register plugin and make instances of core classes
 *
 * @package Qzy_Question_CPT
 * @version 1.0.0
 * @since   1.0.0
 */
class Qzy_Question_CPT {

    /**
     * Qzy_Question_CPT constructor.
     */
    function __construct() {

        $this->create_post_type();

    }

    function create_post_type()
    {
        add_action('init', array($this, 'register_post_type'));

        add_action('init', array($this, 'register_taxonomies'));

        add_action( 'add_meta_boxes', array($this, 'register_meta_boxes'));

        add_action( 'save_post', array($this, 'save_meta_boxs'));
    }

    function register_meta_boxes() {
        // Question answers
        add_meta_box( 'qzy_answers', 'Answers', array($this, 'add_answers_metabox'), 'qzy_question' );
    }

    function add_answers_metabox( $post ) {
        $question_answers = get_post_meta($post->ID,'answers');
        $question_goods = get_post_meta($post->ID,'goods');

        $question_answers = $question_answers[0];
        $question_goods = $question_goods[0];

        $nbr_answers = count($question_answers)? count($question_answers): 0;

        ?>
        <ul>
        <?php if($question_answers): ?>
            <?php foreach ($question_answers as $key => $answer) : ?>
                <li>
                    <label>
                        Answer 1 <input type="text" name="answers[<?php echo $key;?>]" value="<?php echo $answer; ?>" class="regular-text">
                    </label>
                    <?php $cheched_or_not = ( is_array($question_goods) && array_key_exists($key, $question_goods) && $question_goods[$key] == 'on') ? 'checked="checked"' : '' ?>
                    <label>is this a good answer ? <input type="checkbox" name="goods[<?php echo $key;?>]" <?php echo $cheched_or_not; ?>></label>
                </li>
            <?php endforeach;  ?>
        <?php endif; ?>
        <!-- Additional empty answer -->
            <li>
                <label>
                    Answer <?php echo $nbr_answers+1;?> <input type="text" name="answers[<?php echo $nbr_answers;?>]" value="" class="regular-text">
                </label>
                <label>is this a good answer ? <input type="checkbox" name="goods[<?php echo $nbr_answers;?>]" ></label>
            </li>
        </ul>
        <?php
    }
     
    function save_meta_boxs( $post_id ) {
        $answers = $_POST['answers'];
        $goods = $_POST['goods'];

        if( !update_post_meta($post_id, 'answers', $answers) ){
            add_post_meta($post_id, 'answers', $answers);
        }

        if( !update_post_meta($post_id, 'goods', $goods) ){
            add_post_meta($post_id, 'goods', $goods);
        }
    }

    function register_post_type()
    {
	    $args = array(
	      'public' => true,
	      'label'  => 'Questions',
          'taxonomies' => array('question_cat'),
          'supports' => array('editor','author','thumbnail')
	    );
	    register_post_type( 'qzy_question', $args );
    }

    function register_taxonomies(){
        $args = array(
            'hierarchical'          => true,
            'label'                => 'Category',
            'show_ui'               => true
        );

        register_taxonomy( 'question_cat', 'qzy_question', $args );
    }
}