# Quizy

## Description
Quizy is a Wordpress plugin which enables you to create fully customized quizzes and tests.

## Shortcode
You should use `[quizy id=1234]` where 1234 is the quiz id to show.
Add the `list=yes` if you need to list all the quiz questions in one single page `[quizy id=1234 list=yes ]`, otherwise the one question per screen mode will be the default mode for your current quiz.

## Action Hooks
 * do_action('`quizy_before_questions`', $quiz_post)
 * do_action('`quizy_before_question`', $question)
 * do_action('`quizy_after_question`', $question)
 * do_action('`quizy_after_questions`', $quiz_post, $questions)

## Filter Hooks
 * apply_filters('`quizy_locate_template`', $template, $template_name, $template_path )
 * apply_filters('`qzy_stylesheet_url`', QUIZY_PLUGIN_URL . 'assets/style.css')
