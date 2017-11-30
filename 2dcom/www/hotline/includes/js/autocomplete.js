$(document).ready(function(){

	$( "#rechercheEan" ).autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "../includes/hotlineInstantSearch.php",
          dataType: "json",
          data: {
            codeEan: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 2
    } );

});