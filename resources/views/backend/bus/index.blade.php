@extends('app')

@section('sidemenu_bus')
    active
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-bus fa-fw "></i>
                {{ trans("sidemenu.bus") }}
                <span>>
                    {{ trans("general.list") }}
            </span>
            </h1>
        </div>
    </div>
    @if (\Session::has('warning'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ \Session::get('warning') }}
                </div>
            </article>
        </div>
    @endif
    @if (\Session::has('update'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ \Session::get('update') }}
                </div>
            </article>
        </div>
    @endif
    @if (\Session::has('delete_fail'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-danger fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ \Session::get('delete_fail') }}
                </div>
            </article>
        </div>
    @endif
    @if (\Session::has('delete_success'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ \Session::get('delete_success') }}
                </div>
            </article>
        </div>
    @endif
    <section id="widget-grid" class="">
        <div class="row">
            <article class="col-lg-12">
                @box_open(trans("sidemenu.bus"))
                    <div>
                        <div class="widget-body">
                            @include("layouts.elements.table", [
                            'url' => '/ajax/buses',
                            'columns' =>
                            [
							['data' => 'name', 'title' => 'Số xe'],
							['data' => 'bus_number', 'title' => trans('title.Number')],
							['data' => 'driver_name', 'title' => trans('title.driver_name')],
							['data' => 'phone_number', 'title' => trans('title.driver_phone_number')],
							['data' => 'supervisor_name', 'title' => trans('title.Driver')],
							['data' => 'supervisor_phone', 'title' => trans('title.driver_phone')],
							['data' => 'created_at', 'title' => trans('title.Created_at')],
							['data' => 'action', 'title' => trans('title.Action'), 'sortable' => false, 'hasFilter' => false],
						    ]
					        ])
                            <div class="widget-footer">
                                <a href="{{ url('/school/bus/create') }}" class="btn btn-primary">
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
