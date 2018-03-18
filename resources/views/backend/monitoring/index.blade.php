@extends('app')

@section('sidemenu_live_report')
active
@endsection
@push('css')
<style>
	#map{
		width: 80%;
	    height: 600px;
	    float: left;
	}
	#info_trip {
		width: 20%;
		height: 600px;
		background: gray;
		float: right;
		overflow: scroll;
	}
	table {
		width: 100%;
/*		text-align: center;
*/		border: 1px solid white;
		margin: 0 auto;
	}
	table thead {
		background: green;
		padding-bottom: 5px;
	}
    table thead tr {
        height: 30px;
    }
    table tbody tr td {
        padding-left: 14px;
    }
    .name_bus {
        cursor: pointer;
    }
    .myPopup {
        z-index: 9999999999 !important;
    }
    .selected {
        background: yellow;
        color: blue;
    }
    .page-footer {
        z-index: -1 !important;
        bottom: none !important;
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4" style="width: 800px;">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-dashboard fa-fw "></i>
            Monitoring
        <span>>
            Chuyến xe mới nhất trong ngày
        </span>
        </h1>
    </div>
</div>
<div class="content" id=" main-content" ng-app="myApp" ng-controller='myController'>
 	<div id="map"></div>
    <div id="info_trip">	
		<div style="background: #c5c542; height: 45px; line-height: 45px ;text-align: center; font-size: 20px;">@{{info_trip.length}} {{trans('monitoring.trip_active')}}</div>
		<table ng-repeat="x in info_trip"  ng-click = "getDataPopup(x.id);">
			<thead class="name_bus">
				<tr id="trip_@{{x.id}}" ng-class="{1: 'selected'}[active[x.id]]">
					<td style="text-align: center;">Xe số @{{x.bus_name}}-@{{x.type}} </td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>@{{ x.total_pending }} {{trans("monitoring.pending")}}</td>
				</tr>
				<tr>
					<td>@{{ x.fabricate }} {{trans("monitoring.fabricate")}}</td>
				</tr>
				<tr>
					<td>@{{ x.received }} {{trans("monitoring.received")}}</td>
				</tr>
                <tr>
                    <td>@{{ x.leave }} {{trans("monitoring.leave")}}</td>
                </tr>
			</tbody>

		</table>
		
	</div>

	<!-- The Modal -->
    <div id="dialog_simple" title="" style="overflow-x: hidden; ">
        <div class="row">
            <section id="widget-grid">
                <div class="row">
                    <article class="col-lg-12">
                    <div class="widget-body">
                        <header class="padding-10">
                            <h2> Xe số: @{{ infoTrip.bus_name }} - <font ng-if="infoTrip.type == 1">Về nhà</font><font ng-if="infoTrip.type == 2">Tới trường</font> </h2>
                            <strong>Giờ khởi hành: </strong> @{{ infoTrip.started_at | limitTo: 5 }},
                            <strong>Giờ khởi hành thực tế: </strong> <font ng-if="infoTrip.active_at == null">Chưa khởi hành</font><font ng-if="infoTrip.active_at != null">@{{ infoTrip.active_at | date: 'yyyy-MM-dd' }}</font>,
                            <strong>Giờ kết thúc thực tế: </strong> <font ng-if="infoTrip.finish_at == null">Chưa kết thúc</font><font ng-if="infoTrip.finish_at != null">@{{ infoTrip.finish_at | date }}</font>
                        </header>
                        <hr>
                        <div>
                            <div id="accordion_modal">
                                <div ng-repeat="departure in infoTrip.departures">
                                    <div class="padding-10">
                                        <h4 style="color: yellow; padding-bottom: 4px;">@{{ departure.name }}</h4>
                                        <strong>Giờ đến dự kiến: </strong>@{{ departure.pivot.arrive_time | limitTo: 5 }}
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center">Học sinh</th>
                                                        <th style="text-align: center">Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                    <tr ng-repeat="student in infoTrip.students" style="text-align: center" ng-if="departure.id == student.pivot.pick_up_id || departure.id == student.pivot.drop_off_id">
                                                        <td>
                                                            @{{ student.full_name }}
                                                        </td>
                                                        <td>
                                                            <span ng-if="student.pivot.status == 3">{!! trans("student.pending") !!}</span>
                                                            <span ng-if="student.pivot.status == 4">{!! trans("student.picked_up") !!} (@{{ student.pivot.updated_at }})</span>
                                                            <span ng-if="student.pivot.status == 5 && infoTrip.type == 1">{!! trans("student.get_off") !!} (@{{ student.pivot.updated_at }})</span>
                                                            <span ng-if="student.pivot.status == 5 && infoTrip.type == 2">{!! 'Đã đến trường' !!} (@{{ student.pivot.updated_at }})</span>
                                                            <span ng-if="student.pivot.status == 6">{!! trans("student.absence") !!}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>                                              
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </article> 
                </div>
            </section>
        </div>
    </div>
    <!-- end modal -->
</div>
  

@endsection
							
@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="{{asset('/js/jquery.scrollIntoView.min.js')}}"></script>
<script>

    var map; // contain object for map
    var active = [];
    var data_map;
    var markers = [];
    var flash = 1;
	$("document").ready(function(){
		configPopup();
	})

    var app = angular.module("myApp", []); 
    app.controller('myController', function($scope, $http, $interval, $window) {
        $http.get("/ajax/monitoring")
            .then(function(response) {
                $scope.info_trip = response.data.trip_info;
                data_map = response.data.trip_info;
                initMap(data_map);
            });

        $interval(function () {
            $http.get("/ajax/monitoring")
            .then(function(response) {
                $scope.active = $window.active;
                flash+= 1;
                $scope.info_trip = response.data.trip_info;
                data_map = response.data.trip_info;
                initMap(data_map);

            });
        }, 30000);  

        $scope.getDataPopup = function(trip_id) {
            $('tr').removeClass('selected');
            $http({
                method: 'GET',
                url: '/ajax/getDataPopup',
                params: { 
                    trip_id: trip_id 
                }
            }).then(function successCallback(response) {
                active = [];
                $scope.infoTrip = response.data;
                // selected marker when click trip_info
                active[response.data.id] = 1;
                $('#trip_'+ response.data.id).addClass('selected');
                initMap(data_map);
                $('#dialog_simple').dialog('open');
            }, function errorCallback(response) {

            });
        }
    });

    function popupData(trip_id) {
        var $scope = angular.element('.content').scope().getDataPopup(trip_id);
    }
    
	function initMap(data) {
        removeMarkers();
		var bus_image = {
	        url: '../frontend/images/bus2.png',
	        scaledSize: new google.maps.Size(50, 60), 
   		}
        var bus_image_change = {
            url: '../frontend/images/bus1.png',
            scaledSize: new google.maps.Size(50, 60), 
        }
        if (flash == 1) {
            map = new google.maps.Map(document.getElementById('map'), {
               mapTypeId: google.maps.MapTypeId.ROADMAP,
            });    
        }
        //Create LatLngBounds object.
        var latlngbounds = new google.maps.LatLngBounds();
		// data for marker
		for (var i in data) {
            if (active[data[i].id] == 1) {
                var icon = bus_image_change;
                var x = document.getElementById('trip_'+ data[i].id);
                x.scrollIntoView();
            } else {
                var icon = bus_image;
            }
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(data[i]['lat']), parseFloat(data[i]['lng'])),
                map: map,
                icon: icon
            }); 
            //Extend each marker's position in LatLngBounds object.
            latlngbounds.extend(marker.position);
            //push marker array
            markers.push(marker);
            // add event click for marker
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    //set active selected
                    active[data[i].id] = 1;
                    var x = document.getElementById('trip_'+ data[i].id);
                    $('#trip_'+ data[i].id).addClass('selected');
                    x.scrollIntoView();
                    //call popup
                    popupData(data[i].id);
                    marker.setIcon(bus_image_change);
                }
            })(marker, i));

		}
        if (flash == 1) {
            //Get the boundaries of the Map.
            var bounds = new google.maps.LatLngBounds();
            //Center map and adjust Zoom based on the position of all markers.
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);    
        }
	}

    function removeMarkers(){
        for(i = 0; i < markers.length; i++){
            markers[i].setMap(null);
        }
    }

	function configPopup()
	{
		$("#dialog_simple").dialog({
			autoOpen: false,
        	height:600,
			modal: true,
            dialogClass:"myPopup",
			width: "1200px",
			title: "{{trans('monitoring.trip_info')}}",
			buttons: [{
				html: "{{trans('monitoring.Cancel')}}",
				class: "btn btn-default",
				click: function() {
					$(this).dialog("close");
				}
			}]
		}).dialog("widget").draggable({ containment: "none", scroll: false });		
	}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCSYHSL4koJwJ976VGEmyVTRXfTDtbsKSE"></script>
