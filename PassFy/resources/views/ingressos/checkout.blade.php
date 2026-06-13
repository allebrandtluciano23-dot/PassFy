@extends('layouts.app')

@section('title', 'Finalizar Compra - PassFy')

@section('content')
<section class="checkout-section">
    <h1>Finalizar Compra</h1>
    
    <div class="checkout-container">
        {{-- Resumo do pedido --}}
        <div class="checkout-resumo">
            <h2><i class="fa-solid fa-receipt"></i> Resumo do Pedido</h2>
            
            <div class="resumo-itens">
                @foreach($itens as $item)
                <div class="resumo-item">
                    <div class="resumo-item-info">
                        <h3>{{ $item->lote->evento->nomeEvento }}</h3>
                        <p>
                            <i class="fa-solid fa-ticket"></i> Lote: {{ $item->lote->nomeLote }} |
                            <i class="fa-solid fa-calculator"></i> Quantidade: {{ $item->quantidade }}
                        </p>
                        <p><i class="fa-regular fa-calendar"></i> Data: {{ \Carbon\Carbon::parse($item->lote->evento->dataEvento)->isoFormat('DD/MM/YYYY') }}</p>
                        <p><i class="fa-solid fa-location-dot"></i> Local: {{ $item->lote->evento->localEvento }}</p>
                    </div>
                    <div class="resumo-item-preco">
                        R$ {{ number_format($item->quantidade * $item->valorUnitario, 2, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="resumo-total">
                <div class="total-linha">
                    <span>Subtotal:</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
                <div class="total-linha">
                    <span>Taxas:</span>
                    <span>R$ 0,00</span>
                </div>
                <div class="total-linha final">
                    <span>Total:</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        {{-- Formulário de pagamento --}}
        <div class="checkout-pagamento">
            <h2><i class="fa-solid fa-credit-card"></i> Forma de Pagamento</h2>
            
            <form id="form-pagamento" action="#" method="POST">
                @csrf
                
                <div class="pagamento-opcoes">
                    <label class="pagamento-opcao">
                        <input type="radio" name="forma_pagamento" value="cartao" checked>
                        <i class="fa-solid fa-credit-card"></i>
                        <span>Cartão de Crédito</span>
                    </label>
                    
                    <label class="pagamento-opcao">
                        <input type="radio" name="forma_pagamento" value="pix">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>PIX</span>
                    </label>
                    
                    <label class="pagamento-opcao">
                        <input type="radio" name="forma_pagamento" value="carteira">
                        <i class="fa-solid fa-wallet"></i>
                        <span>Carteira Digital</span>
                    </label>
                </div>
                
                {{-- Dados do cartão (inicialmente visível) --}}
                <div id="cartao-fields" class="pagamento-campos">
                    <div class="campo">
                        <label>Número do Cartão</label>
                        <input type="text" placeholder="0000 0000 0000 0000" maxlength="19">
                    </div>
                    <div class="campo-row">
                        <div class="campo">
                            <label>Validade</label>
                            <input type="text" placeholder="MM/AA" maxlength="5">
                        </div>
                        <div class="campo">
                            <label>CVV</label>
                            <input type="text" placeholder="123" maxlength="4">
                        </div>
                    </div>
                    <div class="campo">
                        <label>Nome no Cartão</label>
                        <input type="text" placeholder="Como está no cartão">
                    </div>
                </div>
                
                {{-- PIX fields (inicialmente escondido) --}}
                <div id="pix-fields" class="pagamento-campos" style="display: none;">
                    <div class="pix-info">
                        <i class="fa-solid fa-qrcode"></i>
                        <p>Escaneie o QR Code abaixo ou copie o código PIX</p>
                        <div class="pix-qrcode">
                            <img src="https://via.placeholder.com/150" alt="QR Code PIX">
                        </div>
                        <div class="pix-codigo">
                            <p><strong>Código PIX:</strong> <span id="pix-codigo">00020126360014BR.GOV.BCB.PIX...</span></p>
                            <button type="button" class="btn-copiar" onclick="copiarPix()">Copiar código</button>
                        </div>
                    </div>
                </div>
                
                {{-- Carteira Digital fields (inicialmente escondido) --}}
                <div id="carteira-fields" class="pagamento-campos" style="display: none;">
                    <div class="carteira-info">
                        <p>Saldo disponível: <strong>R$ {{ number_format($carteiraSaldo ?? 0, 2, ',', '.') }}</strong></p>
                        @if(($carteiraSaldo ?? 0) < $total)
                            <div class="carteira-insuficiente">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <p>Saldo insuficiente para realizar esta compra.</p>
                                <a href="{{ route('carteira.depositar') }}" class="btn-carteira">Depositar na carteira</a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <button type="submit" id="btn-finalizar" class="btn-finalizar-pedido">
                    <i class="fa-solid fa-check"></i> Finalizar Pedido
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    // Mostrar/esconder campos conforme forma de pagamento
    const opcoesPagamento = document.querySelectorAll('input[name="forma_pagamento"]');
    const cartaoFields = document.getElementById('cartao-fields');
    const pixFields = document.getElementById('pix-fields');
    const carteiraFields = document.getElementById('carteira-fields');
    
    opcoesPagamento.forEach(option => {
        option.addEventListener('change', function() {
            cartaoFields.style.display = 'none';
            pixFields.style.display = 'none';
            carteiraFields.style.display = 'none';
            
            if (this.value === 'cartao') {
                cartaoFields.style.display = 'block';
            } else if (this.value === 'pix') {
                pixFields.style.display = 'block';
            } else if (this.value === 'carteira') {
                carteiraFields.style.display = 'block';
            }
        });
    });
    
    function copiarPix() {
        const pixCodigo = document.getElementById('pix-codigo').innerText;
        navigator.clipboard.writeText(pixCodigo);
        alert('Código PIX copiado!');
    }
</script>
@endsection