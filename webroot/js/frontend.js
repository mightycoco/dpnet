var map = null;
var old_map_pos = {};
var svgNormal = '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"><circle cx="25" cy="25" r="10" stroke="black" stroke-width="1" fill="orange" /></svg>';
var svgHover = '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"><circle cx="25" cy="25" r="15" stroke="black" stroke-width="1" fill="blue" /></svg>';

// zoom to a rectangle from locations: http://stackoverflow.com/questions/7798024/how-to-zoom-a-bing-map-to-display-a-set-of-lat-long-points
// or http://gis.stackexchange.com/questions/153594/zoom-to-fit-pushpins-in-bing-maps

$(function() {
	$(document).on("click", ".event:not(.daysplit)", function(e) {
		var pin = $(e.currentTarget).data("pin");
		markActivePin(pin);
		showEvent($(e.currentTarget).attr("id"));
		old_map_pos.zoom = map.getZoom();
		old_map_pos.center = map.getCenter();
		map.setView({zoom:11, center:pin.getLocation(), animate: true});
	});

	$(document).on("keydown", "body", function(e) {
		if(e.keyCode == 27) {
			$("#close_event").trigger("click");
		}
	});
	
	$(document).on("click", "#close_event", function(e) {
		$("#event").hide();
		$("body").removeClass("noscroll");
		map.setView({zoom: old_map_pos.zoom, center: old_map_pos.center, animate: true});
	});
	
	$(document).on("mouseover", ".event:not(.daysplit)", function(e) {
		markActivePin($(e.currentTarget).data("pin"));
	});
	
	$(window).trigger("resize");
	
	$(window).scroll(function() {
		if(window.scrollY > 40 && !$("body").hasClass("sticky")) {
			$("body").addClass("sticky");
		} else if(window.scrollY <= 40 && $("body").hasClass("sticky")) {
			$("body").removeClass("sticky");
		}
	});
	
	// $(".event cover img").each(function(i,img) {
	// 	var r = Math.random()*8000;
	// 	window.setTimeout(function() {
	// 		$(img).addClass("animate");
	// 	}, r);
	// });
});

var showVisibleEvents = function() {
	if($("#event").is(":visible")) 
		return;
	var bounds = map.getBounds();
	$(".event:not(.daysplit)").hide();
	
	$.each(map.getV8Map().getLayers()[0].getPrimitives(), function(i,p) {
		if(bounds.contains(p.getLocation())) {
			$(".event:not(.daysplit)").each(function(i, e) {
				if(p.entity.id == $(e).data().pin.entity.id) {
					$(e).show();
				}
			});
		}
	});
}

/*
{
	"id":"553650001491609",
	"datasource_id":"120052948051316",
	"event_description":"long description",
	"event_name":"19.1.17 // Düsterdisco // Eisenlager // feat Dj Alexx Botox",
	"event_approval":"approved",
	"event_start":"2017-01-19T20:00:00+00:00",
	"event_end":"2017-01-20T04:15:00+00:00",
	"place_id":2147483647,
	"place_name":"Düsterdisco im Eisenlager Zentrum Altenberg",
	"loc_city":"Oberhausen",
	"loc_country":"Germany",
	"loc_street":"Hansastr. 20",
	"loc_zip":"46049",
	"loc_latitude":51.4752,
	"loc_longitude":6.84733,
	"created":null,
	"modified":null,
	"pushpin":"Pushpin_2"
}
*/

var showEvent = function(id) {
	$("#event").show();
	
	var event = $.grep(events, function(e) {return e.id == id})[0];
	var el = $("#event article");
	$("etitle", el).text(event.event_name);
	$("description", el).text(event.event_description);
	$("img.cover", el).attr("src", event.cover);
	$("body").addClass("noscroll");
}

var markActivePin = function(pin) {
	$(".event.active").removeClass('active');
	if(!pin) return;
	
	$.each(events, function(i,e) {
		if(e.pushpin == pin.entity.id) {
			$("#"+e.id).addClass('active');
			scrollIntoViewIfOutOfView($("#"+e.id).get(0));
		}
	});

	$(".event:not(.daysplit)").each(function(i,e) {
		$(e).data("pin").setOptions({ 
			color: 'orange',
			//anchor: new Microsoft.Maps.Point(25, 50),
			//icon: svgNormal
		});
	});
	
	pin.setOptions({
		color: '#2098D1',
		//anchor: new Microsoft.Maps.Point(25, 25),
		//icon: svgHover
	});
}

var scrollIntoViewIfOutOfView = function(el) {
    var topOfPage = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
    var heightOfPage = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    var elY = 0;
    var elH = 0;
    if (document.layers) { // NS4
        elY = el.y;
        elH = el.height;
    }
    else {
        for(var p=el; p&&p.tagName!='BODY'; p=p.offsetParent){
            elY += p.offsetTop;
        }
        elH = el.offsetHeight;
    }
    if ((topOfPage + heightOfPage) < (elY + elH)) {
        el.scrollIntoView(false);
    }
    else if (elY < topOfPage) {
        el.scrollIntoView(true);
    }
}

var viewChanged = function(e) {
	showVisibleEvents();
}

function GetMap()
{
	// https://www.microsoft.com/maps/choose-your-bing-maps-API.aspx
	map = new Microsoft.Maps.Map('#map', {
		credentials: 'AtUkIfRPJe2s4ai4cWBUq9pSNC_C12ihR8jlCYlsNm7462vreYHy2c32AW9kTFRp',
		center: new Microsoft.Maps.Location(49,5, 8),
		zoom: 5,
		animate: true,
		culture: 'en-us',
		disableStreetside: false,
		showCopyright: false,
		// showDashboard: false,
		showLogo: false,
		showScalebar: false,
		showTermsLink: false,
		showZoomButtons: false
	});
	
	Microsoft.Maps.Events.addHandler(map, 'viewchangeend', viewChanged);
	
	$.each(events, function(i, evt) {
		var pin = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(evt.loc_latitude, evt.loc_longitude), {
	            //title: evt.event_name,
	            //subTitle: evt.place_name,
	            //text: evt.place_name[0],
	            data: i,
	            color: 'orange',
				//anchor: new Microsoft.Maps.Point(25, 50),
				//icon: svgNormal
	        });
	    map.entities.push(pin);
	    
	    $("#"+evt.id).data("pin", pin);
	    
	    evt.pushpin = pin.entity.id;
	    
	    Microsoft.Maps.Events.addHandler(pin, 'mouseover', function (e) {
            //e.target.setOptions({ color: '#2098D1' });
        });

        Microsoft.Maps.Events.addHandler(pin, 'mousedown', function (e) {
			markActivePin(pin);
        });

        Microsoft.Maps.Events.addHandler(pin, 'mouseout', function (e) {
            e.target.setOptions({ color: 'orange' });
        });
	});
}