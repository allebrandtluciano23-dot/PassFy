@extends('layouts.app')

@section('title', 'Passfy - Cadastro')

@section('content')
    <section class="meus-eventos">
		<div class="cadastro-header">
            <h1>Meus Eventos</h1>
            <p>Aqui estão listados os eventos que você criou</p>
        </div>
        <div class="eventos-lista">
            @forelse($eventos as $evento)
                <div class="card-evento">
                    <div class="card-tipo-badge" data-tipo="{{ strtolower($evento->tipoEvento) }}">
                        {{ ucfirst($evento->tipoEvento) }}
                    </div>
                    <img src="{{ asset('storage/' . $evento->imagemEvento) }}" alt="Imagem do Evento" class="card-imagem">
                    <div class="card-conteudo">
                        <h2>{{ $evento->nomeEvento }}</h2>
                        <p class="card-data">
                            <i class="fa-regular fa-calendar"></i> 
                            {{ \Carbon\Carbon::parse($evento->dataEvento)->isoFormat('ddd, DD MMM') }} - 
                            {{ \Carbon\Carbon::parse($evento->horaEvento)->format('H:i') }}
                        </p>
                        <p class="card-cidade">
                            <i class="fa-solid fa-location-dot"></i> 
                            {{ $evento->cidade->nomeCidade }}, {{ $evento->cidade->ufCidade }}
                        </p>
                    </div>
                    <a href="{{ route('eventos.edit', $evento->idEvento) }}" class="btn-editar-evento">Editar</a>
                </div>
            @empty
                <p>Nenhum evento encontrado.</p>
            @endforelse
        </div>
	</section>
@endsection
