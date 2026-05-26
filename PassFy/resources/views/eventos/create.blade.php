@extends('layouts.app')

@section('title', 'Passfy - Cadastro')

@section('content')
    <section class="secao-cadastro">
		<div class="cadastro-header">
			<h1>Criar Evento</h1>
			<p>Preencha os dados para criar o evento</p>
		</div>
		<form id="form-criar-evento" class="form-cadastro" method="POST" action="{{ route('eventos.store') }}" enctype="multipart/form-data">
			@csrf
			<div class="input-wrapper-registro" style="grid-column: span 4;" required>
				<label>Nome do Evento</label>
				<input type="text" name="nomeEvento" placeholder="Nome do evento" required>
			</div>
			<div class="input-wrapper-registro" required>
				<label>Data do Evento</label>
				<input type="date" name="dataEvento" required>
			</div>
			<div class="input-wrapper-registro" required>
				<label>Hora do Evento</label>
				<input type="time" name="horaEvento" required>
			</div>
			<div class="input-wrapper-registro" style="grid-column: span 2;" required>
				<label>Tipo de Evento</label>
				<select name="tipoEvento" required>
					<option value="">Selecione o tipo de evento</option>
					<option value="show">Show</option>
					<option value="moda">Moda</option>
					<option value="gastronomia">Gastronomia</option>
					<option value="palestra">Palestra</option>
					<option value="esporte">Esporte</option>
					<option value="arte">Arte</option>
				</select>
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
				<select id="cidade_select" name="idCidade" required disabled>
					<option value="">Selecione uma UF primeiro</option>
				</select>
			</div>
			<div class="input-wrapper-registro" style="grid-column: span 3;" required>
				<label>Local do Evento</label>
				<input type="text" name="localEvento" placeholder="Local do Evento" required>
			</div>
			<div class="input-wrapper-registro" style="grid-column: span 4;" required>
				<label>Descrição</label>
				<textarea name="descricaoEvento" placeholder="Descrição do evento" style="height: 100px;" required></textarea>
			</div>
			<div class="file-upload" style="grid-column: 2">
				<label for="image" class="file-upload-label">
					<i class="fas fa-cloud-upload-alt"></i>
					<span>Clique para selecionar a imagem</span>
					<span class="file-name" id="fileName"></span>
				</label>
				<input type="file" id="image" name="imagemEvento" accept="image/*">
				<small>A imagem deve ser no formato JPG, PNG ou GIF e não pode exceder 2MB.</small>
			</div>
			<div class="file-preview" id="filePreview" style="grid-column: 3;">
					<img id="previewImage" src="#" alt="Prévia da imagem">
			</div>
			<div class="lotes-section" style="grid-row: 7; grid-column: span 4;">
				<h3>Lotes de Ingressos</h3>
				<p>Adicione os lotes de ingressos (ex: Pista, VIP, Meia-entrada)</p>
				
				<div id="lotes-container">
					<!-- Os lotes vão aparecer aqui dinamicamente -->
				</div>
				
				<button type="button" id="add-lote-btn" class="btn-add-lote">
					<i class="fas fa-plus-circle"></i> Adicionar Novo Lote
				</button>
			</div>
			<button type="submit" id="btn-criar-evento" class="btn-cadastro">Criar Evento</button>
			@if(session('error'))
				<div class="error-message" style="color: red; margin-top: 1rem;">
					{{ session('error') }}
				</div>
			@endif
			@if($errors->any())
				<div class="error-message" style="color: red; margin-top: 1rem;">
					<ul style="margin: 0; padding-left: 1.2rem;">
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div id="registerError" style="display: none; color: red;"></div>
		</form>
	</section>
@endsection
