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

    private $post_type_name = "qzy_quiz";

    /**
     * Qzy_Quiz_CPT constructor.
     */
    function __construct() {

        $this->create_post_type();

    }

    function create_post_type()
    {
    	add_action("init", array($this, "register_post_type"));
    }

    function register_post_type()
    {
	    $args = array(
	      'public' => true,
	      'label'  => 'Quizzes'
	    );
	    register_post_type( $this->post_type_name, $args );
    }
    
    function get_post_type_name(){
        return $this->post_type_name;
    }
}