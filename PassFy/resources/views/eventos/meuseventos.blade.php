@extends('layouts.app')

@section('title', 'Passfy - Cadastro')

@section('content')
    <section class="meus-eventos">
		<div class="cadastro-header">
            <h1>Meus Eventos</h1>
            <p>Aqui estão listados os eventos que você criou</p>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="eventos-lista">
            @forelse($eventos as $evento)
                <div class="card-evento" data-url="{{ route('evento.show', $evento->idEvento) }}">
                    <div class="card-tipo-badge" data-tipo="{{ strtolower($evento->tipoEvento) }}">
                        {{ ucfirst($evento->tipoEvento) }}
                    </div>
                    <div class="card-status-badge" data-status="{{ $evento->statusEvento }}">
                        {{ $statusLabels[$evento->statusEvento] ?? $evento->statusEvento }}
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
                    <div class="card-acoes">
                        @if($evento->statusEvento == 'R')
                            <form action="{{ route('eventos.ativar', $evento->idEvento) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn-ativar-evento">Ativar Evento</button>
                            </form>
                        @endif
                        
                        @if(in_array($evento->statusEvento, ['R', 'A', 'E']))
                            <form action="{{ route('eventos.cancelar', $evento->idEvento) }}" method="POST" style="flex: 1;" 
                                onsubmit="return confirm('Tem certeza que deseja cancelar este evento? Todos os ingressos serão reembolsados.');">
                                @csrf
                                <button type="submit" class="btn-cancelar-evento">Cancelar Evento</button>
                            </form>
                        @endif
                    </div>
                    <a href="{{ route('eventos.edit', $evento->idEvento) }}" class="btn-editar-evento">Editar</a>
                </div>
            @empty
                <p>Nenhum evento encontrado.</p>
            @endforelse
        </div>
        <div class="pagination " style="margin-top: 30px; gap: 10px;">
            {{ $eventos->appends(request()->query())->links() }}
        </div>
	</section>
@endsection
