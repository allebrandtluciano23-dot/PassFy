@extends('layouts.app')

@section('title', $evento->nomeEvento)

@section('content')
<section class="evento-detalhes">
    {{-- Card principal com imagem e informações --}}
    <div class="evento-card">
        <div class="evento-imagem">
            <img src="{{ asset('storage/' . $evento->imagemEvento) }}" alt="{{ $evento->nomeEvento }}">
        </div>
        <div class="evento-info">
            <h1>{{ $evento->nomeEvento }}</h1>
            <div class="evento-info-columns">
                <div class="evento-info-item">
                    <i class="fa-regular fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($evento->dataEvento)->isoFormat('ddd, DD MMM') }}</span>
                </div>
                <div class="evento-info-item">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ \Carbon\Carbon::parse($evento->horaEvento)->format('H:i') }}</span>
                </div>
                <div class="evento-info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>{{ $evento->cidade->nomeCidade }}, {{ $evento->cidade->ufCidade }}</span>
                </div>
                <div class="evento-info-item">
                    <i class="fa-solid fa-map-pin"></i>
                    <span>{{ $evento->localEvento }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Lotes e Descrição --}}
    <div class="evento-lotes-descricao">
        {{-- Lado esquerdo: Lotes de ingressos --}}
        <div class="evento-lotes">
            <h2><i class="fa-solid fa-ticket"></i> Ingressos</h2>
            <div class="lotes-grid">
                @foreach($evento->lotes as $lote)
                    @php
                        $vendidos = $lote->ingressos()->whereIn('status', ['A', 'R'])->count();
                        $disponivel = $lote->quantidadeTotal - $vendidos;
                    @endphp
                    <div class="lote-item" data-lote-id="{{ $lote->idLote }}" data-preco="{{ $lote->valorIngresso }}" data-disponivel="{{ $disponivel }}">
                        <div class="lote-info">
                            <h3>{{ $lote->nomeLote }}</h3>
                            <p>Disponíveis: <span class="lote-disponivel">{{ $disponivel }}</span> ingressos</p>
                        </div>
                        <div class="lote-preco">
                            R$ {{ number_format($lote->valorIngresso, 2, ',', '.') }}
                        </div>
                        <div class="lote-quantidade">
                            <button type="button" class="btn-diminuir-show">-</button>
                            <input type="number" class="quantidade-input" value="0" min="0" max="{{ $disponivel }}" step="1">
                            <button type="button" class="btn-aumentar-show">+</button>
                        </div>
                        <button type="button" class="btn-comprar">Comprar</button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Lado direito: Descrição do evento --}}
        <div class="evento-descricao">
            <h3><i class="fa-solid fa-info-circle"></i> Sobre o evento</h3>
            <p>{{ $evento->descricaoEvento }}</p>
        </div>
    </div>
</section>
@endsection