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

    /**
     * Quizy constructor.
     */
    function __construct() {

        $this->define_constants();
        $this->requirements();

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
    	$quiz_object = new Qzy_quiz_cpt();
    	$question_object = new Qzy_Question_cpt();
    }

}

// Starting the Quizy plugin
new Quizy();