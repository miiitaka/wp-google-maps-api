"use strict";

(function ($) {
  $(function () {
    var
      mapButton = $("#preview-map"),
      mapScript = $("#google-api-script"),
      mapPreview = $("#wp-google-map-api-preview");

    function initMap() {
      var map = new google.maps.Map(document.getElementById("wp-google-map-api-map"), {
        center: {
          lat: $("#lat").val(),
          lng: $("#lng").val()
        },
        scrollwhell: false,
        zoom: $("#zoom").val()
      });
    }

    mapButton.on("click", function () {
      $.getScript("https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap")
        .done( function ( script, textStatus ) {
          console.info( textStatus );
        })
        .fail( function ( jqxhr, settings, exception ) {
          console.error( jqxhr.status );
        })
        .always( function ( script, settings, exception ) {
          console.log( exception );
        });
    });
  });
})(jQuery);