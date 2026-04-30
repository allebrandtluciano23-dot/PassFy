@extends('layouts.app')

@section('title', 'Passfy - Cadastro')

@section('content')
    <section class="secao-cadastro">
		<div class="cadastro-header">
			<h1>Cadastro</h1>
			<p>Preencha os dados para realizar o cadastro</p>
		</div>
		<div class="tipo-cadastro" id="tipo-cadastro">
			<button type="button" class="register-type-btn type-btn active" data-type="cliente">Sou Cliente</button>
			<button type="button" class="register-type-btn type-btn" data-type="organizadora">Sou Organizadora</button>
		</div>
		<form id="form-cadastro" method="POST" action="{{ route('register.cliente') }}">
			@csrf
			<div class="input-wrapper-registro" id="nome-cadastro" required>
				<label>Nome Completo</label>
				<input type="text" name="name" placeholder="Nome completo" required>
			</div>
			<div class="input-wrapper-registro" id="input-dinamico-cadastro" required></div>
			<div class="input-wrapper-registro" id="email-cadastro" required>
			<label>Email</label>
				<input type="text" name="email" placeholder="exemplo@email.com" required>
			</div>
			<div class="input-wrapper-registro" id="cep-cadastro" required>
				<label>CEP</label>
				<input type="text" name="cep" placeholder="Formato: 12345678" maxlength="8" pattern="\d{8}" required>
				<small id="cep-status" style="display: none; margin-top: 5px;"></small>
			</div>
			<div class="input-wrapper-registro" id="cidade-cadastro" required>
				<label>Cidade</label>
				<input type="text" id="city_display" placeholder="Cidade" readonly>
				<input type="hidden" name="city" id="city_hidden">
			</div>
			<div class="input-wrapper-registro" id="uf-cadastro" required>
				<label>UF</label>
				<input type="text" id="state_display" placeholder="UF" readonly maxlength="2">
				<input type="hidden" name="state" id="state_hidden">
			</div>
			<div class="input-wrapper-registro" id="endereço-cadastro" required>
				<label>Endereço</label>
				<input type="text" name="address" placeholder="Endereço">
			</div>
			<div class="input-wrapper-registro" id="telefone-cadastro" required>
				<label>Telefone</label>
				<input type="text" name="phone" placeholder="Telefone">
			</div>
			<div class="input-wrapper-registro" id="senha-cadastro" required>
				<label>Senha</label>
				<input type="password" name="password" placeholder="Senha">
			</div>
			<div class="input-wrapper-registro" id="confirmar-senha-cadastro" required>
				<label>Confirmar Senha</label>
				<input type="password" name="passwordConfirmation" placeholder="Confirmar Senha">
			</div>
			<button type="submit" id="btn-cadastrar">Cadastrar</button>
			<div id="registerError" style="display: none; color: red;"></div>
		</form>
	</section>
@endsection
