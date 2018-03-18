@extends('app')

@section('sidemenu_trip_list')
active
@endsection

@section('content')
<div class="row">
	<div class="col-sm-12 text-center">
		<h1>{!! $date !!}</h1>
	</div>
</div>

<section id="widget-grid">
	<div class="row">
		<article class="col-lg-12">
			@box_open(trans('title.trip'))
				<div>
					<div class="widget-body">
						<div class="row">
							<div class="col-sm-6">
								<ul class="bus_status">
									<li>
										<img class="bus_status_img" src="{!! url('frontend/images/gray_circle.png') !!}" />
										<p>{!! trans('bus.pending') !!}</p>
									</li>
									<li>
										<img class="bus_status_img" src="{!! url('frontend/images/blue_circle.png') !!}" />
										<p>{!! trans('bus.active') !!}</p>
									</li>
									<li>
										<img class="bus_status_img" src="{!! url('frontend/images/ic_curcle.png') !!}" />
										<p>{!! trans('bus.finish') !!}</p>
									</li>
								</ul>
							</div>

							<div class="col-sm-offset-2 col-sm-3">
								<a href="#map-canvas"><img class="map_link" src="{!! url('frontend/images/map.png') !!}" alt="map" /></a>
							</div>
						</div>
					</div>
				</div>
			@box_close
		</article>
	</div>
</section>
@if(isset($data))  
<section>
	<div class="row">
		<div class="col-lg-12">
			@foreach($data as $item)
			<div class="row">
				<article class="col-lg-12">
						<div class="well" style="background-color: rgba(0,0,0,.18)!important; border-color: rgba(0,0,0,.13)!important; ">
							<div class="widget-body">
								<div class="row">
									<div class="col-sm-4">
										<ul class="bus_status">
											<li>
												@if ($item['active_at'] == NULL)
													<img class="bus_status_img bus_img" src="{!! url('frontend/images/gray_circle.png') !!}" />
												@elseif ($item['active_at'] != NULL && $item['finish_at'] == NULL)
													<img class="bus_status_img bus_img" src="{!! url('frontend/images/blue_circle.png') !!}" />
												@else
													<img class="bus_status_img bus_img" src="{!! url('frontend/images/ic_curcle.png') !!}" />
												@endif

												<p><b>{!! $item['bus_name'] !!}</b></p>
											</li>
										</ul>
									</div>
									<div class="col-sm-4">
										<p>{!! trans('driver.phone') !!}: {!! $item['phone_number'] !!}</p>
									</div>
									<div class="col-sm-1 text-center">
										<p>{!! $item['arrive_time'] !!}</p>
									</div>
									<div class="col-sm-3">
										<a href="#" class="btn btn-primary btn-xs outline begin-chat" data-user="{{ $item['driver_id'] }}">{{ trans("parent.chat_with_driver") }}</a>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<p style="padding-left: 40px"> {!! $item['type'] !!}</p>
									</div>
									<div class="distance_info col-sm-offset-2 col-sm-3" >
										<p><span class="distance_left"></span> <span class="travel_data"></span> </p>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<p style="padding-left: 40px">{!! trans('school.hotline') !!}: {!! $item['hotline'] !!}</p>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<ul class="bus_status">
											<li class="text-uppercase" style="margin: 5px 0px; ">{!! $item['student_name'] !!}</li>
                                            <li class="student_status"></li>
										</ul>
									</div>
									<div class="col-sm-offset-2 col-sm-3">
										 <img class="student-avatar img-circle" src="{!! url('lbmedia/'. $item['avatar_id']) !!} " alt="student-avatar"/> 
										<!--<img class="student-avatar img-circle" src="{!! url('uploads/images.jpg') !!}" alt="test"/>-->
									</div>
								</div>
							</div>
						</div>
					
				</article>
			</div>
			@endforeach
		</div>
	</div>
</section>

<div id="map-canvas"></div>
@endif
@endsection

@if (isset($data))
@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsczjWmb0HQ0u5pLYV2pg_-ABudlT_B5Q&libraries=geometry&language=en"></script>
<!-- Map setting -->
<script>
    var map = new google.maps.Map(document.getElementById('map-canvas'),
        {
            zoom: 13
        });
    var student_image = {
        url: '../frontend/images/students.png',
    };      
    var bus_image = {
        url: '../frontend/images/marker_bus.png',
        scaledSize: new google.maps.Size(50, 60), 
    }
    var options = {
                strokeColor: 'black',
                // strokeOpacity: 1.0,
                 strokeWeight: 0.2,
                fillColor: '#33ccff',
                fillOpacity: 0.1,
                //center: current_center,
                radius: 1000
            };
    var bus_markers = new Array();
    var student_markers = new Array();
    var all_markers = new Array();
    var circles = new Array();
    var i = 0;
    var j = 0;
    var k = 0;
    var p_class = 0; duration_no = 0;

    @foreach ($data as $item)
        {
            var student_content =   "<div>" +
                                        "<p style='color: black'><b>{!! $item['student_name'] !!}</b></p>" +
                                        "<p style='color: black'><b>Bus: </b>{!! $item['bus_name'] !!}</p>" + 
                                        "<p style='color: black'>{!! $item['departure_name'] !!}</p>" +
                                    "</div>";
            var bus_content = "<div><p style='color: black'><b>{!! $item['bus_name'] !!}</b></p></div>";
            var student_marker = new google.maps.Marker({
                position: {
                    lat: {!! $item['student_lat'] !!},
                    lng: {!! $item['student_long'] !!}
                },
                icon: student_image,
            });
            student_markers[i] = student_marker;
            student_markers[i].setMap(map);
            i = i + 1;
            var student_infoWindow = new google.maps.InfoWindow();
            google.maps.event.addListener(student_marker,'click', (function(student_marker,student_content,student_infoWindow){ 
                return function() {
                    student_infoWindow.setContent(student_content);
                    student_infoWindow.open(map,student_marker);
                };
            })(student_marker,student_content,student_infoWindow));  
            <?php
                if($item['active_at'] != NULL && $item['finish_at'] == NULL)
                {
            ?>
                    var bus_marker = new google.maps.Marker
                    ({
                        position: {
                            lat: {!! $item['trip_lat'] !!},
                            lng: {!! $item['trip_long'] !!}
                        },
                        icon: bus_image,
                    });     
                    bus_markers[j] = bus_marker;
                    bus_markers[j].setMap(map);
                    
                    var bus_infoWindow = new google.maps.InfoWindow();
                    google.maps.event.addListener(bus_marker,'click', (function(bus_marker,bus_content,bus_infoWindow){ 
                        return function() {
                            student_infoWindow.setContent(bus_content);
                            student_infoWindow.open(map,bus_marker);
                        };
                    })(bus_marker,bus_content,bus_infoWindow));  

                    var current_center = new google.maps.LatLng({!! $item['trip_lat'] !!}, {!! $item['trip_long'] !!});
                    
                    circles[k] = new google.maps.Circle(options);
                    circles[k].setCenter(current_center);
                    circles[k].setMap(map);
                    
            <?php
                }
            ?>
            j = j + 1;
            k = k +1;
        }
    @endforeach
    var sub_bus_markers = new Array();
        var sub_bus_markers_no = 0;
        for (var i = 0; i < bus_markers.length; i++)
        {
            if (bus_markers[i])
            {
                sub_bus_markers[sub_bus_markers_no] = bus_markers[i];
                sub_bus_markers_no ++;
            }
        }
        all_markers = student_markers.concat(sub_bus_markers);
    //all_markers = student_markers.concat(bus_markers);
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < all_markers.length; i++) {
        bounds.extend(all_markers[i].getPosition());
    }   
    map.setCenter(bounds.getCenter());
    //map.fitBounds(bounds);
    ajaxCall();
    setInterval(function(){
        ajaxCall()
    }, 30000); 

function ajaxCall(){
    $(document).ready(function(){
        $.ajax({
            type:'get',
            url:'{!!URL::to("parent/trip/findbus")!!}',
            data: '',
            success:function(data_ajax){
                var no = 0;
                var mang = new Array();
                var chiso = 0;
                var origin = new Array();
                var destination = new Array();
                for (var i=0;i<data_ajax.length;i++)
                {
                    switch(parseInt(data_ajax[i].status))
                    {
                        case 3:
                            document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.pending") !!}';
                            break;
                        case 4:
                            document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.picked_up") !!}';
                            break;
                        case 5:
                            document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.get_off") !!}';
                            break;
                        case 6:
                            document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.absence") !!}';
                            break;
                    }
                    // console.log(data_ajax[i].status);
                    if(data_ajax[i].status === 3)
                    {
                        document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.pending") !!}';
                    } 
                    else if (data_ajax[i].status === 4)
                    {
                        document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.picked_up") !!}';
                    }
                    else if (data_ajax[i].status === 5)
                    {   
                        document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.get_off") !!}';
                    }
                    else if (data_ajax[i].status === 6)
                    {
                        document.getElementsByClassName('student_status')[i].innerHTML = '{!! trans("student.absence") !!}';
                    }
                    // Chinh lai icon thong bao trang thai xe
                    if (!data_ajax[i].active_at)
                    {
                        document.getElementsByClassName('bus_img')[i].src = '../frontend/images/gray_circle.png';
                        document.getElementsByClassName('distance_info')[i].style.display = 'none';
                        if(bus_markers[i])
                        {
                            bus_markers[i].setMap(null);
                            bus_markers[i] = null;
                            circles[i].setMap(null);
                            circles[i] = null;
                        }
                    }
                    else if (data_ajax[i].active_at && !data_ajax[i].finish_at)
                    {   
                        document.getElementsByClassName('bus_img')[i].src = '../frontend/images/blue_circle.png';
                        if(data_ajax[i].status == 3)
                        {
                            document.getElementsByClassName('distance_info')[i].style.display = 'block';    
                        }
                        else 
                        {
                            document.getElementsByClassName('distance_info')[i].style.display = 'none';
                        }
                        var busLatLng = new google.maps.LatLng(data_ajax[i].trip_lat, data_ajax[i].trip_long);
                        if (bus_markers[i])
                        {
                            bus_markers[i].setPosition(busLatLng);
                        }
                        else 
                        {
                            bus_markers[i] = new google.maps.Marker
                            ({
                                position: busLatLng,
                                icon: bus_image,
                            });     
                            bus_markers[i].setMap(map);
                            circles[i] = new google.maps.Circle(options);
                            circles[i].setMap(map);
                            var bus_content = "<div><p style='color: black'><b>"+ data_ajax[i].bus_name +"</b></p></div>";
                            var bus_infoWindow = new google.maps.InfoWindow();
                            google.maps.event.addListener(bus_markers[i],'click', (function(bus_marker,bus_content,bus_infoWindow){ 
                                    return function() {
                                        student_infoWindow.setContent(bus_content);
                                        student_infoWindow.open(map,bus_marker);
                                    };
                                })(bus_markers[i],bus_content,bus_infoWindow));  
                        }

                        circles[i].setCenter(busLatLng);
                        origin[no] = data_ajax[i].trip_lat + ', ' + data_ajax[i].trip_long;
                        destination[no] = data_ajax[i].student_lat + ', ' + data_ajax[i].student_long;  
                        no++;
                        mang[chiso] = i;
                        chiso++;
                    }
                    else
                    {
                        document.getElementsByClassName('bus_img')[i].src = '../frontend/images/ic_curcle.png';
                        document.getElementsByClassName('distance_info')[i].style.display = 'none';
                        if(bus_markers[i])
                        {
                            bus_markers[i].setMap(null);
                            bus_markers[i] = null;
                            circles[i].setMap(null);
                            circles[i] = null;
                        }
                    }
                }
                var sub_bus_markers = new Array();
                var sub_bus_markers_no = 0;
                for (var i = 0; i < bus_markers.length; i++)
                {
                    if (bus_markers[i])
                    {
                        sub_bus_markers[sub_bus_markers_no] = bus_markers[i];
                        sub_bus_markers_no ++;
                    }
                }
                all_markers = student_markers.concat(sub_bus_markers);
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < all_markers.length; i++) {
                    bounds.extend(all_markers[i].getPosition());
                }
                map.setCenter(bounds.getCenter());
                if(origin[0] && destination[0])
                {
                    
                    var service = new google.maps.DistanceMatrixService();
                    service.getDistanceMatrix(
                      {
                          origins: origin, //LatLng Array
                          destinations: destination, //LatLng Array
                          travelMode: google.maps.TravelMode.DRIVING,
                          unitSystem: google.maps.UnitSystem.METRIC,
                          avoidHighways: false,
                          avoidTolls: false
                      }, callback);

                    function callback(response, status) {
                        if (status != google.maps.DistanceMatrixStatus.OK) {
                            alert('Error was: ' + status);
                        } else {
                            var origins = response.originAddresses;
                            var destinations = response.destinationAddresses;

                            for (var i = 0; i < origins.length; i++) {
                                var results = response.rows[i].elements;
                                var dst = results[i].distance.value;
                                var time = parseInt(results[i].duration.value/60);

                                if (dst > 1000)
                                {
                                    document.getElementsByClassName('distance_left')[mang[i]].innerHTML = results[i].distance.text + " /";
                                }
                                else 
                                {
                                    document.getElementsByClassName('distance_left')[mang[i]].innerHTML = dst + " m /";
                                }
                                if (time < 60)
                                {
                                    document.getElementsByClassName('travel_data')[mang[i]].innerHTML = time + ' {!! trans("parent.minutes") !!}';    
                                }
                                else 
                                {
                                    var hours = time/60;
                                    var minutes = time%60;
                                    document.getElementsByClassName('travel_data')[mang[i]].innerHTML = hours + ' {!! trans("parent.hours") !!} ' + minutes + ' {!! trans("parent.minutes") !!} ';
                                }
                                
                            }
                        }
                    }
                }  
            }
        });
    });
}

</script>
<script>
	$(document).ready(function () {
        $('.begin-chat').click(function (e) {
        	e.preventDefault();
            var id = $(this).attr('data-user');
            var data = {};
            data['id'] = id;
            data['_token'] = '{{ csrf_token() }}';
            $.ajax({
                url: '/lbmessenger/ajax/conversation',
                method: "POST",
                dataType: "json",
                data: data,
                complete: function(xhr, status) {
                    // console.log(xhr);
                    if (xhr.status == 200)
                    {
                        window.location = '/lbmessenger/conversation/' + xhr.responseJSON.id + '/item';
                    }
                }
            })
        });
    });
</script>		
@endpush
@endif
@push('css')
<style type="text/css">
    .bus_status {
    	list-style-type: none;
    }

    .bus_status_img {
    	width: 20px;
    	height: 20px;
    	float: left;
    	margin-right: 10px;
    }

    .student-avatar {
    	width: 60px;
    	height: 60px;
    }
    /*#map_canvas_container {
    	position: relative;
    	width:100%; height:100%;
    }*/
    #map-canvas {
    	/*width:100%; height:100%;*/
    	/*position: absolute;
    	top: 0; bottom: 0; left: 0; right: 0;*/
    	width: 100%;
    	height: 400px;
    	
    }

    .map_link {
    	width: 100px;
    	height: 70px;
    }
    /***********************
      OUTLINE BUTTONS
    ************************/

    .btn.outline {
        background: none;
        
    }
    .btn-primary.outline {
        border: 2px solid #b3ffff;
        color: #b3ffff;
    }
    .btn-primary.outline:hover, .btn-primary.outline:focus, .btn-primary.outline:active, .btn-primary.outline.active, .open > .dropdown-toggle.btn-primary {
        color: white;
        border-color: #b3ffff;
    }
    .btn-primary.outline:active, .btn-primary.outline.active {
        border-color: #b3ffff;
        color: #b3ffff;
        box-shadow: none;
    }

    /***********************
      CUSTON BTN VALUES
    ************************/

    .btn {
        border: 0 none;
        font-weight: 700;
        letter-spacing: 1px;
        
    }
    .btn:focus, .btn:active:focus, .btn.active:focus {
        outline: 0 none;
    }
</style>
@endpush