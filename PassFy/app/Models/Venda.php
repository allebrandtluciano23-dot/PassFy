<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Venda extends Model
{
    protected $table = 'venda';
    protected $primaryKey = 'idVenda';

    protected $fillable = [
        'idCliente',
        'quantidadeVenda',
        'dataCompra',
        'formaPagamento',
        'valorTotal',
    ];

    protected $casts = [
        'dataCompra' => 'datetime',
        'valorTotal' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function ingressos(): BelongsToMany
    {
        return $this->belongsToMany(Ingresso::class, 'ingresso_venda', 'idVenda', 'idIngresso')
                    ->withPivot('quantidade', 'valorUnitario')
                    ->withTimestamps();
    }
}