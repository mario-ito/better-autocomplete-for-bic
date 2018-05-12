(function($) {
	var $minLength = 2;
	var $appendTo = '.compat-attachment-fields';

	$('body').on('focus', 'input[name*=credits_source]', function(e) {
		console.log(e);
		$(this).autocomplete({
			source: ajaxurl + '?action=credit_search',
			minLength: $minLength,
			appendTo: $appendTo,
		});
	});

	$('body').on('focus', 'input[name*=credits_link]', function(e) {
		$(this).url.autocomplete({
			source: ajaxurl + '?action=credit_url_search',
			minLength: $minLength,
			appendTo: $appendTo,
		});
	});

	$('body').on('focus', 'input[name*=license]', function() {
		$(this).autocomplete({
			source: ajaxurl + '?action=license_search',
			minLength: 1,
			appendTo: $appendTo,
		});
	});

	$('body').on('focus', 'input[name*=license_link]', function() {
		$(this).autocomplete({
			source: ajaxurl + '?action=license_url_search',
			minLength: $minLength,
			appendTo: $appendTo,
		});
	});

})(jQuery);