@extends('app')
@section('sidemenu_trip')
active
@endsection
@push('css')
    <style>
      #wizard-1 .help-block{
          color: red;
      }

    </style>
  
@endpush
@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-edit fa-fw "></i>
            {{ trans("sidemenu.trip") }}
            <span>>
                {{ trans("general.add") }}
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">

  <!-- row -->
  <div class="row">

    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-12">

      <!-- Widget ID (each widget will need unique ID)-->
      <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
      
        <header>
          <span class="widget-icon"> <i class="fa fa-check"></i> </span>
          <h2>{{trans('trip.Trip')}}</h2>
        </header>
        <!-- widget div-->
        <div>
          <!-- widget edit box -->
          <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
          </div>
          <!-- end widget edit box -->
          <!-- widget content -->
          <div class="widget-body">
            <div class="row">
              <form id="wizard-1" novalidate="novalidate">
                <div id="bootstrap-wizard-1" class="col-sm-12">
                  <div class="form-bootstrapWizard" style="padding-left: 100px;">
                    <ul class="bootstrapWizard form-wizard">
                      <li class="active " data-target="#step1">
                        <a href="#tab1" class="unclick" data-toggle="tab"><span  class="step">1</span> <span class="title">{{trans('trip.info')}}</span></a>
                      </li>
                      <li data-target="#step2" >
                          <a href="#tab2" id="unclick2" data-toggle="tab"><span data-toggle="tab" class="step">2</span> <span class="title">{{trans('trip.Select_student')}}</span> </a>
                      </li>
                      <li data-target="#step3"  >
                        <a href="#tab3" id="unclick3" data-toggle="tab"> <span class="step">3</span> <span class="title">{{trans('trip.daparture')}}</span> </a>
                      </li>
                      <li data-target="#step4" >
                        <a href="#tab4" id="unclick4" data-toggle="tab"> <span class="step">4</span> <span class="title">{{trans('trip.finish')}}</span> </a>
                      </li>
                      
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="tab-content">
                    <div class="tab-pane " id="tab2">
                      <br>
                      <h3><strong>{{trans('title.Step_2')}}</strong> - {{trans('trip.Select_student')}}</h3>

                      <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group">
                                 {!! Form::lbSelect('class', @$student->class_id, App\Models\Classes::allToOptionTrip(1), trans('title.Class'), ['class'=> 'form-control input-lg'] )!!} 
                              </div>
                              <div class="form-group " >
                                <div class="row">
                                    <div class="col-sm-12 list_student"></div>
                                </div>
                                 
                              </div>
                          </div>
                         
                         <div class="col-sm-6">  
                          <p style="padding-left: 14px;">  {{trans('trip.Selected_result')}}</p>                      
                              <table class="table table-bordered selected" >
                                <thead>
                                    <tr>
                                        <th>{{trans('trip.name')}}</th>
                                        <th style="text-align: center;">{{trans('trip.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody class=" student_selected"> 
                                      
                                </tbody>
                            </table>
                         </div> 
                      </div>

                    </div>
                    <div class="tab-pane" id="tab3">
                      <br>
                      <h3><strong>{{trans('trip.Step_3')}}</strong> - {{trans('trip.Departure_selected')}}</h3>

                      <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{trans('trip.departure')}}</th>
                                        <th style="text-align: center;">{{trans('trip.arrive_time')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="table-student"> 
                                     
                                </tbody>
                            </table>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="row">
                              <div class="col-sm-8">
                                <h4>{{trans('trip.pay_back')}}</h4>
                                <div class="form-group">
                                  <select name="pay_back" id="" class="form-control">
                                     
                                  </select>
                                  
                                </div>
                              </div>
                              <div class="col-sm-2">
                                  <div style="padding-top: 25px;"><a class="btn btn-success " id='add_pay_back'>{{trans('trip.Add')}}</a></div>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{trans('trip.Come')}}</th>
                                        <th style="text-align: center;">{{trans('trip.arrive_time')}}</th>
                                        <th style="text-align: center;">{{trans('trip.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="lis_pay_back"> 
                                      
                                </tbody>
                            </table>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane active" id="tab1">
                      <br>
                      <h3><strong>{{trans('trip.Step_1')}}</strong> - {{trans('trip.Save_trip')}}</h3>
                      <div class="row">
                      <div class="col-sm-12">
                        <div class="widget-body ">
                            {!! Form::open(array('url' => "school/teacher", 'method' => "post", 'files' => true)) !!}
                            {!! Form::lbText("name", @$trip->name, trans("trip.name")) !!}
                            {!! Form::lbSelect('bus', @$trip->bus_id, App\Models\Bus::getBusBySchool(), trans('trip.Bus') )!!} 
                            {!! Form::lbSelect('trip_type',@$trip->type, App\Models\TripType::allToOption(), trans('trip.trip_type') )!!} 
                            {!! Form::lbSelect('driver',@$trip->drivers[0]->id, App\Models\Driver::getDriverBySchool(), trans('trip.driver') )!!}

                            <div class="form-group"> 
                                <label for="date" class="control-label">{{ trans("trip.Date") }}</label>
                                <div id="date"></div>
                            </div>

                            {!! Form::lbText("started_at",'', trans("trip.Satrted_at"),'','',['class'=>'form-control clockpicker','data-autoclose'=>'true']) !!}
                           
                        </div>
                      </div>
                      </div>
                    </div>

                    <div class="tab-pane" id="tab4">
                      <div class="row">
                        <div class="col-sm-8">  
                          <h1 style="margin: 35px 0px 9px;">{{trans('trip.Step_4')}}</h1>                      
                              <table class="table table-bordered selected" >
                                <thead>
                                    <tr>
                                        <th>{{trans('trip.name')}}</th>
                                        <th style="text-align: center;">{{trans('trip.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="postion_school"> 
                                      
                                </tbody>
                            </table>
                         </div>
                         </div> 
                     </div>

                    <div class="form-actions">
                      <div class="row">
                        <div class="col-sm-12">
                          <ul class="pager wizard no-margin">
                            <!--<li class="previous first disabled">
                            <a href="javascript:void(0);" class="btn btn-lg btn-default"> First </a>
                            </li>-->
                            <li class="previous disabled">
                              <a href="javascript:void(0);" class="btn btn-lg btn-default"> {{trans('trip.Previous')}} </a>
                            </li>
                            <!--<li class="next last">
                            <a href="javascript:void(0);" class="btn btn-lg btn-primary"> Last </a>
                            </li>-->
                            <li class="next">
                              <a href="javascript:void(0);" class="btn btn-lg txt-color-darken" id="submit"> {{trans('trip.Next')}} </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </form>
            </div>

          </div>
          <!-- end widget content -->
        </div>
        <!-- end widget div -->
      </div>
      <!-- end widget -->
    </article>
    <!-- WIDGET END -->
    <!-- NEW WIDGET START -->
  </div>
  <!-- end row -->
</section>
<!-- end widget grid -->
@endsection

@push('script')
<script type="text/javascript">
 
  var student_tmp = [];    

  var departure_array = [];

  var arrive_time = [];
  var student_class = [];
  var pay_back = [];
  var pay_back_time = [];
  var tmp_data_dparture = [];
  //save data  for step 1
  var trip_info = {};
  var postion_student = [];
  pageSetUp();

  function runClockPicker(){
      $('.clockpicker').clockpicker({
        placement: 'top',
          donetext: 'Done'
      });
  }
  var pagefunction = function() {
      
      /*
     * CLOCKPICKER
     */
    
    // load clockpicker script
    
    loadScript("/sa/js/plugin/clockpicker/clockpicker.min.js", runClockPicker);
  
    function runClockPicker(){
      $('.clockpicker').clockpicker({
        placement: 'top',
          donetext: 'Done'
      });
    }
  
    /*
     * ION SLIDER
     */
    loadScript("/sa/js/plugin/ion-slider/ion.rangeSlider.min.js", ion_slider);
  
    function ion_slider() {
  
        //* ion Range Sliders
  
        $("#range-slider-1").ionRangeSlider({
            min: 0,
            max: 5000,
            from: 1000,
            to: 4000,
            type: 'double',
            step: 1,
            prefix: "$",
            prettify: false,
            hasGrid: true
        });
  
        $("#range-slider-2").ionRangeSlider();
  
        $("#range-slider-3").ionRangeSlider({
            min: 0,
            from: 2.3,
            max: 10,
            type: 'single',
            step: 0.1,
            postfix: " mm",
            prettify: false,
            hasGrid: true
        });
  
        $("#range-slider-4").ionRangeSlider({
            min: -50,
            max: 50,
            from: 5,
            to: 25,
            type: 'double',
            step: 1,
            postfix: "°",
            prettify: false,
            hasGrid: true
        });
  
        $("#range-slider-5").ionRangeSlider({
            min: 0,
            from: 0,
            max: 10,
            type: 'single',
            step: 0.1,
            postfix: " mm",
            prettify: false,
            hasGrid: true
        });
  
    }

    /*
     * BOOTSTRAP DUALLIST BOX
     */
    
    loadScript("/sa/js/plugin/bootstrap-duallistbox/jquery.bootstrap-duallistbox.min.js", initializeDuallistbox);
    
    function initializeDuallistbox(){
      var initializeDuallistbox = $('#initializeDuallistbox').bootstrapDualListbox({
            nonSelectedListLabel: 'Non-selected',
            selectedListLabel: 'Selected',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
          });
    }
    
    /*
     * COLOR PICKER
     */
    loadScript("/sa/js/plugin/colorpicker/bootstrap-colorpicker.min.js", initializeColorpicker);
  
    function initializeColorpicker() {
  
      if($('.colorpicker.dropdown-menu').length){
        $('.colorpicker.dropdown-menu').remove();
      }
  
        $('#colorpicker-1').colorpicker()
        $('#colorpicker-2').colorpicker()
  
    }
  
    /*
     * TAGS
     */
  
    loadScript("/sa/js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js", function(){
      $('.tagsinput').tagsinput('refresh')
    });

  var pagedestroy = function(){
    

    // remove clockpicker
    $('.clockpicker').off();
    $('.clockpicker').remove();
    
    // destroy colorpicker
    $('#colorpicker-1').off();
    $('#colorpicker-1').remove();
      $('#colorpicker-2').off();
      $('#colorpicker-2').remove();
      
      //destroy xeditable
      $('#username').editable("destroy");
      $('#firstname').editable("destroy");
      $('#sex').editable("destroy");
      $('#group').editable("destroy");
      $('#status').editable("destroy");
      $('#vacation').editable("destroy");
      $('#dob').editable("destroy");
      $('#event').editable("destroy");
      $('#comments').editable("destroy");
      $('#state2').editable("destroy");
      //$('#fruits').editable("destroy");
      //$('#tags').editable("destroy");
      //$('#country').editable("destroy");
      //$('#address').editable("destroy");
     
      // destroy knob
    $('.knob').find('*').addBack().off();
    
    if (debugState){
      root.console.log("✔ Spiner destroyed");
      root.console.log("✔ Maxlength destroyed");
      root.console.log("✔ Duellist destroyed");
      root.console.log("✔ Timepicker destroyed");
      root.console.log("✔ Clockpicker destroyed");
      root.console.log("✔ Custom Datepicker destroyed");
      root.console.log("✔ NoUI Slider destroyed");
      root.console.log("✔ Ion Range Slider destroyed");
      root.console.log("✔ Tagsinput destroyed");
      root.console.log("✔ Colorpicker destroyed");
      root.console.log("✔ Xeditable  destroyed");
      root.console.log("✔ JSKnob  destroyed");
    } 
    
  }

    // load bootstrap wizard
    
    loadScript("/sa/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js", runBootstrapWizard);

    //Bootstrap Wizard Validations

    function runBootstrapWizard() {

      $.validator.addMethod("time", function(value, element) {  
        return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);  
      });

      var $validator = $("#wizard-1").validate({

        rules: {

            arrive_time: {
                required: true,
                time:true
            },
            started_at : {
                time :true,
                required: true,
            },
            name: {
                required: true
            },
            date:{
                required: true
            },
            pay_back_input: {
                required: true,
                time: true
            },
        },

        messages : {
          arrive_time :{
                required :'<?php echo trans("validate.arrive_time_required"); ?>',
                number :'<?php echo trans("validate.arrive_time_number"); ?>',
                time :'<?php echo trans("validate.arrive_time_time_h:i:s"); ?>'
          },
          pay_back_input:'<?php echo trans("validate.pay_back_input_required"); ?>',
          started_at :'<?php echo trans("validate.started_at_h:i:s"); ?>',
          name :'<?php echo trans("validate.name_required"); ?>',
          date :'<?php echo trans("validate.date_required"); ?>',
        
        },

        highlight : function(element) {
          $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight : function(element) {
          $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        errorElement : 'span',
        errorClass : 'help-block',
        errorPlacement : function(error, element) {
          if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
          } else {
            error.insertAfter(element);
          }
        }
      });
      

      $('#bootstrap-wizard-1').bootstrapWizard({

        'tabClass' : 'form-wizard',
        'onLast': function(tab, navigation, index) {
              return true;
        },
        'onTabClick' : function(tab, navigation, index) {
            return false;
        },
        'onNext' : function(tab, navigation, index) {
          var $valid = $("#wizard-1").valid();
          if (!$valid) {
            $validator.focusInvalid();
            return false;
          } else {
            
            $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
            $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
            //set data info_trip for setep 1
            if (index == 1) {
                var name = $('#name').val();
                var bus = $('#bus').val();
                var trip_type = $('#trip_type').val();
                var driver = $('#driver').val();
                // var date = $('#date').val();
                var date_array =  $('#date').multiDatesPicker('getDates');
                var started_at = $('#started_at').val();  
                if (!date_array.length || !started_at) {
                    alert('<?php echo trans("trip.please_fill_date_info") ?>');
                    return false;
                }

                // if ($('#started_at').val()){
                //     
                // } else {
                //     var started_at = 'NULL';
                // }
                
                trip_info = {
                  name:name,
                  bus:bus,
                  trip_type: trip_type,
                  driver:driver,
                  date:date_array,
                  started_at : started_at
                }
                // chose student by class
                $('select[name = class]').change(function() {
                    $("option[value = '#']").fadeOut();
                    var id = $('select[name = class]').val();
                    $.get('{{route("ajax.get.student")}}', {
                        'class_id': id,
                        _token : '{!! csrf_token() !!}'
                    }, 
                    function(result) {
                        if (result.code =='200') {
                            var data = result.data;
                            student_class = data;          
                            generateCheckBoxes();
                        } else {
                            alert('<?php echo trans("trip.student_error") ?>');
                        }                      
                    },'json');
                });
            }

            if (index ==  2) {
                if (student_tmp.length == 0) {
                    alert('<?php echo trans("trip.you_have_to_chose_student") ?>');
                    return false;
                } else {
                    var student_unique = toUnique(student_tmp);
                    $.post('{{route('ajax.post.student')}}', 
                    {
                        _token : '{!!csrf_token()!!}',
                        student: student_unique,
                        trip_type: trip_info.trip_type  
                    },
                    function(data) {
                        console.log(data);
                        tmp_data_dparture = [];
                        tmp_data_dparture = data;
                        console.log('=====');
                        console.log(trip_info.trip_type);
                        generateDparture(data);
                        
                        $.get('{{route('ajax.get.pay_back')}}',{
                            'data': data,
                            _token :'{!!csrf_token()!!}'
                        }, 
                        function(result) {
                            var option = '';
                            option += "<option value='null'><?php echo trans('trip.chose_pay_back'); ?></option>"
                            $.each(result, function(k,i){
                                option += "<option value="+i.id+">"+i.name+"</option>"; 
                            });
                            $("select[name='pay_back']").html(option);

                        },'json');
                                           
                    },'json');

                    var object_pay_back ={}; 
                    $("select[name='pay_back']").change(function() {
                        $('option[value="null"]').fadeOut();
                        var id  = $(this).val();
                        var text =  $(this).find('option:selected').text();
                        if (id != 'null') {
                            object_pay_back = {
                                id : id,
                                name : text
                              };
                        }
                    });
                 
                    $('#add_pay_back').click(function(){
                      var flash = true;
                      for (var i = 0; i< pay_back.length; i++) {
                          if (pay_back[i].id == object_pay_back.id) {
                              flash = false;
                          }
                      }
                      if (flash == true &&  !$.isEmptyObject(object_pay_back)) {
                          pay_back.push(object_pay_back);
                          generaListPayBack(); 
                      }
                        
                    });

                }
              
            }
            if (index == 3) {
                if (pay_back.length == 0) {
                    alert('<?php echo trans("trip.you_have_to_pay_back") ?>');
                    return false;
                }

                pay_back_time = [];
                arrive_time = [];
                for (var i = 0; i< tmp_data_dparture.length; i++) {
                  var item = tmp_data_dparture[i];
                  if ($('#arrive'+item.id).val()) {
                      var object_arrive = {
                          id: item.id,
                          time: $('#arrive'+item.id).val()
                      };
                      departure_array.push(object_arrive);
                      arrive_time.push(object_arrive); 
                  }
                  else
                  {
                      $('#arrive'+item.id ).parent().append('<span id="arrive'+item.id+'-error" style="color:red;" class="help-block"><?php echo trans('validate.arrive_time_required') ?></span>');
                      return false;
                  }
                }
                
                for (var i=0; i < pay_back.length; i++) {
                  var item = pay_back[i];
                    if ($('#pay_back'+item.id).val()) {
                        var object_pay_back = {
                            id: item.id,
                            time: $('#pay_back'+item.id).val()
                        };
                        pay_back_time.push(object_pay_back); 
                    } else {
                        $('#pay_back'+item.id ).parent().append('<span id="arrive'+item.id+'-error" style="color:red;" class="help-block"><?php echo trans('validate.pay_back_time_required') ?></span>');
                        return false;
                    }
                }
                generateStep4();
                postion_student = [];
                for (var j =0; j < student_tmp.length; j++ ) {
                    var item = student_tmp[j];
                    for (var i = 0; i < pay_back.length; i++) {
                        var sub_item = pay_back[i];
                        var value =  $('#postion_student'+item.id+sub_item.id+':checked').val();
                        $('#postion_student'+item.id+sub_item.id).click(function(){
                            var value_update = $(this).val();
                            var student_id = $(this).attr('data-sutudent_id');
                            var object_update = {
                                  'student_id':student_id,
                                  'postion_pay_back' :value_update
                            }
                            for (var k = 0; k < postion_student.length; k++) {
                                if (postion_student[k]['student_id'] == student_id) {
                                    postion_student.splice(k, 1);
                                    postion_student.push(object_update);
                                }
                            }
                           
                        });
                    }
                    var object = {
                        'student_id': item.id,
                        'postion_pay_back': value
                    };
                    postion_student.push(object);
                }  
            } 

            if (index == 4) {
               
                if (trip_info.length != 0  && postion_student.length != 0) {
                    $.post("{{url('/school/trip')}}",
                    {
                        'arrive_time': arrive_time,
                        'postion_student': postion_student,
                        'student_trip': student_tmp,
                        'trip_info': trip_info,
                        'pay_back_time': pay_back_time,
                        _token: '{!!csrf_token()!!}'
                    },
                    function(data){
                        if (data.code == 200) {
                          alert('<?php echo trans("trip.create_trip_success"); ?>');
                        }else {
                          alert('<?php echo trans('trip.create_trip_error'); ?>')
                        }
                    },'json');
                    location.href = "{{url('/school/trip')}}";
                }else {
                    alert('<?php echo trans("trip.create_trip_error_you_have_to_chose_data"); ?>');
                }
                
            }

          }
        }
      });

    };

    // load fuelux wizard
    
    loadScript("/sa/js/plugin/fuelux/wizard/wizard.min.js", fueluxWizard);
    
    function fueluxWizard() {
      var wizard = $('.wizard').wizard();
      wizard.on('finished', function(e, data) {
        $.smallBox({
          title : "Congratulations! Your form was submitted",
          content : "<i class='fa fa-clock-o'></i><i>1 seconds ago...</i>",
          color : "#5F895F",
          iconSmall : "fa fa-check bounce animated",
          timeout : 4000
        });

      });

    };

  };

  // Load bootstrap wizard dependency then run pagefunction
  pagefunction();

    $('document').ready(function(){
        // show list student selected for edit 
        generateButton();
        $('#date').multiDatesPicker();
    });

    function toUnique(a, b, c) { //array,placeholder,placeholder
      b = a.length;
      while (c = --b)
        while (c--) a[b] !== a[c] || a.splice(c, 1);
      return a // not needed 
    }

    function converArray(array) {
      var array_convert = [];
      for (var i= 0; i< array.length; i++) {
        array_convert[i] = Number(array[i].id);
      }
      return array_convert;
    } 

    function generateButton() {
       var  selected = '';
        for (var i = 0; i < student_tmp.length; i ++)
            {
              var item = student_tmp[i];
              selected += "<tr><td>"+item.name+"</td><td><a student_id = '"+item.id+"' class='btn btn-xs btn-danger button_delete'><?php echo trans('trip.delete') ?></a></td></tr>";
            }
            $('.student_selected').html(selected);
            deleteButton()
    }

    function generateCheckBoxes() {
        var html = "";
        $.each(student_class, function(index, item) {
            html+= "<div class='col-sm-4'><div class='checkbox'><label><input  name='check' id='"+item.id+"' type='checkbox'";
            var student = converArray(student_tmp);
            if($.inArray(item.id, student) > -1) {
                html+= "checked";
            }
            if (item.dp_id == null) {
                html+= "disabled title='<?php echo trans('trip.student_do_not_departure'); ?>'";
            }
            html+= ">"+item.name+ '('+ item.code + ')'+"</label></div></div>";
        });
        $('.list_student').html(html);
        // event checkbox
        $("input[name = 'check']").change(function() {
            var ischecked = $(this).is(':checked');
            if (!ischecked) {
                for(var i=0 ; i<student_tmp.length; i++)
                {
                    if(student_tmp[i].id == $(this).attr('id'))
                    student_tmp.splice(i,1);
                }
               
            } else {
                student_tmp.push({'name':$(this).parent().text(),'id':$(this).attr('id')});
            }
            //generate button
            generateButton(); 
        });
    }

    function generateDparture(data) {
        var html = "";
        $.each(data, function(k, i) {
              html += "<tr><td>"+i.departure+"</td><td><input data-autoclose='true' name='arrive_time'  type='text' class='form-control clockpicker' id='arrive"+i.id+"'";
                  //checked for edit
                  for (var j = 0; j < departure_array.length ; j++) {
                      if(i.id == departure_array[j].id) {
                          html += "value = '"+ departure_array[j].time +"'";
                      } 
                  }
              html+= "></td></tr>";

              $('.table-student').html(html);

        });
        loadScript("/sa/js/plugin/clockpicker/clockpicker.min.js", runClockPicker);
          
    }

    function deleteButton() {
      $(".button_delete").each(function () {
          $(this).bind("click", function() {
                for(var i=0 ; i<student_tmp.length; i++)
                {
                    if(student_tmp[i].id == $(this).attr('student_id'))
                      student_tmp.splice(i,1);
                }
                generateButton();     
                generateCheckBoxes();                      
          });    
      });
    }

    function generaListPayBack() {
        var html = '';
        for (var i = 0; i < pay_back.length; i++) {
          var item = pay_back[i];
          html += "<tr><td>"+item.name+"</td><td><input id='pay_back"+item.id+"' name='pay_back_input' class='form-control clockpicker' data-autoclose='true'></td><td><a pay_back_id='"+item.id+"' class='btn btn-xs btn-danger button_delete_pay_back'><?php echo trans('trip.delete') ?></a></td></tr>";
        }
            
        $('.lis_pay_back').html(html);
        loadScript("/sa/js/plugin/clockpicker/clockpicker.min.js", runClockPicker);

        deleteButtonPayBack();
    }

    function deleteButtonPayBack() {
        $(".button_delete_pay_back").each(function () {
          $(this).bind("click", function() {
                for(var i=0 ; i<pay_back.length; i++) {     
                    item = pay_back[i];
                    if(item.id == $(this).attr('pay_back_id'))
                        pay_back.splice(i,1);
                }
                generaListPayBack();                      
          });    
      });
    }

    function generateStep4() {
        
        var html = '';
        for (var i = 0; i< student_tmp.length; i++) {
          sitem = student_tmp[i];
          var html_input = '';
          for (var j = 0; j < pay_back.length; j++) {
              var sub_item = pay_back[j];
              html_input+= sub_item.name+"<input style='margin: 10px 8px 0px;' id='postion_student"+sitem.id+sub_item.id+"' type='radio' name='radio"+i+"' checked value='"+sub_item.id+"' data-sutudent_id ='"+sitem.id+"'>";
          }
  
          html+= "<tr><td>"+sitem.name+"</td><td >"+html_input+"</td></tr>";
        }
        $('.postion_school').html(html);
    }
   
</script>
@endpush
