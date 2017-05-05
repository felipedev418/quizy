<?php
/*
Plugin Name: Quizy
Plugin URI: https://www.tooltipy.com
Description: Quizy enable you to create awesome Quizzes..
Version: 1.0
Author: Jamel Zarga
Author URI: https://www.tooltipy.com
License: GPL
*/

if (!defined('ABSPATH')) exit;

/**
 * Quizy core class.
 *
 * Register plugin and make instances of core classes
 *
 * @package Quizy
 * @version 1.0.0
 * @since   1.0.0
 */
class Quizy {

    private $quiz_object;
    private $question_object;

    /**
     * Quizy constructor.
     */
    function __construct() {

        $this->define_constants();
        $this->requirements();

        $this->quiz_object = new Qzy_quiz_cpt();
        $this->question_object = new Qzy_Question_cpt();

        $this->qz_start();
    }

    /**
     * Definition wrapper.
     *
     * Creates some useful defines in environment to handle
     * plugin paths
     *
     * @since   1.0.0
     */
    function define_constants() {

        if ( ! defined( 'QUIZY_BASE_FILE' ) )
            define( 'QUIZY_BASE_FILE', __FILE__ );
        if ( ! defined( 'QUIZY_BASE_DIR' ) )
            define( 'QUIZY_BASE_DIR', dirname( QUIZY_BASE_FILE ) );
        if ( ! defined( 'QUIZY_PLUGIN_URL' ) )
            define( 'QUIZY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        if ( ! defined( 'QUIZY_PLUGIN_DIR' ) )
            define( 'QUIZY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        if ( ! defined( 'QUIZY_TEMPLATES_DIR' ) )
            define( 'QUIZY_TEMPLATES_DIR', QUIZY_BASE_DIR.'/includes/templates' );

    }

    function requirements(){
    	// Required files should be loaded here
    	require_once QUIZY_BASE_DIR.'/includes/quiz-cpt-class.php';
    	require_once QUIZY_BASE_DIR.'/includes/question-cpt-class.php';
    }

    /**
     *  Check plugin requirement's and run
     */
    function qz_start()
    {
        // main function

        $this->enqueue_assets();

        add_action( 'admin_menu', array($this, 'add_admin_menu') );

        add_action('admin_init', array($this, 'settings_init') );

        add_filter('single_template', array($this, 'single_templates') );

    }

    function add_admin_menu() {
        add_options_page( 
            'Quizy Options',
            'Quizy',
            'manage_options',
            'quizy_settings',
            array($this, 'settings_page')
        );
    }

    function settings_page(){
        // show error/update messages
        settings_errors( 'wporg_messages' );
        ?>
        <div class="wrap">
            <h2 class="page-title">Quizy</h2>
            <div class="page-content">
                <form action="options.php" method="post">
                    <?php
                    // output setting sections and their fields
                    do_settings_sections( 'quizy_sections_group' );

                    // Output the HIDDEN fields, nonce, etc.
                    settings_fields( 'qzy_settings_group' );

                    // output save settings button
                    submit_button( 'Save Settings' );
                    ?>
                </form>
            </div>
        </div>
        <?php
    }

    function enqueue_assets(){
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    function admin_scripts(){
        global $current_screen;

        $question_post_type_name = Qzy_Question_CPT::get_post_type_name();
        
        if( 'edit-'.$question_post_type_name == $current_screen->id ){
            // When in list questions
            wp_enqueue_script( 'qzy_quickedit_admin_script', QUIZY_PLUGIN_URL . 'admin/list-questions.js', array('jquery') );
        }else if( $question_post_type_name == $current_screen->id ){
            // When in single question edit page
            wp_enqueue_script( 'qzy_admin_script', QUIZY_PLUGIN_URL . 'admin/admin.js', array('jquery') );
            wp_enqueue_style( 'qzy_admin_style', QUIZY_PLUGIN_URL . 'admin/style.css' );
        }
    }

    function settings_init()
    {
        // register a new section "general_section" assigned to the "quizy_sections_group"
        add_settings_section(
            'general_section',
            'General settings',
            array( $this, 'settings_section_cb'),
            'quizy_sections_group'
        );
        
        // Default duration
        // register a new field in the "general_section" section, inside the "quizy_sections_group" quizy group page
        add_settings_field(
            'qzy_default_duration_field',
            'Default question duration (sec)',
            array( $this, 'default_duration_field_cb'),
            'quizy_sections_group',
            'general_section'
        );

        // Create the duration option ( default 30sec )
        add_option( 'qzy_default_duration', '30' );

        // Add the duration option to the hidden settings group
        register_setting('qzy_settings_group', 'qzy_default_duration'); 

        // Default max questions by quiz
        add_settings_field(
            'qzy_default_max_questions_field',
            'Default max questions per quiz',
            array( $this, 'default_questions_field_cb'),
            'quizy_sections_group',
            'general_section'
        );

        add_option( 'qzy_default_questions', '10' );

        register_setting('qzy_settings_group', 'qzy_default_questions');

        // Default Quiz Type
        add_settings_field(
            'qzy_default_quiz_type_field',
            'Default quiz type',
            array( $this, 'default_quiz_type_field_cb'),
            'quizy_sections_group',
            'general_section'
        );

        add_option( 'qzy_default_quiz_type', 'mcq' ); // Multiple choise question as default

        register_setting('qzy_settings_group', 'qzy_default_quiz_type');
    }
     
    // section content cb
    function settings_section_cb()
    {
        echo '<i>General settings goes here</i>';
    }
     
    // field content cb
    function default_duration_field_cb()
    {
        $default_duration = get_option('qzy_default_duration');

        ?>
        <input type="number" min="1" name="qzy_default_duration" value="<?php echo(isset($default_duration) ? esc_attr($default_duration) : ''); ?>">
        <?php
    }

    function default_questions_field_cb()
    {
        $default_questions = get_option('qzy_default_questions');

        ?>
        <input type="number" min="1" name="qzy_default_questions" value="<?php echo(isset($default_questions) ? esc_attr($default_questions) : ''); ?>">
        <?php
    }
    
    function default_quiz_type_field_cb(){
        $quiz_type = get_option('qzy_default_quiz_type');

        $q_types = array(
                array('slug'=>'mcq', 'name' => 'Multiple Choise Questions'),
                array('slug'=>'ucq', 'name' =>'Unique Choise Questions'),
            );
        ?>
        <p>
            <select name="qzy_default_quiz_type">
                <option value="">-- Select one --</option>
                <?php foreach ($q_types as $type) :?>
                    <?php $selected = ($type['slug'] == $quiz_type ? 'selected' : ''); ?>
                    <option value="<?php echo $type['slug']; ?>" <?php echo $selected; ?>><?php echo $type['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    function single_templates($single) {
        global $post;

        // Quiz custom template page
        if ( $post->post_type == Qzy_Quiz_CPT::get_post_type_name() ){
            $tpl_path = QUIZY_BASE_DIR.'/includes/templates/single-quiz-tpl.php';
            if(file_exists( $tpl_path ))
                return $tpl_path;
        }

        return $single;
    }
}

// Starting the Quizy plugin
new Quizy();