@extends('app')

@section('sidemenu_schooladmin')
active
@endsection

@section('content')
 <div class="row">
<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
    <h1 class="page-title txt-color-blueDark">
        <i class="fa fa-edit fa-fw "></i>
        {{ trans("general.admin") }}
        <span>>
            {{ trans("general.list") }}
    </span>
    </h1>
</div>
</div>
<section id="widget-grid">                          
    <div class="row">                       
        <article class="col-lg-12">
            @include('custom.alert')                    
            @box_open(trans("backend.admin"))              
            <div>               
                <div class="widget-body" id="post_container">           
                    @include("layouts.elements.table", [
                        'url' => '/ajax/admin',     
                        'columns' => [          
                            ['data' => 'id', 'title' => trans('title.No')],                 
                            ['data' => 'full_name', 'title' => trans('title.Name')],                                         
                            ['data' => 'email', 'title' => trans('title.Email')],                                         
                            ['data' => 'created_at', 'title' => trans('title.Created_at')],                 
                        ]
                    ])
                </div>          
            </div>              
            @box_close              
        </article>                  
    </div>                      
</section>  

@endsection

                            
