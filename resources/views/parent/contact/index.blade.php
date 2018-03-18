@extends('app')

@section('sidemenu_contact_parent')
    active
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-edit fa-fw "></i>
                {{ trans("contact.contact") }}
            </h1>
        </div>
    </div>
   
    <div class="row">
        <div class="col-lg-12">
                @if (Session::has('flash_message'))
                    <div class="alert alert-{!! Session::get('flash_level') !!}">
                        {!! Session::get('flash_message') !!}
                    </div>
                @endif      
        </div>
    </div>
  
    <section id="widget-grid">
        <div class="row">
            <article class="col-lg-6">
                @box_open(trans("contact.contact"))
                <div>
                    <div class="widget-body">

                        {!! Form::open(array('url' => 'parent/contact', 'method' => 'post')) !!}
                            {!! Form::lbText('title', '' , trans('general.title'),'') !!}
                            {!! Form::lbTextarea('message', '' , trans('general.description'),'') !!} 
                            {!! Form::lbSelect('school', '', App\Models\School::getListSchoolByParent(), trans('contact.school'),['class'=>'form-control class'] )!!} 
                            @if ($errors->has('school'))
                                <div class="note note-error">{{ $errors->first('school') }}</div>
                                <br>
                            @endif
                        <div class="widget-footer">
                            {!! Form::lbSubmit(trans('contact.add')) !!}
                            
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
@push('script')
<script>
    $("div.alert").delay(10000).slideUp();
</script>
@endpush
@endpush