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

if(history.forward(1)){
	console.log('Clic al botón Back');
	location.replace( history.forward(1) );
}