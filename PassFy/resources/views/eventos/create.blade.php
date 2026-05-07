@extends('layouts.app')

@section('title', 'Passfy - Cadastro')

@section('content')
    <section class="secao-cadastro">
		<div class="criar-evento-header">
			<h1>Criar Evento</h1>
			<p>Preencha os dados para criar o evento</p>
		</div>
		<form id="form-cadastro" method="POST" action="{{ route('register.cliente') }}">
			@csrf
			<div class="input-wrapper-registro" id="nome-evento" required>
				<label>Nome do Evento</label>
				<input type="text" name="name" placeholder="Nome do evento" required>
			</div>
			<div class="input-wrapper-registro" id="data-evento" required>
				<label>Data do Evento</label>
				<input type="date" name="date" required>
			</div>
			<div class="input-wrapper-registro" id="hora-evento" required>
				<label>Hora do Evento</label>
				<input type="time" name="time" required>
			</div>
			<select id="tipo-evento" name="tipo-evento" class="input-wrapper-registro" required>
                <option value="">Selecione o tipo de evento</option>
                <option value="show">Show</option>
                <option value="moda">Moda</option>
                <option value="gastronomia">Gastronomia</option>
                <option value="palestra">Palestra</option>
                <option value="esporte">Esporte</option>
                <option value="arte">Arte</option>
            </select>
			<div class="input-wrapper-registro" id="cidade-evento" required>
				<label>Cidade</label>
				<input type="text" id="city_display" placeholder="Cidade" readonly>
				<input type="hidden" name="city" id="city_hidden">
			</div>
			<div class="input-wrapper-registro" id="local-evento" required>
				<label>Local do Evento</label>
				<input type="text" id="local-evento" placeholder="Local do Evento">
			</div>
			<div class="input-wrapper-registro" id="descricao-evento" required>
				<label>Descrição</label>
				<input type="text" name="description" placeholder="Descrição do evento">
			</div>
			<button type="submit" id="btn-criar-evento">Criar Evento</button>
			<div id="registerError" style="display: none; color: red;"></div>
		</form>
	</section>
@endsection
