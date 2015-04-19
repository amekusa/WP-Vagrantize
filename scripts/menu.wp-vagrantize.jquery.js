jQuery(document).ready(function($) {

	!function() {
		var table = $("#rewp-data-table");
		if (!table.length)
			$.error("WP Vagrantize: DOM:#rewp-data-table is not found");

		var request = $.ajax({
			url : WPVagrantize.ajaxUrl,
			//method : "POST", // jQuery >= 1.9.0
			  type : "POST",   // jQuery <  1.9.0
			data : {
				action : 'get_rewp_data',
				nonce : WPVagrantize.actions.get_rewp_data.nonce
			},
			context : table,
			dataType : "json",
			cache : false
		});

		request.fail(function(request, status, error) {
			var dom = this;
			dom.empty();
			dom.addClass("failed");
		});

		request.done(function(response) {
			var dom = this;
			dom.empty();

			$.each(response.data, function(i, iRow) {
				var row = $("<tr>")
					.append($("<th>", { text : i }))
					.append($("<td>", {	text : iRow }));

				dom.append(row);
			});
		});
	}();
});