jQuery(document).ready(function () {
 	$('.mp-zamowienia_publiczne').each(function(index){
	 	
	 	var div = $(this);
	 	var highstock_div = div.find('.highstock');
	 	var aggs_div = div.find('.dataAggs');
	 	
	 	var aggs = [];
	 	var aggs_divs = aggs_div.find('.agg');
	 	for( var i=0; i<aggs_divs.length; i++ )
	 		aggs.push( $(aggs_divs[i]).attr('data-agg_id') );
	 	
	 	var request = div.data('request');
	 	
	 	var url = decodeURIComponent(div.attr('data-url'));
	 	var histogram_data = div.data('histogram');
	 	
	 	var extremes = false;
	 	
	 	var load = function(min, max) {
		 	
		 	request['date_min'] = Highcharts.dateFormat("%Y-%m-%d", min);
		 	request['date_max'] = Highcharts.dateFormat("%Y-%m-%d", max);
		 	
		 	var params = {
			 	request: request,
			 	aggs: aggs
		 	};
		 	
		 	console.log('params', params);
		 	
		 	$.get('/zamowienia_publiczne/aggs.html', params).done(function(data){
			 	
			 	console.log('data', data);
			 	for( var i=0; i<aggs.length; i++ ) {
				 	
				 	agg_id = aggs[i];
				 	console.log('agg_id', agg_id);
				 	
				 	var target_div = aggs_div.find('.agg[data-agg_id="' + agg_id + '"]');
				 	if( target_div.length ) {
					 	
					 	target_div.html( data );
					 	
				 	}
				 	
			 	}
			 	
		 	});
		 			 	
	 	}
	 	
	 	highstock_div.highcharts('StockChart', {
	
			chart: {
				height: 210,
				backgroundColor: 'transparent',
			},
			
			navigator: {
				height: 140
			},
			
			credits: {
				enabled: false
			},
			
            rangeSelector : {
                selected : 1
            },

            title : {
                text : ''
            },

            series : [{
                name : 'Suma',
                data : histogram_data,
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
	            events: {
	                setExtremes: function (e) {
	                    
	                    if( e.trigger == 'navigator' ) {
	                    	                    
		                    extremes = e;
		                    setTimeout(function(){
			                    if( extremes == e ) {
				                    
				                    load(e.min, e.max);
				                    
			                    }
		                    }, 300);
	                    
	                    } else {
		                    
		                    load(e.min, e.max);
		                    
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
            },
            
            title: {
	            text: ''
            }
            
        });
	 	
 	}); 
});