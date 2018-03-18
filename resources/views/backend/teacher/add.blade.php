@extends('app')
@section('sidemenu_teacher')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("teacher.teacher"))
        <div>
            <div class="widget-body ">
            @if (isset($teacher))
                {!! Form::open(array('url' => "school/teacher/$teacher->id", 'method' => "put", 'files' => true)) !!}
            @else
                {!! Form::open(array('url' => "school/teacher", 'method' => "post", 'files' => true)) !!}
            @endif

                {!! Form::lbText("full_name", @$teacher->full_name, trans("teacher.Name")) !!}
                {!! Form::lbText("email", @$user->email, trans("teacher.Email")) !!}
                {!! Form::lbText("address", @$teacher->address, trans("teacher.Address")) !!}
                {!! Form::lbText("birthday", @$teacher->birthday, trans("teacher.Birthday")) !!}
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.add_new_teacher')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
