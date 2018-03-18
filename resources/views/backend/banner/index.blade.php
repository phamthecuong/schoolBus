@extends('app')

@section('sidemenu_banner')
    active
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-edit fa-fw "></i>
                {{ trans("sidemenu.banner") }}
                <span>>
                    {{ trans("general.list") }}
            </span>
            </h1>
        </div>
    </div>
    @if (\Session::has('warning'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ \Session::get('warning') }}
                </div>
            </article>
        </div>
    @endif
    @if (\Session::has('update'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    {{ \Session::get('update') }}
                </div>
            </article>
        </div>
    @endif
    <section id="widget-grid" class="">
        <div class="row">
            <article class="col-lg-12">
                @box_open(trans("sidemenu.banner"))
                <div>
                    <div class="widget-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th style="text-align: center">{{ trans("general.image") }}</th>
                                    <th style="text-align: center">{{ trans("general.url") }}</th>
                                    <th style="text-align: center">{{ trans("general.created_at") }}</th>
                                    <th style="text-align: center">{{ trans("general.updated_at") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($banner))
                                    @foreach ($banner as $ban)
                                        <tr style="text-align: center">
                                            <td>
                                                <img src="{!! url('/lbmedia/'.$ban->image_id) !!}" width="200px">
                                            </td>
                                            <td>{{ $ban->url }}</td>
                                            <td>{{ $ban->created_at }}</td>
                                            <td>{{ $ban->updated_at }}</td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="widget-footer">
                            @if(!isset($banner[0]))
                                <a href="{{ url('/admin/banner/create') }}" class="btn btn-primary">
                                    {{ trans('general.add')}}
                                </a>
                            @else
                                <a href="{{ url('/admin/banner/'.$banner[0]->id.'/edit') }}" class="btn btn-primary">
                                    {{ trans('general.edit')}}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @box_close()
            </article>
        </div>
    </section>

@endsection
