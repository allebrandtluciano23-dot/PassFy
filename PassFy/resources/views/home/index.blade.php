@extends('layouts.app')

@section('title', 'PassFy - Venda de Ingressos')

@section('content')
    <section class="encontre-eventos">
		<h1>Encontre eventos que combinam com você</h1>
		<p>Descubra experiências incríveis acontecendo perto de você</p>
		<form class="pesquisa-home">
			<div class="inputs-pesquisa-home">
				<div class="input-wrapper">
					<input type="text" name="name" placeholder="Buscar Eventos">
					<i class="fa-brands fa-sistrix"></i>
				</div>
				<div class="input-wrapper">
					<input type="text" name="city" placeholder="Cidade">
					<i class="fa-solid fa-magnifying-glass-location"></i>
				</div>
				<div class="input-wrapper">
					<input id="input-date" type="date" name="date" placeholder="dd/mm/aa">
				</div>
			</div>
			<div class="button-wrapper">
				<i class="fa-brands fa-sistrix"></i>
				<button type="submit">Buscar Eventos</button>
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

	<section class="secao-destaque">
		

	</section>
@endsection