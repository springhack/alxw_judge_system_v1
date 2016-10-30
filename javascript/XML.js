$(function () {

    $('#export').on('click', function () {

        var list = Array.prototype.join.call($('table[data-type=rank] tr').map(function (index, item) {
            var ret = $(item).find('td').map(function (index, item) {
		        return item.innerText;
		    });
		    delete ret['prevObject'];
		    delete ret['context'];
		    ret = Array.prototype.map.call(ret, function (item) {
		        return '"' + item + '"';
		    });
		        return ret.join(',');
		}), '\n');
		
		var file = new File([list], 'Rank.csv', {type: "text/plain;charset=utf-8"});
		
		saveAs(file);
		
    });

});
