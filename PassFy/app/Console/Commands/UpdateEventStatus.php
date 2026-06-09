<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evento;
use App\Models\Ingresso;
use Carbon\Carbon;

class UpdateEventStatus extends Command
{
    protected $signature = 'events:update-status';
    protected $description = 'Atualiza status de eventos (encerrado, esgotado) e ingressos (usado)';

    public function handle()
    {
        // 1. Eventos que passaram da data (encerrar)
        $eventosParaEncerrar = Evento::where('statusEvento', 'A')
            ->where('dataEvento', '<', Carbon::now())
            ->get();

        foreach ($eventosParaEncerrar as $evento) {
            // Mudar status do evento para Encerrado (X)
            $evento->statusEvento = 'X';
            $evento->save();
            
            // Buscar ingressos ativos através dos lotes do evento
            $lotesIds = $evento->lotes->pluck('idLote')->toArray();
            
            Ingresso::whereIn('idLote', $lotesIds)
                ->where('status', 'A')
                ->update(['status' => 'U']);
            
            $this->info("Evento '{$evento->nomeEvento}' encerrado e ingressos marcados como usados.");
        }

        // 2. Eventos ativos que esgotaram
        $eventosParaEsgotar = Evento::where('statusEvento', 'A')
            ->with('lotes.ingressos')
            ->get()
            ->filter(function ($evento) {
                $totalDisponivel = $evento->lotes->sum(function ($lote) {
                    $vendidos = $lote->ingressos->where('status', 'A')->count();
                    $reservados = $lote->ingressos->where('status', 'R')->count();
                    return $lote->quantidadeTotal - $vendidos - $reservados;
                });
                return $totalDisponivel <= 0;
            });

        foreach ($eventosParaEsgotar as $evento) {
            $evento->statusEvento = 'E'; // Esgotado
            $evento->save();
            $this->info("Evento '{$evento->nomeEvento}' está esgotado.");
        }

        $this->info('Atualização de status concluída!');
    }
}