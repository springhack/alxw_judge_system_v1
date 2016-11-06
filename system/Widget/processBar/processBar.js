// JavaScript Document

function processBar(id, width, height, color, borderColor)
{
	var element = document.getElementById(id);
	var full = width;
	var childElement = document.createElement("div");
	var callback = function () {};
	if (color == undefined)
		color = "#000";
	if (borderColor == undefined)
		borderColor = "#000";
	element.innerHTML = "";
	element.style.display = "";
	element.style.border = "1px solid " + borderColor
	element.style.width = width + "px";
	element.style.height = height + "px";
	element.style.overflow = "hidden";
	childElement.style.width = "0px";
	childElement.style.height = "100%";
	childElement.style.backgroundColor = color;
	childElement.innerHTML = "&nbsp;"
	element.appendChild(childElement);
	this.setProcess = function (process) {
			childElement.style.width = parseInt(full*process) + "px";
			if (this.getProcess() == 1)
				callback(element);
		}
	this.getProcess = function () {
			return (parseFloat(childElement.style.width)/full);
		}
	this.setCallback = function (func) {
			callback = func;
		}
	this.removeSelf = function () {
			element.style.display = "none";
		}
	this.autoProcess = function (delay) {
			$(childElement).animate({width : full}, delay, function () {
					callback(element);
				});
		}
}