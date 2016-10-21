/**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-13 20:54:24
        Filename: javascript/progress.js
        Description: Created by SpringHack using vim automatically.
**/
(function (window, undefined) {

	var timeout = 6000;

	window.follow_progress = function () {
		var cid = 0;
		if (arguments.length)
			cid = arguments[0];
		var p = $('#progress');
		var n = $('#now');
		var t = $('table:last tr:last td');
        var code = $('#code');
        var compile = $('#compile');
		var id = /id=([\da-zA-Z]*)[&]{0,}/.exec(location.href)[1];
		var count = p.width();
		var cb = function () {
			$.get('getJSON.php', {
				id	:	id,
				cid	:	cid
			}, function (data) {
				var json = $.parseJSON(data);
				t.each(function (i, e) {
					e.innerHTML = json[i];
				});
                code.html(json['code']);
                compile.html(json['compileinfo']);
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
	
