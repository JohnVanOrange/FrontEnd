(function() {
	'use strict';

	var UI = {

		init: function() {
			$('body').removeClass('preload');
			UI.ubuntu();
			UI.firefox();
			UI.notyinit();
			UI.logo_rotate();
			UI.icon_visuals();
			UI.event_handlers_init();
			JVO.notifications.load();
		},

		ubuntu: function() {
			try {
				window.Unity = external.getUnityObject(1.0);
				Unity.init({
					name: site_name,
					iconUrl: web_root + 'icons/' + icon_set + '/128.png',
					onInit: null
				});
			} catch(err) {}
		},

		firefox: function() {
			$('#firefox_menu').click(function (event) {
				event.preventDefault();
				var apps = window.navigator.mozApps.getInstalled();
				apps.onsuccess = function() {
					if (!apps.result.length) {
						var request = window.navigator.mozApps.install( web_root + 'manifest.webapp');
						request.onsuccess = function () {
							noty({text: 'Web App Installed', dismissQueue: true});
						};
						request.onerror = function () {
							// Display the error information from the DOMError object
							noty({text: 'Install failed, error: ' + this.error.name, type: 'error', dismissQueue:true});
						};
					}
				};
			});
		},

		notyinit: function() {
			$.noty.defaults.theme = 'relax';
			$.noty.defaults.layout = 'topLeft';
			$.noty.defaults.type = 'information';
			$.noty.defaults.timeout = 10000;
		},

		logo_rotate: function() {
			var $body = $(document.body),
			bodyHeight = $body.height(),
			$logo = $('.title_o');
			$(window).on('scroll', function () {
				//header scaling
				var scroll = $(this).scrollTop();
				if (scroll > 100) {
					$('header h1').addClass('mini');
				} else {
					$('header h1').removeClass('mini');
				}
				//logo circling
				$logo.css({
					'transform': 'rotate(' + ($body.scrollTop() / bodyHeight * 360) + 'deg)'
				});
			});
		},

		icon_visuals: function() {
			$('.icon').click(function() {
				$(this).addClass('pressed');
				setTimeout(function(){
					$('.icon').removeClass('pressed');
				}, 80);
			});
		},

		event_handlers: {

			logout: function() {
				$('#logout').click(function () {
					event.preventDefault();
					JVO.call('user/logout').done(function( response ) {
						JVO.notifications.store( response.notification_message );
						window.location.reload();
					});
				});
			},

			menu: function() {
				$('#menu').click(function () {
					$('header > div').toggle();
				});
			}

		},

		event_handlers_init: function() {
			UI.event_handlers.logout();
			UI.event_handlers.menu();
		}

	};

	$(function () {

		UI.init();

	});


})();
