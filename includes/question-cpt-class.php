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
    	add_action("init", array($this, "register_post_type"));
    }

    function register_post_type()
    {
	    $args = array(
	      'public' => true,
	      'label'  => 'Questions'
	    );
	    register_post_type( 'qzy_question', $args );
    }
}