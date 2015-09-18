jQuery(document).ready(function($) {

	var app = {
		context: '#wp-vagrantize-menu-root',

		main: function() {
			this.forms.customize.render();
			this.forms.download.activate();
		},

		nodes: {

			find: function(query, context) {
				switch (arguments.length - 1) {
					case 0: context = $(app.context);
				}
				var r = $(query, context);
				if (!r.length) return false;
				return r;
			},

			require: function(query, context) {
				switch (arguments.length - 1) {
					case 0: context = $(app.context);
				}
				var r = this.find(query, context);
				if (!r) $.error('WP Vagrantize: The node: ' + query + ' is not found');
				else return r;
			},

			compose: {

				notice: function(msg, classes) {
					switch (arguments.length - 1) {
						case 0: classes = '';
					}
					if (classes) classes = ' ' + classes;
					var r = $('<div class="notice' + classes + '"><p>' + msg + '</p></div>');

					setTimeout(function() {
						r.fadeOut(2000, function() {
							$(this).remove();
						});
					}, 8000);

					return r;
				}
			}
		},

		forms: {

			buttons: {

				onClick: function(ev) { // We need to know which button is clicked
					var button = $(this);
					var form = app.nodes.require(button.closest('form'));
					var submit = app.nodes.find(':input[name="submit"][value]', form);
					if (!submit) {
						submit = $('<input>').attr({
							type: 'hidden',
							name: 'submit'
						});
						form.append(submit);
					}
					submit.attr({value: button.attr('name')}); // button name
				}
			},

			activate: function() {
				var form = app.nodes.require('form');
				var fields = app.nodes.find(':input[name][value]', form);
				if (fields) fields.removeAttr('disabled');
			},

			deactivate: function() {
				var form = app.nodes.require('form');
				var fields = app.nodes.find(':input[name][value]', form);
				if (fields) fields.attr('disabled', 'disabled');
			},

			customize: {
				context: 'form#customize-form',
				isActive: false,
				render: function() {
					var self = this;
					var table = app.nodes.require('#settings-table');
					app.forms.deactivate();

					$.ajax($.extend(
						true, // Deep merge
						actions.renderSettingsTable,
						{context: table}
					))
					.always(function(response) {
						app.forms.activate();
					})
					.fail(function(request, status, error) {
						var node = this;
						node.empty();
						node.addClass('failed');
					})
					.done(function(response) {
						var node = this;
						node.html(response.data);

						var extensible = $('.extensible', node);
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

						self.activate();
					});
				},
				activate: function() {
					if (this.isActive) return;
					var self = this;
					var form = app.nodes.require(this.context);
					var button = app.nodes.require(':submit[name]', form);
					button.on('click', app.forms.buttons.onClick);

					form.on('submit', function(ev) {
						ev.preventDefault(); // Abort browser-native submission
						var submit = app.nodes.require(':input[name="submit"][value]', this);
						var fields = app.nodes.require(':input[name!="submit"][value]', this);
						var spinner = app.nodes.find('.spinner', this);
						if (spinner) spinner.addClass('active');
						var sData = fields.serialize();
						app.forms.deactivate();

						switch (submit.attr('value')) {
						case 'save':
							$.ajax($.extend(
								true,
								actions.saveSettings,
								{
									context: form,
									data: {
										data: sData
									}
								}
							))
							.always(function(response) {
								app.forms.activate();
								if (spinner) spinner.removeClass('active');
							})
							.fail(function(request, status, error) {
								$.error('WP Vagrantize: Request failed');
							})
							.done(function(response) {
								this.append(app.nodes.compose.notice(
									'Saved to <strong class="code">' + response.data.file
									+ '</strong> @ <time datetime="' + response.data.datetime + '">'
									+ response.data.date + '</time>', 'notice-success'
								));
								self.render();
							});
							break;

						case 'reset':
							$.ajax($.extend(
								true,
								actions.resetSettings,
								{context: form}
							))
							.always(function(response) {
								app.forms.activate();
								if (spinner) spinner.removeClass('active');
							})
							.fail(function(request, status, error) {
								$.error('WP Vagrantize: Request failed');
							})
							.done(function(response) {
								self.render();
							});
							break;
						}
					});

					this.isActive = true;
				}
			},

			download: {
				context: 'form#download-form',
				isActive: false,
				activate: function() {
					if (this.isActive) return;
					var self = this;
					var form = app.nodes.require(this.context);
					var button = app.nodes.require(':submit[name]', form);
					button.on('click', app.forms.buttons.onClick);

					form.on('submit', function(ev) {
						ev.preventDefault();
						var submit = app.nodes.require(':input[name="submit"][value]', this);
						var spinner = app.nodes.find('.spinner', this);
						if (spinner) spinner.addClass('active');
						app.forms.deactivate();

						switch (submit.attr('value')) {
						case 'download':
							var downloader = app.nodes.find('#downloader', form);
							if (downloader) downloader.remove();

							$.ajax($.extend(
								true,
								actions.download,
								{context: form}
							))
							.always(function(response) {
								app.forms.activate();
								if (spinner) spinner.removeClass('active');
							})
							.fail(function(request, status, error) {
								$.error('WP Vagrantize: Request failed with status: ' + status);
							})
							.done(function(response) {
								this.append(app.nodes.compose.notice(
									'ZIP-Archived into <strong class="code">' + response.data.file
									+ '</strong> @ <time datetime="' + response.data.datetime + '">'
									+ response.data.date + '</time>', 'notice-success'
								));

								this.append($('<iframe>').attr({
									id: 'downloader',
									src: response.data.fileUrl,
									style: 'display:none'
								}));
							});
							break;
						}
					});

					this.isActive = true;
				}
			}
		}
	};

	app.main();
});
