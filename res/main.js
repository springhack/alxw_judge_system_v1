(function (window, undefined) {
    
    window.onload = function () {
        var a = document.getElementsByClassName('item')[0];
        a.addEventListener('click', function (e) {
            var t = document.createElement('div');
            t.className = 'exit';
            t.style.left= (e.clientX - 10) + 'px';
            t.style.top= (e.clientY - 10) + 'px';
            document.body.appendChild(t);
            setTimeout(function () {
                location.href = 'system/';
            }, 700);
        });
    };

})(window);
