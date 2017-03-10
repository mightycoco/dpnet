var map = null;
var old_map_pos = {};
var animation_duration = 300;
var svgNormal = '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"><circle cx="25" cy="25" r="10" stroke="black" stroke-width="1" fill="orange" /></svg>';
var svgHover = '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"><circle cx="25" cy="25" r="15" stroke="black" stroke-width="1" fill="blue" /></svg>';

// zoom to a rectangle from locations: http://stackoverflow.com/questions/7798024/how-to-zoom-a-bing-map-to-display-a-set-of-lat-long-points
// or http://gis.stackexchange.com/questions/153594/zoom-to-fit-pushpins-in-bing-maps

$(function() {
	$("header .text").text(location.hostname);
	$(document).on("click", ".event_item:not(.daysplit)", function(e) {
		var pin = $(e.currentTarget).data("pin");
		markActivePin(pin);
		showEvent($(e.currentTarget).attr("id"));
		old_map_pos.zoom = map.getZoom();
		old_map_pos.center = map.getCenter();
		// map.setView({zoom:11, center:pin.getLocation()});
	});

	$(document).on("keydown", "body", function(e) {
		if(e.keyCode == 27) {
			$("#close_event").trigger("click");
		}
	});
	
	$(document).on("click", "#close_event", function(e) {
		if(window.outerWidth < 900) {
		$("#event")
			.offset({left:0})
			.animate({left:window.outerWidth}, animation_duration, function() { 
				$(this).hide(); 
			});
		} else {
			$("#event").fadeOut();
		}
		$("body").removeClass("noscroll");
		// map.setView({zoom: old_map_pos.zoom, center: old_map_pos.center});
	});
	
	$(document).on("mouseover", ".event_item:not(.daysplit)", function(e) {
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
	
	// $(".event_item cover img").each(function(i,img) {
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
	$(".event_item:not(.daysplit)").hide();
	
	var localShow = function(lpin) {
		$(".event_item:not(.daysplit)").each(function(i, e) {
			if(lpin.entity.id == $(e).data().pin.entity.id) {
				$(e).show();
			}
		});
	}

	$.each(map.getV8Map().getLayers()[0].getPrimitives(), function(i,p) {
		if(bounds.contains(p.getLocation())) {
			if(p.containedPushpins) {
				$.each(p.containedPushpins, function(i, pin) {
					localShow(pin);
				})
			} else {
				localShow(p);
			}
		}
	});
}

var showEvent = function(id) {
	if(window.outerWidth < 900) {
		$("#event")
			.offset({left:window.outerWidth})
			.show()
			.animate({left:0}, animation_duration);
	} else {
		$("#event").fadeIn();
	}
	
	var event = $.grep(events, function(e) {return e.id == id})[0];
	var el = $("#event");
	el.data("event", event);
	
	var date = new Date(event.event_start);
	
	var longlat = event.loc_latitude + "," + event.loc_longitude;
	
	$("name", el).text(event.event_name);
	$("description", el).text(event.event_description);
	$("img.cover", el).attr("src", event.cover);
	$("img.map", el).attr("src", "http://dev.virtualearth.net/REST/v1/Imagery/Map/Road/"+longlat+"/14?mapSize=400,300&pushpin="+longlat+"&key=AtUkIfRPJe2s4ai4cWBUq9pSNC_C12ihR8jlCYlsNm7462vreYHy2c32AW9kTFRp");
	$("date", el).html(date.toDateString());
	$("time", el).html(date.toTimeString().substr(0, 5));
	$(".extern-facebook", el).attr("href", "https://facebook.com/" + event.id);
	$(".intern-ics", el).attr("href", "./events/export-ics/" + event.id);
	$("body").addClass("noscroll");
}

var markActivePin = function(pin) {
	$(".event_item.active").removeClass('active');
	if(!pin) return;
	
	$.each(events, function(i,e) {
		if(e.pushpin == pin.entity.id) {
			$("#"+e.id).addClass('active');
			scrollIntoViewIfOutOfView($("#"+e.id).get(0));
		}
	});

	$(".event_item:not(.daysplit)").each(function(i,e) {
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

function entityFromPin(pin) {
	var entity = null;
	$.each(events, function(i,e) {
		if(e.pushpin == pin.entity.id) {
			entity = e;
			return false;
		}
	});
	return entity;
}

var createPins = function() {
	var pins = [];
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
	    
	    //map.entities.push(pin);
	    
	    $("#"+evt.id).data("pin", pin);
	    
	    evt.pushpin = pin.entity.id;
	    
	    //Microsoft.Maps.Events.addHandler(pin, 'mouseover', function (e) {
	    Microsoft.Maps.Events.addThrottledHandler(pin, 'mouseover', function (e) {
            //e.target.setOptions({ color: '#2098D1' });
        }, 1000);

        //Microsoft.Maps.Events.addHandler(pin, 'mousedown', function (e) {
        Microsoft.Maps.Events.addThrottledHandler(pin, 'mousedown', function (e) {
			markActivePin(pin);
			showEvent(entityFromPin(pin).id);
        }, 1000);

        //Microsoft.Maps.Events.addHandler(pin, 'mouseout', function (e) {
        Microsoft.Maps.Events.addThrottledHandler(pin, 'mouseout', function (e) {
            e.target.setOptions({ color: 'orange' });
        }, 1000);
        
	    pins.push(pin);
	});
	return pins;
}

var clusterClicked = function(e) {
    if (e.target.containedPushpins) {
        var locs = [];
        for (var i = 0, len = e.target.containedPushpins.length; i < len; i++) {
            //Get the location of each pushpin.
            locs.push(e.target.containedPushpins[i].getLocation());
        }

        //Create a bounding box for the pushpins.
        var bounds = Microsoft.Maps.LocationRect.fromLocations(locs);

        //Zoom into the bounding box of the cluster. 
        //Add a padding to compensate for the pixel area of the pushpins.
        map.setView({ bounds: bounds, padding: 100 });
    }
}

function GetMap()
{
	// https://www.microsoft.com/maps/choose-your-bing-maps-API.aspx
	map = new Microsoft.Maps.Map('#map', {
		credentials: 'AtUkIfRPJe2s4ai4cWBUq9pSNC_C12ihR8jlCYlsNm7462vreYHy2c32AW9kTFRp',
		center: new Microsoft.Maps.Location(49,5, 8),
		navigationBarMode: Microsoft.Maps.NavigationBarMode.default,
		zoom: 5,
		culture: 'en-us',
		disableStreetside: false,
		showCopyright: false,
		// showDashboard: false,
		showLogo: false,
		showScalebar: false,
		showTermsLink: false,
		showZoomButtons: false
	});
	
	Microsoft.Maps.loadModule("Microsoft.Maps.Clustering", function () {
        //Generate 1,000 random pushpins in the map view.
        var pins = createPins();

        //Create a ClusterLayer and add it to the map.
        var clusterLayer = new Microsoft.Maps.ClusterLayer(
        	pins, 
        	{
        		// gridSize: 30, 
        		clusteredPinCallback: function(cluster) {
        			Microsoft.Maps.Events.addHandler(cluster, 'click', clusterClicked);
        		}
        	}
        );
        map.layers.insert(clusterLayer);
		Microsoft.Maps.Events.addThrottledHandler(map, 'viewchangeend', viewChanged, 2000);
		
		window.setTimeout(function() {viewChanged();}, 2000);

	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(function(position) {
	        	var location = new Microsoft.Maps.Location(position.coords.latitude, position.coords.longitude);
	        	map.setView({zoom: 11, center: location});
	        	
	        	var pin = new Microsoft.Maps.Pushpin(location, {
		            color: 'red'
		        });
		    	map.entities.push(pin);
	        });
	    }
    });
	
	// map.entities.push(createPins());
}