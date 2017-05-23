=== Quizy ===
Contributors: lebleut
Tags: Quizzes, tests, evaluation, extensible, shortcode
Requires at least: 3.9
Tested up to: 4.7
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Quizy enables you to create quizzes, tests with several common settings with evaluation

== Description ==

Quizy is in the 1st step of development, if you would like to contribute please refer to our Github repository.

*   Create Quizzes with (Quiz type, Duration (sec), max nbr questions) 
*   Create questions and assign them to a picked Quiz (add answers, duration, image ...)
*   A short code for every quiz
*   ...

== Template System ==
The Quizy template system is pretty similar to the Woocommerce template system :
If you need overwrite the Quizy template pages, you just need to copy the `templates` folder from the Quizy directory to your theme or child theme directory and rename it to `quizy`.

== Shortcode ==
You should use `[quizy id=1234]` where 1234 is the quiz id to show.
Add the `list=yes` if you need to list all the quiz questions in one single page `[quizy id=1234 list=yes ]`, otherwise the one question per screen mode will be the default mode for your current quiz.

== Hooks ==
Action Hooks
 * do_action('`quizy_before_questions`', $quiz_post)
 * do_action('`quizy_before_question`', $question)
 * do_action('`quizy_after_question`', $question)
 * do_action('`quizy_after_questions`', $quiz_post, $questions)

Filter Hooks
 * apply_filters('`quizy_locate_template`', $template, $template_name, $template_path )
 * apply_filters('`quizy_stylesheet_url`', QUIZY_PLUGIN_URL . 'assets/style.css')
 * apply_filters('`quizy_script_url`', QUIZY_PLUGIN_URL . 'assets/quizy.js');

== Installation ==

This section describes how to install Quiz and get it working.

1. Upload the plugin files to the `/wp-content/plugins/quizy` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Quizy screen to configure the plugin


== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. Quizzes list with shortcode
2. Questions list
3. Question example
4. Quizy general settings (Settings->Quizy)

== Changelog ==

= 1.1 =
* New TEMPLATING SYSTEM (Similar to Woocommerce template system)
* Questions are now shown one after the other (1 question per page) as a the default mode, you can chage the mode to list by adding 'list' as a parameter to your shortcode
* Add result percentage section
* Filter hooks : ( quizy_locate_template, quizy_stylesheet_url, quizy_script_url )
* Action hooks : ( quizy_before_questions, quizy_before_question,q uizy_after_question, quizy_after_questions )
* Folders organisation
* Add questions and answers number
* Fix bugs


= 1.0 =
* 1'st published Quizy version
* Duration is not yet concidered
* only 1 quiz template is provided for now 