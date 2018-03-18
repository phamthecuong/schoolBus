@extends('layouts.app') 
    
@push('sidebar')
    @include("libressltd.lbsidemenu.sidemenu")
@endpush

@push('css')
<style type="text/css">
	.form-group,  
	nav ul li a,
	table,
	table th,
	.hasinput input {
		text-align: left;
	}
	.select2-selection {
		text-align: left;
	}
	.note-error {
		color: #b94a48;
	}
	div.demo {
		display: none;
	}
	.menu-on-top .menu-item-parent {
		font-size: 10px !important;
	}
	.jarviswidget-delete-btn {
		display: none !important;
	}
	header > .widget-icon {
		display: none !important;
	}
	.smart-style-5 .ui-tabs .ui-tabs-nav li a:hover {
		-webkit-box-shadow: none !important;
		box-shadow: none !important;
	}
	.ui-tabs .ui-tabs-nav li.ui-tabs-active {
		padding-bottom: 0px !important;
	}
	 table.dataTable thead th { min-width: 80px; }
</style>
@endpush

@section('body_class')
smart-style-5
@endsection

