/**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-31 09:30:43
        Filename: js.js
        Description: Created by SpringHack using vim automatically.
**/
$(function () {
		var list = $("table");
		list.each(function () {
				if ($(this).attr("data-type") != "rank")
					$(this).addClass("menu");
			});
		$('.navigator.contest').css('padding-bottom', '20px').parent().css('padding-bottom', '40px')
		$(document.body).attr('id', 'particles-js');
		$.getScript(//'theme/ACG/particles.min.js', 
		'http://cdn.maxjia.com/js/particles.min.js', function (script, status) {
			if (status == 'success')
			particlesJS('particles-js', {
			    "particles": {
			      "number": {
			        "value": 110,
			        "density": {
			          "enable": true,
			          "value_area": 800
			        }
			      },
			      "color": {
			        "value": "#ffffff"
			      },
			      "shape": {
			        "type": "circle",
			        "stroke": {
			          "width": 0,
			          "color": "#000000"
			        },
			        "polygon": {
			          "nb_sides": 5
			        },
			        "image": {
			          "src": "img/github.svg",
			          "width": 100,
			          "height": 100
			        }
			      },
			      "opacity": {
			        "value": 0.5,
			        "random": false,
			        "anim": {
			          "enable": false,
			          "speed": 1,
			          "opacity_min": 0.1,
			          "sync": false
			        }
			      },
			      "size": {
			        "value": 1,
			        "random": true,
			        "anim": {
			          "enable": false,
			          "speed": 20,
			          "size_min": 0.1,
			          "sync": false
			        }
			      },
			      "line_linked": {
			        "enable": true,
			        "distance": 40,
			        "color": "#fff",
			        "opacity": 1,
			        "width": 1
			      },
			      "move": {
			        "enable": true,
			        "speed": 3,
			        "direction": "none",
			        "random": false,
			        "straight": false,
			        "out_mode": "out",
			        "attract": {
			          "enable": false,
			          "rotateX": 600,
			          "rotateY": 1200
			        }
			      }
			    },
			    "interactivity": {
			      "detect_on": "canvas",
			      "events": {
			        "onhover": {
			          "enable": true,
			          "mode": "grab"
			        },
			        "onclick": {
			          "enable": true,
			          "mode": "push"
			        },
			        "resize": true
			      },
			      "modes": {
			        "grab": {
			          "distance": 120,
			          "line_linked": {
			            "opacity": 1
			          }
			        },
			        "bubble": {
			          "distance": 400,
			          "size": 40,
			          "duration": 2,
			          "opacity": 8,
			          "speed": 3
			        },
			        "repulse": {
			          "distance": 300
			        },
			        "push": {
			          "particles_nb": 4
			        },
			        "remove": {
			          "particles_nb": 2
			        }
			      }
			    },
			    "retina_detect": true,
			    "config_demo": {
			      "hide_card": false,
			      "background_color": "#b61924",
			      "background_image": "",
			      "background_position": "50% 50%",
			      "background_repeat": "no-repeat",
			      "background_size": "cover"
			    }
			  }
			
			);	
		});
	});

