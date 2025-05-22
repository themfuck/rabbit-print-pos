$(function () {
	$("input, select, textarea").attr("autocomplete", "off");
	$('input').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
    $('input').keypress(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});

function setSidebarToActive() {

	var path = window.location.pathname;
	var page_name = path.split("/").pop();
	var as = document.getElementById("mainSidebar").getElementsByTagName("a");
	var lis = document.getElementById("mainSidebar").getElementsByTagName("li");
	var href = "";
	
	for (var i = 0; i < as.length; i++) {

	    href = as[i].href.split("/").pop();
	    if (href == page_name) {
	        lis[i].classList.add("active");
	    }

	}

};

function numbersOnly(a, y) {
  	// numbersOnly(event, this.value)
    var keyValue;
    var evt;
    evt = a || window.event
    keyValue = evt.keyCode || evt.which;
    if (!(keyValue >= 48 && keyValue <= 57)) {   //0-9 
        return false;
    }
};

function formatSeparator(y) {
  	// numbersOnly(event, this.value)
    var keyValue = parseInt(y).toLocaleString();
    return keyValue;
};