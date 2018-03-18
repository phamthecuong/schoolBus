@extends('app')
@section('sidemenu_admin')
active
@endsection

@section('content')

<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("account.account"))
        <div>
            <div class="widget-body ">
            @if (isset($user))
                {!! Form::open(array('url' => "admin/account/$user->id", 'method' => "put", 'files' => true)) !!}
            @else
                {!! Form::open(array('url' => "admin/account", 'method' => "post", 'files' => true)) !!}
            @endif
                {!! Form::lbText("email", @$user->email, trans("account.Email")) !!}
                @if(isset($user))

                @else
                @include ('custom.password',['name' => 'password', 'title' => trans('account.password')])
                @include ('custom.password',['name' => 'confirm_password', 'title' => trans('account.confirm_password')])
                @endif
            <div class="widget-footer ">
                @if(isset($user))
                {!! Form::lbSubmit(trans('general.edit_account')) !!}
                @else
                {!! Form::lbSubmit(trans('general.add_new_account')) !!}
                @endif
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection
