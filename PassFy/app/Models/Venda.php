<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venda extends Model
{
    protected $table = 'venda';

    protected $primaryKey = 'idVenda';

    protected $fillable = [
        'idCliente',
        'idIngresso',
        'quantidadeVenda',
        'dataCompra',
        'formaPagamento',
        'valorTotal',
    ];

    protected $casts = [
        'dataCompra' => 'date',
        'valorTotal' => 'decimal:2',
    ];

    // Relacionamento com Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    // Relacionamento com Ingresso
    public function ingresso(): BelongsTo
    {
        return $this->belongsTo(Ingresso::class, 'idIngresso');
    }
}