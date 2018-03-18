@extends('app')

@section('sidemenu_bus')
    active
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-edit fa-fw "></i>
                {{ trans("sidemenu.bus") }}
                <span>>
                    {!! (isset($bus) ? trans("general.edit") : trans("general.add")) !!}
            </span>
            </h1>
        </div>
    </div>
    
    <div class="col-lg-12">
        @if (Session::has('flash_message'))
            <div class="alert alert-{!! Session::get('flash_level') !!}">
                {!! Session::get('flash_message') !!}
            </div>
        @endif      
    </div>
    <section id="widget-grid">
        <div class="row">
            <article class="col-lg-6">
                @box_open(trans("sidemenu.bus"))
                <div>
                    <div class="widget-body">
                        @if(isset($bus))
                            {!! Form::open(array('url' => "/school/bus/$bus->id", 'method' => "patch", 'files' => true)) !!}
                        @else
                            {!! Form::open(array('url' => "/school/bus", 'method' => "post", 'files' => true)) !!}
                        @endif
                            {!! Form::lbText('name', @$bus->name, trans('general.name'),'') !!}
                        <div class="widget-footer">
                            @if(isset($bus))
                                {!! Form::lbSubmit(trans('general.edit')) !!}
                            @else
                                {!! Form::lbSubmit(trans('general.add')) !!}
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @box_close()
            </article>
        </div>
    </section>
@endsection
