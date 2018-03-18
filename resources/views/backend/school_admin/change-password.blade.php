@extends('app')
@section('sidemenu_school')
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
        @box_open(trans("school_admin.school_admin"))
        <div>
            <div class="widget-body ">
                {!! Form::open(array('url' => "admin/school/$school_id/admin/$admin->id/change_password", 'method' => "post", 'files' => true)) !!}
                    @include ('custom.password',['name' => 'new_password', 'title' => trans('school_admin.new_password')])
                    @include ('custom.password',['name' => 'confirm_password', 'title' => trans('school_admin.confirm_password')])
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('school_admin.change_password')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
