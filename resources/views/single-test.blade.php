@extends('layouts.app') @section('content')
	<div class="container">
		<div class="row">
			<single-test-manager :test_id="{{$test}}">
			</single-test-manager>
			<question-manager size_prop="col-md-6" :sync_prop="true" :test_id="{{$test}}"></question-manager>
		</div>
	</div>
@endsection