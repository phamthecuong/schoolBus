<!DOCTYPE html>
<html lang="en-us" id="extr-page">
    <head>
        @include("layouts.partials.htmlheader")
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    </head>
    
    <body class="animated fadeInDown">
        <header id="header">
            <div id="logo-group">
                <span id="logo"><b>School Bus</b></span>
            </div>
            <span id="extr-page-header-space"> <a href="{{ url('register')}}" class="btn btn-danger">{!! trans('auth.register_as_parent') !!}</a> </span>
        </header>

        <div id="main" role="main">
            <!-- MAIN CONTENT -->
            <div id="content" class="container">
                <div class="row">
                     <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">{!! trans('auth.reset_password') !!}</div>
                            <div class="panel-body">
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form id="send_email" class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">{!! trans('auth.email') !!}</label>

                                        <div class="col-md-6">
                                            <input id="email" class="form-control" name="email" value="{{ old('email') }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button id="btn_send" type="submit" class="btn btn-primary">
                                                {!! trans('auth.send_password_reset') !!}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    $('form').submit(function() {
      $(this).find("button[type='submit']").prop('disabled',true);
    }); 
</script>