@extends('app')
@section('sidemenu_driver')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
    <div class="col-lg-12">
        @if (Session::has('flash_message'))
            <div class="alert alert-{!! Session::get('flash_level') !!}">
                {!! Session::get('flash_message') !!}
            </div>
        @endif      
    </div>
     <article class="col-lg-8 col-md-6">
        @box_open(trans("driver.driver"))
        <div>
            <div class="widget-body ">
                 {!! Form::open(array('url' => "school/driver/$driver->id/change_password", 'method' => "post", 'files' => true)) !!}
                    @include ('custom.password',['name' => 'old_password','title' => trans('driver.old_password')])
                    @include ('custom.password',['name' => 'new_password', 'title' => trans('driver.new_password')])
                    @include ('custom.password',['name' => 'confirm_password', 'title' => trans('driver.confirm_password')])
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('driver.change_password')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
