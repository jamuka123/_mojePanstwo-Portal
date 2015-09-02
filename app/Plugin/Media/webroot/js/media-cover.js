$(document).ready(function () {
	// STICKY
	$('#accountsSwitcher').sticky({
		widthFromWrapper: false
	});

	$('.sticky').sticky();

	// TAGS CLOUD
	var tagsCloud = $("#tagsCloud");
	if (tagsCloud.length)
		tagsCloud.cloud({
			hwratio: .5,
			fog: .4
		});

	// HighstockPicker
	(function () {
		function dateToYYYYMMDD(date) {
			var d = date,
				month = '' + (d.getMonth() + 1),
				day = '' + d.getDate(),
				year = d.getFullYear();

			if (month.length < 2) month = '0' + month;
			if (day.length < 2) day = '0' + day;

			return [year, month, day].join('-');
		}

		function dataSlownie(date) {
			var m = [
					'stycznia',
					'lutego',
					'marca',
					'kwietnia',
					'maja',
					'czerwca',
					'lipca',
					'sierpnia',
					'września',
					'października',
					'listopada',
					'grudnia'
				],
				current = new Date(),
				month = date.getMonth();
			if (m.hasOwnProperty(month)) {
				month = m[month];
			}

			if (
				current.getDate() == date.getDate() &&
				current.getMonth() == date.getMonth() &&
				current.getFullYear() == date.getFullYear())
				return 'dzisiaj';

			return date.getDate() + ' ' + month + ' ' + date.getFullYear() + ' r.';
		}

		var main = $('.mediaHighstockPicker'),
			chart = main.find('.chart').first(),
			pie = $('.pie'),
			aggs = chart.data('aggs'),
			range = chart.data('range'),
			xmax = chart.data('xmax'),
			switcher = main.find('.dataWrap a.switcher').first(),
			switcherCancel = main.find('.dataWrap a.switcherCancel').first(),
			display = main.find('.dataWrap .display').first(),
			data = [],
			highchart,
			appPie,
			appPieData = [];

		for (var i = 0; i < aggs.buckets.length; i++) {
			var bucket = aggs.buckets[i];

			data.push([
				bucket.key,
				bucket.doc_count
			]);
		}

		highchart = chart.highcharts('StockChart', {
			chart: {
				width: $(this.li).parent('.dataAggsDropdownList').first().outerWidth(),
				height: 160,
				events: {
					load: function () {
						this.xAxis[0].setExtremes(range.min * 1000, range.max * 1000, true, false);
					}
				},
				marginLeft: 20,
				marginRight: 20,
				backgroundColor: null
			},
			navigator: {
				height: 132,
				yAxis: {
					tickWidth: 0,
					lineWidth: 0,
					gridLineWidth: 1,
					tickPixelInterval: 40,
					gridLineColor: '#EEE',
					labels: {
						enabled: false
					}
				},
				xAxis: {
					gridLineWidth: 1,
					startOnTick: false,
					endOnTick: false,
					labels: {
						enabled: true
					},
					tickWidth: 1,
					tickPixelInterval: 40,
					// minRange: 3600000
					max: xmax ? xmax : null

				}
			},
			credits: {
				enabled: false
			},
			rangeSelector: {
				enabled: false
			},
			title: {
				text: ''
			},
			series: [{
				name: 'Suma',
				data: data,
				tooltip: {
					valueDecimals: 2
				},
				color: 'transparent'
			}],
			xAxis: {
				labels: {
					enabled: false
				},
				gridLineWidth: 0,
				lineWidth: 0,
				tickWidth: 0,
				minRange: 86400000,
				events: {
					setExtremes: function (e) {
						if (e.trigger == 'navigator') {
							switcher.removeClass('hidden');
							switcherCancel.removeClass('hidden');

							var extremes = e,
								start = new Date(extremes.min),
								end = new Date(extremes.max),
								parms = '?t=[' + dateToYYYYMMDD(start) + ' TO ' + dateToYYYYMMDD(end) + ']';

							if (switcher.attr('data-type') !== '')
								parms += '&a=' + switcher.attr('data-type');

							switcher.attr('href', parms);

							display.html('<span class="_ds" datetime="' + dateToYYYYMMDD(start) + '">' + dataSlownie(start) + '</span> <span class="separator">—</span> <span class="_ds" datetime="' + dateToYYYYMMDD(end) + '">' + dataSlownie(end) + '</span>');

						} else {
							//load(e.min, e.max);
						}
					}
				}
			},
			yAxis: {
				labels: {
					enabled: false
				},
				gridLineWidth: 0,
				lineWidth: 0,
				tickWidth: 0
			}
		});

		var appPieDataNr = 1,
			appPieDataY = 30;
		$.map($.parseJSON(pie.attr('data-json')), function (el) {
			var name = $(el.label.buckets[0].key).text();
			el = {
				id: el.key,
				name: name,
				logo: name.toLowerCase().replace(/\s/g, '_'),
				x: appPieDataNr++,
				y: appPieDataY
			};
			appPieData.push(el);
			appPieDataY -= 5;
		});

		appPie = pie.highcharts({
			chart: {
				backgroundColor: null,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: null
			},
			tooltip: {
				title: {
					text: null
				},
				pointFormat: '<div style="display: block"><b>#{point.x}</b> <img src="/media/img/twitterapp/{point.logo}.png"/> {point.name}</div>',
				useHTML: true,
				headerFormat: ' '
			},
			legend: {
				enabled: false
			},
			legacy: {
				enabled: false
			},
			credits: {
				enabled: false
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				colorByPoint: true,
				data: appPieData,
				point: {
					events: {
						click: function () {
							window.location.href = '?conditions[twitter.source_id]=' + this.id + appPie.attr('data-parms');
							return false;
						}
					}
				},
				dataLabels: {
					enabled: true,
					formatter: function () {
						var ret = '<b>#' + this.point.x + '</b> <img src="/media/img/twitterapp/' + this.point.logo + '.png" style="margin-right:4px" />',
							name = this.point.name;
						if (name.length > 55) {
							name = this.point.name.substring(0, 52);
							name += '...';
						}
						return ret + name;
					},
					useHTML: true
				}
			}]
		});
	}());
});
