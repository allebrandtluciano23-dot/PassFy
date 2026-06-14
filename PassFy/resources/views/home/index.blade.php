@extends('layouts.app')

@section('title', 'PassFy - Venda de Ingressos')

@section('content')
    <section class="encontre-eventos">
		<h1>Encontre eventos que combinam com você</h1>
		<p>Descubra experiências incríveis acontecendo perto de você</p>
		<form class="pesquisa-home" action="{{ route('eventos.buscar') }}" method="GET">
			<div class="inputs-pesquisa-home">
				<div class="input-wrapper">
					<input type="text" name="name" placeholder="Buscar Eventos" value="{{ request()->get('name') }}">
					<i class="fa-brands fa-sistrix"></i>
				</div>
				<div class="input-wrapper">
					<input type="text" name="city" placeholder="Cidade" value="{{ request()->get('city') }}">
					<i class="fa-solid fa-magnifying-glass-location"></i>
				</div>
				<div class="input-wrapper">
					<input id="input-date" type="date" name="date" placeholder="dd/mm/aa" value="{{ request()->get('date') }}">
				</div>
			</div>
			<div class="button-wrapper">
				<button type="submit">
					<i class="fa-brands fa-sistrix"></i>
					Buscar Eventos
				</button>
			</div>
		</form>
	</section>
	<section class="secao-categorias">
		<h2>Explore por categorias</h2>
		<div class="categorias">
			<div class="categoria">
				<span class="circle-icon" style="background-color: #A855F7;">
					<i class="fa-brands fa-itunes-note"></i>
				</span>
				<p>Shows</p>
			</div>
			<div class="categoria">
				<span class="circle-icon" style="background-color: #FF3D7B;">
					<i class="fa-brands fa-itunes-note"></i>
				</span>
				<p>Moda</p>
			</div>
			<div class="categoria">
				<span class="circle-icon" style="background-color: #FF8D3B;">
					<i class="fa-brands fa-itunes-note"></i>
				</span>
				<p>Gastronomia</p>
			</div>
			<div class="categoria">
				<span class="circle-icon" style="background-color: #555DF7;">
					<i class="fa-brands fa-itunes-note"></i>
				</span>
				<p>Palestras</p>
			</div>
			<div class="categoria">
				<span class="circle-icon" style="background-color: #000000;">
					<i class="fa-brands fa-itunes-note"></i>
				</span>
				<p>Esportes</p>
			</div>
			<div class="categoria">
				<span class="circle-icon" style="background-color: #EE4346;">
					<i class="fa-brands fa-itunes-note"></i>
				</span>
				<p>Arte</p>
			</div>
		</div>
	</section>
	<section class="meus-eventos">
        <div class="meus-eventos-header">
            <h1>Eventos em Destaque</h1>
            <p>Confira os melhores eventos disponíveis</p>
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
                    </div>
					<p class="card-preco">
						<i class="fa-solid fa-tag"></i>
						A partir de: R$ {{ number_format($evento->preco_minimo, 2, ',', '.') }}
					</p>
                </div>
			</a>
            @empty
                <p>Nenhum evento disponível no momento.</p>
            @endforelse
        </div>
    </section>
@endsection