$(function() {
	$(document).on("click", ".hiddenController", function(e) {
		e.stopPropagation();
		var id = $(e.currentTarget).attr("ref");
		$("[hidden-ref]").not("[hidden-ref="+id+"]").attr("hidden", "");

		var $el = $("[hidden-ref="+id+"]");
		if($el.attr("hidden") !== undefined) 
			$el.removeAttr("hidden");
		else
			$el.attr("hidden", "");
		
		return false;
	});
	
	var nav_collapsed = localStorage.getItem('nav-collapsed');
	if(nav_collapsed && nav_collapsed == 'true' && $(document).width() <= 640) {
		$("[role=navigation]").hide();
	} else {
		$("[role=navigation]").show();
	}
	
	$(document).on("click", ".main-hamburger", function(e) {
		$("[role=navigation]").slideToggle(300, function(e) {
			if($(this).is(":visible")) {
				localStorage.setItem('nav-collapsed', 'false');
			} else {
				localStorage.setItem('nav-collapsed', 'true');
			}
		});
	});
	
	$(document).on("click", "[role=navigation] a", function(e) {
		if($(document).width() <= 640) {
			$("[role=navigation]").slideToggle(300, function(e) {
				localStorage.setItem('nav-collapsed', 'true');
			});
		}
	});
	
	// $(".swipe-h").draggable({
	// 	axis: "x",
	// 	revert: true,
	// 	//handle: '.short-info',
	// 	start: function() {
	// 		//console.log("start")
	// 	},
	// 	stop: function() {
	// 		//console.log("stop")
	// 	}
	// }).addTouch();
});

// $.fn.addTouch = function() {
// 	var ts = null;
	
// 	this.each(function(i,el) {
// 		$(el).bind('touchstart touchmove touchend touchcancel',function(){
// 			//we pass the original event object because the jQuery event
// 			//object is normalized to w3c specs and does not provide the TouchList
// 			handleTouch(event);
// 		});
// 	});
	
// 	var handleTouch = function(event)
// 	{
// 		var touches = event.changedTouches,
// 			first = touches[0],
// 			type = '',
// 			direction = '';
		
// 		switch(event.type)
// 		{
// 			case 'touchstart':
// 				if(first) this.ts = first;
// 				type = 'mousedown';
// 				break;
			
// 			case 'touchmove':
// 				type = 'mousemove';
// 				if(parseInt(this.ts.clientY / 10) == parseInt(first.clientY / 10)) {
// 					if(this.ts.clientX < first.clientX) direction = 'left';
// 					if(this.ts.clientX > first.clientX) direction = 'right';
// 				}
// 				break;
			
// 			case 'touchend':
// 				type = 'mouseup';
// 				break;
			
// 			default:
// 				return;
// 		}

// 		if(direction !== '' || type == 'mousedown' || type == 'mouseup') {
// 			if(type == 'mousemove') event.preventDefault();
// 			var simulatedEvent = document.createEvent('MouseEvent');
// 			simulatedEvent.initMouseEvent(type, true, true, window, 1, first.screenX, first.screenY, first.clientX, first.clientY, false, false, false, false, 0/*left*/, null);
// 			first.target.dispatchEvent(simulatedEvent);
// 		}
// 	};
// };