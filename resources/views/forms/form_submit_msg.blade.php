@extends($template)

@section('content')


</style>	
<body>
	<div class="container-fluid">
		<div class="alert alert-success" id="submit-msg" style="margin-top:20px;margin-bottom:10px">	 
			<h4>{{ __('Success')}}!</h4>
					{{ __('Your response was successfully recorded.')}}
					@if ($user_type == 'in') 
					    @if ($user_role == 2 || $is_super == 1)
						{{ __('Click')}} <a href="{{url('audit/completed')}}">{{ __('here')}}</a> {{ __('to go back to Forms List')}}
					    @else
						Click <a href="{{url('audit/completed')}}">{{ __('here')}}</a> {{ __('to go back to Forms List')}}
						@endif
					@endif
		</div>
	</div>
@endsection