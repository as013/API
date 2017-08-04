(function ($, window, document) {
    $(function () {
        $(window).scroll(function() {
            if ($(document).scrollTop() > 90) {
                $('.navbar').addClass('shrink');
            }
            else {
                $('.navbar').removeClass('shrink'); }
        });

        function initMap() {
            var uluru = {lat: -25.363, lng: 131.044};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }

        $('.one-page-nav').onePageNav({
            currentClass: 'active',
            changeHash: true,
            scrollSpeed: 750,
            scrollThreshold: 0.2,
            filter: '',
            easing: 'swing',
            begin: function() {
                //I get fired when the animation is starting
            },
            end: function() {
                //I get fired when the animation is ending
            },
            scrollChange: function($currentListItem) {
                //I get fired when you enter a section and I pass the list item of the section
            }
        });

        $('.slider').fractionSlider({
            'fullWidth': 			true,
            'controls': 			false,
            'pager': 				false,
            'responsive': 			true,
            'dimensions': 			"2500,900",
            'increase': 			false,
            'pauseOnHover': 		false
        });
    });
}(window.jQuery, window, document));