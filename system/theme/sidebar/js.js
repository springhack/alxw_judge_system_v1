/**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-10 17:24:23
        Filename: js.js
        Description: Created by SpringHack using vim automatically.
**/
document.writeln('<link href="theme/sidebar/mui-0.3.0/css/mui.min.css" rel="stylesheet" type="text/css" />\
<link href="theme/sidebar/github.css" rel="stylesheet" type="text/css" />\
<script src="theme/sidebar/mui-0.3.0/js/mui.min.js"></script>\
<script src="theme/sidebar/highlight.pack.js"></script>');
window.update = function () {
    $(document).ready(function() {
        $('pre#code').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });
};
update();
$(function () {
		$('.navigator').addClass('mui-panel mui-appbar').append($('<br><br><a href="http://www.dosk.win/" target="_blank">Design By SpringHack</a>'));
        var tmp = $('.navigator').html();
        $('.navigator').html(tmp.replace('&nbsp;=&gt;&nbsp;', '<font style="display: inline-block; width: 40px;">&nbsp;</font>'))
		$('.navigator.contest').addClass('mui-panel mui-appbar');
		$('.navigator a').addClass('mui-btn mui-btn--primary');
		$('.navigator a:first').css('marginTop', '30px');
		$('.navigator.contest a').addClass('mui-btn mui-btn--primary').css('background-color', '#137C82');
		$('.header a.btn').addClass('mui-btn mui-btn--accent');
		$('.page_btn').addClass('mui-btn mui-btn--flat mui-btn--accent').removeClass('page_btn');
		$('.page_input').addClass('mui-btn mui-btn--flat mui-btn--accent').removeClass('page_input');
		$('table').addClass('mui-table mui-panel').css('background-color', '#EEE');
		$('table a').addClass('mui-btn mui-btn--accent');
		$('input[type=submit]').addClass('mui-btn mui-btn--accent');
		$('#search .item').addClass('mui-textfield mui-textfield--float-label').css({
            'display' : 'inline-block',
            'width' : '200px',
            'position' : 'relative',
            'top' : '1px'
        });
		$('select').each(function () {
				var div = $('<div class="mui-select" style="width: 200px; display: inline-block;"></div>');
				$(this).before(div);
				$(this).appendTo(div);
			});
		$('table textarea').css('width', '100%');
		$('center:first').addClass('mui-container mui-panel BBB');
		$('center:first input[type=password]').addClass('mui-btn mui-btn--flat mui-btn--accent');
        if (/status.php/.test(location.href))
            $('table font').each(function (index, item) {
                switch (item.innerText)
                {
                    case 'Presentation Error':
                        item.style.color = '#FFA500';
                    break;
                    case 'Accepted':
                        item.style.color = '#00FF00';
                    break;
                    case 'Wrong Answer':
                    case 'Compile Error':
                        item.style.color = '#FF0000';
                    break;
                    default:
                    break;
                }
            });
	});
