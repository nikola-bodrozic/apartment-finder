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
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <!-- selectize CSS -->
    <link href="bower_components/selectize/dist/css/selectize.bootstrap3.min.css" rel="stylesheet">
    
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
            <!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container" style="margin-top: 110px">
        <div class="row">
            
            <div class="col-md-4 hidden-xs">
                    <h3>Filter</h3>
                <div class="filter-holder">
                    <p>Filter by price in euros</p>
                    <div>
                        <b>10000 &nbsp;&nbsp;</b>
                        <input type="text" class="span2" value="" data-slider-min="10000" data-slider-max="50000" data-slider-step="1000" data-slider-value="[10000,50000]" id="housePrice">
                        <b> &nbsp;&nbsp; 50000</b>
                    </div>
                </div>

                <br>        

                <div class="filter-holder">
                    <hr>
                    <h3>Choose City</h3> <p>select or type at least one character</p>
                    <div id="wrapper">
                        <div class="demo">
                            <div class="control-group">
                                <select id="select-area" class="demo-default" tabindex="1" placeholder="Select a city...">
                                    <option value="">Select a city...</option>
                                    <option value="2" selected>London</option>
                                    <option value="1">Luton</option>
                                    <option value="3">Oxford</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <h3>Results</h3>
                <hr>
                <div id="out" class="alert alert-success"></div>
    			<div id="platno" style="width: 100%; height: 400px"><img id="imgload" src="images/ajax-loader.gif" /></div>
               
                <hr>

                <div id="loader"> </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- selectize JavaScript -->
    <script src="bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
    <!-- Google maps API -->    
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8ytq0lf_FAeZg9MKJwockhO73p1J93co"></script>                

    <script type="text/javascript">
       var min;
       var max;
       var id;
       var lat = Array();
       var lang = Array();

        // initialse range slider
       $('#housePrice').slider({
           tooltip: 'show'
       });

        // initialise selectize input element
       $('#select-area').selectize({
           create: false,
           sortField: {
               field: 'text',
               direction: 'asc'
           },
           dropdownParent: 'body',
           onChange: function(value) {
               //console.log("onChange"+value);
           },
           onItemAdd: function(value, $item) {
               id = value;
               makeAjax(min, max);
               //console.log("onItemAdd"+value, $item);
           }
       });

       $(document).ready(function() {
           // initials values for slider
           id = $('#select-area').find("option:selected").val();
           min = $('#housePrice').data("slider-min");
           max = $('#housePrice').data("slider-max");
           makeAjax(min, max);
       }); // end doc ready

       // handle slider event
       $("#housePrice").on("slideStop", function(slideEvt) {
           id = $('#select-area').find("option:selected").val();
           var t = slideEvt.value;
           min = t[0];
           max = t[1];
           makeAjax(t[0], t[1]);
       });

        // sending AJAX request and parsing JSON that server returned
       function makeAjax(min, max) {

           // console.log("--------- " + id, min, max);
           var request = $.ajax({
               url: window.location.href  + 'ajax.php',
               method: "POST",
               data: {
                   id: id,
                   minprice: min,
                   maxprice: max
               },
               beforeSend: function(xhr) {
                   $("#out").hide();
                   $("#platno").fadeOut();
                   xhr.setRequestHeader('X-Sec-Header', '<?php echo $_SESSION["sectok"]; ?>');
               }
           });

           request.done(function(msg) {
               var obj = jQuery.parseJSON(msg);
               var nr = obj[0].numrows;
               lat = [];
               lang = [];
               locations = [];
               for (i = 1; i <= nr; i++) {
                   locations.push([obj[i].slug, obj[i].lat, obj[i].lang], i);
               }

               $("#platno").fadeIn();               
               afterLoad(nr, locations);
               $("#out").show();
               $("#out").html("Query found " + nr + " result(s)").fadeIn("slow", "linear");
           });

           request.fail(function(jqXHR, textStatus) {
               alert("Request failed: " + textStatus);
           });

           request.always(function() {
               $("#imgload").remove();
           });
       }

        // render pins on map
       function afterLoad(nr, locations) {
           // set maps`s initial valuse - zoom, center of mat, etc...
           var mapDiv = document.getElementById('platno');
           var map = new google.maps.Map(mapDiv, {
               center: new google.maps.LatLng(51.507351, -0.127758),
               zoom: 8,
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

               google.maps.event.addListener(marker, 'click', (function(marker, i) {
                   return function() {
                       infowindow.setContent(locations[i][0]);
                       infowindow.open(map, marker);
                   }
               })(marker, i));
           } // endfor
       }
</script>
</body>
</html>
