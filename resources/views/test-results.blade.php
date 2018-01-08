@extends('layouts.app') @section('content')
	<div class="container">
		<div class="row">
			<test-attempt-manager :user_attempt_id={{$user_attempt_id}} :test_id={{$test}}></test-attempt-manager>
		</div>
	</div>
@endsection