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
});