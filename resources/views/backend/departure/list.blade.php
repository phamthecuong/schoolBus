@extends('app')

@section('departure')
active
@endsection

@section('content')

<!-- /.col-lg-12 -->
	 <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-align-justify fa-fw "></i>
                {{ trans("general.departure") }}
                <span>>
                    {{ trans("general.list") }}
            </span>
            </h1>
        </div>
    </div>
     <section id="widget-grid" class="">
		<div class="row">
			<div class="col-lg-12">
				@if (Session::has('flash_message'))
					<div class="alert alert-{!! Session::get('flash_level') !!}">
						{!! Session::get('flash_message') !!}
					</div>
				@endif		
			</div>
			<article class="col-lg-12">
				@box_open(trans("general.list"))
					<div>
						<div class="widget-body">
							<table id="MyDataTable" class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr align="center">
										<th style="text-align: center">{{ trans("departure.no") }}</th>
										<th style="text-align: center">{{ trans("departure.name") }}</th>
										<th style="text-align: center">{{ trans("departure.latitude") }}</th>
										<th style="text-align: center">{{ trans("departure.longitude") }}</th>
										<th class="no-sort" style="text-align: center">{{ trans("general.action") }}</th>
									</tr>
								</thead>
								<tbody>
									<?php $stt = 0; ?>
									@foreach($data as $item)
									<?php $stt += 1; ?>
									<tr  class="odd gradeX"  align="center">
										<td>{!! $stt !!}</td>
										<td>{!! $item["name"] !!}</td>
										<td>{!! $item["lat"] !!}</td>
										<td>{!! $item["long"] !!}</td>	
										
										<td>
                                            <a href="{!! URL::route('backend.departure.getEdit',$item['id']) !!}" class="btn btn-info btn-xs">{{ trans("general.edit") }}</a>
                                         	<a href="{!! URL::route('backend.departure.getDelete',$item['id']) !!}" class="btn btn-xs btn-danger" onclick="return confirm('Do you want to delete this departure?');">{{ trans("general.delete") }}</a>
                                        </td>
									</tr>
									@endforeach
								</tbody>
							</table>

							<div class="widget-footer">
							    <a href="{{ url('/school/departure/add') }}" class="btn btn-primary">
							        {{ trans('general.add')}}
							    </a>
							</div>
						</div>
					</div>
				@box_close()
			</article>
		</div>
	</section>

@endsection

@push('script')
<script>
$(document).ready(function() {
    $('#MyDataTable').DataTable({
		"columnDefs": [ {
			"targets": 'no-sort',
			"orderable": false,
		} ]
	});
} );
</script>

@endpush

