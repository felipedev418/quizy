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
    function create_post_type(){
        add_action('init', array($this, 'register_post_type'));

        add_action('init', array($this, 'register_taxonomies'));

        add_action( 'add_meta_boxes', array($this, 'register_meta_boxes'));

        add_action( 'save_post', array($this, 'save_meta_boxs'));
    }

    function register_meta_boxes(){
        // Question answers
        add_meta_box( 'qzy_answers', 'Question settings', array($this, 'add_settings_metabox'), 'qzy_question' );
    }

    function add_settings_metabox( $post ){
        $this->admin_display_answers( $post );
        $this->admin_display_duration( $post );
    }

    function admin_display_answers( $post ){
        $question_answers = get_post_meta($post->ID,'answers');
        $question_goods = get_post_meta($post->ID,'goods');

        $question_answers   = is_array( $question_answers ) && count( $question_answers ) > 0 ? $question_answers[0] : false;
        $question_goods     = is_array( $question_goods ) && count( $question_goods ) > 0 ? $question_goods[0] : false; 

        $current_answer_num = 1;
        ?>
        <h3>Answers</h3>
        <ul id="qzy_answers_list">
        <?php if($question_answers): ?>
            <?php foreach ($question_answers as $key => $answer) : if($answer == '') continue; ?>
                <li>
                    <label>
                        Answer <?php echo $current_answer_num; ?> <input type="text" name="answers[<?php echo $current_answer_num-1;?>]" data-num="<?php echo $current_answer_num;?>" value="<?php echo $answer; ?>" placeholder="Add your answer here ..." class="regular-text">
                    </label>
                    <?php $cheched_or_not = ( is_array($question_goods) && array_key_exists($key, $question_goods) && $question_goods[$key] == 'on') ? 'checked="checked"' : '' ?>
                    <label>is this a good answer ? <input type="checkbox" name="goods[<?php echo $current_answer_num-1;?>]" <?php echo $cheched_or_not; ?>></label>
                </li>
                <?php $current_answer_num++; ?>
            <?php endforeach;  ?>
        <?php endif; ?>
        <!-- Additional empty answer -->
            <li>
                <label>
                    Answer <?php echo $current_answer_num;?> <input type="text" name="answers[<?php echo $current_answer_num-1;?>]" data-num="<?php echo $current_answer_num;?>" value="" placeholder="Add your answer here ..." class="regular-text">
                </label>
                <label>is this a good answer ? <input type="checkbox" name="goods[<?php echo $current_answer_num-1;?>]" ></label>
            </li>
        </ul>
        <a id="qzy_add_new_answer" href="#" title="Add new answer">
            <span class="dashicons dashicons-plus-alt"></span>
        </a>
        <?php
    }

    function admin_display_duration( $post ){
        $question_duration = get_post_meta($post->ID,'duration', true);

        $duration_value = "";
        if( $question_duration ){
            $duration_value = intval($question_duration);
        }
        ?>
        <h3>Duration</h3>
        <label for="">Duration in seconds : <input type="number" name="duration" min="1" value="<?php echo $duration_value; ?>"></label>
        <?php
    }
     
    function save_meta_boxs( $post_id ){

        // Save Answers
        if( array_key_exists('answers', $_POST) ){
            $answers = $_POST['answers'];

            // Remove empty answers
            foreach ($answers as $key => $answer) {
                if( "" == trim($answer) ){
                    unset( $answers[$key] );
                }
            }

            if( !update_post_meta($post_id, 'answers', $answers) ){
                add_post_meta($post_id, 'answers', $answers);
            }
        }

        // Save Good Answers
        if( array_key_exists('goods', $_POST) ){
            $goods = $_POST['goods'];
            if( !update_post_meta($post_id, 'goods', $goods) ){
                add_post_meta($post_id, 'goods', $goods);
            }
        }

        // Save Duration
        if( array_key_exists('duration', $_POST) ){
            $duration = $_POST['duration'];
            if( !update_post_meta($post_id, 'duration', $duration) ){
                add_post_meta($post_id, 'duration', $duration);
            }
        }
    }

    function register_post_type(){
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