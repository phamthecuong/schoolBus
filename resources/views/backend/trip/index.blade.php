@extends('app')

@section('sidemenu_trip')
    active
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-map-marker fa-fw "></i>
                {{ trans("sidemenu.trip") }}
                <span>>
                    {{ trans("general.list") }}
            </span>
            </h1>
        </div>
    </div>

    <section id="widget-grid" class="">
        <div class="row">
            <article class="col-lg-12">
                @include('custom.alert')
                @box_open(trans("sidemenu.trip"))
                <div>
                    <div class="widget-body">
                        @include("layouts.elements.table", [
                        'url' => '/ajax/trip',
                        'columns' => [
                            ['data' => 'name', 'title' => trans('general.name')],
                            ['data' => 'buses.name', 'name' => 'buses.name', 'title' => 'Số hiệu xe'],
                            ['data' => 'types.name', 'title' => trans('general.type')],
                            ['data' => 'driver_name', 'title' => trans('general.driver'), 'sortable' => false, 'hasFilter' => false],
                            ['data' => 'arrive_date', 'title' => trans('general.arrive_date')],
                            ['data' => 'action', 'title' => trans('general.action'), 'sortable' => false, 'hasFilter' => false],
                        ]
                        ])
                        <div class="widget-footer">
                            <a href="{{ url('/school/trip/create') }}" class="btn btn-primary">
                                {{ trans('general.add_new_trip')}}
                            </a>
                        </div>
                    </div>
                </div>

                @box_close()
            </article>
        </div>
    </section>
@endsection
