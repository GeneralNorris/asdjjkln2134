$(function() {
	var dataCache = [];
	var dataIdsToShow = [];
	var dataCount=0;

	var monthdesc =["Jan","Feb","Mär","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"];
	var options = {
        lines: { show: true },
        points: { show: true },
        grid: { hoverable: true, clickable: true },
        xaxis: {
        	tickDecimals: 0,
        	tickSize: 1,
        	tickFormatter: function(val,axis){ return monthdesc[val];}
        	},
        legend: {container: '#legend'}
    };
    var data = [];
    var placeholder = $("#holder");
    $.plot(placeholder, dataCache, options);
    // fetch one series, adding to what we got
    var alreadyFetched = {};

    $("tr.inputs input").change(function(){
    	$('html').addClass('busy');
    	fetchUserinput();
    	window.setTimeout(fetchData,100);

    });

	function fetchUserinput(){
		dataIdsToShow = [];
		var id='';
		var years=[];
		var logactions=[];
		var users=[];
		$('#logyears input:checked').each(function(){
			years.push($(this).val());
		});
		$('#logactions input:checked').each(function(){
			logactions.push($(this).val());
		});
		$('#users input:checked').each(function(){
			users.push($(this).val());
		});

		var dataid='';
		for ( var i = 0; i < years.length; i++) {
			for ( var j = 0; j < logactions.length; j++) {
				for ( var k = 0; k < users.length; k++) {
					dataIdsToShow.push( years[i]+"_"+logactions[j]+"_"+users[k]);
				}
			}
		}
		//console.log(dataIdsToShow);
	}
	function fetchData(){
		data = [];
		//$('#legend').html('');
		//console.log(dataIdsToShow);
		for ( var i = 0; i < dataIdsToShow.length; i++) {
			//wenn daten nicht bereits im Cache, dann von Server laden
			var dataid = dataIdsToShow[i];
			if(dataCache[dataid] == undefined){
				$.ajax({
			         url: "do_backend_action.php?action=json_statistic&dataid="+dataIdsToShow[i],
			         method: 'GET',
			         dataType: 'json',
			         async: false,
			         success: function(data){
			        	 //farbe festlegen, damit diese nicht ständig wechselt
			        	 data.color = dataCount++;
			        	 dataCache[dataid]=data;
			         }
			     });
			}
			data.push(dataCache[dataid]);
		}
	    //console.log(dataCache);
		$.plot(placeholder, data, options);
		$('html').removeClass('busy');
	}


//TOOLTIPS
    var previousPoint = null;
    $("#holder").bind("plothover", function (event, pos, item) {
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(0),
                    y = item.datapoint[1].toFixed(0);

                showTooltip(item.pageX, item.pageY,y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y - 20,
            left: x + 10,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
//END TOOLTIPS
});