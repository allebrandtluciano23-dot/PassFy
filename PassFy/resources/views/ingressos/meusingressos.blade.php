@extends('layouts.app')

@section('title', 'Meus Ingressos - PassFy')

@section('content')
<section class="meus-ingressos">
    <div class="meus-ingressos-header">
        <h1>Meus Ingressos</h1>
        <p>Aqui estão todos os ingressos que você comprou</p>
    </div>

    @if($ingressosReservados->count() > 0)
        <div class="reservados-banner">
            <div class="reservados-info">
                <i class="fa-solid fa-clock"></i>
                <span>Você tem {{ $ingressosReservados->count() }} ingresso(s) reservado(s).</span>
                <span class="reservados-timer">Expira em: <span id="timer"></span></span>
            </div>
            @php $ids = $ingressosReservados->pluck('idIngresso')->implode(','); @endphp
            <a href="{{ route('checkout.index') }}?ingressos={{ $ids }}" class="btn-finalizar-todos">
                <i class="fa-solid fa-credit-card"></i> Finalizar Pagamento de Todos
            </a>
        </div>
    @endif

    @if($ingressos->count() > 0)
        <div class="ingressos-grid">
            @foreach($ingressos as $ingresso)
            <div class="ingresso-card" data-status="{{ $ingresso->status }}">
                <div class="ingresso-status">
                    <span class="status-badge status-{{ 
                        $ingresso->status == 'R' ? 'reservado' : 
                        ($ingresso->status == 'A' ? 'ativo' : 
                        ($ingresso->status == 'U' ? 'usado' : 'cancelado')) 
                    }}">
                        @if($ingresso->status == 'R')
                            <i class="fa-solid fa-clock"></i> Reservado
                        @elseif($ingresso->status == 'A')
                            <i class="fa-solid fa-check-circle"></i> Ativo
                        @elseif($ingresso->status == 'U')
                            <i class="fa-solid fa-check-double"></i> Usado
                        @else
                            <i class="fa-solid fa-ban"></i> Cancelado
                        @endif
                    </span>
                </div>
                
                <div class="ingresso-imagem">
                    <img src="{{ asset('storage/' . $ingresso->lote->evento->imagemEvento) }}" alt="{{ $ingresso->lote->evento->nomeEvento }}">
                </div>
                
                <div class="ingresso-conteudo">
                    <h2>{{ $ingresso->lote->evento->nomeEvento }}</h2>
                    
                    <div class="ingresso-info">
                        <p>
                            <i class="fa-solid fa-ticket"></i> 
                            <strong>Lote:</strong> {{ $ingresso->lote->nomeLote }}
                        </p>
                        <p>
                            <i class="fa-regular fa-calendar"></i> 
                            <strong>Data:</strong> {{ \Carbon\Carbon::parse($ingresso->lote->evento->dataEvento)->isoFormat('DD/MM/YYYY') }}
                        </p>
                        <p>
                            <i class="fa-regular fa-clock"></i> 
                            <strong>Hora:</strong> {{ \Carbon\Carbon::parse($ingresso->lote->evento->horaEvento)->format('H:i') }}
                        </p>
                        <p>
                            <i class="fa-solid fa-location-dot"></i> 
                            <strong>Local:</strong> {{ $ingresso->lote->evento->localEvento }}
                        </p>
                        <p>
                            <i class="fa-solid fa-city"></i> 
                            <strong>Cidade:</strong> {{ $ingresso->lote->evento->cidade->nomeCidade }}, {{ $ingresso->lote->evento->cidade->ufCidade }}
                        </p>
                    </div>
                    
                    <div class="ingresso-codigo">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Código: {{ $ingresso->codigoUnico }}</span>
                    </div>
                    
                    <div class="ingresso-acoes">
                        @if($ingresso->status == 'R')
                            <a href="{{ route('checkout.index') }}?ingresso={{ $ingresso->idIngresso }}" class="btn-pagar">
                                <i class="fa-solid fa-credit-card"></i> Finalizar Pagamento
                            </a>
                            <button type="button" class="btn-cancelar-ingresso" onclick="cancelarIngresso({{ $ingresso->idIngresso }}, 'R')">
                                <i class="fa-solid fa-times"></i> Cancelar Reserva
                            </button>
                        @elseif($ingresso->status == 'A')
                            <button type="button" class="btn-cancelar-ingresso" onclick="cancelarIngresso({{ $ingresso->idIngresso }}, 'A')">
                                <i class="fa-solid fa-times"></i> Cancelar Ingresso
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $ingressos->links() }}
        </div>
    @else
        <div class="nenhum-ingresso">
            <i class="fa-solid fa-ticket-alt"></i>
            <p>Você ainda não possui ingressos.</p>
            <a href="{{ route('home') }}" class="btn-explorar">Explorar Eventos</a>
        </div>
    @endif
</section>
@if(!empty($nextExpiryTimestamp))
    <script>
        (function() {
            const target = {{ $nextExpiryTimestamp }};
            const timerEl = document.getElementById('timer');
            if (!timerEl) return;

            function formatTime(ms) {
                const totalSeconds = Math.floor(ms / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                return `${minutes}m ${seconds}s`;
            }

            function update() {
                const now = Date.now();
                const diff = target - now;
                if (diff <= 0) {
                    timerEl.textContent = 'Expirado';
                    clearInterval(interval);
                    return;
                }
                timerEl.textContent = formatTime(diff);
            }

            update();
            const interval = setInterval(update, 1000);
        })();
    </script>
@endif

@endsection