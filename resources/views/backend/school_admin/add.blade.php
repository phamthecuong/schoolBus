@extends('app')

@section('sidemenu_school')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("title.School_admin"))
        <div>
            <div class="widget-body ">
            @if (isset($admin))
                {!! Form::open(array('url' => "admin/school/$school_id/admin/$admin->id", 'method' => "put", 'files' => true)) !!}
            @else
                {!! Form::open(array('url' => "admin/school/$school_id/admin", 'method' => "post", 'files' => true)) !!}
            @endif

                {!! Form::lbText("name", @$admin->full_name, trans("title.Name")) !!}
                {!! Form::lbText("email", @$user->email, trans("title.Email")) !!}
            @if(!isset($admin))
                @include ('custom.password',['name' => 'password', 'title' => trans('title.password')])
                @include ('custom.password',['name' => 'confirm_password', 'title' => trans('title.confirm_password')])
            @endif
            
            <div class="widget-footer ">
                {!! Form::lbSubmit() !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
