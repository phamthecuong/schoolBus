@extends('app')
@section('sidemenu_student')
active
@endsection
@section('content')

<section id="widget-grid" class="">
<div class="row">
    <div class="col-lg-8 col-md-6">
        @if (Session::has('flash_message'))
            <div class="alert alert-{!! Session::get('flash_level') !!}">
                {!! Session::get('flash_message') !!}
            </div>
        @endif      
    </div>
     <article class="col-lg-8 col-md-6">
        @box_open(trans("title.Student"))
        <div>
            <div class="widget-body ">
            @if (isset($student))
                {!! Form::open(array('url' => "school/student/$student->id", 'method' => "put", 'files' => true)) !!}
            @else
                {!! Form::open(array('url' => "school/student", 'method' => "post", 'files' => true)) !!}
            @endif
                {!! Form::lbText("full_name", @$student->full_name, trans("student.full_name")) !!}
                @if (isset($student->avatar_id))
                    @include('custom.file_input', ['name' => 'image', 'title' => trans('student.image'), 'image_url' => url("/lbmediacenter/$student->avatar_id")])
                @else
                    @include('custom.file_input', ['name' => 'image', 'title' =>trans('student.image')])
                @endif
                {!! Form::lbSelect('gender', @$student->gender, App\Models\User::allToOption(), trans('student.gender') )!!} 
                {!! Form::lbSelect('departure_1', @$student->departure->id, App\Models\Departure::listDeparturce(), trans('student.departure_1') )!!} 
                {!! Form::lbSelect('departure_2', @$student->departure2->id, App\Models\Departure::listDeparturce(), trans('student.departure_2') )!!} 
                {!! Form::lbText("address", @$student->address, trans("student.address")) !!}
                {!! Form::lbText("code", @$student->code, trans("student.code")) !!}
                {!! Form::lbText("birthday", @$student->birthday, trans("student.birthday")) !!}
                {!! Form::lbSelect('class', @$student->classes[0]->id, App\Models\Classes::allToOption(), trans('student.class') )!!} 
                {{-- {!! Form::lbSelect('parent', @$student->parents[0]->id, App\Models\School::getListParent(), trans('student.Parent') )!!}  --}}
            @if (isset($student))
                {!! Form::lbSelect2multi('parent[]', @$student->parents->pluck('id')->toArray(), App\Models\School::getListParent(), trans('student.Parent')) !!} 
            @else
                {!! Form::lbSelect2multi('parent[]', null, App\Models\School::getListParent(), trans('student.Parent')) !!} 
            @endif  
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.submit')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection

@push('css')
<style type="text/css">
    .select2-container .selection .select2-selection {
        background: rgba(255,255,255,.2);
        border-color: rgba(255,255,255,.4);
    }

</style>
@endpush