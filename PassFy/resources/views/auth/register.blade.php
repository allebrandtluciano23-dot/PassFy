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
		<form id="form-registrar-cliente" class="form-cadastro" method="POST" action="{{ route('register.cliente') }}">
			@csrf
			<div class="input-wrapper-registro" style="grid-column: span 4;" required>
				<label>Nome Completo</label>
				<input type="text" name="name" placeholder="Nome completo" required>
			</div>
			<div class="input-wrapper-registro" id="input-dinamico-cadastro" style="grid-column: span 2;" required></div>
			<div class="input-wrapper-registro" style="grid-column: 3 / 5;" required>
			<label>Email</label>
				<input type="text" name="email" placeholder="exemplo@email.com" required>
			</div>
			<div class="input-wrapper-registro" required>
				<label>CEP</label>
				<input type="text" name="cep" placeholder="Formato: 12345678" maxlength="8" pattern="\d{8}" required>
				<button type="button" id="btn-buscar-cep" class="btn-cadastro" style="height: 45px;">Buscar CEP</button>
				<small id="cep-status" style="display: none; margin-top: 5px;"></small>
			</div>
			<div class="input-wrapper-registro" required>
				<label>UF</label>
				<select id="uf_select" name="state" required>
					<option value="">Selecione a UF</option>
					<option value="AC">AC</option><option value="AL">AL</option><option value="AP">AP</option>
					<option value="AM">AM</option><option value="BA">BA</option><option value="CE">CE</option>
					<option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option>
					<option value="MA">MA</option><option value="MT">MT</option><option value="MS">MS</option>
					<option value="MG">MG</option><option value="PA">PA</option><option value="PB">PB</option>
					<option value="PR">PR</option><option value="PE">PE</option><option value="PI">PI</option>
					<option value="RJ">RJ</option><option value="RN">RN</option><option value="RS">RS</option>
					<option value="RO">RO</option><option value="RR">RR</option><option value="SC">SC</option>
					<option value="SP">SP</option><option value="SE">SE</option><option value="TO">TO</option>
				</select>
			</div>
			<div class="input-wrapper-registro" style="grid-column: span 2;" required>
				<label>Cidade</label>
				<select id="cidade_select" name="city" required disabled>
					<option value="">Selecione uma UF primeiro</option>
				</select>
			</div>
			<div class="input-wrapper-registro" style="grid-column: span 4;" required>
				<label>Endereço</label>
				<input type="text" name="address" placeholder="Endereço">
			</div>
			<div class="input-wrapper-registro" style="grid-column: span 2;" required>
				<label>Telefone</label>
				<input type="text" name="phone" placeholder="Telefone">
			</div>
			<div class="input-wrapper-registro" style="grid-row: 6; grid-column: span 2;" required>
				<label>Senha</label>
				<input type="password" name="password" placeholder="Senha">
			</div>
			<div class="input-wrapper-registro" style="grid-row: 6; grid-column: span 2;" required>
				<label>Confirmar Senha</label>
				<input type="password" name="passwordConfirmation" placeholder="Confirmar Senha">
			</div>
			<button type="submit" id="btn-cadastrar" class="btn-cadastro">Cadastrar</button>
			<div id="registerError" style="display: none; color: red;"></div>
		</form>
	</section>
@endsection
