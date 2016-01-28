/*global $, document, window, google, pl_number_format,number_format*/

function ColorLuminance(hex, lum) {
	hex = String(hex).replace(/[^0-9a-f]/gi, '');
	if (hex.length < 6) {
		hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
	}
	lum = lum || 0;

	var rgb = "#", c, i;
	for (i = 0; i < 3; i++) {
		c = parseInt(hex.substr(i * 2, 2), 16);
		c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
		rgb += ("00" + c).substr(c.length);
	}

	return rgb;
}

$(document).ready(function () {
	var $wpfMapa = $('#wpfMapa'),
		featureStyle = {
			fillColor: '#0000aa',
			fillOpacity: 0.05,
			strokeWeight: 2,
			strokeColor: '#0000aa'
		},
		base = ($wpfMapa.attr('data-pk')) ? '/wpf/' : '/dane/gminy/903,krakow/wpf/',
		$places = $.parseJSON($wpfMapa.attr('data-json')),
		markers = [],
		infowindow = null,
		map = new google.maps.Map(document.getElementById('wpfMapa'), {
			center: {lat: 50.0467656, lng: 20.0048731},
			zoom: 11,
			scrollwheel: false,
			navigationControl: true,
			panControl: false,
			zoomControl: true,
			zoomControlOptions: {
				position: google.maps.ControlPosition.RIGHT_CENTER
			},
			mapTypeControl: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false
		});

	var iconBase = {
		typ1: {
			icon: 'M22.275 14.66c-0.075-0.082-0.182-0.131-0.297-0.131h-2.445c-0.194 0-0.361 0.141-0.393 0.334 -0.237 1.391-0.605 2.524-1.093 3.377 -0.9 1.57-2.17 2.332-3.88 2.332 -1.88 0-3.251-0.723-4.191-2.209 -0.505-0.797-0.883-1.734-1.124-2.789h7.354c0.178 0 0.333-0.117 0.383-0.288l0.63-2.13c0.035-0.121 0.012-0.254-0.064-0.353 -0.077-0.103-0.194-0.159-0.321-0.159h-8.31c0-0.117-0.001-0.234-0.001-0.352 0-0.422 0.008-0.832 0.024-1.219h7.66c0.178 0 0.333-0.117 0.383-0.288l0.629-2.13c0.035-0.121 0.012-0.254-0.063-0.353 -0.077-0.103-0.194-0.159-0.321-0.159H8.97c0.26-1.055 0.649-1.936 1.156-2.623 1.048-1.432 2.391-2.131 4.101-2.131 1.388 0 2.434 0.371 3.195 1.129 0.771 0.77 1.287 1.838 1.542 3.168C19 7.877 19.165 8.012 19.355 8.012h2.447c0.111 0 0.218-0.046 0.293-0.125 0.076-0.084 0.115-0.191 0.105-0.305 -0.148-1.945-0.905-3.711-2.251-5.244C18.58 0.785 16.646 0 14.196 0c-2.875 0-5.162 1.199-6.793 3.561 -0.9 1.3-1.539 2.838-1.902 4.584H2.648c-0.178 0-0.335 0.116-0.383 0.281l-0.63 2.133C1.6 10.678 1.624 10.813 1.7 10.91c0.074 0.102 0.193 0.164 0.32 0.164h3.15c-0.009 0.234-0.012 0.469-0.012 0.705 0 0.294 0.002 0.582 0.013 0.865H2.648c-0.178 0-0.335 0.116-0.383 0.282l-0.63 2.132C1.6 15.18 1.624 15.313 1.7 15.41c0.074 0.104 0.193 0.164 0.32 0.164h3.437c0.425 2.516 1.334 4.524 2.707 5.977C9.696 23.175 11.614 24 13.868 24c2.609 0 4.712-1.007 6.252-2.989 1.272-1.627 2.031-3.657 2.257-6.045C22.388 14.855 22.353 14.745 22.275 14.66z',
			scale: 0.8,
			strokeWeight: 0,
			color: '#5cb85c',
			min: 9999999999999,
			max: 0
		},
		typ2: {
			icon: 'M127.484 61.417l-20.501-36.41c-0.697-1.238-2.006-2.007-3.412-2.007 -0.709 0-1.405 0.195-2.013 0.566l-8.328 5.077c-1.832 1.114-2.455 3.555-1.395 5.439l1.156 2.054c-1.549 0.514-3.051 0.823-4.279 0.72 -1.334-0.111-3.06-0.516-5.056-0.985 -3.473-0.816-7.795-1.832-12.495-1.832L70.83 34.04c-6.9 0.066-18.868 0.724-21.474 4.39 -0.34 0.479-0.753 1.047-1.191 1.65 -0.688-0.115-1.42-0.227-2.213-0.344 -2.287-0.338-5.418-0.8-6.513-1.421 -0.904-0.512-2.548-1.524-4.215-2.567l0.938-1.665c1.062-1.886 0.437-4.327-1.391-5.438l-8.332-5.08c-0.607-0.37-1.303-0.566-2.011-0.566 -1.409 0-2.718 0.771-3.414 2.01L0.515 61.421c-1.057 1.883-0.432 4.322 1.394 5.438l8.331 5.078c0.607 0.369 1.302 0.564 2.009 0.564 1.408 0 2.718-0.77 3.416-2.01l1.647-2.927 1.492 1.659c0.235 0.292 1.635 2.035 3.547 4.424l-0.571 0.774c-2.119 2.882-1.38 7.255 1.647 9.75 1.209 0.997 2.659 1.591 4.132 1.708 -0.165 2.317 0.828 4.789 2.832 6.443 1.353 1.115 3.009 1.73 4.663 1.73 0.417 0 0.821-0.048 1.217-0.123 0.384 1.574 1.279 3.074 2.641 4.196 1.357 1.119 3.015 1.733 4.671 1.733 0.699 0 1.372-0.115 2.004-0.322 0.438 1.402 1.282 2.722 2.51 3.732 1.356 1.114 3.014 1.729 4.667 1.729 2.009 0 3.823-0.896 4.975-2.461l1.03-1.396c4.305 1.496 6.981 2.011 7.518 2.104 0.492 0.12 2.232 0.507 4.271 0.507 1.624 0 3.433-0.244 4.95-1.064 2.244-1.207 3.85-3.228 4.707-4.518 1.447 0.075 3.699-0.025 5.338-1.098 2.155-1.412 2.919-3.865 3.181-5.652 1.559-0.068 3.619-0.404 5.059-1.57 1.994-1.621 2.633-3.736 2.775-5.444 2.508 0.172 5.521-0.54 7.245-3.696 1.264-2.323 1.256-4.74 0.055-6.926 0.975-0.562 2.146-1.356 3.408-2.501 0.826-0.747 1.422-1.372 1.949-1.925 0.521-0.546 0.988-1.03 1.598-1.555l1.512 2.685c0.699 1.241 2.008 2.012 3.414 2.012 0.709 0 1.404-0.196 2.011-0.567l8.332-5.078C127.92 65.743 128.543 63.304 127.484 61.417zM95.311 32.059l8.229-5.016 20.47 36.396 -8.229 5.016L95.311 32.059zM106.329 66.598c-0.502 0.526-1.02 1.07-1.74 1.721 -1.375 1.249-2.62 1.98-3.429 2.38 -0.008 0.004-0.02 0.011-0.027 0.015l-2.029-1.672 -0.035-0.029c-0.123-0.103-1.939-1.625-5.775-4.465 -2.613-1.937-6.158-4.479-10.776-7.625 -0.596-0.405-2.562-1.674-2.922-1.898 -6.816-4.255-15.157-8.43-15.56-8.631 -0.28-0.139-0.586-0.21-0.893-0.21 -0.204 0-0.408 0.031-0.608 0.092l-5.229 1.663c-0.461 0.147-0.855 0.459-1.106 0.873 -0.045 0.074-4.597 7.461-11.33 7.982 -0.322 0.032-0.62 0.05-0.896 0.05 -0.702 0-0.961-0.122-0.973-0.11 -0.028-0.07-0.192-0.594 0.255-2.202 1.355-2.664 8.61-12.74 9.038-13.336 0.105-0.147 0.226-0.312 0.323-0.448 0.916-1.228 8.154-2.612 18.252-2.708l0.293-0.001c4.235 0 8.138 0.917 11.579 1.726 2.154 0.505 4.01 0.942 5.64 1.077 2 0.168 4.346-0.383 6.59-1.194l13.858 24.613C108.714 64.351 106.946 65.951 106.329 66.598zM12.218 68.458l-8.217-5.077 20.459-36.337 8.216 5.078L12.218 68.458zM54.52 100.165c-0.535 0.728-1.33 0.835-1.756 0.835 -0.729 0-1.484-0.291-2.125-0.816 -0.952-0.783-1.447-1.943-1.398-2.977 0.022-0.48 0.151-0.936 0.426-1.309 0-0.001 0-0.002 0.001-0.002l0.347-0.471 1.247-1.691 2.563-3.477c0.537-0.729 1.333-0.838 1.76-0.838 0.728 0 1.483 0.291 2.125 0.818 1.393 1.145 1.836 3.109 0.97 4.287l-1.344 1.822 -2.459 3.334L54.52 100.165zM43.582 95.861c-0.729 0-1.484-0.291-2.127-0.82 -0.871-0.716-1.371-1.751-1.411-2.714 -0.013-0.322 0.037-0.63 0.129-0.922 0.074-0.232 0.169-0.456 0.315-0.652l1.445-1.962 5.481-7.438c0.16-0.217 0.343-0.374 0.532-0.494 0.446-0.285 0.928-0.344 1.227-0.344 0.728 0 1.482 0.291 2.12 0.816 1.395 1.148 1.84 3.113 0.972 4.29l-4.653 6.315 -2.274 3.088c-0.045 0.062-0.094 0.112-0.143 0.166C44.669 95.766 43.972 95.861 43.582 95.861zM31.711 85.399c0.067-0.16 0.149-0.313 0.253-0.454l2.055-2.789 0.1-0.135 1.72-2.332c0.006-0.008 0.01-0.017 0.016-0.024l4.698-6.376c0.539-0.729 1.336-0.837 1.764-0.837 0.728 0 1.481 0.29 2.123 0.819 1.396 1.145 1.838 3.108 0.97 4.289l-0.375 0.509c-0.306 0.273-0.591 0.573-0.839 0.911L38.64 86.52l-1.37 1.858c-0.233 0.314-0.425 0.649-0.589 0.994 -0.137 0.15-0.284 0.269-0.436 0.36 -0.437 0.264-0.9 0.32-1.192 0.32 -0.727 0-1.48-0.289-2.117-0.814C31.709 88.225 31.219 86.581 31.711 85.399zM24.944 76.896c0.021-0.033 0.033-0.07 0.056-0.102l2.391-3.244 0.374-0.507c0.538-0.729 1.335-0.836 1.762-0.836 0.729 0 1.482 0.29 2.125 0.818 1.391 1.142 1.835 3.095 0.977 4.275 -0.003 0.004-0.005 0.01-0.008 0.014l-1.074 1.455 -0.299 0.404 -1.396 1.893c-0.295 0.401-0.668 0.611-1.016 0.723 -0.284 0.091-0.552 0.116-0.744 0.116 -0.728 0-1.481-0.29-2.122-0.818C24.615 79.97 24.169 78.082 24.944 76.896zM100.301 78.796c-1.094 2.008-3.381 1.739-4.518 1.458L76.935 68.936c-0.947-0.567-2.178-0.263-2.744 0.686 -0.568 0.945-0.262 2.176 0.686 2.744l17.704 10.63c0.094 0.896 0.068 2.633-1.305 3.75 -0.723 0.583-2.57 0.733-3.822 0.671l-15.922-9.215c-0.957-0.553-2.18-0.227-2.732 0.729 -0.553 0.956-0.227 2.18 0.73 2.732l15.28 8.845c-0.121 1.077-0.48 2.589-1.447 3.222 -0.828 0.545-2.766 0.535-3.869 0.356 -0.07-0.012-0.139 0.003-0.209-0.002l-13.296-6.421c-0.996-0.482-2.193-0.062-2.672 0.931 -0.481 0.995-0.064 2.191 0.931 2.671l11.873 5.734c-0.635 0.775-1.488 1.622-2.506 2.17 -1.867 1.005-5.272 0.471-6.421 0.182 -0.059-0.015-0.117-0.026-0.176-0.037 -0.037-0.006-2.117-0.36-5.753-1.553l0.635-0.861c2.121-2.885 1.381-7.258-1.65-9.75 -1.086-0.893-2.365-1.458-3.68-1.65 0.495-2.535-0.489-5.406-2.731-7.253 -1.178-0.97-2.584-1.56-4.018-1.697 0.14-2.299-0.854-4.734-2.839-6.362 -1.353-1.117-3.01-1.732-4.666-1.732 -2.009 0-3.824 0.896-4.982 2.461l-1.128 1.531c-0.479-0.937-1.149-1.801-2.013-2.51 -1.355-1.115-3.012-1.729-4.666-1.729 -1.858 0-3.544 0.776-4.704 2.131 -1.727-2.156-2.909-3.627-2.938-3.663 -0.023-0.028-0.047-0.056-0.072-0.083l-2.431-2.704 13.879-24.65c1.652 1.032 3.275 2.03 4.208 2.558 1.743 0.988 4.873 1.451 7.9 1.898 0.053 0.008 0.107 0.016 0.16 0.024 -2.265 3.157-4.68 6.667-5.936 9.198 -0.053 0.106-0.096 0.216-0.129 0.33 -0.489 1.684-0.988 4.127 0.332 5.885 0.855 1.138 2.261 1.715 4.179 1.715 0.399 0 0.832-0.024 1.25-0.066 7.478-0.578 12.516-7.107 13.974-9.243l3.77-1.199c2.221 1.132 9.148 4.718 14.753 8.234 0.178 0.113 2.387 1.549 2.545 1.657 4.814 3.281 8.474 5.919 11.093 7.87 1.764 1.345 3.594 2.76 5.072 3.926 0.045 0.037 0.072 0.059 0.076 0.062l2.861 2.359C101.297 76.315 100.832 77.821 100.301 78.796z',
			scale: 0.2,
			strokeWeight: 1,
			color: '#337ab7',
			min: 9999999999999,
			max: 0
		},
		typ3: {
			icon: 'M65.205 273.054c69.277 0 138.206 0 207.677 0 0-69.412 0-138.459 0-207.488 92.991-2.78 194.313 68.773 206.798 185.112 13.262 123.588-81.526 225.926-198.6 230.102C157.509 485.186 64.414 384.993 65.205 273.054zM34.096 256.634C21.599 133.242 130.53 21.978 256.738 34.041c0 74.061 0 148.146 0 222.596C182.533 256.637 108.315 256.637 34.096 256.634z',
			scale: 0.05,
			strokeWeight: 0,
			color: '#d9534f',
			min: 9999999999999,
			max: 0
		}
	};
	var icon = {
		fillOpacity: 1
	};

	map.data.loadGeoJson('/Dane/files/krakow_dzielnice.geojson');
	map.data.setStyle(featureStyle);

	function mapSize() {
		$wpfMapa.css('height', 'auto');
		$wpfMapa.css('height', $('#_wrapper').height() - $('.appHeader ').outerHeight() - $('.appMenu').outerHeight() - $('footer.footer').height());
	}

	infowindow = new google.maps.InfoWindow({
		content: "..."
	});

	if ($places.length) {
		for (var k = 0; k < $places.length; k++) {
			var kat = $places[k].kategoria_id,
				val = $places[k].laczne_naklady_fin;

			if (val < iconBase['typ' + kat].min) {
				iconBase['typ' + kat].min = Number(val);
			}
			if (val > iconBase['typ' + kat].max) {
				iconBase['typ' + kat].max = Number(val);
			}
		}

		for (var j = 0; j < $places.length; j++) {
			var el = $places[j],
				position = {lat: Number(el.lat), lng: Number(el.lon)},
				type = iconBase['typ' + el.kategoria_id];

			var percent = -0.5 + ((Math.floor((100 * (type.max - Number(el.laczne_naklady_fin))) / (type.max - type.min))) / 100);

			icon.path = type.icon;
			icon.scale = type.scale;
			icon.strokeWeight = type.strokeWeight;
			icon.fillColor = ColorLuminance(type.color, -percent);
			icon.strokeColor = ColorLuminance(type.color, -percent);

			markers.push(new google.maps.Marker({
				map: map,
				title: el.nazwa,
				position: position,
				icon: icon,
				data: {
					id: el.id,
					title: el.nazwa,
					kwota: number_format(el.laczne_naklady_fin, 0)
				}
			}));
		}

		for (var i = 0; i < markers.length; i++) {
			var marker = markers[i];
			google.maps.event.addListener(marker, 'click', function () {
				var contentString = '<div id="wpfInfoContent">' +
					'<div class="title">' +
					'<a href="' + base + this.data.id + '">' + this.data.title + '</a>' +
					'</div>' +
					'<div class="cost">Wartość inwestycji: <strong>' + this.data.kwota + ' zł</strong></div>' +
					'</div>';

				infowindow.setContent(contentString);
				infowindow.open(map, this);
			});
		}
	}
	window.onresize = mapSize;

	mapSize();
});