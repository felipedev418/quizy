jQuery( document ).ready(function() {
	quick_edit_fields();

	jQuery('#qzy_add_new_answer').click(function(e){
		// Add new answer element in admin
		e.preventDefault();

		answer_num = jQuery('#qzy_answers_list li:last input:first').data('num');
		new_answer_num = answer_num + 1;

		jQuery('<li/>').appendTo('#qzy_answers_list');
		// answer text

		jQuery('<label/>', {text: 'Answer '+new_answer_num}).appendTo('#qzy_answers_list li:last');

		jQuery('<input/>', {
		    type: 'text',
		    name: 'answers['+(new_answer_num-1)+']',
		    value: '',
		    class: 'regular-text',
		    'data-num': new_answer_num,
		    placeholder: "Add your answer here ..."
		}).appendTo('#qzy_answers_list li:last label');

		// answer goods
		jQuery('<label/>', {text: ' is this a good answer ? '}).appendTo('#qzy_answers_list li:last');

		jQuery('<input/>', {
		    type: 'checkbox',
		    name: 'goods['+(new_answer_num-1)+']'
		}).appendTo('#qzy_answers_list li:last label:last');

		jQuery('#qzy_answers_list li:last input:first').focus();
	});
});

function quick_edit_fields() {

	// we create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;

	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {

		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );

		// now we take care of our business

		// get the post ID
		var $post_id = 0;
		if ( typeof( id ) == 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			// define the edit row
			var $edit_row = jQuery( '#edit-' + $post_id );
			var $post_row = jQuery( '#post-' + $post_id );

			// get the data
			var $question_duration = jQuery( '.column-question_duration', $post_row ).text();

			$question_duration = $question_duration.replace(' s', '');
			// populate the data
			if(parseInt($question_duration)){
				jQuery( ':input[name="duration_quick_edit"]', $edit_row ).val( parseInt($question_duration) );
			}
		}
	};

};