@extends('app')

@section('departure')
active
@endsection

@section('content')
	<section id="widget-grid">
		<div class="row">
			<article class="col-lg-12">
				@box_open(trans("general.edit"))
				<div>
					<div class="widget-body">

						{!! Form::open(["url"=>"school/departure/edit/".$departure['id'],"method"=>"post"]) !!}
							<fieldset>
								{!! Form::lbText('txtName',$departure['name'],trans('departure.name'),'') !!}
								<div class="form-group">
									<label>{!! trans('departure.map') !!}</label>
									<input class="form-control" type="text" id="searchmap" placeholder=" ">
									<br>
									<div id="map-canvas"></div>		
								</div>
								{!! Form::lbText('lat',$departure['lat'],trans('departure.latitude'),'') !!}
								{!! Form::lbText('lng',$departure['long'],trans('departure.longitude'),'') !!}
							</fieldset>

						<div class="widget-footer">
							{!! Form::lbSubmit(trans('general.edit')) !!}
						</div>
						{!! Form::close() !!}
					</div>
				</div>
				@box_close
			</article>
		</div>
	</section>
@endsection               

@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsczjWmb0HQ0u5pLYV2pg_-ABudlT_B5Q&libraries=places&language=en"></script>
<script>

	var map = new google.maps.Map(document.getElementById('map-canvas'),
	{
		center:{
			lat: {!! (float) $departure['lat'] !!},
			lng: {!! (float) $departure['long'] !!}
		},
		zoom: 15
	});
	
	var marker = new google.maps.Marker(
	{
		position: {
			lat: {!! (float) $departure['lat'] !!},
			lng: {!! (float) $departure['long'] !!}
		},
		map: map,
		draggable: true
	});
	
	var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));
	
	google.maps.event.addListener(searchBox,'places_changed',function()
	{
		var places = searchBox.getPlaces();
		var bounds = new google.maps.LatLngBounds();
		var i, place;
		for (i=0; place=places[i];i++){
			bounds.extend(place.geometry.location);
			marker.setPosition(place.geometry.location);
		}
		map.fitBounds(bounds);
		map.setZoom(15);
	});
	
	google.maps.event.addListener(marker,'position_changed',function()
	{
		var lat = marker.getPosition().lat();
		var lng = marker.getPosition().lng();
		
		$('#lat').val(lat);
		$('#lng').val(lng);
	});
	
</script>


@endpush

@push('css')
<style type="text/css">
#map-canvas {
	width:100%;height:400px;
}
</style>
@endpush