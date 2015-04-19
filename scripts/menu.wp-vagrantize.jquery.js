jQuery(document).ready(function($) {

	!function() {
		var $table = $("#rewp-data-table");
		if (!$table.length)
			$.error("WP Vagrantize: DOM:#rewp-data-table is not found");

		$.ajax({
			url : WPVagrantize.url,
			//method : "POST", // jQuery >= 1.9.0
			  type : "POST",   // jQuery <  1.9.0
			data : {
				nonce : WPVagrantize.nonce,
				action : WPVagrantize.action
			},
			context : $table,
			dataType : "json",
			cache : false
		})
		.fail(function() {
			this.addClass("error");
		})
		.done(function(data) {
			body = $("<tbody>");
			$.each(data, function(i, iRow) {
				row = $("<tr>");
				row.append($("<th>", {
					text : i
				}));
				row.append($("<td>", {
					text : iRow
				}));
				body.append(row);
			});
			this.find(".dummy").remove();
			this.append(body);
		});
	}();
});