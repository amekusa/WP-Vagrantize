jQuery(document).ready(function($) {

	!function() {
		var table = $('#rewp-settings-table');
		if (!table.length)
			$.error('WP Vagrantize: DOM:' + table.selector + ' is not found');

		$.ajax({
			url : WPVagrantize.ajaxUrl,
			method : 'POST', // jQuery >= 1.9.0
			type : 'POST',   // jQuery <  1.9.0
			data : WPVagrantize.actions.render_rewp_settings_table,
			context : table,
			dataType : 'json',
			cache : false
		})
		.fail(function(request, status, error) {
			var dom = this;
			dom.empty();
			dom.addClass('failed');
		})
		.done(function(response) {
			var dom = this;
			dom.html(response.data);
			activateForm();
		});

		/*
		$.ajax({
			url : WPVagrantize.ajaxUrl,
			method : 'POST', // jQuery >= 1.9.0
			type : 'POST',   // jQuery <  1.9.0
			data : WPVagrantize.actions.get_rewp_data,
			context : table,
			dataType : 'json',
			cache : false
		})
		.fail(function(request, status, error) {
			var dom = this;
			dom.empty();
			dom.addClass('failed');
		})
		.done(function(response) {
			var dom = this;
			dom.empty();

			$.each(response.data, function(i, iData) {
				var row = $('<tr>')
					.append($('<th>', { text : i }))
					.append($('<td>', {	text : iData }));

				dom.append(row);
			});

			activateForm();
		});
		*/
	}();

	function activateForm() {
		var form = $('form#rewp-settings-form');
		if (!form.length)
			$.error('WP Vagrantize: DOM:' + form.selector + ' is not found');

		var button = $(':submit[name]', form);
		if (!button.length)
			$.error('WP Vagrantize: DOM:' + button.selector + ' is not found');

		// We need to know which button is clicked in form submission handler
		button.on('click', function() {
			var submit = $('input[name="submit"]', form);
			if (!submit.length) {
				submit = $('<input>').attr({
					type : 'hidden',
					name : 'submit'
				});
				form.append(submit);
			}
			submit.attr({ value : $(this).attr('name') }); // button name
		});

		form.on('submit', function(ev) {
			ev.preventDefault(); // Abort browser-native submit

			var submit = '';
			var settings = {};

			var formData = $(this).serializeArray();
			$.each(formData, function(i, iData) {
				if (!iData.hasOwnProperty('name')) return;
				if (iData.name == 'submit') submit = iData.value;
				else settings[iData.name] = iData.value;
			});

			if (submit == 'save') {
				$.ajax({
					url : WPVagrantize.ajaxUrl,
					method : 'POST',
					type : 'POST',
					data : $.extend(
						WPVagrantize.actions.set_rewp_data,
						{ data : settings }
					),
					context : form,
					dataType : 'json',
					cache : false
				})
				.fail(function(request, status, error) {
					var dom = this;
					dom.addClass('failed');
				})
				.done(function(response) {
					var dom = this;

					$.each(response.data, function(i, iRow) {
					});
				});
			}
		});
	}
});