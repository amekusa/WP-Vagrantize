jQuery(document).ready(function($) {
	var isFormActive = false;
	renderTable();

	function renderTable() {
		var table = $('#rewp-settings-table');
		if (!table.length)
			$.error('WP Vagrantize: DOM:' + table.selector + ' is not found');

		$.ajax({
			url : WPVagrantize.ajaxUrl,
			method : 'POST', // jQuery >= 1.9.0
			type : 'POST',   // jQuery <  1.9.0
			data : WPVagrantize.actions.renderReWPSettingsTable,
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

			autosize($('textarea', dom));

			var extensible = $('.extensible', dom);
			extensible.each(function() {
				var iEach = $(this);

				var plus = $('<a class="extend" title="Extend">+</a>');
				plus.appendTo(iEach);
				plus.on('click', function(ev) {
					ev.preventDefault();
					var parent = $(this).parent();
					var clone = parent.clone(true);
					clone.find('input[value]').attr('value', '');
					parent.after(clone);
				});

				var minus = $('<a class="unextend" title="Unextend">−</a>');
				minus.appendTo(iEach)
				minus.on('click', function(ev) {
					ev.preventDefault();
					$(this).parent().remove();
				});
			});

			activateForm();
		});
	}

	function activateForm() {
		if (isFormActive) return;

		var form = $('form#rewp-settings-form');
		if (!form.length)
			$.error('WP Vagrantize: DOM:' + form.selector + ' is not found');

		var button = $(':submit[name]', form);
		if (!button.length)
			$.error('WP Vagrantize: DOM:' + button.selector + ' is not found');

		// We need to know which button is clicked
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
			ev.preventDefault(); // Abort browser-native submission

			var spinner = button.siblings('.spinner');
			if (spinner.length) spinner.addClass('active');

			submit = $('[name="submit"]', this);
			if (!submit.length)
				$.error('WP Vagrantize: DOM:' + submit.selector + ' is not found');

			switch (submit.attr('value')) {
				case 'save':
					$.ajax({
						url : WPVagrantize.ajaxUrl,
						method : 'POST',
						type : 'POST',
						data : $.extend(
							WPVagrantize.actions.saveReWPSettings,
							{ data : $('[name!="submit"]', this).serialize() }
						),
						context : form,
						dataType : 'json',
						cache : false
					})
					.always(function(response) {
						if (spinner.length) spinner.removeClass('active')
					})
					.fail(function(request, status, error) {
						var dom = this;
						dom.addClass('failed');
					})
					.done(function(response) {
						renderTable();
					});
					break;

				case 'reset':
					$.ajax({
						url : WPVagrantize.ajaxUrl,
						method : 'POST',
						type : 'POST',
						data : WPVagrantize.actions.resetReWPSettings,
						context : form,
						dataType : 'json',
						cache : false
					})
					.always(function(response) {
						if (spinner.length) spinner.removeClass('active')
					})
					.fail(function(request, status, error) {
						var dom = this;
						dom.addClass('failed');
					})
					.done(function(response) {
						renderTable();
					});
					break;
			}
		});

		isFormActive = true;
	}
});