jQuery(document).ready(function($) {

	!function() {
		var table = $("#rewp-data-table");
		if (!table.length)
			$.error("WP Vagrantize: DOM:#rewp-data-table is not found");

		var request = $.ajax({
			url : WPVagrantize.url,
			//method : "POST", // jQuery >= 1.9.0
			  type : "POST",   // jQuery <  1.9.0
			data : {
				nonce : WPVagrantize.nonce,
				action : WPVagrantize.action
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