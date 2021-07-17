@extends('admin.layout_master.admin_design')

@section('Stylescss')
    <!-- Leaflet map -->
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/leaflet.css') }}">
    <style>
        #mapid {
            height: calc(100vh - 150px);
            width: auto;
        }
    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div id="mapid"></div>
    </div>
    <!-- /.Content Wrapper. Contains page content -->
@endsection

@section('javascript')
    <!-- Leaflet map -->
    <script src="{{ asset('admin/node_modules/js/leaflet.js') }}"></script>
    <script>
        var myMap;
        var lyrOSM;
        var mrkCurrentLocation;
        var popZocalo;
        var popExample;
        var ctlZoom;
        var cltAttribute;
        var cltScale;

        $(document).ready(function (){

            myMap = L.map('mapid',
                {
                    center: [-29.68057,-51.05270],
                    zoom: 13,
                    zoomControl:false,
                    attributionControl:false
                    //minZoom:
                    //maxZoom:
                }
            );
            lyrOSM = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png');

            myMap.addLayer(lyrOSM);

            popZocalo = L.popup(
                {
                    maxWidth:200,
                    keepInView:true
                }
            );

            ctlZoom = L.control.zoom({zoomInText:'In',zoomOutText:'Out',
            position:'bottomright'});
            ctlZoom.addTo(myMap);

            cltAttribute = L.control.attribution({position:'bottomleft'}).addTo(myMap);
            cltAttribute.addAttribution('OSM');
            cltAttribute.addAttribution('<a href="">text</a>');

            cltScale = L.control.scale({position:'bottomleft'}).addTo(myMap);

            //popZocalo.setLatLng([19.43262, -99.13325]);
            //popZocalo.setContent('<h2>Zocalo</h2><img src="img">');

            //popExample = L.popup();
            //popExample.setLatLng([19.43262, -99.13325]);
            //popExample.setContent($('#side-bar')[0]);
            //popExample.openOn(myMap);

            //Pega de 5 em 5 segundos a localização atual
            //setInterval(function (){
            //    myMap.locate();
            //}, 5000);

            //Pega zoom atual do mapa
            //myMap.on('zoomend', function (){
            //   $('#idelement').html(myMap.getZoom());
            //});

            //myMap.on('moveend', function (){
            //    $('#idelemtn').html(myMap.getCenter().toString());
            //    $('#idelemtn').html(LatLngToArrayString(myMap.getCenter() ));
            //});

            //myMap.on('mousemove', function (e){
            //    $('#idelemtn').html(LatLngToArrayString(e.latlng));
            //});

            //function LatLngToArrayString(ll){
            //    return "["+ll.lat.toFixed(5)+", "+ll.lng.toFixed(5)+"]";
            //}

            //$('#btnZomcalo').click(function(){
            //    myMap.setView([19.43262, -99.13325], 17);
            //    myMap.openPopup(popZocalo);
            // });

            myMap.on('click', function (e){
               if(e.originalEvent.shiftKey){
                   alert(myMap.getZoom());
               } else {
                   alert(e.latlng.toString());
               }
            });

            myMap.on('contextmenu', function (e){
               var dtCurrentTime = new Date();
               L.marker(e.latlng).addTo(myMap).bindPopup(e.latlng.toString()+"<br>"+dtCurrentTime.toString());
            });

            myMap.on('keypress', function (e){
                if(e.originalEvent.key == "l"){
                   myMap.locate();
                }
                //console.log(e);
            });

            myMap.on('locationfound', function (e){
                console.log(e);
                if(mrkCurrentLocation){
                    mrkCurrentLocation.remove();
                }
                //mrkCurrentLocation = L.circleMarker(e.latlng).addTo(myMap);
                mrkCurrentLocation = L.circle(e.latlng,
                    {
                        radius: e.accuracy/2
                    }
                ).addTo(myMap);
                myMap.setView(e.latlng, 16);
            });

            myMap.on('locationerror', function (e){
               console.log(e);
               alert('error na localizçao');
            });

        });
    </script>
@endsection
