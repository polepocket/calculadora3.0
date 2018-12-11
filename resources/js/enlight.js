//Método para evitar ahacer zoom a la página
$(document).keydown(function(event) {
	if (event.ctrlKey==true && (event.which == '61' || event.which == '107' || event.which == '173' || event.which == '109'  || event.which == '187'  || event.which == '189'  ) ) {
		event.preventDefault();
	}
	// 107 Num Key  +
	// 109 Num Key  -
	// 173 Min Key  hyphen/underscor Hey
	// 61 Plus key  +/= key
});
//Método para evitar ahacer zoom a la página
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
		// history.back();
		console.log('Confirmó');
		window.location.replace('/');
        // Uncomment below line to redirect to the previous page instead.
        // window.location = document.referrer // Note: IE11 is not supporting this.
    } else {
        // Stay on the current page.
		console.log('Canceló');
		// window.location.replace('/');
        // history.pushState(null, null, window.location.pathname);
    }
    // history.pushState(null, null, window.location.pathname);
}, false);
// document.onmouseover = function() {
//     //User's mouse is inside the page.
// 	window.innerDocClick = true;
// 	console.log(window.innerDocClick);
// }
// document.onmouseleave = function() {
//     //User's mouse has left the page.
//     window.innerDocClick = false;
// 	console.log(window.innerDocClick);
// }
// window.onhashchange = function(event) {	
// 	console.log(window.innerDocClick);
//     if (window.innerDocClick) {
//         //Your own in-page mechanism triggered the hash change
//     } else {
// 		//Browser back button was clicked
// 		// event.preventDefault();
// 		event.preventDefault();
// 		console.log('Se hizo clic en el botón Back');
//     }
// }
// $(function(){
//     /*
//      * this swallows backspace keys on any non-input element.
//      * stops backspace -> back
//      */
//     var rx = /INPUT|SELECT|TEXTAREA/i;
//     $(document).bind("keydown keypress", function(e){
//         if( e.which == 8 ){ // 8 == backspace
//             if(!rx.test(e.target.tagName) || e.target.disabled || e.target.readOnly ){
//                 e.preventDefault();
//             }
//         }
//     });
// });