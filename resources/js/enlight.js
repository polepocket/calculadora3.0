$(document).keydown(function(event) {
	if (event.ctrlKey==true && (event.which == '61' || event.which == '107' || event.which == '173' || event.which == '109'  || event.which == '187'  || event.which == '189'  ) ) {
		event.preventDefault();
	}
	// 107 Num Key  +
	// 109 Num Key  -
	// 173 Min Key  hyphen/underscor Hey
	// 61 Plus key  +/= key
});

$(window).bind('mousewheel DOMMouseScroll', function (event) {
	if (event.ctrlKey == true) {
	event.preventDefault();
	}
});
window.addEventListener('popstate', function(event) {
    // The popstate event is fired each time when the current history entry changes.

    var r = confirm("You pressed a Back button! Are you sure?!");

    if (r == true) {
        // Call Back button programmatically as per user confirmation.
        history.back();
        // Uncomment below line to redirect to the previous page instead.
        // window.location = document.referrer // Note: IE11 is not supporting this.
    } else {
        // Stay on the current page.
        history.pushState(null, null, window.location.pathname);
    }

    history.pushState(null, null, window.location.pathname);

}, false);