"use strict";

function initMap() {
  var map = new google.maps.Map(document.getElementById("wp-google-map-api-map"), {
    center: {
      lat: -34.397,
      lng: 150.644
    },
    scrollwheel: false,
    zoom: 8
  });
}

(function ($) {
  $(function () {
    var mapButton = $("#preview-map");

    function previewMap() {
      var map = new google.maps.Map(document.getElementById("wp-google-map-api-map"), {
        center: {
          lat: Number($("#lat").val()),
          lng: Number($("#lng").val())
        },
        scrollwhell: false,
        zoom: Number($("#zoom").val())
      });
    }

    mapButton.on("click", function () {
      previewMap();
    });
  });
})(jQuery);