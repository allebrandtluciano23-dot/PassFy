<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngressoCarrinho extends Model
{
    protected $table = 'ingresso_carrinho';

    public $incrementing = false;

    protected $primaryKey = ['idCarrinho', 'idLote'];

    protected $fillable = [
        'idCarrinho',
        'idLote',
        'quantidade',
        'valorUnitario',
    ];

    protected $casts = [
        'valorUnitario' => 'decimal:2',
    ];

    /**
     * Get the primary key for the model.
     */
    public function getKeyName()
    {
        return 'idCarrinho';
    }

    /**
     * Get the value of the model's primary key.
     */
    public function getKey()
    {
        return $this->getAttribute('idCarrinho');
    }

    public function carrinho(): BelongsTo
    {
        return $this->belongsTo(Carrinho::class, 'idCarrinho');
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'idLote');
    }
}