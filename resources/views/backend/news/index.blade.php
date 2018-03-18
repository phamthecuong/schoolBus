@extends('app')

@if(isset($school_admin))
    @section('sidemenu_school_news')
        active
    @endsection
@else
    @section('sidemenu_new')
        active
    @endsection
@endif

@section('content')
    
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-edit fa-fw "></i>
                    {{ trans("general.news") }}
                    <span>>
                        {{ trans("general.list") }}
                </span>
                </h1>
            </div>
        </div>

        <section id="widget-grid" class="">

            <div class="row">
                <div class="col-lg-12">
                    @if (Session::has('flash_message'))
                        <div class="alert alert-{!! Session::get('flash_level') !!}">
                            {!! Session::get('flash_message') !!}
                        </div>
                    @endif      
                </div>
                <article class="col-lg-12">
                    @box_open(trans("general.news"))
                        <div>
                            <div class="widget-body">
                                <div class="table-responsive">
                                    <table id="MyDataTable" class="table table-bordered table-hover">
                                        <thead>
                                        <tr >
                                            <th style="text-align: center">{{ trans("general.no") }}</th>
                                            <th style="text-align: center">{{ trans("general.title") }}</th>
                                            <th style="text-align: center">{{ trans("general.short_description") }}</th>
                                            <th style="text-align: center">{{ trans("general.description") }}</th>
                                            <th style="text-align: center">{{ trans("general.created_at") }}</th>
                                            <th style="text-align: center">{{ trans("general.action") }}</th>
                                        </tr>
                                        </thead>
                                        @if(isset($news))
                                        <tbody>
                                            @foreach ($news as $item) 

                                            <tr style="text-align: center">
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->short_description }}</td>
                                                <td>{!! $item->description !!}</td>
                                                <td>{{ $item->created_at }}</td>

                                                <td>
                                                    @if(isset($school_admin))
                                                        <a href="{{ url("/school/news/$item->id/edit") }}" class="btn btn-info btn-xs">{{ trans("general.edit") }}</a>
                                                        {!! Form::lbButton(url("/school/news/$item->id"), "DELETE",trans("general.delete"), ["class" => "btn btn-xs btn-danger", "onclick" => "return confirm('".trans('general.delete_cfm')."')"]) !!}
                                                    @else 
                                                        <a href="{{ url("/admin/news/$item->id/edit") }}" class="btn btn-info btn-xs">{{ trans("general.edit") }}</a>
                                                        {!! Form::lbButton(url("/admin/news/$item->id"), "DELETE",trans("general.delete"), ["class" => "btn btn-xs btn-danger", "onclick" => "return confirm('".trans('general.delete_cfm')."')"]) !!}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                         @endif
                                    </table>
                                </div>

                                <div class="widget-footer">
                                    @if(isset($school_admin))
                                    <a href="{{ url('/school/news/create') }}" class="btn btn-primary">
                                        {{ trans('general.add')}}
                                    </a>
                                    @else
                                    <a href="{{ url('/admin/news/create') }}" class="btn btn-primary">
                                        {{ trans('general.add')}}
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

@push('script')
<script>
    $("div.alert").delay(10000).slideUp();

</script>
@endpush