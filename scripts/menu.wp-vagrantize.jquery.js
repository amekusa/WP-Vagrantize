jQuery(document).ready(function($) {

	!function() {
		var $injectee = $("#rewp-data");
		if (!$injectee.length)
			$.error("WP Vagrantize: DOM#rewp-data is not found");

		$.ajax({
			url : WPVagrantize.url,
			type : "POST",
			cache : false,
			data : {
				nonce : WPVagrantize.nonce,
				action : WPVagrantize.action
			}
		}).done(function(response) {
			console.log(response);
			alert(response);
		});
	}();
});