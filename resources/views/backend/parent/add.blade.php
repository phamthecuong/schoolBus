@extends('app')
@section('sidemenu_parent')
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
        @box_open(trans("parent.parent"))
        <div>
            <div class="widget-body ">
                @if (isset($admin))
                    {!! Form::open(array('url' => "school/parent/$admin->id", 'method' => "put", 'files' => true)) !!}
                @else
                    {!! Form::open(array('url' => "school/parent ", 'method' => "post", 'files' => true)) !!}
                @endif

                    {!! Form::lbText("full_name", @$admin->full_name, trans("title.full_name")) !!}
                    {!! Form::lbText("email", @$user->email, trans("title.Email")) !!}
                    {!! Form::lbText("address", @$admin->address, trans("title.Address")) !!}
                    {!! Form::lbText("phone", @$admin->phone_number, trans("title.Phone")) !!}
                    {!! Form::lbText("contact_email", @$admin->contact_email, trans("title.Contact_email")) !!}
                    @if (isset($admin->avatar_id))    
                         @include('custom.file_input', ['name' => 'image', 'title' => trans("title.image") , 'image_url' => url("/lbmediacenter/$admin->avatar_id")])
                    @else
                        @include('custom.file_input', ['name' => 'image', 'title' => trans("title.image") , 'image_url' => ''])
                    @endif
                    @if(!isset($admin))
                        {!! Form::lbText("student_code", null, trans("title.student_code")) !!}
                        @include ('custom.password',['name' => 'password', 'title' => trans('title.password')])
                        @include ('custom.password',['name' => 'password_confirmation', 'title' => trans('title.confirm_password')])
                    @endif
                <div class="widget-footer ">
                    @if(isset($admin))
                    {!! Form::lbSubmit(trans('general.edit_parent')) !!}
                    @else
                    {!! Form::lbSubmit(trans('general.add_new_parent')) !!}
                    @endif
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @box_close
     </article>
</div>
</section>

@endsection