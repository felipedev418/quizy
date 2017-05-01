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

    private $post_type_name = "qzy_question";

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

        add_action( 'save_post', array($this, 'save_question'));

        // Alter columns
        add_filter('manage_'.$this->post_type_name.'_posts_columns', array($this, 'custom_columns_head') );
        add_action('manage_'.$this->post_type_name.'_posts_custom_column', array($this, 'custom_columns_content'), 10, 2);

        add_action( 'quick_edit_custom_box', array($this, 'display_quickedit_settings'), 10, 2 );

        add_action( 'save_post', array($this, 'quick_save_duration') );

        add_action( 'admin_notices', array($this, 'report_admin_notice') );
    }

    function register_meta_boxes(){
        // Question answers
        add_meta_box( 'qzy_answers', 'Question settings', array($this, 'add_settings_metabox'), $this->post_type_name );
    }

    function add_settings_metabox( $post ){
        $this->admin_display_answers( $post );
        $this->admin_display_duration( $post );
    }

    function admin_display_answers( $post ){
        $question_answers = get_post_meta($post->ID,'answers');
        $question_goods = get_post_meta($post->ID,'goods', true);
        $question_answers   = is_array( $question_answers ) && count( $question_answers ) > 0 ? $question_answers[0] : false;

        $current_answer_num = 1;
        ?>
        <h3>
            <span>Answers</span>
            <a id="qzy_add_new_answer" href="#" title="Add new answer">
                <span class="dashicons dashicons-plus-alt"></span>
            </a>
        </h3>
        <ul id="qzy_answers_list">
        <?php if($question_answers): ?>
            <?php foreach ($question_answers as $key => $answer) : if($answer == '') continue; ?>
                <li>
                    <label>
                        Answer <?php echo $current_answer_num; ?> <input type="text" name="answers[<?php echo $current_answer_num-1;?>]" data-num="<?php echo $current_answer_num;?>" value="<?php echo esc_html($answer); ?>" placeholder="Add your answer here ..." class="regular-text">
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
     
    function save_question( $post_id ){

        // Save Answers
        if( array_key_exists('answers', $_POST) ){
            $answers = $_POST['answers'];

            // Remove empty answers and sanitize
            foreach ($answers as $key => $answer) {
                $answers[$key] = sanitize_text_field($answer);
                if( "" == $answers[$key] ){
                    unset( $answers[$key] );
                }
            }

            if( !update_post_meta($post_id, 'answers', $answers) ){
                add_post_meta($post_id, 'answers', $answers);
            }
        }else{            
            if( !update_post_meta($post_id, 'answers', array()) ){
                add_post_meta($post_id, 'answers', array());
            }
        }

        // Save Good Answers
        if( array_key_exists('goods', $_POST) ){
            $goods = $_POST['goods'];

            // remove good answers with no answers
            if(is_array($goods)){
                foreach ($goods as $good_key => $good) {
                    if( !array_key_exists($good_key, $answers) ){
                        unset($goods[$good_key]);
                    }
                }
            }

            if( !update_post_meta($post_id, 'goods', $goods) ){
                add_post_meta($post_id, 'goods', $goods);
            }
        }else{            
            if( !update_post_meta($post_id, 'goods', array()) ){
                add_post_meta($post_id, 'goods', array());
            }
        }

        // Save Duration
        if( array_key_exists('duration', $_POST) ){
            $duration = intval($_POST['duration']);

            if($duration < 1){
                $duration = "";
            }
            
            if( !update_post_meta($post_id, 'duration', $duration) ){
                add_post_meta($post_id, 'duration', $duration);
            }
        }else{            
            if( !update_post_meta($post_id, 'duration', "") ){
                add_post_meta($post_id, 'duration', "");
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
	    register_post_type( $this->post_type_name, $args );
    }

    function register_taxonomies(){
        $args = array(
            'hierarchical'          => true,
            'label'                => 'Category',
            'show_ui'               => true,
            'show_admin_column'     => true,
            'show_in_quick_edit'    => true
        );

        register_taxonomy( 'question_cat', $this->post_type_name, $args );
    }

    function custom_columns_head($old_column_names) {
        // Remove the title column
        unset($old_column_names['title']);

        // Checkbox column 1st
        $new_column_names['cb'] = $old_column_names['cb'];

        // Question 2nd
        $new_column_names['question_desc'] = 'Question';

        // Duration 3rd
        $new_column_names['question_duration'] = 'Duration';

        // Duration 4th
        $new_column_names['question_answers'] = 'Answers';

        foreach ($old_column_names as $column_key => $column_name) {
            $new_column_names[$column_key] = $column_name;
        }

        return $new_column_names;
    }

    function custom_columns_content($column_name, $post_id) {
        $question_duration = get_post_meta($post_id, 'duration', true);

        $question_description = esc_html(get_the_content($post_id));
        $question_edit_link = get_edit_post_link($post_id);

        $answers = get_post_meta($post_id, 'answers', true);

        switch ($column_name) {
            case 'question_duration':
                if ($question_duration) {
                    echo $question_duration." s";
                }else{
                    echo "<i>Default</i>";
                }
                break;

            case 'question_desc':
                if ($question_description) {
                    echo '<a href="'.$question_edit_link.'">'.$question_description.'</a>';
                }else{
                    echo "<strong>No question yet!</strong>";
                }
                break;
            case 'question_answers':
                echo( count($answers) );
                break;
            
            default:
                # code...
                break;
        }
    }

    function get_post_type_name(){
        return $this->post_type_name;
    }

    function display_quickedit_settings( $column_name, $post_type ) {
        static $printNonce = TRUE;
        if ( $printNonce ) {
            $printNonce = FALSE;
            wp_nonce_field( plugin_basename( __FILE__ ), $this->post_type_name.'_edit_nonce' );
        }

        ?>
        <fieldset class="inline-edit-col-right inline-edit-duration">
          <div class="inline-edit-col column-<?php echo $column_name; ?>">
            <label class="inline-edit-group">
            <?php 
             switch ( $column_name ) {
             case 'question_duration':
                 ?>
                    <span class="title">Duration</span>
                    <input type="number" min="1" name="duration_quick_edit" value="" placeholder="in seconds" />
                 <?php
                 break;
             default:
                 break;
             }
            ?>
            </label>
          </div>
        </fieldset>
        <?php
    }

    // Save duration on quick edit
    function quick_save_duration( $post_id ) {

        $slug = $this->post_type_name;
        if ( !array_key_exists('post_type', $_POST) || $slug !== $_POST['post_type'] ) {
            return;
        }
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $_POST += array("{$slug}_edit_nonce" => '');
        if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"], plugin_basename( __FILE__ ) ) )
        {
            return;
        }

        if ( isset( $_REQUEST['duration_quick_edit'] ) ) {
            update_post_meta( $post_id, 'duration', $_REQUEST['duration_quick_edit'] );
        }
    }

    function report_admin_notice() {
        global $post;
        $post_meta = get_post_meta($post->ID);

        $current_screen = get_current_screen();
        $terms = get_the_terms( $post, 'question_cat' );

        $report = array();


        if( $current_screen->id != $this->post_type_name ){
            return;
        }
        // Question text
        if( "" == trim($post->post_content) ){
            array_push($report, "No question yet!");
        }

        // if no categories choosen
        if( !$terms ){            
            array_push($report, "Choose ONE category at least");
        }

        // Answers number
        if( count(unserialize($post_meta['answers'][0])) < 2 ){            
            array_push($report, "Answers should be more than 1");
        }

        // Good answers
        if( count(unserialize($post_meta['goods'][0])) == 0 ){            
            array_push($report, "Choose ONE good answer at least");
        }

        // Show report if any missings
        if(count($report)){
        ?>
        <div class="notice notice-error is-dismissible">
            <?php
                foreach ($report as $message) {
                    ?>
                    <p><strong><?php echo $message; ?></strong></p>
                    <?php
                }
            ?>
        </div>
        <?php
        } 
    }
}