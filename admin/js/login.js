// JavaScript Document

function login()
{
	$("#login").show(300);
	$("#register").hide(300);
}

function register()
{
	$("#register").show(300);
	$("#login").hide(300);
}

function deal()
{
	if ($("#pass").val() != $("#check").val())
		$("#prompt").html("两次输入不匹配");
	else
		$("#prompt").html("");
}