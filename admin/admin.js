jQuery( document ).ready(function() {
	
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
		    'data-num': new_answer_num
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
