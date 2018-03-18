<!DOCTYPE html>
<html lang="en-us" id="extr-page">
    <head>
        @include("layouts.partials.htmlheader")
    </head>
    <body class="animated fadeInDown">
        <header id="header">

            <div id="logo-group" style="width:90px!IMPORTANT">
                <span id="logo"><b>School Bus</b></span>
            </div>
            <span id="extr-page-header-space"> <a href="{{ url('register')}}" class="btn btn-danger">{{trans('auth.register_as_parent')}}</a> </span>
        </header>

        <div id="main" role="main">
            <!-- MAIN CONTENT -->
            <div id="content" class="container">
                <div class="row">
                     <div class=" col-xs-offset-1 col-lg-offset-4 col-sm-offset-2 col-md-offset-4 col-xs-10 col-sm-8 col-md-5 col-lg-4">
                        <div class="well no-padding">
                            <form method="POST" action="{{ url('/login') }}" id="login-form" class="smart-form client-form">
                                <header>
                                    {{trans('auth.login')}}
                                </header>

                                <fieldset>
                                    {{ csrf_field() }}
                                    <section>
                                        <label class="label">{{trans('auth.email_or_phone')}}</label>
                                        <label class="input"> <i class="icon-append fa fa-user"></i>
                                            <input type="text" name="email" value="{{ old('email') }}" required autofocus>
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> {{trans('auth.email_hint')}}</b></label>
                                            @if (count($errors) > 0)
                                                 <span class="help-block">
                                                    <strong>{{ $errors->first() }}</strong>
                                                </span>
                                            @endif  

                                    </section>

                                    <section>
                                        <label class="label">{{trans('auth.password')}}</label>
                                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                                            <input type="password" name="password" required>
                                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> {{ trans('auth.login_box_password_hint')}}</b> </label>
                                            <!-- </br>
                                            <a href="{{url('/password/reset')}}">{{trans('auth.forgot_password')}}</a> -->
                                            
                                    </section>

                                    <section>
                                        <label class="checkbox">
                                            <input type="checkbox" name="remember" checked="">
                                            <i></i>{{trans('auth.remember_me')}}</label>
                                    </section>
                                </fieldset>
                                <footer>
                                    <button type="submit" class="btn btn-primary" >
                                        {{trans('auth.login_submit')}} 
                                    </button>
                                </footer>
                            </form>

                        </div>
                        <!-- <h5 class="text-center">{{trans('auth.sign_in_as_parent_with')}}</h5>
                        <ul class="list-inline text-center">
                            <li>
                                <a  class="btn btn-primary btn-circle" href="{{url('/redirect')}}"><i class="fa fa-facebook"></i></a>
                            </li>
                        </ul> -->
                    </div>
                </div>
            </div>

        </div>

        <!--================================================== -->  

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="/sa/js/plugin/pace/pace.min.js"></script>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script> if (!window.jQuery) { document.write('<script src="/sa/js/libs/jquery-2.1.1.min.js"><\/script>');} </script>

        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script> if (!window.jQuery.ui) { document.write('<script src="/sa/js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="/sa/js/app.config.js"></script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events         
        <script src="/sa/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

        <!-- BOOTSTRAP JS -->       
        <script src="/sa/js/bootstrap/bootstrap.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="/sa/js/plugin/jquery-validate/jquery.validate.min.js"></script>
        
        <!-- JQUERY MASKED INPUT -->
        <script src="/sa/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        
        <!--[if IE 8]>
            
            <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
            
        <![endif]-->

        <!-- MAIN APP JS FILE -->
        <script src="/sa/js/app.min.js"></script>

        <script type="text/javascript">
            runAllForms();

            $(function() {
                // Validation
                $("#login-form").validate({
                    // Rules for form validation
                    rules: {
                        email: {
                            required : true,
                            // email : true
                        },
                        password : {
                            required : true,
                            minlength : 3,
                            maxlength : 20
                        }
                    },

                    // Messages for form validation
                    messages : {
                        email : {
                            required : '{{trans("auth.please_enter_email")}}',
                            // email : '{{trans("auth.please_enter_valid_email")}}'
                        },
                        password : {
                            required : '{{trans("auth.please_enter_password")}}'
                        }
                    },

                    // Do not change code below
                    errorPlacement : function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
        </script>
    </body>
</html>