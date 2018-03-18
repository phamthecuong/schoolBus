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
                    {!! (isset($payment) ? trans("payment.edit") : trans("payment.add")) !!}
            </span>
            </h1>
        </div>
    </div>
    @if (\Session::has('error'))
        <div class="row">
            <article class="col-lg-12">
                <div class="alert alert-danger fade in">
                    <button class="close" data-dismiss="alert">
                        ×
                    </button>
                    <i class="fa-fw fa fa-times"></i>
                    <strong>{{ trans('admin.Error') }}!</strong> {{ \Session::get('error') }}
                </div>
            </article>
        </div>
    @endif
    <section id="widget-grid">
        <div class="row">
            <article class="col-lg-6">
                @box_open(trans("payment.payment"))
                <div>
                    <div class="widget-body">

                        @if(isset($payment))
                            {!! Form::open(array('url' => "/school/payment/$payment->id", 'method' => "put")) !!}
                                @foreach($class as $item)
                                    {!! Form::lbSelect('class', $item['id'] , App\Models\School::getListClass(), trans('payment.class'),['class'=>'form-control class'] )!!} 
                                @endforeach
                                @if ($errors->has('class'))
                                    <div class="note note-error">{{ $errors->first('class') }}</div>
                                    <br>
                                @endif
                                
                                @foreach($class as $item)
                                    {!! Form::lbSelect('student', $payment->student_id, App\Models\Classes::getListStudent($item['id']), trans('payment.student'),['class'=>'form-control student'] )!!} 
                                @endforeach
                                  @if ($errors->has('student'))
                                    <div class="note note-error">{{ $errors->first('student') }}</div>
                                    <br>
                                @endif
                                
                                {!! Form::lbTextarea('description', $payment->description, trans('payment.description'),'') !!} 
                                <!-- {!! Form::lbCKEditor('description', $payment->description, trans('payment.description'), '') !!} 
                                @if ($errors->has('description'))
                                    <div class="note note-error">{{ $errors->first('description') }}</div>
                                     <br>
                                @endif -->
                               
                                {!! Form::lbText('amount', $payment->amount , trans('payment.amount'),'') !!}
                                {!! Form::lbText('month', $payment->month , trans('payment.month'),'') !!}
                                {!! Form::lbText('year', $payment->year , trans('payment.year'),'') !!}
                            
                        @else
                            {!! Form::open(array('url' => "/school/payment", 'method' => "post")) !!}
                                {!! Form::lbSelect('class', '', App\Models\School::getListClass(), trans('payment.class'),['class'=>'form-control class'] )!!} 
                                @if ($errors->has('class'))
                                    <div class="note note-error">{{ $errors->first('class') }}</div>
                                    <br>
                                @endif
                                
                                {!! Form::lbSelect('student', '', [['name' => 'choose student', 'value' => '' ]], trans('payment.student'),['class'=>'form-control student'] )!!} 
                                @if ($errors->has('student'))
                                    <div class="note note-error">{{ $errors->first('student') }}</div>
                                     <br>
                                @endif
                                
                                {!! Form::lbTextarea('description', '', trans('payment.description'),'') !!} 
                                <!-- {!! Form::lbCKEditor('description', '' , trans('payment.description'), 
                                '') !!} 
                                @if ($errors->has('description'))
                                    <div class="note note-error">{{ $errors->first('description') }}</div>
                                    <br>
                                @endif -->
                                
                                {!! Form::lbText('amount', '' , trans('payment.amount'),'') !!}
                                {!! Form::lbText('month', '' , trans('payment.month'),'') !!}
                                {!! Form::lbText('year', '' , trans('payment.year'),'') !!}
                        @endif
                        <div class="widget-footer">
                            @if(isset($payment))
                                {!! Form::lbSubmit(trans('payment.edit')) !!}
                            @else
                                {!! Form::lbSubmit(trans('payment.add')) !!}
                            @endif
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
                @box_close()
            </article>
        </div>
    </section>
@endsection

@push('script')
    <script type="text/javascript">
    $(document).ready(function(){

        $(document).on('change','.class',function(){
            // console.log("hmm its change");

            var class_id=$(this).val();
            // console.log(cat_id);
            var div=$(this).parents();

            var op=" ";

            $.ajax({
                type:'get',
                url:'{!!URL::to("school/payment/findstudent")!!}',
                data:{'id':class_id},
                success:function(data){
                    //console.log('success');

                    //console.log(data);

                    console.log(data.length);
                    op+='<option value="0" selected disabled>choose student</option>';
                    for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].student_id+'">'+data[i].full_name+'</option>';
                   // op+='<option value="'+data[i]['users'][0]['id']+'">'+data[i]['users'][0]['name']+'</option>';
                   }

                   div.find('.student').html(" ");
                   div.find('.student').append(op);
                },
                error:function(){

                }
            });
        });
    });
</script>
@endpush