@extends('app')
        
@section('sidemenu_add_student')
    active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>
            {{ trans("general.parentstudent") }}
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
     <article class="col-lg-6 col-md-6">
        @box_open(trans("title.Parent_Student"))
        <div>
            <div class="widget-body ">
                    {!! Form::open(array('url' => "school/parent/student/add", 'method' => "post", 'files' => true)) !!}
                    {!! Form::lbText("code", '', trans("title.student_code")) !!}
                <div class="widget-footer ">
                    {!! Form::lbSubmit() !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
