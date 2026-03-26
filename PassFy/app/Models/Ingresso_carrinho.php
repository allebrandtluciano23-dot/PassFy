<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngressoCarrinho extends Model
{
    protected $table = 'ingresso_carrinho';

    public $incrementing = false; // chave composta

    protected $primaryKey = null;

    protected $fillable = [
        'idCarrinho',
        'idLote',
        'quantidade',
        'valorUnitario',
    ];

    protected $casts = [
        'valorUnitario' => 'decimal:2',
    ];

    public function carrinho(): BelongsTo
    {
        return $this->belongsTo(Carrinho::class, 'idCarrinho');
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'idLote');
    }
}