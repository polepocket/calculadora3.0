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

if (window.history && history.pushState) {
	console.log('Hizo clic en back');
    addEventListener('load', function() {
        history.pushState(null, null, null); // creates new history entry with same URL
        addEventListener('popstate', function() {
            var stayOnPage = confirm("Would you like to save this draft?");
            if (!stayOnPage) {
                history.back() 
            } else {
                history.pushState(null, null, null);
            }
        });    
    });
}