// JavaScript Document

var frm_url = "";
var is_setting = false;

$(function () {
		setInterval(function () {
				var t = $("#main"), p = $("#left");
				var s = document.getElementById("main").contentDocument;
				try {
					if (t.height() != s.body.scrollHeight)
						t.height(s.body.scrollHeight);
					if ($("#location").html() != " > " + s.title)
						$("#location").html(" > " + s.title);
					s.body.onclick = function () {
							$("#status").hide(300);
						};
				} catch (e) {};
				var scroll_top;  
				if (document.documentElement && document.documentElement.scrollTop)
					scroll_top = document.documentElement.scrollTop;   
				else {
					if (document.body)
						scroll_top = document.body.scrollTop;
					else
						scroll_top = 0;
				}
				if (scroll_top > 1)
				{
					if (!is_setting)
					{
						is_setting = true;
						$("#top").slideDown(300, function () {
								is_setting = false;
							});
					}
				} else {
					if (!is_setting)
					{
						is_setting = true;
						$("#top").slideUp(300, function () {
								is_setting = false;
							});
					}
				}
			}, 100);
		$(".item_parent").on("click", function (e) {
				$(".item_parent").attr("class", "item_parent");
				$(".item_children").attr("class", "item_children");
				$(this).addClass("selected");
			});
		$(".item_children").on("click", function (e) {
				$(".item_parent").attr("class", "item_parent");
				$(".item_children").attr("class", "item_children");
				$(this).addClass("selected");
			});
		$("#top").on("click", function () {
				$('body,html').animate({scrollTop : 0}, 500);
			});
		$(document).on("click", function () {
				$("#status").hide(300);
			});
		$("#header_right").on("click", function (e) {
				e.stopPropagation();
				$("#status").toggle(300);
			});
	});
	
var menu = {
		toggle : function (selector) {
				$(selector).toggle(200);
			},
		open : function (url) {
				$("#main").attr("src", url);
			}
	};