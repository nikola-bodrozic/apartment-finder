<?php
session_start();
$_SESSION["sectok"] = uniqid(true);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Apartment finder</title>
        <!-- Bootstrap and demo CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet">

        <!-- select2 CSS -->
        <link href="css/select2.min.css" rel="stylesheet" />

        <!-- slider CSS -->
        <link href="css/slider.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--if lt IE 9
            script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
            script(src='https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js')
        -->
    </head>

    <body>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span> <span
                            class="icon-bar"></span> <span class="icon-bar"></span> <span
                            class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Place for logo</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                </div>
                <!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container" style="margin-top: 110px">
            <div class="row">
                <!-- sidebar  -->
                <div class="col-md-4 hidden-xs">
                    <h3>Filter</h3>
                    <!-- range slider  -->
                    <div class="filter-holder">
                        <div>Filter by price in euros</div>
                        <div>
                            <b>10000 &nbsp;&nbsp;</b>
                            <input type="text" class="span2" value="" data-slider-min="10000" data-slider-max="50000" data-slider-step="1000" data-slider-value="[10000,50000]" id="housePrice">
                            <b> &nbsp;&nbsp; 50000</b>
                        </div>
                    </div>

                    <br>        
                    <!-- select2 element  -->
                    <div class="filter-holder">

                        <hr>
                        <div>Area</div>
                        <div>
                            <select class="area-val" style="width: 100%">
                                <option value="1">Hillvesum</option>
                                <option value="2">Slagelse</option>
                                <option value="3">Hellerup</option>          
                            </select>
                        </div>
                    </div>


                </div>
                <!-- main content  -->
                <div class="col-md-8">
                    <h3>Results</h3>
                    <hr>
                    <div id="out" style="height:50px"></div>
                    <div id="platno" style="width: 516px; height: 250px"><img id="imgload" src="images/ajax-loader.gif" /></div>

                    <hr>

                    <div id="loader"> </div>
                </div>
            </div>
        </div>


        <!-- Bootstrap core JavaScript-->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <!-- select2 JavaScript-->
        <script src="js/select2.min.js"></script>

        <!-- slider JavaScript-->
        <script src="js/bootstrap-slider.min.js"></script>

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8ytq0lf_FAeZg9MKJwockhO73p1J93co"></script>                

        <script type="text/javascript">
            // initial parameters
            var min;
            var max;
            var id;
            var lat = Array();
            var lang = Array();

            // iniialise range slider
            $('#housePrice').slider({
                tooltip: 'show'
            });

            // initialise select2 element
            $(".area-val").select2();

            $(document).ready(function () {
                min = $('#housePrice').data("slider-min");
                max = $('#housePrice').data("slider-max");
                makeAjax(min, max);
            });

            // handle select event on select2 element
            $('.area-val').on('select2:select',
                    function (evt) {
                        makeAjax(min, max);
                    });

            // handle slide stop event
            $("#housePrice").on("slideStop", function (slideEvt) {
                var t = slideEvt.value;
                min = t[0];
                max = t[1];
                makeAjax(t[0], t[1]);
            });

            // fetch JSON from backend
            function makeAjax(min, max) {
                var host = location.protocol + '//' + location.hostname;

                var request = $.ajax({
                    url: host + '/folkmatic/ajax.php',
                    method: "POST",
                    data: {
                        id: $('.area-val').val(),
                        minprice: min,
                        maxprice: max
                    },
                    beforeSend: function (xhr) {
                        // $("#out").hide();
                        //$("#platno").append('<img id="imgload" src="images/ajax-loader.gif" />');
                        xhr.setRequestHeader('X-Sec-Header', '<?php echo $_SESSION["sectok"]; ?>');
                    }
                });

                request.done(function (msg) {
                    var obj = jQuery.parseJSON(msg);
                    var nr = obj[0].numrows;
                    lat = [];
                    lang = [];
                    locations = [];
                    for (i = 1; i <= nr; i++) {
                        locations.push([obj[i].slug, obj[i].lat, obj[i].lang], i);
                    }
                    afterLoad(nr, locations);
                    //$("#out").show();
                    $("#out").html("Query found " + nr + " result(s)");//.fadeIn("slow", "linear");
                });

                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });

                request.always(function () {
                    //$("#imgload").remove();
                });
            }

            // render pins on google map
            function afterLoad(nr, locations) {
                var mapDiv = document.getElementById('platno');
                var map = new google.maps.Map(mapDiv, {
                    center: new google.maps.LatLng(55.399833, 11.374667),
                    zoom: 6,
                    disableDefaultUI: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false,
                    navigationControl: true
                });

                var infowindow = new google.maps.InfoWindow();

                var marker, i;

                for (i = 0; i < locations.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                        map: map
                    });

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infowindow.setContent(locations[i][0]);
                            infowindow.open(map, marker);
                        };
                    })(marker, i));
                } // endfor
            }
        </script>
    </body>

</html>