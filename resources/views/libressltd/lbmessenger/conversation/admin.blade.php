@extends('app')
@section('content')
<section id="widget-grid">
	<h3>Contact list</h3>
	<div class="row">
		<article class="col-md-4">
			<div class="panel panel-default" style="height: calc(100vh - 191px); overflow-y: auto;">
				@foreach ($parents as $parent)
				<a href="#" onclick="return false;" class="create-conversation" data-user="{{ $parent->users[0]->id }}">
					<div class="panel-body status">
						<div class="who clearfix">
							<img src="https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/user_male2-512.png" alt="img">
							<span class="name">
								<b>{{ $parent->full_name }}</b>
							</span>
						</div>
					</div>
				</a>
				@endforeach
				@foreach ($drivers as $driver)
				<a href="#" onclick="return false;" class="create-conversation" data-user="{{ $driver->users[0]->id }}">
					<div class="panel-body status">
						<div class="who clearfix">
							<img src="https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/user_male2-512.png" alt="img">
							<span class="name">
								<b>{{ $driver->full_name }}</b>
							</span>
						</div>
					</div>
				</a>
				@endforeach
			</div>
		</article>
	</div>
</section>
@endsection

@push('script')

<script type="text/javascript">
	$(document).ready(function () {
		$('.create-conversation').click(function () {
			var id = $(this).attr('data-user');
			var data = {};
			data['id'] = id;
			data['_token'] = '{{ csrf_token() }}';
			$.ajax({
                url: '/lbmessenger/ajax/conversation',
                method: "POST",
                dataType: "json",
                data: data,
                success: function (rs) {
                    window.location = '/lbmessenger/conversation';
                },
                error: function (xhr) {
                    window.location = '/lbmessenger/conversation';
                }
            })
		});
	});
</script>
@endpush