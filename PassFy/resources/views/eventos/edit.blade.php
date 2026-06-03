@extends('layouts.app')

@section('title', 'Passfy - Editar Evento')

@section('content')
    <section class="secao-cadastro">
		<div class="cadastro-header">
			<h1>Editar Evento</h1>
			<p>Altere os dados do evento</p>
		</div>
		<form id="form-editar-evento" class="form-cadastro" method="POST" action="{{ route('eventos.update', $evento->idEvento) }}" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			
			<div class="input-wrapper-registro" style="grid-column: span 4;" required>
				<label>Nome do Evento</label>
				<input type="text" name="nomeEvento" value="{{ old('nomeEvento', $evento->nomeEvento) }}" placeholder="Nome do evento" required>
			</div>
			
			<div class="input-wrapper-registro" required>
				<label>Data do Evento</label>
				<input type="date" name="dataEvento" value="{{ old('dataEvento', $evento->dataEvento) }}" required>
			</div>
			
			<div class="input-wrapper-registro" required>
				<label>Hora do Evento</label>
				<input type="time" name="horaEvento" value="{{ old('horaEvento', $evento->horaEvento) }}" required>
			</div>
			
			<div class="input-wrapper-registro" style="grid-column: span 2;" required>
				<label>Tipo de Evento</label>
				<select name="tipoEvento" required>
					<option value="">Selecione o tipo de evento</option>
					<option value="show" {{ old('tipoEvento', $evento->tipoEvento) == 'show' ? 'selected' : '' }}>Show</option>
					<option value="moda" {{ old('tipoEvento', $evento->tipoEvento) == 'moda' ? 'selected' : '' }}>Moda</option>
					<option value="gastronomia" {{ old('tipoEvento', $evento->tipoEvento) == 'gastronomia' ? 'selected' : '' }}>Gastronomia</option>
					<option value="palestra" {{ old('tipoEvento', $evento->tipoEvento) == 'palestra' ? 'selected' : '' }}>Palestra</option>
					<option value="esporte" {{ old('tipoEvento', $evento->tipoEvento) == 'esporte' ? 'selected' : '' }}>Esporte</option>
					<option value="arte" {{ old('tipoEvento', $evento->tipoEvento) == 'arte' ? 'selected' : '' }}>Arte</option>
				</select>
			</div>
			
			<div class="input-wrapper-registro" required>
				<label>UF</label>
				<select id="uf_select" name="state" required>
					<option value="">Selecione a UF</option>
					<option value="AC" {{ old('state', $evento->cidade->ufCidade ?? '') == 'AC' ? 'selected' : '' }}>AC</option>
					<option value="AL" {{ old('state', $evento->cidade->ufCidade ?? '') == 'AL' ? 'selected' : '' }}>AL</option>
					<option value="AP" {{ old('state', $evento->cidade->ufCidade ?? '') == 'AP' ? 'selected' : '' }}>AP</option>
					<option value="AM" {{ old('state', $evento->cidade->ufCidade ?? '') == 'AM' ? 'selected' : '' }}>AM</option>
					<option value="BA" {{ old('state', $evento->cidade->ufCidade ?? '') == 'BA' ? 'selected' : '' }}>BA</option>
					<option value="CE" {{ old('state', $evento->cidade->ufCidade ?? '') == 'CE' ? 'selected' : '' }}>CE</option>
					<option value="DF" {{ old('state', $evento->cidade->ufCidade ?? '') == 'DF' ? 'selected' : '' }}>DF</option>
					<option value="ES" {{ old('state', $evento->cidade->ufCidade ?? '') == 'ES' ? 'selected' : '' }}>ES</option>
					<option value="GO" {{ old('state', $evento->cidade->ufCidade ?? '') == 'GO' ? 'selected' : '' }}>GO</option>
					<option value="MA" {{ old('state', $evento->cidade->ufCidade ?? '') == 'MA' ? 'selected' : '' }}>MA</option>
					<option value="MT" {{ old('state', $evento->cidade->ufCidade ?? '') == 'MT' ? 'selected' : '' }}>MT</option>
					<option value="MS" {{ old('state', $evento->cidade->ufCidade ?? '') == 'MS' ? 'selected' : '' }}>MS</option>
					<option value="MG" {{ old('state', $evento->cidade->ufCidade ?? '') == 'MG' ? 'selected' : '' }}>MG</option>
					<option value="PA" {{ old('state', $evento->cidade->ufCidade ?? '') == 'PA' ? 'selected' : '' }}>PA</option>
					<option value="PB" {{ old('state', $evento->cidade->ufCidade ?? '') == 'PB' ? 'selected' : '' }}>PB</option>
					<option value="PR" {{ old('state', $evento->cidade->ufCidade ?? '') == 'PR' ? 'selected' : '' }}>PR</option>
					<option value="PE" {{ old('state', $evento->cidade->ufCidade ?? '') == 'PE' ? 'selected' : '' }}>PE</option>
					<option value="PI" {{ old('state', $evento->cidade->ufCidade ?? '') == 'PI' ? 'selected' : '' }}>PI</option>
					<option value="RJ" {{ old('state', $evento->cidade->ufCidade ?? '') == 'RJ' ? 'selected' : '' }}>RJ</option>
					<option value="RN" {{ old('state', $evento->cidade->ufCidade ?? '') == 'RN' ? 'selected' : '' }}>RN</option>
					<option value="RS" {{ old('state', $evento->cidade->ufCidade ?? '') == 'RS' ? 'selected' : '' }}>RS</option>
					<option value="RO" {{ old('state', $evento->cidade->ufCidade ?? '') == 'RO' ? 'selected' : '' }}>RO</option>
					<option value="RR" {{ old('state', $evento->cidade->ufCidade ?? '') == 'RR' ? 'selected' : '' }}>RR</option>
					<option value="SC" {{ old('state', $evento->cidade->ufCidade ?? '') == 'SC' ? 'selected' : '' }}>SC</option>
					<option value="SP" {{ old('state', $evento->cidade->ufCidade ?? '') == 'SP' ? 'selected' : '' }}>SP</option>
					<option value="SE" {{ old('state', $evento->cidade->ufCidade ?? '') == 'SE' ? 'selected' : '' }}>SE</option>
					<option value="TO" {{ old('state', $evento->cidade->ufCidade ?? '') == 'TO' ? 'selected' : '' }}>TO</option>
				</select>
			</div>
			
			<div class="input-wrapper-registro" style="grid-column: span 2;" required>
				<label>Cidade</label>
				<select id="cidade_select" name="idCidade" required>
                    <option value="">Selecione uma UF primeiro</option>
                    @foreach($cidades as $cidade)
                        <option value="{{ $cidade->idCidade }}" data-uf="{{ $cidade->ufCidade }}" 
                            {{ old('idCidade', $evento->idCidade) == $cidade->idCidade ? 'selected' : '' }}>
                            {{ $cidade->nomeCidade }}
                        </option>
                    @endforeach
                </select>
			</div>
			
			<div class="input-wrapper-registro" style="grid-column: span 3;" required>
				<label>Local do Evento</label>
				<input type="text" name="localEvento" value="{{ old('localEvento', $evento->localEvento) }}" placeholder="Local do Evento" required>
			</div>
			
			<div class="input-wrapper-registro" style="grid-column: span 4;" required>
				<label>Descrição</label>
				<textarea name="descricaoEvento" placeholder="Descrição do evento" style="height: 100px;" required>{{ old('descricaoEvento', $evento->descricaoEvento) }}</textarea>
			</div>
			
			<div class="file-upload" style="grid-column: 2 / span 2;">
                <label for="image" class="file-upload-label" id="uploadLabel"
                    data-imagem-preview="{{ $evento->imagemEvento ? asset('storage/' . $evento->imagemEvento) : '' }}">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Clique para selecionar a imagem</span>
                    <span class="file-name" id="fileName">{{ $evento->imagemEvento ? basename($evento->imagemEvento) : '' }}</span>
                </label>
                <input type="file" id="image" name="imagemEvento" accept="image/*">
                <small>A imagem deve ser no formato JPG, PNG ou GIF e não pode exceder 2MB.</small>
            </div>
			
			<div class="lotes-section" style="grid-row: 7; grid-column: span 4;">
				<h3>Lotes de Ingressos</h3>
				<p>Adicione os lotes de ingressos (ex: Pista, VIP, Meia-entrada)</p>
				
				<div id="lotes-container">
					@foreach($evento->lotes as $index => $lote)
						<div class="lote-card" data-lote-id="{{ $lote->idLote }}">
							<div class="lote-header">
								<h4>Lote {{ $index + 1 }}</h4>
								<button type="button" class="btn-remove-lote" onclick="removerLoteExistente({{ $lote->idLote }})">
									<i class="fas fa-trash-alt"></i>
								</button>
							</div>
							<div class="lote-fields">
								<div class="lote-field">
									<label>Nome do Lote *</label>
									<input type="text" name="lotes[{{ $lote->idLote }}][nomeLote]" value="{{ $lote->nomeLote }}" placeholder="Ex: Pista, VIP" required>
								</div>
								<div class="lote-field">
									<label>Quantidade Total *</label>
									<input type="number" name="lotes[{{ $lote->idLote }}][quantidadeTotal]" value="{{ $lote->quantidadeTotal }}" placeholder="Número de ingressos" min="1" required>
								</div>
								<div class="lote-field">
									<label>Valor do Ingresso (R$) *</label>
									<input type="number" name="lotes[{{ $lote->idLote }}][valorIngresso]" value="{{ $lote->valorIngresso }}" placeholder="0,00" step="0.01" min="0" required>
								</div>
							</div>
						</div>
					@endforeach
				</div>
				
				<button type="button" id="add-lote-btn" class="btn-add-lote">
					<i class="fas fa-plus-circle"></i> Adicionar Novo Lote
				</button>
			</div>
			
			<div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 20px; grid-column: span 4;">
                <a href="{{ route('meus.eventos') }}" class="btn-cancelar">Cancelar</a>
                <button type="submit" class="btn-cadastro">Salvar Alterações</button>
            </div>
			
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