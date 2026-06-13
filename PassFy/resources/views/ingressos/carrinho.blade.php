@extends('layouts.app')

@section('title', 'Meu Carrinho - PassFy')

@section('content')
<section class="carrinho-section">
    <h1>Meu Carrinho</h1>
    
    @if($itens->count() > 0)
        <div class="carrinho-itens">
            @foreach($itens as $item)
            <div class="carrinho-item" data-id="{{ $item->idLote }}" data-valor="{{ $item->valorUnitario }}">
                <img src="{{ asset('storage/' . $item->lote->evento->imagemEvento) }}" alt="Imagem">
                <div class="item-info">
                    <h3>{{ $item->lote->evento->nomeEvento }}</h3>
                    <p><i class="fa-solid fa-ticket-simple"></i></i>Lote: {{ $item->lote->nomeLote }}</p>
                    <p><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($item->lote->evento->dataEvento)->isoFormat('DD/MM/YYYY') }}</p>
                    <p><i class="fa-solid fa-location-dot"></i> {{ $item->lote->evento->localEvento }}</p>
                </div>
                <div class="item-preco">
                    <p>R$ {{ number_format($item->valorUnitario, 2, ',', '.') }}</p>
                </div>
                <div class="item-quantidade">
                    <button class="btn-diminuir" data-id="{{ $item->idLote }}">-</button>
                    <input type="number" class="quantidade" value="{{ $item->quantidade }}" min="1" max="{{ $item->disponivel }}" data-id="{{ $item->idLote }}" data-disponivel="{{ $item->disponivel }}">
                    <button class="btn-aumentar" data-id="{{ $item->idLote }}">+</button>
                </div>
                <div class="item-subtotal">
                    <p>R$ {{ number_format($item->quantidade * $item->valorUnitario, 2, ',', '.') }}</p>
                </div>
                <button class="btn-remover" data-id="{{ $item->idLote }}">Remover</button>
            </div>
            @endforeach
        </div>
        
        <div class="carrinho-resumo">
            <h3>Resumo do Pedido</h3>
            <p>Total: <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong></p>
            <button type="button" class="btn-finalizar">Finalizar Compra</button>
        </div>
    @else
        <p class="carrinho-vazio">Seu carrinho está vazio.</p>
        <a href="{{ route('home') }}" class="btn-voltar">Continuar comprando</a>
    @endif
</section>
@endsection