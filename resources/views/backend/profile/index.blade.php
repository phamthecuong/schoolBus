@extends('app')
@section('sidemenu_profile')
active
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>
            {{ trans("general.profile") }}
            <span>>
                {{ trans("general.list") }}
        </span>
        </h1>
    </div>
    <div class="col-lg-12">
        @if (Session::has('flash_message'))
            <div class="alert alert-{!! Session::get('flash_level') !!}">
            <a class="close" data-dismiss="alert" href="#">×</a>
            <p>{!! Session::get('flash_message') !!}</p>
            </div>
        @endif      
    </div>
</div>
   
<section id="widget-grid" class="">
    <div class="row" style="margin-left: 3px; margin-right: 3px;">
        <article class="col-lg-6 col-md-6" >
            @box_open(trans("backend.profile_info"))
            <div>
                <div class="widget-body ">
                    {!! Form::open(array('url' => "school/profile/$user->id", 'method' => "put", 'files' => true)) !!}
                        @if (isset($user->profile->full_name))
                            {!! Form::lbText("name", @$user->profile->full_name, trans("title.Name")) !!}
                        @endif
                        {!! Form::lbText("email", @$user->email, trans("title.Email")) !!}

                        @if (in_array($user->profile_type, ['parent']))
                            {!! Form::lbText("phone_number", @$user->profile->phone_number, trans("auth.phone_number")) !!}
                        @endif
                <div class="widget-footer ">
                    {!! Form::lbSubmit(trans('profile.edit_submit')) !!}
                </div>
                    {!! Form::close() !!}
            </div>
            @box_close
        </article>

        <article class="col-lg-6 col-md-6" >
            @box_open(trans("profile.change_password"))
            <div>
                <div class="widget-body ">
                    {!! Form::open(array('url' => "school/profile/$user->id/change_password", 'method' => "post", 'files' => true)) !!}
    				@include ('custom.password',['name' => 'old_password', 'title' => trans('profile.old_password')])
    				@include ('custom.password',['name' => 'new_password', 'title' => trans('profile.new_password')])
    				@include ('custom.password',['name' => 'confirm_password', 'title' => trans('profile.confirm_password')])
                <div class="widget-footer ">
                    {!! Form::lbSubmit(trans('profile.change_password_submit')) !!}
                </div>
                    {!! Form::close() !!}
            </div>
            @box_close
        </article>
    </div>
</section>

@endsection
