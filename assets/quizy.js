jQuery( document ).ready(function() {
	questionsIds = [];

	jQuery('input[name="questions[]"]').each(function(index){
		questionsIds.push( jQuery(this).val() );
	})
	setSubmitButtonState( questionsIds );

	jQuery('.questions-wrap input').change(function(e){
		setSubmitButtonState( questionsIds );
	});
});

function isQuestionAnswered( questionId ){
	// Check if at least one answer is selected for a given question

	$answers = jQuery("[name^='answer["+questionId+"]']");
	done = false;

	$answers.each(function(index){
		if( jQuery(this).is(":checked") ){
			done = true;
		}
	});
	
	return done;
}

function setSubmitButtonState( questionsIds ){
	allQuestionsAnswered = true;
	for (var i = questionsIds.length - 1; i >= 0; i--) {
		if( !isQuestionAnswered(questionsIds[i]) ){
			allQuestionsAnswered = false;
			break;
		}
	}

	if( allQuestionsAnswered ){
		jQuery('.questions-wrap').find('input[type="submit"]').prop('disabled', false);
	}else{
		jQuery('.questions-wrap').find('input[type="submit"]').prop('disabled', true);
	}
}