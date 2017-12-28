@extends('layouts.app') @section('content')
	<div class="container">
		<div class="row">
			<single-test-manager :test_id="{{$test}}"></single-test-manager>
		</div>
	</div>
@endsection