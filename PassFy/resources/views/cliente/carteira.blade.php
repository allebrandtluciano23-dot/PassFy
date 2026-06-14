@extends('layouts.app')

@section('title', 'Minha Carteira - PassFy')

@section('content')
<section class="carteira-section">
    <div class="carteira-header">
        <h1><i class="fa-solid fa-wallet"></i> Minha Carteira Digital</h1>
        <p>Gerencie seu saldo e faça depósitos</p>
    </div>

    <div class="carteira-container">
        {{-- Card de Saldo --}}
        <div class="carteira-saldo-card">
            <div class="saldo-info">
                <span class="saldo-label">Saldo Disponível</span>
                <span class="saldo-valor">R$ {{ number_format($saldo, 2, ',', '.') }}</span>
            </div>
            <button class="btn-depositar" id="btn-depositar">
                <i class="fa-solid fa-circle-plus"></i> Depositar
            </button>
        </div>

        {{-- Modal de Depósito PIX (simulado) --}}
        <div id="modal-pix" class="modal-pix" style="display: none;">
            <div class="modal-pix-content">
                <div class="modal-pix-header">
                    <h3><i class="fa-solid fa-qrcode"></i> Depósito via PIX</h3>
                    <button type="button" class="close-modal" id="close-modal">&times;</button>
                </div>
                
                <div class="modal-pix-body">
                    <div class="pix-valor-info">
                        <p>Valor a depositar:</p>
                        <div class="pix-valor-input">
                            <span class="prefixo">R$</span>
                            <input type="number" id="valor-pix" step="0.01" min="1" placeholder="0,00">
                        </div>
                    </div>
                    
                    <div id="pix-qrcode-area" class="pix-qrcode-area" style="display: none;">
                        <div class="qrcode-placeholder">
                            <i class="fa-solid fa-qrcode"></i>
                            <p>Escaneie o QR Code com seu banco</p>
                        </div>
                        <div class="pix-codigo">
                            <p><strong>Código PIX:</strong></p>
                            <div class="codigo-wrapper">
                                <span id="pix-codigo">00020126360014BR.GOV.BCB.PIX...</span>
                                <button type="button" class="btn-copiar" id="copiar-pix">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="pix-sucesso" class="pix-sucesso" style="display: none;">
                        <i class="fa-solid fa-circle-check"></i>
                        <h4>Depósito realizado com sucesso!</h4>
                        <p>O valor foi adicionado à sua carteira.</p>
                        <button type="button" class="btn-fechar-sucesso" id="fechar-sucesso">Continuar</button>
                    </div>
                    
                    <div class="modal-pix-acoes">
                        <button type="button" class="btn-cancelar" id="cancelar-pix">Cancelar</button>
                        <button type="button" class="btn-gerar-pix" id="gerar-pix">Gerar PIX</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection