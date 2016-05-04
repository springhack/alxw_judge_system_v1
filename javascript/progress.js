/**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-04 18:49:17
        Filename: progress.js
        Description: Created by SpringHack using vim automatically.
**/
(function (window, undefined) {

	var timeout = 6000;

	window.follow_progress = function () {
		var p = $('#progress');
		var n = $('#now');
		var t = $('table:last tr:last td');
		var id = /id=([\da-zA-Z]*)[&]{0,}/.exec(location.href)[1];
		var count = p.width();
		var cb = function () {
			$.get('getJSON.php', {
				id	:	id
			}, function (data) {
				var json = $.parseJSON(data);
				t.each(function (i, e) {
					e.innerHTML = json[i];
				});
			});
			n.width(0);
			n.animate({
				width	: 	'' + count + 'px'
			}, timeout, 'swing', cb);
		};
		n.width(0);
		n.animate({
			width	: 	'' + count + 'px'
		}, timeout, 'swing', cb);
	};

})(window);
	
