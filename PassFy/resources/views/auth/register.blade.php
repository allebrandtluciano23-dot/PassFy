@extends('layouts.app')

@section('title', 'Cadastro')

@section('content')
    <section class="secao-cadastro">
		<h1>Cadastro</h1>
		<p>Preencha os dados para realizar o cadastro</p>
		<form id="form-cadastro" method="POST" action="{{ route('register.cliente') }}">
			@csrf
			<div class="input-wrapper-registro">
				<label>Tipo de Cadastro</label>
				<select name="tipo-cadastro" id="tipo-cadastro">
		    		<option value="cliente">Cliente</option>
		    		<option value="organizadora">Instituição Organizadora</option>
				</select>
			</div>
			<div class="input-wrapper-registro">
				<label>Nome Completo</label>
				<input type="text" name="name" placeholder="Nome completo" required>
			</div>
			<div class="input-wrapper-registro" id="input-dinamico-cadastro" required></div>
			<div class="input-wrapper-registro">
			<label>Email</label>
				<input type="text" name="email" placeholder="exemplo@email.com" required>
			</div>
			<div class="input-wrapper-registro" required>
				<label>Cidade</label>
				<input type="text" name="city" placeholder="Cidade">
			</div>
			<div class="input-wrapper-registro" required>
				<label>CEP</label>
				<input type="text" name="cep" placeholder="CEP">
			</div>
			<div class="input-wrapper-registro" required>
				<label>Endereço</label>
				<input type="text" name="address" placeholder="Endereço">
			</div>
			<div class="input-wrapper-registro" required>
				<label>Telefone</label>
				<input type="text" name="phone" placeholder="Telefone">
			</div>
			<div class="input-wrapper-registro" required>
				<label>Senha</label>
				<input type="password" name="password" placeholder="Senha">
			</div>
			<div class="input-wrapper-registro" required>
				<label>Confirmar Senha</label>
				<input type="password" name="passwordConfirmation" placeholder="Confirmar Senha">
			</div>
			<button type="submit">Cadastrar</button>
			<div id="registerError" style="display: none; color: red;"></div>
		</form>
	</section>
@endsection
