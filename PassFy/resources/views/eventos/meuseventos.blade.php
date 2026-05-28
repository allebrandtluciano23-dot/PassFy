@extends('layouts.app')

@section('title', 'Passfy - Cadastro')

@section('content')
    <section class="meus-eventos">
		<div class="meus-eventos-header">
            <h1>Meus Eventos</h1>
            <p>Aqui estão listados os eventos que você criou</p>
        </div>
        <div class="eventos-lista">
            @forelse($eventos as $evento)
                <div class="evento-item">
                    <h2>{{ $evento->nomeEvento }}</h2>
                    <p>Data: {{ \Carbon\Carbon::parse($evento->dataEvento)->format('d/m/Y') }}</p>
                    <p>Hora: {{ \Carbon\Carbon::parse($evento->horaEvento)->format('H:i') }}</p>
                    <p>Tipo: {{ ucfirst($evento->tipoEvento) }}</p>
                    <p>Local: {{ $evento->localEvento }}</p>
                </div>
            @empty
                <p>Nenhum evento encontrado.</p>
            @endforelse
        </div>
	</section>
@endsection
