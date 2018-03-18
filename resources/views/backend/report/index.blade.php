@extends('app')

@section('sidemenu_report_by_day')
active
@endsection
@push('css')
<style>
	.jarviswidget-ctrls {
		display: none !important;
	}
</style>
@endpush
@section('content')
	<div class="row">
	    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
	        <h1 class="page-title txt-color-blueDark">
	            <i class="fa fa-dashboard fa-fw "></i>
	            Monitoring
	            <span>>
	                Báo cáo theo ngày
	        </span>
	        </h1>
	    </div>
	</div>

	<section id="widget-grid" ng-app="myApp">							
		<div class="row"  ng-controller="myController">						
			<article class="col-lg-4">					
				<div class="form-group">
					<label class="control-label">Chọn ngày</label>
				    {{ Form::text('date', $today, [
				    	'class' => 'form-control datepicker date',
				    	'data-dateformat' => 'yy-mm-dd',
				    	'ng-model' => 'date',
				    	'ng-init' => 'date = "'. $today. '"'
				    ]) }}
				</div>
			</article>

			<article class="col-lg-12" ng-repeat="trip in trips">
				<div class="jarviswidget" id="@{{ 'widget-' + trip.id }}" data-widget-sortable="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false">
					<header>
						<h2 style="margin-top: -10px !important;"> Xe số: @{{ trip.bus_name }} - <font ng-if="trip.type == 1">Về nhà</font><font ng-if="trip.type == 2">Tới trường</font> </h2>
					</header>
					<div ng-controller="tripController" ng-init="init(trip.id)">
						<div class="widget-body">
							<strong>Giờ khởi hành: </strong> @{{ trip.started_at | limitTo: 5 }},
							<strong>Giờ khởi hành thực tế: </strong> <font ng-if="trip.active_at == null">Chưa khởi hành</font><font ng-if="trip.active_at != null">@{{ trip.active_at | date: 'yyyy-MM-dd' }}</font>,
							<strong>Giờ kết thúc thực tế: </strong> <font ng-if="trip.finish_at == null">Chưa kết thúc</font><font ng-if="trip.finish_at != null">@{{ trip.finish_at | date }}</font>

							<div id="@{{ 'accordion-' + trip.id }}">
								<div ng-repeat="departure in infoTrip.departures">
									<h4>@{{ departure.name }}</h4>
									<div class="padding-10">
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
		                                            		<span ng-if="student.pivot.status == 5 && trip.type == 1">{!! trans("student.get_off") !!} (@{{ student.pivot.updated_at }})</span>
		                                            		<span ng-if="student.pivot.status == 5 && trip.type == 2">{!! 'Đã đến trường' !!} (@{{ student.pivot.updated_at }})</span>
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
				</div>
			</article>
		</div>						
	</section>	
@endsection

@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script> 
<script type="text/javascript">

	var app = angular.module('myApp', [] );


	app.controller('myController', ['$scope','$http', function($scope,$http){
		$scope.$watch("date", function(newValue, oldValue) {
	    	$http({
	            method: 'GET',
	            url: '{!!URL::to("ajax/report")!!}' + '/' + $scope.date,
	        }).then(function successCallback(response) {
	            $scope.trips = response.data;
	        }, function errorCallback(response) {

	        });
		});	
	}]);

	app.controller('tripController', ['$scope','$http', function($scope, $http){
		$scope.init = function(trip_id) {
			$http({
	            method: 'GET',
	            url: '{!!URL::to("ajax/report")!!}' + '/' + $scope.date + '/' + trip_id,
	        }).then(function successCallback(response) {
	            $scope.infoTrip = response.data;
	            setTimeout(function() {
	            	$("#widget-" + trip_id).accordion({
	            		autoHeight: true,
						heightStyle: "content",
						collapsible: true,
						active: false,
						animate: false,
						header: "header",
						icons: {
					        header: "fa fa-plus",
					        activeHeader: "fa fa-minus"
					    }
	            	});
	            	$("#accordion-" + trip_id).accordion({
						autoHeight: true,
						heightStyle: "content",
						collapsible: true,
						active: false,
						animate: false,
						header: "h4",
						icons: {
					        header: "fa fa-plus",
					        activeHeader: "fa fa-minus"
					    }
					})
	            }, 100);
	        }, function errorCallback(response) {

	        });
		}
	}]);
</script>
@endpush
