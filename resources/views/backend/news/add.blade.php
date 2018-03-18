@extends('app')

@if(isset($user))
    @section('sidemenu_school_news')
        active
    @endsection
@else
    @section('sidemenu_new')
        active
    @endsection
@endif

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-edit fa-fw "></i>
                {{ trans("general.news") }}
                <span>>
                    {!! (isset($news) ? trans("general.edit") : trans("general.add")) !!}
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
                @box_open(trans("general.news"))
                <div>
                    <div class="widget-body">

                        @if(isset($news))
                            {!! Form::open(array('url' => (isset($user)?"/school/news/$news->id":"admin/news/$news->id"), 'method' => "put", 'files' => true)) !!}
                                {!! Form::lbText('title', $news->title, trans('general.title'),'') !!}
                                {!! Form::lbText('short_description', $news->short_description, trans('general.short_description'),'') !!}
                                {!! Form::lbCKEditor('description', $news->description, trans('general.description'), 
                                '') !!} 
                                @if ($errors->has('description'))
                                    <div class="note note-error">{{ $errors->first('description') }}</div>
                                @endif
                                
                                <div class="form-group">
                                    <label>{!! trans('general.cur_img') !!}</label><br>
                                    <img src="{!! url('lbmedia/'.$news->image_id) !!}" class="img_current" style="width:100px;height:100px"/>
                                </div>

                                @include('custom.file_input', ['name' => 'image', 'title' => trans('general.image'), 'image_url' => ''])
                        @else
                            {!! Form::open(array('url' => (isset($user)?"/school/news":"admin/news"), 'method' => "post", 'files' => true)) !!}
                                {!! Form::lbText('title', '' , trans('general.title'),'') !!}
                                {!! Form::lbText('short_description', '' , trans('general.short_description'),'') !!}
 
                                {!! Form::lbCKEditor('description','' , trans('general.description'), 
                                '') !!} 
                                @if ($errors->has('description'))
                                    <div class="note note-error">{{ $errors->first('description') }}</div>
                                @endif
                                @include('custom.file_input', ['name' => 'image', 'title' => trans('general.image') , 'image_url' => ''])
                        @endif
                        <div class="widget-footer">
                            @if(isset($news))
                                {!! Form::lbSubmit(trans('news.edit')) !!}
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

@push('css')
@endpush

@push('script')
@endpush