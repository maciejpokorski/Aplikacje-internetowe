@extends('layouts.app') @section('content')
	<div class="container">
		<div class="row">
			<test-attempt-manager :test_id={{$test}}></test-attempt-manager>
		</div>
	</div>
@endsection