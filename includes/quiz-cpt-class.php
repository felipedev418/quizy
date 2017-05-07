<?php
// Quiz CPT class goes here
if (!defined('ABSPATH')) exit;

/**
 * Qzy_Quiz_CPT core class.
 *
 *
 * @package Qzy_Quiz_CPT
 * @version 1.0.0
 * @since   1.0.0
 */
class Qzy_Quiz_CPT {

    private static $post_type_name = "qzy_quiz";

    /**
     * Qzy_Quiz_CPT constructor.
     */
    function __construct() {

        $this->create_post_type();

    }

    function create_post_type()
    {
    	add_action("init", array($this, "register_post_type"));
        add_action('init', array($this, 'register_taxonomies'));

        add_action( 'add_meta_boxes', array($this, 'register_meta_boxes'));

        add_action( 'save_post', array($this, 'save_quiz'));

        // Edit Quiz actions
        add_filter( 'post_row_actions', array($this, 'edit_question_actions'), 10, 2 );

        // Shortcode
        add_shortcode( 'quizy', array( $this, 'shortcode') );
    }

    function register_post_type()
    {

        $qz_labels = array(
            'name'               => 'Quizzes',
            'singular_name'      => 'Quiz',
            'menu_name'          => 'Quizzes',
            'name_admin_bar'     => 'Quiz',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Quiz',
            'new_item'           => 'New Quiz',
            'edit_item'          => 'Edit Quiz',
            'view_item'          => 'View Quiz',
            'all_items'          => 'All Quizzes',
            'search_items'       => 'Search Quizzes',
            'parent_item_colon'  => 'Parent Quizzes:',
            'not_found'          => 'No Quizzes found.',
            'not_found_in_trash' => 'No Quizzes found in Trash.'
        );

	    $args = array(
            'public' => true,
            'labels'  => $qz_labels,
            'taxonomies' => array('quiz_cat'),
	    );
	    register_post_type( self::$post_type_name, $args );
    }
    
    public static function get_post_type_name(){
        return self::$post_type_name;
    }

    function register_taxonomies(){
        $labels = array(
            'name'              => 'Quiz Categories',
            'add_new_item'      => 'Add New Quiz Category',
            'menu_name'         => 'Categories'
        );
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'show_in_quick_edit'    => true
        );

        register_taxonomy( 'quiz_cat', self::$post_type_name, $args );
    }

    function register_meta_boxes(){

        // Quiz settings metabox
        add_meta_box( 'qzy_quiz_settings_meta', 'Quiz Settings', array($this, 'add_settings_metabox'), self::$post_type_name );
    }

    function add_settings_metabox($post){
        ?>
        <h3>Quiz</h3>
        <?php
        $this->admin_display_quiz_type( $post );
        ?>

        <h3>Questions</h3>
        <?php
        $this->admin_display_question_duration( $post );
        $this->admin_display_questions_per_quiz( $post );
    }

    function admin_display_quiz_type($post){
        $quiz_type = get_post_meta($post->ID,'type', true);

        $q_types = array(
                array('slug'=>'mcq', 'name' => 'Multiple Choice Questions'),
                array('slug'=>'ucq', 'name' =>'Unique Choice Questions'),
            );
        ?>
        <p>
            <label>Quiz type : 
                <select name="quiz_type">
                    <option value="">-- Select one --</option>
                    <?php foreach ($q_types as $type) :?>
                        <?php $selected = ($type['slug'] == $quiz_type ? 'selected' : ''); ?>
                        <option value="<?php echo $type['slug']; ?>" <?php echo $selected; ?>><?php echo $type['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <?php
    }

    function admin_display_question_duration($post){
        $question_duration = get_post_meta($post->ID,'duration', true);
        $duration_value = "";
        if( $question_duration ){
            $duration_value = intval($question_duration);
        }
        ?>
        <p>
            <label>Duration per question (sec) : <input type="number" name="duration" min="1" placeholder="sec/question..." value="<?php echo $duration_value; ?>"></label>
        </p>
        <?php
    }

    function admin_display_questions_per_quiz($post){
        $questions_per_quiz = get_post_meta($post->ID,'questions_nbr', true);
        $questions_per_quiz_value = "";
        if( $questions_per_quiz ){
            $questions_per_quiz_value = intval($questions_per_quiz);
        }
        ?>
        <p>
            <label>Questions per quiz : <input type="number" name="questions_nbr" min="1" placeholder="questions nbr..." value="<?php echo $questions_per_quiz_value; ?>"></label>
        </p>
        <?php
    }

    function save_quiz($post_id){
        $the_post = get_post($post_id);

        // return if not quiz post type
        if($the_post->post_type != self::$post_type_name){
            return;
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

        // Save Questions nbr
        if( array_key_exists('questions_nbr', $_POST) ){
            $questions_nbr = intval($_POST['questions_nbr']);
            if($questions_nbr < 1){
                $questions_nbr = "";
            }

            if( !update_post_meta($post_id, 'questions_nbr', $questions_nbr) ){
                add_post_meta($post_id, 'questions_nbr', $questions_nbr);
            }
        }else{            
            if( !update_post_meta($post_id, 'questions_nbr', "") ){
                add_post_meta($post_id, 'questions_nbr', "");
            }
        }

        // Save Quiz type
        if( array_key_exists('quiz_type', $_POST) ){
            $quiz_type = $_POST['quiz_type'];

            if( !update_post_meta($post_id, 'type', $quiz_type) ){
                add_post_meta($post_id, 'type', $quiz_type);
            }
        }else{            
            if( !update_post_meta($post_id, 'type', "") ){
                add_post_meta($post_id, 'type', "");
            }
        }
    }

    function edit_question_actions($actions, $post){
        if( $post->post_type != self::$post_type_name )
            return $actions;

        $questions_filtered =add_query_arg(
                                    array('post_type' => Qzy_Question_CPT::get_post_type_name(), 'quiz_id' => $post->ID),
                                    admin_url().'edit.php'
                                );
        // Add a new link to see related questions
        $actions['questions'] = '<a href="'.$questions_filtered.'" style="color:#FF9800;">View Questons</a>';

        return $actions;
    }

    function shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'id' => ''
        ), $atts, 'quizy' );

        $quiz_id = $atts['id'];

        ob_start();
        require QUIZY_BASE_DIR.'/includes/templates/single-quiz-tpl.php';
        
        return ob_get_clean();  
    }
}