@extends('app')

@section('sidemenu_conversation')
active
@endsection

@section('content')
<section id="widget-grid" class="" ng-app="BFApp">
    <div class="row">
        <article class="col-md-4" ng-controller="ConversationController">
        	<div class="panel panel-default" style="height: calc(100vh - 191px); overflow-y: auto;" id="chat-conversation">
        		<a href="/lbmessenger/conversation/@{{ conversation.id }}/item" ng-repeat="conversation in conversations" ng-cloak>
					<div class="panel-body status" ng-class="{active_item: conversation.id == active_item}">
						<div class="who clearfix" ng-if="conversation.last_user_id == null">
							<img src="https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/user_male2-512.png" alt="img">
							<span class="name">
								<b ng-repeat="user in conversation.users">
									@{{ user.profile.full_name }}, 
								</b>
							</span>
						</div>
						<div class="who clearfix" ng-if="conversation.last_user_id">
							<img src="/lbmedia/@{{ conversation.last_user.profile.avatar.id }}?width=50&height=50&style=scale_to_fill" alt="img">
							<span class="name">
								<b ng-repeat="user in conversation.users">
									@{{ user.profile.full_name }}, 
								</b>
							</span>
							<span class="from"><b>@{{ conversation.last_content }}</b></span>
						</div>
					</div>
				</a>
			</div>
        </article>
        @if (isset($conversation))
        <article class="col-md-8" ng-controller="ConversationItemController">
	        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-comments txt-color-white"></i> </span>
					<h2>
						@foreach ($conversation->users as $user)
						{{ $user->profile->full_name }}, 
						@endforeach
					</h2>
				</header>
				<div>
					<div class="widget-body widget-hide-overflow no-padding">
						<div id="chat-body" class="chat-body custom-scroll" style="height: calc(100vh - 330px);" ng-cloak>
							<ul>
								<li class="message" ng-repeat="item in items">
									<img src="/lbmedia/@{{ item.creator.profile.avatar.id }}?width=50&height=50&style=scale_to_fill" alt="" style="width: 50px; height: 50px;">
									<div class="message-text">
										<time>
											@{{ item.created_at }}
										</time> <a href="javascript:void(0);" class="username">@{{ item.creator.profile.full_name }}</a>
										@{{ item.content }}
									</div>
								</li>
							</ul>
						</div>
						<div class="chat-footer">
							<div class="textarea-div">
								<div class="typearea">
									{!! Form::open(["url" => "/lbmessenger/conversation/$conversation->id/item", "method" => "post"]) !!}
									<textarea placeholder="Write a reply..." id="textarea-expand" ></textarea>
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </article>
        @endif
    </div>
</section>
@endsection

@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
<script type="text/javascript">
	var bfapp = angular.module('BFApp', []);
	var active_item = '<?= @$conversation->id ?>';

	@if (isset($conversation))
		$(document).ready(function() {
			$('#content .jarviswidget-delete-btn').removeClass('jarviswidget-delete-btn').on('click', function() {
				if (confirm('Bạn có chắc chắn xoá hội thoại này?')) {
					$.ajax({
					    url: '/lbmessenger/contact/conversation/' + active_item,
					    type: 'DELETE',
					    data: {
					    	"_token": "{{ csrf_token() }}",
					    },
					    success: function(result) {
					        location.href = '/lbmessenger/conversation';
					    }
					});
				}
			});
		});

		bfapp.controller('ConversationItemController', function($scope, $http) {
			$('#textarea-expand').on('keyup', function(e) {
			    if (e.which == 13 && ! e.shiftKey) {
			    	$.post("{{ url("/lbmessenger/ajax/conversation/$conversation->id/item") }}", {
			    		content: this.value
			    	}, function(data) {
			    		loadChatItem();
			    	}, "json");
		    		$('#textarea-expand').val('');
			    }
			});

		    loadChatItem();

		    setInterval(function() {
				loadChatItem();			    
		    }, 3000);

		    function loadChatItem() {
		    	$http.get("{{ url("/lbmessenger/ajax/conversation/$conversation->id/item") }}").then(function (response) {
		    		if (!$scope.items || $scope.items.length != response.data.length) {
				    	$scope.items = response.data;
				    	setTimeout(function() {
					    	var scroller = document.getElementById("chat-body");
							scroller.scrollTop = scroller.scrollHeight;
				    	}, 100);
				    }
			    });
		    }
		});
	@endif

	bfapp.controller('ConversationController', function($scope, $http) {
	    loadConversation();

	    setInterval(function() {
			loadConversation();		    
	    }, 3000);

	    function loadConversation() {
	    	$http.get("/lbmessenger/ajax/conversation").then(function (response) {
		    	$scope.conversations = response.data;
		    	$scope.active_item = active_item;
		    });
	    }
	});
</script>
@endpush
@push('css')
<style>
	.name b{
		color: black !important;
	}
	.active_item {
		background: darkgoldenrod;
	}
</style>
@endpush