@extends('app')
@section('sidemenu_trip')
active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>
            {{ trans("sidemenu.trip") }}
            <span>>
                {{ trans("general.edit") }}
        </span>
        </h1>
    </div>
</div>
<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("trip.Edit_departure"))
        <div>
            <div class="widget-body ">
	            {!! Form::open(array('url' => "school/trip/edit_pay_back/$trip_id", 'method' => "post", 'files' => true)) !!}
                <table class="table table-bordered selected" >
                    <thead>
                        <tr>
                            <th>{{trans('trip.name')}}</th>
                            <th style="text-align: center;">{{trans('trip.action')}}</th>
                        </tr>
                    </thead>
                    <tbody> 
                          @foreach ($st_dp as $r)
							<tr>
								<td>{{$r->full_name}}</td>
                                <td>
								@foreach ($pay_back as $p)
								@if ($type == 1)
								<input type="radio" {{$r->pivot->pick_up_id == $p['id'] ?'checked':''}} name="radio{{$r->id}}" value="{{$p['id']}}">{{$p['name']}}
								@else
								<input type="radio" {{$r->pivot->drop_off_id == $p['id'] ?'checked':''}} name="radio{{$r->id}}" value="{{$p['id']}}">{{$p['name']}}
								@endif
								@endforeach
                                </td>
							</tr>
                          @endforeach
                    </tbody>
                </table>
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.submit')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection

@push('script')

@endpush


