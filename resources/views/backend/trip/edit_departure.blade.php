@extends('app')
@section('sidemenu_trip')
active
@endsection

@section('content')
<div class="row">
      <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
          <h1 class="page-title txt-color-blueDark">
              <i class="fa fa-edit fa-fw "></i>
              {{ trans("sidemenu.trip") }}
              <span>>
                  {{ trans("general.edit") }}
          </span>
          </h1>
      </div>
</div>
<section id="widget-grid" class="">
<div class="row">
     <article class="col-lg-8 col-md-6">
        @box_open(trans("trip.Edit_departure"))
        <div>
            <div class="widget-body ">
	            {!! Form::open(array('url' => "school/trip/edit_departure/$trip_id", 'method' => "post", 'files' => true)) !!}
	            <table class="table table-bordered">
	                <thead>
	                    <tr>
	                        <th style="text-align: center;">{{trans('trip.departure_name')}}</th>
	                        <th style="text-align: center;">{{trans('trip.arrive_time')}}</th>
	                    </tr>
	                </thead>
	                <tbody > 
	                @foreach ($dp as $k => $r)
	                <tr>
	                	<td>{{$r->name}}</td>
	                	<td>
                    {!!
                        Form::lbText("dp{$r->id}", substr($r->pivot->arrive_time, 0, 5), null, '', '', ['class' => 'form-control clockpicker', 'data-autoclose' => 'true']) 
                    !!}
	                	</td>
	                </tr>
	                @endforeach
	                </tbody>
	            </table>
            <div class="widget-footer ">
                {!! Form::lbSubmit(trans('general.submit')) !!}
            </div>
                {!! Form::close() !!}
        </div>
        @box_close
     </article>
</div>
</section>

@endsection

@push('script')
<script>
var count = <?php echo count($dp) ?>
	  /**
     * CLOCKPICKER
     */
    
    // load clockpicker script
    
    loadScript("/sa/js/plugin/clockpicker/clockpicker.min.js", runClockPicker);
  
    function runClockPicker() {
	    for(var i = 0; i< count; i++) {
	    		$('#clockpicker'+i).clockpicker({
			        placement: 'top',
			          donetext: 'Done'
	      });
	    }
      
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
    $('#clockpicker').off();
    $('#clockpicker').remove();
    
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
</script>
@endpush


