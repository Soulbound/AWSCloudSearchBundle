// Create a closure to maintain scope of the '$' and CMS
;
(function (SAWSCS, $, window, document, undefined) {

	'use strict';

	$(function () {

	});

	SAWSCS.SearchBar = {
		defaults: {
			source: "",
			minlength: 2 
		},

		create: function( searchBarSelector ){

			var searchBarObj = $(searchBarSelector);

			if( searchBarObj.length > 0 ){

				var conf = SAWSCS.SearchBar.defaults;

				$.each(conf, function( index, value ){
					if( searchBarObj.data(index) != undefined ){
						conf[index] = searchBarObj.data(index);
					}
				});

				searchBarObj.autocomplete({
					source: conf.source,
					minLength: conf.minlength,
					select: function( event, ui ) {
						$(this).val(ui.item.label);
						return false;
					},
					focus: function( event, ui ) {
						$(this).val(ui.item.label);
						return false;
					}
				});
			}
		}
	};


})(window.SAWSCS = window.SAWSCS || {}, jQuery, window, document);