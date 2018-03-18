@extends('app')
@section('sidemenu_driver')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("title.Driver"))
        <div>
            <div class="widget-body ">
            @if (isset($driver))
                {!! Form::open(array('url' => "school/driver/$driver->id", 'method' => "put", 'files' => true)) !!}
            @else
                {!! Form::open(array('url' => "school/driver", 'method' => "post", 'files' => true)) !!}
            @endif
    
                {!! Form::lbText("full_name", @$driver->full_name, trans("title.full_name")) !!}
                {!! Form::lbText("email", @$user->email, trans("title.Email")) !!}
                {!! Form::lbText("phone", @$driver->phone_number, trans("title.Phone")) !!}
            @if (isset($driver->avatar_id))    
                 @include('custom.file_input', ['name' => 'image', 'title' => trans("title.image"), 'image_url' => url("/lbmediacenter/$driver->avatar_id")])
            @else
                @include('custom.file_input', ['name' => 'image', 'title' => trans("title.image"), 'image_url' => ''])
            @endif

            @if (!isset($driver))
                @include ('custom.password',['name' => 'password', 'title' => trans('title.password')])
                @include ('custom.password',['name' => 'confirm_password', 'title' => trans('title.confirm_password')])
            @endif
               
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.add_new_driver')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
