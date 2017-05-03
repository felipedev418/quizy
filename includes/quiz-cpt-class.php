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

}