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
	    register_post_type( 'qzy_quiz', $args );
    }
}