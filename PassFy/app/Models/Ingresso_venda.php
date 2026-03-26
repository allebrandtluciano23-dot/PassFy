<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngressoVenda extends Model
{
    protected $table = 'ingresso_venda';

    public $incrementing = false; // chave composta

    protected $primaryKey = null;

    protected $fillable = [
        'idIngresso',
        'idVenda',
        'quantidade',
        'valorUnitario',
    ];

    protected $casts = [
        'valorUnitario' => 'decimal:2',
    ];

    // Relacionamento com Ingresso
    public function ingresso(): BelongsTo
    {
        return $this->belongsTo(Ingresso::class, 'idIngresso');
    }

    // Relacionamento com Venda
    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'idVenda');
    }
}