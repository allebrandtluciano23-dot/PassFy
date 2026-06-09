@extends('layouts.app')

@section('title', 'Resultados da Busca - PassFy')

@section('content')
    <section class="meus-eventos">
        <div class="meus-eventos-header">
            <h1>Resultados da Busca</h1>
            <p>
                @if(request()->filled('name'))
                    Eventos com "{{ request()->name }}"
                @endif
                @if(request()->filled('city'))
                    em "{{ request()->city }}"
                @endif
                @if(request()->filled('date'))
                    para {{ \Carbon\Carbon::parse(request()->date)->isoFormat('DD/MM/YYYY') }}
                @endif
            </p>
        </div>

        <div class="eventos-lista">
            @forelse($eventos as $evento)
                <a href="{{ route('evento.show', $evento->idEvento) }}" style="text-decoration: none; color: inherit; display: block;">
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
                            <p class="card-preco">
                                <i class="fa-solid fa-tag"></i>
                                A partir de: R$ {{ number_format($evento->preco_minimo, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <p class="sem-resultados">Nenhum evento encontrado para os filtros informados.</p>
            @endforelse
        </div>

        <div class="pagination" style="margin-top: 30px; gap: 10px;">
            {{ $eventos->appends(request()->query())->links() }}
        </div>

        <div class="voltar-busca" style="text-align: center; margin-top: 30px;">
            <a href="{{ route('home') }}" class="btn-voltar">← Voltar para página inicial</a>
        </div>
    </section>
@endsection