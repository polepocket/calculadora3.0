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

document.onmouseover = function() {
    //User's mouse is inside the page.
	window.innerDocClick = true;
	console.log(window.innerDocClick);
}
document.onmouseleave = function() {
    //User's mouse has left the page.
    window.innerDocClick = false;
	console.log(window.innerDocClick);
}
window.onhashchange = function() {	
	console.log(window.innerDocClick);
    if (window.innerDocClick) {
        //Your own in-page mechanism triggered the hash change
    } else {
		//Browser back button was clicked
		// event.preventDefault();
		preventDefault();
		console.log('Se hizo clic en el botón Back');
    }
}
$(function(){
    /*
     * this swallows backspace keys on any non-input element.
     * stops backspace -> back
     */
    var rx = /INPUT|SELECT|TEXTAREA/i;
    $(document).bind("keydown keypress", function(e){
        if( e.which == 8 ){ // 8 == backspace
            if(!rx.test(e.target.tagName) || e.target.disabled || e.target.readOnly ){
                e.preventDefault();
            }
        }
    });
});