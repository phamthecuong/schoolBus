<!DOCTYPE html>
<html lang="en-us" id="extr-page">
    <head>
        @include("layouts.partials.htmlheader")
    </head>
    <body class="animated fadeInDown">
        <header id="header">
            <div id="logo-group">
                <span id="logo"><b>School Bus</b></span>
            </div>
            <span id="extr-page-header-space"> <a href="{{ url('login')}}" class="btn btn-danger">{{trans('auth.login')}}</a> </span>
        </header>

        <div id="main" role="main">
            <!-- MAIN CONTENT -->
            <div id="content" class="container">
                <div class="row">
                    <div class="col-xs-offset-1 col-lg-offset-4 col-sm-offset-2 col-md-offset-4 col-xs-10 col-sm-8 col-md-5 col-lg-4">
                    <!-- <div class="col-md-4 col-md-offset-4"> -->
                        <div class="panel panel-default">
                            
                            <div class="panel-heading">{{trans('auth.register')}}</div>
                            <div class="panel-body">
                               
                                <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                    {{ csrf_field() }}

                                    <input id="facebook_id" type="hidden" name="facebook_id" value="{!! old('facebook_id', Session::has('facebook_id') ? Session::get('facebook_id') : NULL)!!}""/>
                                    
                                    <input id="facebook_avatar" type="hidden" name="facebook_avatar" value="{!! old('facebook_avatar', Session::has('facebook_avatar') ? Session::get('facebook_avatar') : NULL)!!}""/>
                                    
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <div class="col-md-10 col-md-offset-1">
                                            <input id="name" type="text" class="form-control" name="name" value="{!! old('name', Session::has('facebook_name') ? Session::get('facebook_name') : NULL)!!}" placeholder ="{!! trans('auth.name') !!}"  autofocus/>

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <div class="col-md-10 col-md-offset-1">
                                            <input id="email" class="form-control" name="email" value="{!!old('email', Session::has('facebook_email') ? Session::get('facebook_email') : NULL)!!}" placeholder ="{!! trans('auth.email') !!}" />

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                        <div class="col-md-10 col-md-offset-1">
                                            <input id="phone_number" class="form-control" name="phone_number" value="{{ old('phone_number') }}" placeholder ="{!! trans('auth.phone_number') !!}" />

                                            @if ($errors->has('phone_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('student_code') ? ' has-error' : '' }}">
                                        <div class="col-md-10 col-md-offset-1">
                                            <input id="student_code" type="text" class="form-control" name="student_code" value="{{ old('student_code') }}" placeholder ="{!! trans('auth.student_code') !!}"  autofocus/>

                                            @if ($errors->has('student_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('student_code') }}</strong>
                                                </span>
                                            @endif
                                             
                                            @if (Session::has('flash_message'))
                                                 <span class="help-block">
                                                    <strong>{!! Session::get('flash_message') !!}</strong>
                                                </span>
                                            @endif      
                                
                                        </div>
                                    </div>


                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="col-md-10 col-md-offset-1">
                                            <input id="password" type="password" class="form-control" name="password" placeholder ="{!! trans('auth.password') !!}" >
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-10 col-md-offset-1">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder ="{!! trans('auth.confirm_password') !!}" >
                                            <div class="note">
                                                {{ trans('passwords.password') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-10 col-md-offset-1" >
                                            <button type="submit" class="btn btn-primary" style="float:right;">
                                                {{trans('auth.register')}}
                                            </button>
                                        </div>
                                    </div>
                                    <!-- <footer>
                                    <button type="submit" class="btn btn-primary" >
                                        {{trans('auth.register')}}
                                    </button>
                                </footer> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

