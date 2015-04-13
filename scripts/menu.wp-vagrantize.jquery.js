jQuery(document).ready(function($) {

	!function() {
		var $injectee = $("#rewp-data");
		if (!$injectee.length)
			$.error("WP Vagrantize: DOM#rewp-data is not found");

		$.ajax({
			url : $(this).attr("action"),
			type : $(this).attr("method"),
			data : $(this).serialize()
		}).done(function(response) {
			console.log(response);
		});
	}();
});