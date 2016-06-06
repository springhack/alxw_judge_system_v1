/**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-31 09:28:12
        Filename: ../default/js.js
        Description: Created by SpringHack using vim automatically.
**/
document.writeln('<link href="theme/default/mui-0.3.0/css/mui.min.css" rel="stylesheet" type="text/css" />\
<script src="theme/default/mui-0.3.0/js/mui.min.js"></script>');
$(function () {
		$('.navigator').addClass('mui-panel mui-appbar');
		$('.navigator.contest').addClass('mui-panel mui-appbar').css('background-color', '#137C82');
		$('.navigator a').addClass('mui-btn mui-btn--primary');
		$('.navigator.contest a').addClass('mui-btn mui-btn--primary').css('background-color', '#137C82');
		$('.header a.btn').addClass('mui-btn mui-btn--accent');
		$('.page_btn').addClass('mui-btn mui-btn--flat mui-btn--accent').removeClass('page_btn');
		$('.page_input').addClass('mui-btn mui-btn--flat mui-btn--accent').removeClass('page_input');
		$('table').addClass('mui-table mui-panel').css('background-color', '#EEE');
		$('table a').addClass('mui-btn mui-btn--accent');
		$('input[type=submit]').addClass('mui-btn mui-btn--accent');
		$('select').each(function () {
				var div = $('<div class="mui-select" style="width: 200px; display: inline-block;"></div>');
				$(this).before(div);
				$(this).appendTo(div);
			});
		$('table textarea').css('width', '100%');
		$('center:first').addClass('mui-container mui-panel').css({
				'top' : '20px',
				'position' : 'relative'
			});
		$('center:first input[type=password]').addClass('mui-btn mui-btn--flat mui-btn--accent');
		$(document.body).css('background-color', '#DDD').append('<center><a href="http://www.90its.cn/" target="_blank"><br />Design By SpringHack</a><br /><br /></center>');
	});
