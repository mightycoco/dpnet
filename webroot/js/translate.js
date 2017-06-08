$(function() {
	$(document).on("click", ".translate", function(e) {
		e.stopPropagation();
		if($(e.target).attr("state") == "original") {
			$(e.target).attr("state", "translated");
			$(e.target).text("Original");
			$(e.target).siblings(".translated").show();
			$(e.target).siblings(".text").hide();
			
			if($(e.target).siblings(".translated").text() === "") {
				$(e.target).siblings(".translated").text("translating...");
				translate($(e.target).siblings(".text").html()).done(function(data) {
					$(e.target).siblings(".translated").html(data.translated);
				});
			}
		} else {
			$(e.target).attr("state", "original");
			$(e.target).text("Translate");
			$(e.target).siblings(".translated").hide();
			$(e.target).siblings(".text").show();
		}
		return false;
	});
});
var translate = function(text, targetLang) {
	var defer = $.Deferred();
	var sourceLang = "auto";
	if(!targetLang) targetLang = navigator.language ? navigator.language : "en";
	
	var url = "//translate.googleapis.com/translate_a/single?client=gtx&sl=" 
	            + sourceLang + "&tl=" + targetLang + "&dt=t&q=" + encodeURI(text);
	var translated = "";
	  
	$.ajax({
		url:url, 
		processData:false
	})
  	.always(function(data) {
  		if(!data.responseText && !$.isArray(data[0])) {
  			defer.resolve({
				"text": text,
				"translated": "the translation service returned an error"
			});
  		} else if(data.responseText) {
			var keyvalues = JSON.parse(data.responseText.replace(/,,/g, ",").replace(/,,/g, ","))[0];
			$.each(keyvalues, function(i, e) {
				translated += e[0] + "<br/>\n";
			});
			defer.resolve({
				"text": text,
				"translated": translated
			});
  		} else if($.isArray(data[0])) {
  			$.grep(data[0], function(a) {translated+=a[0]});
  			defer.resolve({
				"text": text,
				"translated": translated
			});
  		}
	});
	return defer;
}