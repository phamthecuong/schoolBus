@extends('app')
@section('sidemenu_class')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("title.Class"))
        <div>
            <div class="widget-body ">
                @if (isset($class))
                {!! Form::open(array('url' => "school/class/$class->id", 'method' => "put", 'files' => true)) !!}
                @else
                {!! Form::open(array('url' => "school/class", 'method' => "post", 'files' => true)) !!}
                @endif

                {!! Form::lbText("name", @$class->name, trans("title.Name")) !!}
                {!! Form::lbSelect('teacher', @$class->teacher_id, App\Models\School::getListTeacher(), trans('title.Teacher') )!!} 
                @if ($errors->has('teacher'))
                    <div class="note note-error">{{ $errors->first('teacher') }}</div>
                    <br>
                @endif
                {!! Form::lbText("year", @$class->year, trans("title.year")) !!}
                <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.add_new_class')) !!}
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
