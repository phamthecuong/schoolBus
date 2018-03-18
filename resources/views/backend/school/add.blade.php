@extends('app')
@section('sidemenu_school')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("school.school"))
        <div>
            <div class="widget-body ">
            @if (isset($school))
                {!! Form::open(array('url' => "admin/school/$school->id", 'method' => "put", 'files' => true)) !!}
            @else
                {!! Form::open(array('url' => "admin/school", 'method' => "post", 'files' => true)) !!}
            @endif
                {!! Form::lbText("name", @$school->name, trans("school.name")) !!}
                {!! Form::lbText("phone", @$school->phone_numbers, trans("school.phone")) !!}
                {!! Form::lbText("address", @$school->address, trans("school.address")) !!}
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
