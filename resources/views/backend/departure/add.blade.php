@extends('app')

@section('departure')
active
@endsection

@section('content')

	<section id="widget-grid">
		<div class="row">
	
			<article class="col-lg-12">
				@box_open(trans("general.add"))
				<div>
					<div class="widget-body">

						{!! Form::open(["url"=>"school/departure/add","method"=>"post"]) !!}
							<fieldset>
								{!! Form::lbText('txtName','',trans('departure.name'),'') !!}
								<div class="form-group">
									<label>{!! trans('departure.map') !!}</label>
									<input class="form-control" type="text" id="searchmap" placeholder=" ">
									<br>
									<div id="map-canvas" ></div>		
									
								</div>
								{!! Form::lbText('lat','',trans('departure.latitude'),'') !!}

								{!! Form::lbText('lng','',trans('departure.longitude'),'') !!}
							</fieldset>

						<div class="widget-footer">
							{!! Form::lbSubmit(trans('general.add')) !!}
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
				lat: 21.029028,
				lng: 105.852472
			},
			zoom: 15
		});
		
		var marker = new google.maps.Marker(
		{
			position: {
				lat: 21.029028,
				lng: 105.852472
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