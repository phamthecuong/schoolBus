@extends('app')

@section('sidemenu_payments')
active
@endsection

@section('content')
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-edit fa-fw "></i>
                    {{ trans("payment.payment") }}
                    <span>>
                        {{ trans("payment.list") }}
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
                    @box_open(trans("payment.payment"))
                        <div>
                            <div class="widget-body">
                                <div class="table-responsive">
                                    <table id="MyDataTable" class="table table-bordered table-hover">
                                        <thead>
                                        <tr >
                                            <th style="text-align: center">{{ trans("payment.no") }}</th>
                                            <th style="text-align: center">{{ trans("payment.name") }}</th>
                                            <th style="text-align: center">{{ trans("payment.description") }}</th>
                                            <th style="text-align: center">{{ trans("payment.amount") }}</th>
                                            <th style="text-align: center">{{ trans("payment.month") }}</th>
                                            <th style="text-align: center">{{ trans("payment.year") }}</th>
                                            <th style="text-align: center">{{ trans("payment.payment_date") }}</th>
                                            <th style="text-align: center">{{ trans("payment.action") }}</th>
                                        </tr>
                                        </thead>
                                        @if(isset($data))  
                                        <tbody>
                                         
                                            @foreach ($data as $item) 
                                      
                                            <tr style="text-align: center">
                                                <td>{{ $item['id'] }}</td>
                                                <td>{!! $item['name'] !!}</td>
                                                <td>{!! $item['description'] !!}</td>
                                                <td>{!! $item['amount'] !!}</td>
                                                <td>{!! $item['month'] !!}</td>
                                                <td>{!! $item['year'] !!}</td>
                                                <td>{!! $item['payment_date'] !!}</td>
                                                <td>
                                                    @if (!isset($item['payment_date']))
                                                    {!! Form::lbButton(url("/school/payment/".$item['id']."/pay"), "GET",trans("payment.payment"), ["class" => "btn btn-xs btn-success", "onclick" => "return confirm('Bạn có muốn thanh toán không?')"]) !!}
                                                    
                                                    @endif
                                                    <a href="{{ url("/school/payment/".$item['id']."/edit") }}" class="btn btn-info btn-xs">{{ trans("general.edit") }}</a>
                                                    {!! Form::lbButton(url("/school/payment/".$item['id']), "DELETE",trans("payment.delete"), ["class" => "btn btn-xs btn-danger", "onclick" => "return confirm('Bạn có muốn xóa không?')"]) !!}
                                                 
                                                </td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                        @endif
                                    </table>
                                </div>
                                <div class="widget-footer">
                                    <a href="{{ url('/school/payment/create') }}" class="btn btn-primary">
                                        {{ trans('payment.add')}}
                                    </a>
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