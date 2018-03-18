@extends('app')

@section('sideMenu_trip')
    active
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-edit fa-fw "></i>
                {{ trans("sidemenu.banner") }}
                <span>>
                    {!! (isset($banner) ? trans("general.edit") : trans("general.add")) !!}
            </span>
            </h1>
        </div>
    </div>
    @if (\Session::has('error'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-danger fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    <strong>{{ trans('admin.Error') }}!</strong> {{ \Session::get('error') }}
                </div>
            </article>
        </div>
    @endif
    <section id="widget-grid">
        <div class="row">
            <article class="col-lg-6">
                @box_open(trans("sidemenu.banner"))
                <div>
                    <div class="widget-body">
                        @if(isset($banner))
                            {!! Form::open(array('url' => "/admin/banner/$banner->id", 'method' => "patch", 'files' => true)) !!}
                        @else
                            {!! Form::open(array('url' => "/admin/banner", 'method' => "post", 'files' => true)) !!}
                        @endif
                        @if(isset($banner))
                            @include('custom.file_input',
                            ['name' => 'banner',
                             'title' => trans('general.image'),
                             'image_url' => url('/lbmedia/'.$banner->image_id)
                             ])
                        @else
                            @include('custom.file_input',
                            ['name' => 'banner',
                             'title' => trans('general.image'),
                            ])
                        @endif
                            {!! Form::lbText('url', @$banner->url, trans('general.url'), '') !!}
                        <div class="widget-footer">
                            @if(isset($banner))
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
